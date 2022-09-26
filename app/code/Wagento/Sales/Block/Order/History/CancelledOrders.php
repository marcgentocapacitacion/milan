<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Customer\Model\Session;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

/**
 * Class CancelledOrders
 */
class CancelledOrders extends \Wagento\Sales\Block\Order\History\Orders
{
    /**
     * Get customer orders
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'main_table.status',
                ['in' => [
                    Order::STATE_CANCELED,
                    Order::STATE_CLOSED
                ]]
            )->setOrder(
                'main_table.created_at',
                'desc'
            );
            $this->addFilterSearchText();
        }
        return $this->orders;
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getCreditMemoUrl($order)
    {
        return $this->getUrl('sales/order/creditmemo', ['order_id' => $order->getId()]);
    }
}
