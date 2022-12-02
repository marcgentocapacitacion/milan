<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Model\ResourceModel;

class Customerdiscount extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_pricing_customerdiscount', 'id');
    }
}
