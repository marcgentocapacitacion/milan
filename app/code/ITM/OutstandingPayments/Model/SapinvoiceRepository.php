<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding payments 
 * Copyright (C) 2017  
 * 
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Model;

use ITM\OutstandingPayments\Api\Data\SapinvoiceSearchResultsInterfaceFactory;
use ITM\OutstandingPayments\Api\Data\SapinvoiceInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory as SapinvoiceCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use ITM\OutstandingPayments\Api\SapinvoiceRepositoryInterface;
use ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice as ResourceSapinvoice;
use Magento\Store\Model\StoreManagerInterface;

class SapinvoiceRepository implements sapinvoiceRepositoryInterface
{
    protected $_osp_helper;
    

    protected $resource;

    protected $sapinvoiceFactory;

    protected $searchResultsFactory;

    private $storeManager;

    protected $dataSapinvoiceFactory;

    protected $sapinvoiceCollectionFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $_invoiceCollectionFactory;
    
    protected $_objectManager;
    
    /**
     * @param ResourceSapinvoice $resource
     * @param SapinvoiceFactory $sapinvoiceFactory
     * @param SapinvoiceInterfaceFactory $dataSapinvoiceFactory
     * @param SapinvoiceCollectionFactory $sapinvoiceCollectionFactory
     * @param SapinvoiceSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceSapinvoice $resource,
        SapinvoiceFactory $sapinvoiceFactory,
        SapinvoiceInterfaceFactory $dataSapinvoiceFactory,
        SapinvoiceCollectionFactory $sapinvoiceCollectionFactory,
        SapinvoiceSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\OutstandingPayments\Helper\Data $helper
    ) {
        $this->resource = $resource;
        $this->sapinvoiceFactory = $sapinvoiceFactory;
        $this->sapinvoiceCollectionFactory = $sapinvoiceCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSapinvoiceFactory = $dataSapinvoiceFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->_invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->_osp_helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
    ) {
        try {
            if(empty($sapinvoice->getEmail())) {
                throw new CouldNotSaveException(__(
                    'Could not save the sapinvoice: %1',
                    __("Email is required, Document Number is ").$sapinvoice->getDocNum()));
            }
            $collection = $this->_invoiceCollectionFactory->create();
            $invoiceCollection = $collection
                ->addFieldToFilter("doc_entry", $sapinvoice->getDocentry())
                //->addFieldToFilter("email", $sapinvoice->getEmail())
                ->addFieldToFilter("doc_type", $sapinvoice->getDocType())
                ->addFieldToFilter("sap_company", $sapinvoice->getSapCompany())
                ->setPageSize(1)
            ->setCurPage(1)
            ;
            if($invoiceCollection->getSize()>0) {
                $invoice = $invoiceCollection->getFirstItem();
                $sapinvoice->setEntityId($invoice->getEntityId());
            }
            
            
            $sapinvoice->getResource()->save($sapinvoice);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the sapinvoice: %1',
                $exception->getMessage()
            ));
        }
        return $sapinvoice;
    }
    
    private function getDestinationPath()
    {
        return $this->_osp_helper->getInvoiceFilesPath();
    }
    
    private function is_base64($str)
    {
        if ( base64_encode(base64_decode($str, true)) === $str){
            return true;
        } else {
            return false;
        }
    }
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function saveFile($sapinvoice, $fileName)
    {
        $doc_entry = $sapinvoice->getDocEntry();
        $sap_company = $sapinvoice->getSapCompany();
        
        $collection = $this->_objectManager->create('\ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\Collection');
      
        
        $collection->addFieldToFilter("doc_entry", $doc_entry);
        $collection->addFieldToFilter("sap_company", $sap_company);
        
        if(!$this->is_base64($sapinvoice->getPath())) {
            return false;
        }
        $file = base64_decode($sapinvoice->getPath());
       
        $destinationPath = $this->getDestinationPath()."/".$sapinvoice->getSapCompany()."/".md5($sapinvoice->getDocEntry())."/";
        $path = $destinationPath.$fileName;
        
        
        
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $result = file_put_contents($path, $file);
        
        foreach ($collection as $item) {
            $item->setPath($fileName);
            $item->save();
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function saveList($sapinvoiceList){
        $returnList = [];
        foreach ($sapinvoiceList as $sapinvoice) {
            $returnList[] = $this->save($sapinvoice);
        }
        return $returnList;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($sapinvoiceId)
    {
        $sapinvoice = $this->sapinvoiceFactory->create();
        $sapinvoice->getResource()->load($sapinvoice, $sapinvoiceId);
        if (!$sapinvoice->getId()) {
            throw new NoSuchEntityException(__('Sapinvoice with id "%1" does not exist.', $sapinvoiceId));
        }
        return $sapinvoice;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->sapinvoiceCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
    ) {
        try {
            $sapinvoice->getResource()->delete($sapinvoice);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Sapinvoice: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteInvoice(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
        ) {
            try {
                $collection = $this->_invoiceCollectionFactory->create();
                $invoiceCollection = $collection
                    ->addFieldToFilter("doc_entry", $sapinvoice->getDocentry())
                    ->addFieldToFilter("doc_type", $sapinvoice->getDocType())
                    ->addFieldToFilter("email", $sapinvoice->getEmail())
                    ->addFieldToFilter("sap_company", $sapinvoice->getSapCompany())
                    ->setPageSize(1)
                ->setCurPage(1)
                ;
                if($invoiceCollection->getSize()>0) {
                    $invoice = $invoiceCollection->getFirstItem();
                    $sapinvoice->setEntityId($invoice->getEntityId());
                }
                
                $sapinvoice->getResource()->delete($sapinvoice);

            } catch (\Exception $exception) {
                throw new CouldNotDeleteException(__(
                 'Could not delete the Sapinvoice: %1',
                 $exception->getMessage()
                 ));
            }
            return $sapinvoice->getCode();
            //return true;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteList($sapinvoiceList){
        $returnList = [];
        foreach ($sapinvoiceList as $sapinvoice) {
            $returnList[] = $this->deleteInvoice($sapinvoice);
        }
        return $returnList;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($sapinvoiceId)
    {
        return $this->delete($this->getById($sapinvoiceId));
    }
}