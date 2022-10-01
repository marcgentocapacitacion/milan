<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\StoreLocator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class StoreLocator
 */
class StoreLocator extends AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('store_locator', 'entity_id');
    }
}
