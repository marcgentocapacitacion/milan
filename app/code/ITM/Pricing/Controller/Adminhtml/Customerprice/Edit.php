<?php
/**
 * Copyright © 2015 ITM.
 * All rights reserved.
 */
namespace ITM\Pricing\Controller\Adminhtml\Customerprice;

class Edit extends \ITM\Pricing\Controller\Adminhtml\Customerprice
{

    public function execute()
    {
        
        
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\Pricing\Model\Customerprice');
        
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
                ->prepend(__('Edit Customer Price Entry'));
        } else {
            $resultPage->getConfig()
                ->getTitle()
                ->prepend(__('Add Customer Price Entry'));
        }
        $this->_coreRegistry->register('current_itm_pricing_customerprice', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('customerprice_customerprice_edit');
        $this->_view->renderLayout();
    }
}
