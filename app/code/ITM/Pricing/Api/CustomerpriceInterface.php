<?php
namespace ITM\Pricing\Api;

interface CustomerpriceInterface
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
     * @param ITM\Pricing\Api\Data\CustomerpriceDataInterface $entity
     * @return ITM\Pricing\Api\Data\CustomerpriceDataInterface
     */
    public function save($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\CustomerpriceDataInterface[] $items
     * @return ITM\Pricing\Api\Data\CustomerpriceDataInterface[]
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
     * @param ITM\Pricing\Api\Data\CustomerpriceDataInterface $entity
     * @return ITM\Pricing\Api\Data\ReturnResultDataInterface
     */
    public function deleteByObject($entity);

    /**
     * @api
     *
     * @param ITM\Pricing\Api\Data\CustomerpriceDataInterface[] $items
     * @return ITM\Pricing\Api\Data\ReturnResultDataInterface[]
     */
    public function deleteMultiple($items);
}
