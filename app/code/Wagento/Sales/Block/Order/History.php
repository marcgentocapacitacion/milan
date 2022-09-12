<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\Sales\Block\Order;

/**
 * Class History
 */
class History extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    public CONST HISTORY_PAGE_ORDER = 'order';

    /**
     * @var string
     */
    public CONST HISTORY_PAGE_CANCELLED_ORDER = 'cancelled_orders';

    /**
     * @var string
     */
    public CONST HISTORY_PAGE_REORDER_PRODUCTS = 'reorder_products';

    /**
     * @return bool
     */
    public function isOrder(): bool
    {
        if (!$this->getRequest()->getParam('history_page')) {
            return true;
        }
        return $this->getRequest()->getParam('history_page') == self::HISTORY_PAGE_ORDER;
    }

    /**
     * @return bool
     */
    public function isCancelledOrder(): bool
    {
        return $this->getRequest()->getParam('history_page') == self::HISTORY_PAGE_CANCELLED_ORDER;
    }

    /**
     * @return bool
     */
    public function isReorderProducts(): bool
    {
        return $this->getRequest()->getParam('history_page') == self::HISTORY_PAGE_REORDER_PRODUCTS;
    }
}
