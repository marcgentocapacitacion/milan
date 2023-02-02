<?php
/**
 * This extension for Customer Credit
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace ITM\OutstandingPayments\Observer\Payment;

class MethodIsActive implements \Magento\Framework\Event\ObserverInterface
{

    protected $_helper;

    public function __construct(\ITM\OutstandingPayments\Helper\Data $helper)
    {
        $this->_helper = $helper;
    }

    public function _log($message) {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/magb1.log');
        
        $logger = new \Zend\Log\Logger();
        
        $logger->addWriter($writer);
        
        $logger->info($message);
    }
    
    /**
     * Execute observer
     * 
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_helper->currentCartContainInvoiceItem()) {
           
            $_allowded_payment_methods = $this->_helper->getAllowedPaymentMethods();
            $event = $observer->getEvent();
            $method = $event->getMethodInstance();
            $result = $event->getResult();
            if (!in_array($method->getCode(), $_allowded_payment_methods)) {
                $result["is_available"] = 0;
            }
            
        }
    }
}
