<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\ImportData\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ImportexportProductDescription
 */
class ImportexportProductDescription extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('importexport_product_description', 'entity_id');
    }
}
