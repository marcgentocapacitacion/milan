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

namespace ITM\OutstandingPayments\Observer\Sales;

class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{

    
    protected $_helper;
    
    public function __construct(
        \ITM\OutstandingPayments\Helper\Data $helper
        ) {
            $this->_helper = $helper;
    }
    
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $order = $observer->getOrder();
        if( $this->_helper->orderContainInvoiceItem($order)) {
            $customer_company = $this->_helper->getCustomerSapCompany($order->getCustomerId());
            $order->setItmSboDownloadToSap('0');
            $order->setItmOrderType("invoice");
            $order->setItmSapCompany($customer_company);
        }
        return $this;
    }
}