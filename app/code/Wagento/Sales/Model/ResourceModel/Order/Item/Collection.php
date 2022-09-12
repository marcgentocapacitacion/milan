<?php

namespace Wagento\Sales\Model\ResourceModel\Order\Item;

use Magento\Sales\Model\ResourceModel\Order\Item\Collection as MagentoCollection;

/**
 * Class Collection
 */
class Collection extends MagentoCollection
{
    /**
     * @param int $customerId
     *
     * @return $this
     */
    public function getProductDistinctPerCustomer(int $customerId): self
    {
        $this->getSelect()->join(
            ['sales' => $this->getTable('sales_order')],
            "main_table.order_id = sales.entity_id",
            ['sales.created_at']
        )->where(
            'sales.customer_id = ?',
            $customerId
        )->group('main_table.product_id');
        return $this;
    }
}
