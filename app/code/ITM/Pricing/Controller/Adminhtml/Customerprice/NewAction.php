<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Customerprice;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Customerprice
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
