<?php
namespace ITM\Pricing\Api;

interface UomInterface
{

    /**
     * Get Items list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getUomList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomDataInterface $entity
     * @return ITM\Pricing\Api\Data\UomDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\UomDataInterface[] $items
     * @return ITM\Pricing\Api\Data\UomDataInterface[]
     */
    public function saveMultiple($items);

    /**
     *
     * @param string $entry
     * @return bool Will returned True if deleted
     */
    public function deleteByEntry($entry);
}
