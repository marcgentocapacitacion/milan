<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Controller\Adminhtml\Uom;

class MassDelete extends \ITM\Pricing\Controller\Adminhtml\Uom
{

    /**
     *
     * @return void
     */
    public function execute()
    {
        $uomIds = $this->getRequest()->getParam('id');
        
        if (! is_array($uomIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($uomIds as $uomId) {
                    $model = $this->_objectManager->create('ITM\Pricing\Model\Uom');
                    $model->load($uomId);
                    $model->delete();
                }
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($uomIds)));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('An error occurred while deleting record(s).'));
            }
        }
        $this->_redirect('itm_pricing/*/');
    }
}
