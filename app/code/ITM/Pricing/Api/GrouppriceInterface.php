<?php
namespace ITM\Pricing\Api;

interface GrouppriceInterface
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
     * @param ITM\Pricing\Api\Data\GrouppriceDataInterface $entity
     * @return ITM\Pricing\Api\Data\GrouppriceDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\GrouppriceDataInterface[] $items
     * @return ITM\Pricing\Api\Data\GrouppriceDataInterface[]
     */
    public function saveMultiple($items);

    /**
     *
     * @param string $id
     * @return bool Will returned True if deleted
     */
    public function deleteById($id);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\GrouppriceDataInterface $entity
     * @return ITM\Pricing\Api\Data\ReturnResultDataInterface
     */
    public function deleteByObject($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\GrouppriceDataInterface[] $items
     * @return ITM\Pricing\Api\Data\ReturnResultDataInterface[]
     */
    public function deleteMultiple($items);
}
