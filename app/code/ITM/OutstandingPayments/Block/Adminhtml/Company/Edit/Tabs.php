<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml\Company\Edit;
    
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
            $this->setId('itm_outstandingpayments_company_edit_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(__('Company'));
    }
}
