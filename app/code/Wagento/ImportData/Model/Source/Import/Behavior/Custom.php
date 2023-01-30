<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\ImportData\Model\Source\Import\Behavior;

/**
 * Class Custom
 */
class Custom extends \Magento\ImportExport\Model\Source\Import\AbstractBehavior
{
    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE => __('Add/Update')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'wagento_custom';
    }
}
