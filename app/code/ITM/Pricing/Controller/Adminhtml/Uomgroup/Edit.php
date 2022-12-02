<?php
/**
 * Copyright © 2015 ITM.
 * All rights reserved.
 */
namespace ITM\Pricing\Controller\Adminhtml\Uomgroup;

class Edit extends \ITM\Pricing\Controller\Adminhtml\Uomgroup
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\Pricing\Model\Uomgroup');
        
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('itm_pricing/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (! empty($data)) {
            $model->addData($data);
        }
        $resultPage = $this->resultPageFactory->create();
        if ($id) {
            $resultPage->getConfig()
                ->getTitle()
                ->prepend(__('Edit UOM Group Entry'));
        } else {
            $resultPage->getConfig()
                ->getTitle()
                ->prepend(__('Add UOM Group Entry'));
        }
        $this->_coreRegistry->register('current_itm_pricing_uomgroup', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('uomgroup_uomgroup_edit');
        $this->_view->renderLayout();
    }
}
