<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\System;

class IndexCustomerTables extends \Magento\Backend\App\Action
{
    
    protected $_objectManager;

    protected $_phelper;
    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        //\Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\Pricing\Helper\Data $helper
      //  \Magento\Framework\App\RequestInterface $request
    ) {
        parent::__construct($context);
      //  $this->_request = $request;
        //$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$this->_objectManager = $objectManager;
        $this->_objectManager = $context->getObjectManager();
        $this->_phelper = $helper;
    }
    
    public function execute()
    {
        $model = $this->_request->getParam("model");
        if($this->_phelper->useIndexTables()) {
            $this->index();
            $this->messageManager->addSuccess(
                __('Tables Indexed Successfully')
            );
        }else{
            $this->messageManager->addNotice(
                __('To be able to use the Index Tables please change the configuration to Yes')
            );
        }
        $this->_redirect('itm_pricing/'.$model);
        return;
    }
    
    /**
     *
     * {@inheritdoc}
     *
     */
    public function index()
    {
        $index = $this->_objectManager->get('\ITM\Pricing\Console\IndexCustomerTables');
        $index->DoIndexCustomerTables();
        return true;
    }
}
