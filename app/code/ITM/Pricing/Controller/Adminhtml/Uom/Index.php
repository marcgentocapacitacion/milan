<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uom;

class Index extends \ITM\Pricing\Controller\Adminhtml\Uom
{
    /**
     * Uom list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('Unit Of Measurement'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Uom'), __('Uom'));
        return $resultPage;
    }
}
