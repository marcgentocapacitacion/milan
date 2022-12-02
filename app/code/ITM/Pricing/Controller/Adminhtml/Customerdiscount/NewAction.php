<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Customerdiscount;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Customerdiscount
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
