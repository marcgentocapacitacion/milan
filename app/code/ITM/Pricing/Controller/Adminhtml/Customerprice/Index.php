<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Customerprice;

class Index extends \ITM\Pricing\Controller\Adminhtml\Customerprice
{
    /**
     * Customerprice list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Price (Special Price)'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Customerprice'), __('Customerprice'));
        return $resultPage;
    }
}
