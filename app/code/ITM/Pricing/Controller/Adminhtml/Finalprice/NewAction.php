<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Finalprice;
    
class NewAction extends \ITM\Pricing\Controller\Adminhtml\Finalprice
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
