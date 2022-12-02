<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uomgroupdetails;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Uomgroupdetails
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
