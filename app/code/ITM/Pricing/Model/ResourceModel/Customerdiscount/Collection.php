<?php
/**
 * Copyright © 2015 ITM.
 * All rights reserved.
 */
namespace ITM\Pricing\Model\ResourceModel\Customerdiscount;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define ResourceModel model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\Pricing\Model\Customerdiscount', 'ITM\Pricing\Model\ResourceModel\Customerdiscount');
    }
}
