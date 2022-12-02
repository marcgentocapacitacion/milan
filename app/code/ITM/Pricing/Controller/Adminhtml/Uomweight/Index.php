<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Uomweight;
    
class Index extends \ITM\Pricing\Controller\Adminhtml\Uomweight
{
    
    /**
     * Uomweight list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ITM_Pricing::pricing');
        $resultPage->getConfig()->getTitle()->prepend(__('UOM Weight'));
        $resultPage->addBreadcrumb(__('ITM'), __('ITM'));
        $resultPage->addBreadcrumb(__('UOM Weight'), __('UOM Weight'));
        return $resultPage;
    }
}
