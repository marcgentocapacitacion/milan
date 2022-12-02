<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uomgroup;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Uomgroup
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
