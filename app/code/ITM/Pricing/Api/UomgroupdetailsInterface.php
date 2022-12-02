<?php
namespace ITM\Pricing\Api;

interface UomgroupdetailsInterface
{

    /**
     * Get Items list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getUomGroupDetailsList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomgroupdetailsDataInterface $entity
     * @return ITM\Pricing\Api\Data\UomgroupdetailsDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomgroupdetailsDataInterface[] $items
     * @return ITM\Pricing\Api\Data\UomgroupdetailsDataInterface[]
     */
    public function saveMultiple($items);

    /**
     *
     * @param string $ugp_entry
     * @param string $uom_entry
     * @return bool Will returned True if deleted
     */
    public function deleteByEntry($ugp_entry, $uom_entry);
}
