<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Company;
    
class NewAction extends \ITM\OutstandingPayments\Controller\Adminhtml\Company
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
