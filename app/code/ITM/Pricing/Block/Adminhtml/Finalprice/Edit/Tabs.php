<?php
    
namespace ITM\Pricing\Block\Adminhtml\Finalprice\Edit;
    
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
            $this->setId('itm_pricing_finalprice_edit_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(__('Finalprice'));
    }
}
