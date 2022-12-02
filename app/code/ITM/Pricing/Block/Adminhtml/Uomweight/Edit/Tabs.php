<?php
    
namespace ITM\Pricing\Block\Adminhtml\Uomweight\Edit;
    
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
            $this->setId('itm_pricing_uomweight_edit_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(__('UOM Weight'));
    }
}
