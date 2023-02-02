<?php
namespace ITM\OutstandingPayments\Api\Sales;

interface OrderInterface
{

    /**
     * Return Order Info.
     *
     * @api
     *
     * @param string $increment_id
     * Left hand operand.
     * \Magento\Sales\Api\Data\OrderInterface result.
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getOrderInfo($increment_id);
   
    
    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getOrderList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
