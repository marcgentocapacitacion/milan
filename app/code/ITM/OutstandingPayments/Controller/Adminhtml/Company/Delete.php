<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Company;
    
class Delete extends \ITM\OutstandingPayments\Controller\Adminhtml\Company
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('ITM\OutstandingPayments\Model\Company');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('itm_outstandingpayments/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('itm_outstandingpayments/*/edit', [
                        'id' => $this->getRequest()->getParam('id')
                ]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('itm_outstandingpayments/*/');
    }
}
