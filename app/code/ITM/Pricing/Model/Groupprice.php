<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Model;

class Groupprice extends \Magento\Framework\Model\AbstractModel
{
    
    protected $_eventPrefix = 'pricing_groupprice';
    
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\Pricing\Model\ResourceModel\Groupprice');
    }
}
