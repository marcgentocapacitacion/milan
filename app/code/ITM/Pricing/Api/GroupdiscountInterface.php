<?php
namespace ITM\Pricing\Api;

interface GroupdiscountInterface
{

    /**
     * Get Items list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\GroupdiscountDataInterface $entity
     * @return ITM\Pricing\Api\Data\GroupdiscountDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\GroupdiscountDataInterface[] $items
     * @return ITM\Pricing\Api\Data\GroupdiscountDataInterface[]
     */
    public function saveMultiple($items);

    /**
     *
     * @param string $id
     * @return bool Will returned True if deleted
     */
    public function deleteById($id);
}
