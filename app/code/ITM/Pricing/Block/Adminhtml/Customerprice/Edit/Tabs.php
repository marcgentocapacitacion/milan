<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */
namespace ITM\Pricing\Block\Adminhtml\Customerprice\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('itm_pricing_customerprice_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item'));
    }
}
