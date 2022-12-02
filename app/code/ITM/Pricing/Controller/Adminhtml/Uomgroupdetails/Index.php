<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uomgroupdetails;

class Index extends \ITM\Pricing\Controller\Adminhtml\Uomgroupdetails
{
    /**
     * Uomgroupdetails list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('Uom Group Details'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Uomgroupdetails'), __('Uomgroupdetails'));
        return $resultPage;
    }
}
