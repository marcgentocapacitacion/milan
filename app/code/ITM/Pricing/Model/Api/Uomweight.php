<?php
    
namespace ITM\Pricing\Model\Api;
    
use ITM\Pricing\Api\UomweightInterface;
                
class Uomweight implements UomweightInterface
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
     *
     * {@inheritdoc}
     *
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomweight\Collection');
        $result=$collection->getData();
        $searchResult = $this->_searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($result);
        $searchResult->setTotalCount(count($result));
        return $searchResult;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function save($entity)
    {
        $collection = $this->_objectManager->create('\ITM\Pricing\Model\ResourceModel\Uomweight\Collection');
        $collection->addFieldToFilter("entity_id", $entity->getId());
        $item = $collection->getFirstItem();
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uomweight');
        
        if ($item->getEntityId()) {
            $model->load($item->getEntityId());
        }
            
        $model->setSku($entity->getSku());
        $model->setUomEntry($entity->getUomEntry());
        $model->setWeight($entity->getWeight());
        $model->save();
            
        return $model;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function deleteByEntityId($id)
    {
        try {
            $model = $this->_objectManager->create('ITM\Pricing\Model\Uomweight');
            $model->load($id);
            $model->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}
