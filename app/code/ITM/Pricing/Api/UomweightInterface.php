<?php
        
namespace ITM\Pricing\Api;
        
interface UomweightInterface
{

    /**
     * Get list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
    
    /**
     *
     * @api
     * @param ITM\Pricing\Api\Data\UomweightDataInterface $entity
     * @return ITM\Pricing\Api\Data\UomweightDataInterface
     */
    public function save($entity);
    
    /**
     * @param int $id
     * @return bool Will returned true if deleted
     */
    public function deleteByEntityId($id);
}
