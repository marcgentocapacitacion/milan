<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice;
    
class Index extends \ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice
{
    
    /**
     * Sapinvoice list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_OutstandingPayments::outstandingpayments');
        $resultPage->getConfig()->getTitle()->prepend(__('Sapinvoice'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Sapinvoice'), __('Sapinvoice'));
        return $resultPage;
    }
}
