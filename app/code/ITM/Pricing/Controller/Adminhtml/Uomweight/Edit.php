<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Uomweight;
    
class Edit extends \ITM\Pricing\Controller\Adminhtml\Uomweight
{
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uomweight');
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
    
        $this->_coreRegistry->register('current_itm_pricing_uomweight', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('uomweight_uomweight_edit');
        $this->_view->renderLayout();
    }
}
