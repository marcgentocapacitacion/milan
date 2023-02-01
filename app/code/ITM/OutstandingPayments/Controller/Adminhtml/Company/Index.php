<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Company;
    
class Index extends \ITM\OutstandingPayments\Controller\Adminhtml\Company
{
    
    /**
     * Company list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_OutstandingPayments::outstandingpayments');
        $resultPage->getConfig()->getTitle()->prepend(__('Company'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Company'), __('Company'));
        return $resultPage;
    }
}
