<?php
namespace ITM\Pricing\Model\Api;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use ITM\Pricing\Api\UomgroupdetailsInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class Uomgroupdetails implements UomgroupdetailsInterface
{

    /**
     *
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    protected $_searchResultsFactory;

    protected $_objectManager;

    /**
     *
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(\Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory)
    {
        $this->_searchResultsFactory = $searchResultsFactory;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getUomGroupDetailsList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroupdetails\Collection');
        
        $result = $collection->getData();
        
        $searchResult = $this->_searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($result);
        $searchResult->setTotalCount(count($result));
        return $searchResult;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function save($entity)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroupdetails\Collection');
        $collection->addFieldToFilter("ugp_entry", $entity->getUgpEntry())
            ->addFieldToFilter("uom_entry", $entity->getUomEntry());
        $item = $collection->getFirstItem();
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroupdetails');
        
        if ($item->getId()) {
            $model->load($item->getId());
        }
        
        $model->setUgpEntry($entity->getUgpEntry())
            ->setUomEntry($entity->getUomEntry())
            ->setAltQty($entity->getAltQty())
            ->setBaseQty($entity->getBaseQty())
            ->save();
        
        return $model;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function saveMultiple($items)
    {
        $_saved_items = array();
        foreach ($items as $item) {
            $_saved_items[] = $this->save($item);
        }
        return $_saved_items;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function deleteByEntry($ugp_entry, $uom_entry)
    {
        try {
            $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroupdetails\Collection');
            $collection->addFieldToFilter("ugp_entry", $ugp_entry)->addFieldToFilter("uom_entry", $uom_entry);
            $item = $collection->getFirstItem();
            $id = $item->getId();
            $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroupdetails');
            $model->load($id);
            $model->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
}
