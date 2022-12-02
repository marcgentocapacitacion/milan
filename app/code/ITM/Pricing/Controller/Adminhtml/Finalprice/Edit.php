<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Finalprice;
    
class Edit extends \ITM\Pricing\Controller\Adminhtml\Finalprice
{
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\Pricing\Model\Finalprice');
        if ($id) {
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('itm_pricing/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $resultPage = $this->resultPageFactory->create();
        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Items Entry'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Add Items Entry'));
        }
    
        $this->_coreRegistry->register('current_itm_pricing_finalprice', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('finalprice_finalprice_edit');
        $this->_view->renderLayout();
    }
}
