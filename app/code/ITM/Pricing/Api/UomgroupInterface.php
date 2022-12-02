<?php
namespace ITM\Pricing\Api;

interface UomgroupInterface
{

    /**
     * Get Items list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getUomGroupList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomgroupDataInterface $entity
     * @return ITM\Pricing\Api\Data\UomgroupDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomgroupDataInterface[] $items
     * @return ITM\Pricing\Api\Data\UomgroupDataInterface[]
     */
    public function saveMultiple($items);

    /**
     *
     * @param string $entry
     * @return bool Will returned True if deleted
     */
    public function deleteByEntry($entry);
}
