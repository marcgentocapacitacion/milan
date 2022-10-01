<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\StoreLocator\Model\ResourceModel\StoreLocator;

/**
 * Class Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Wagento\StoreLocator\Model\StoreLocator::class,
            \Wagento\StoreLocator\Model\ResourceModel\StoreLocator::class
        );
    }
}
