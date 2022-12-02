<?php
namespace ITM\Pricing\Model\Api;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use ITM\Pricing\Api\UomgroupInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class Uomgroup implements UomgroupInterface
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
    public function getUomGroupList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroup\Collection');
        
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
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroup\Collection');
        $collection->addFieldToFilter("ugp_entry", $entity->getUgpEntry());
        $item = $collection->getFirstItem();
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroup');
        
        if ($item->getId()) {
            $model->load($item->getId());
        }
        
        $model->setUgpEntry($entity->getUgpEntry())
            ->setUgpCode($entity->getUgpCode())
            ->setUgpName($entity->getUgpName())
            ->setBaseUom($entity->getBaseUom())
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
    public function deleteByEntry($entry)
    {
        try {
            $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomgroup\Collection');
            $collection->addFieldToFilter("ugp_entry", $entry);
            $item = $collection->getFirstItem();
            $id = $item->getId();
            $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroup');
            $model->load($id);
            $model->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
}
