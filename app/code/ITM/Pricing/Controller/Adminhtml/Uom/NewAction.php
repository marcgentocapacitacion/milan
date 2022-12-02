<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uom;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Uom
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
