<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Uomweight;
    
class NewAction extends \ITM\Pricing\Controller\Adminhtml\Uomweight
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
