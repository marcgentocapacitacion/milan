<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

namespace ITM\Pricing\Controller\Adminhtml\System;

class RefreshCache extends \Magento\Backend\App\Action
{
    
    
    protected $_pricing_helper;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \ITM\Pricing\Helper\Data $dataHelper
        //\Magento\Framework\App\RequestInterface $request
    ) {
        parent::__construct($context);
        $this->_pricing_helper = $dataHelper;
       // $this->_request = $request;
    }
    
    public function execute()
    {
        $model = $this->_request->getParam("model");
        $this->refresh();
        $this->messageManager->addSuccess(
            __('The Cache Refreshed Successfully')
        );
        $this->_redirect('itm_pricing/'.$model);
        return;
    }
    
    /**
     *
     * {@inheritdoc}
     *
     */
    public function refresh()
    {
        return  $this->_pricing_helper->refreshCache();
    }
}
