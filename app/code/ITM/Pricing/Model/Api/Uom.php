<?php
namespace ITM\Pricing\Model\Api;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use ITM\Pricing\Api\UomInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class Uom implements UomInterface
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
    public function getUomList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uom\Collection');
        
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
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uom\Collection');
        $collection->addFieldToFilter("uom_entry", $entity->getUomEntry());
        $item = $collection->getFirstItem();
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uom');
        
        if ($item->getId()) {
            $model->load($item->getId());
        }
        
        $model->setUomEntry($entity->getUomEntry())
            ->setUomCode($entity->getuomCode())
            ->setUomName($entity->getUomName())
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
            $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uom\Collection');
            $collection->addFieldToFilter("uom_entry", $entry);
            $item = $collection->getFirstItem();
            $id = $item->getId();
            $model = $this->_objectManager->create('ITM\Pricing\Model\Uom');
            $model->load($id);
            $model->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }
}
