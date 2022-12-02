<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Controller\Adminhtml\Groupprice;

class MassDelete extends \ITM\Pricing\Controller\Adminhtml\Groupprice
{

    /**
     *
     * @return void
     */
    public function execute()
    {
        $itemsIds = $this->getRequest()->getParam('id');
        
        if (! is_array($itemsIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($itemsIds as $itemId) {
                    $model = $this->_objectManager->create('ITM\Pricing\Model\Groupprice');
                    $model->load($itemId);
                    $model->delete();
                }
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($itemsIds)));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('An error occurred while deleting record(s).'));
            }
        }
        $this->_redirect('itm_pricing/*/');
    }
}
