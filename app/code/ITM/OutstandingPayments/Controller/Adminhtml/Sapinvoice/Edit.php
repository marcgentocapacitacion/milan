<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice;
    
class Edit extends \ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice
{
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\OutstandingPayments\Model\Sapinvoice');
        if ($id) {
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('itm_outstandingpayments/*');
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
    
        $this->_coreRegistry->register('current_itm_outstandingpayments_sapinvoice', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('sapinvoice_sapinvoice_edit');
        $this->_view->renderLayout();
    }
}
