<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices
 * Copyright (C) 2017
 *
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Controller\Index;

class Closed extends \Magento\Customer\Controller\AbstractAccount
{
    
    protected $resultPageFactory;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
        ) {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
    }
    
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}