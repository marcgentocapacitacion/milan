<?php
    
namespace ITM\Pricing\Controller\Adminhtml\Finalprice;
    
class MassDelete extends \ITM\Pricing\Controller\Adminhtml\Finalprice
{
    
    /**
     * @return void
     */
    public function execute()
    {
        $itemsIds = $this->getRequest()->getParam('entity_id');
        if (!is_array($itemsIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($itemsIds as $itemId) {
                    $model = $this->_objectManager->create('ITM\Pricing\Model\Finalprice');
                    $model->load($itemId);
                    $model->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of record(s) have been deleted.', count($itemsIds))
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('An error occurred while deleting record(s).'));
            }
        }
        $this->_redirect('itm_pricing/*/');
    }
}
