<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice;
    
class NewAction extends \ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
