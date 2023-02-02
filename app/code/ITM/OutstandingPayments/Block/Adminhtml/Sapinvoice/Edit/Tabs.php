<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml\Sapinvoice\Edit;
    
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
            $this->setId('itm_outstandingpayments_sapinvoice_edit_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(__('Sapinvoice'));
    }
}
