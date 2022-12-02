<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Finalprice;
    
class Index extends \ITM\Pricing\Controller\Adminhtml\Finalprice
{
    
    /**
     * Finalprice list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('Finalprice'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('Finalprice'), __('Finalprice'));
        return $resultPage;
    }
}
