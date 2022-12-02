<?php
namespace ITM\Pricing\Model\Api;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use ITM\Pricing\Api\CustomerpriceInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class Customerprice implements CustomerpriceInterface
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
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Customerprice\Collection');
        
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
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Customerprice\Collection');
        $collection->addFieldToFilter("customer_id", $entity->getCustomerId());
        $collection->addFieldToFilter("sku", $entity->getSku());
        $collection->addFieldToFilter("qty", $entity->getQty());
        $collection->addFieldToFilter("website_id", $entity->getWebsiteId());
        
        if ($entity->getStartDate() != "") {
            $strtotime_start_date = strtotime($entity->getStartDate());
            $start_date = date('Y-m-d', $strtotime_start_date);
            $collection->addFieldToFilter("start_date", $start_date);
        } else {
            $collection->addFieldToFilter("start_date", array(
                "null" => true
            ));
        }
        
        if ($entity->getEndDate() != "") {
            $strtotime_end_date = strtotime($entity->getEndDate());
            $end_date = date('Y-m-d', $strtotime_end_date);
            $collection->addFieldToFilter("end_date", $end_date);
        } else {
            $collection->addFieldToFilter("end_date", array(
                "null" => true
            ));
        }
        
        $collection->addFieldToFilter("uom_entry", $entity->getUomEntry());
        
        $item = $collection->getFirstItem();
        $model = $this->_objectManager->create('ITM\Pricing\Model\Customerprice');
        
        if ($item->getId()) {
            $model->load($item->getId());
            $data["id"] = $item->getId();
        }
        
        $model->setData('customer_id', $entity->getCustomerId());
        $model->setData('website_id', $entity->getWebsiteId());
        $model->setData('sku', $entity->getSku());
        $model->setData('qty', $entity->getQty());
        $model->setData('start_date', $entity->getStartDate());
        $model->setData('end_date', $entity->getEndDate());
        $model->setData('uom_entry', $entity->getUomEntry());
        $model->setData('price', $entity->getPrice());
        $model->setData('discount', $entity->getDiscount());
        $model->setData('status', $entity->getStatus());
        $model->setData('code', $entity->getCode());
        $model->save();
        
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
    public function deleteById($id)
    {
        try {
            $model = $this->_objectManager->create('ITM\Pricing\Model\Customerprice');
            $model->load($id);
            $model->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function deleteByObject($entity)
    {
        $return_result = new \ITM\Pricing\Model\Api\ReturnResult();
        
        try {
            $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Customerprice\Collection');
            $collection->addFieldToFilter("customer_id", $entity->getCustomerId());
            $collection->addFieldToFilter("sku", $entity->getSku());
            $collection->addFieldToFilter("qty", $entity->getQty());
            $collection->addFieldToFilter("website_id", $entity->getWebsiteId());
            
            if ($entity->getStartDate() != "") {
                $strtotime_start_date = strtotime($entity->getStartDate());
                $start_date = date('Y-m-d', $strtotime_start_date);
                $collection->addFieldToFilter("start_date", $start_date);
            } else {
                $collection->addFieldToFilter("start_date", array(
                    "null" => true
                ));
            }
            
            if ($entity->getEndDate() != "") {
                $strtotime_end_date = strtotime($entity->getEndDate());
                $end_date = date('Y-m-d', $strtotime_end_date);
                $collection->addFieldToFilter("end_date", $end_date);
            } else {
                $collection->addFieldToFilter("end_date", array(
                    "null" => true
                ));
            }
            
            $collection->addFieldToFilter("uom_entry", $entity->getUomEntry());
            
            $item = $collection->getFirstItem();
            $id = $item->getId();
            $model = $this->_objectManager->create('ITM\Pricing\Model\Customerprice');
            $model->load($id);
            
            $model->delete();
            // throw new \Exception("dsadas");
            
            $return_result->setError(false);
            $return_result->setData(json_encode(array(
                "code" => $entity->getCode()
            )));
        } catch (\Exception $e) {
            $return_result->setError(true);
            $return_result->setData(json_encode(array(
                "message" => $e->getMessage()
            )));
        }
        
        return $return_result;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function deleteMultiple($items)
    {
        $_saved_items = array();
        foreach ($items as $item) {
            $_saved_items[] = $this->deleteByObject($item);
        }
        return $_saved_items;
    }
}
