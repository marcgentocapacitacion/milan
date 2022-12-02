<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\Uomgroup;

class Index extends \ITM\Pricing\Controller\Adminhtml\Uomgroup
{
    /**
     * Uomgroup list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('UOM Group'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Uomgroup'), __('Uomgroup'));
        return $resultPage;
    }
}
