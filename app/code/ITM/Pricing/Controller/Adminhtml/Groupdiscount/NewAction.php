<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Groupdiscount;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Groupdiscount
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
