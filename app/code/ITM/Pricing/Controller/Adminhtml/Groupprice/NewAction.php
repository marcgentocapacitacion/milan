<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Groupprice;

class NewAction extends \ITM\Pricing\Controller\Adminhtml\Groupprice
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
