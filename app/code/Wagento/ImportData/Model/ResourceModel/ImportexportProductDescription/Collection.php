<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription;

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
            \Wagento\ImportData\Model\ImportexportProductDescription::class,
            \Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription::class
        );
    }
}
