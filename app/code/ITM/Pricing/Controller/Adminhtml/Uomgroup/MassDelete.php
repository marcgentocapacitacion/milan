<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Controller\Adminhtml\Uomgroup;

class MassDelete extends \ITM\Pricing\Controller\Adminhtml\Uomgroup
{

    /**
     *
     * @return void
     */
    public function execute()
    {
        $Ids = $this->getRequest()->getParam('id');
        
        if (! is_array($Ids)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($Ids as $Id) {
                    $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroup');
                    $model->load($Id);
                    $model->delete();
                }
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($Ids)));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('An error occurred while deleting record(s).'));
            }
        }
        $this->_redirect('itm_pricing/*/');
    }
}
