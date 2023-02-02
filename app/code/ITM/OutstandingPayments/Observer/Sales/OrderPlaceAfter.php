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

class OrderPlaceAfter  implements \Magento\Framework\Event\ObserverInterface
{
   
    protected $_helper;
    
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    
    
    
    
    
    public function __construct(
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager
        ) {
            $this->_helper = $helper;
            $this->_objectManager = $objectManager;
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
        $order = $observer->getEvent()->getOrder();
        
        if($this->_helper->orderContainInvoiceItem($order)) {
            $order_docEntry_list = [];
            $company = $this->_helper->getCustomerSapCompany($order->getCustomerId());
            $orderData = $order->getAllItems();
            foreach ($orderData as $item) {
              //  $_quote_item = $item->getQuoteItem();
              //  $product = $_quote_item->getProduct();
                
                $options = $item->getProductOptions();
                if (isset($options['options'])) {
                    $customOptions = $options['options'];
                    $docEntry_array = array_filter($customOptions,function ($var) {
                        return ($var['label'] == 'DocEntry');
                    });
                    $docType_array = array_filter($customOptions,function ($var) {
                        return ($var['label'] == 'Type');
                    });
                    $order_docEntry_list[] = $this->_helper->getDocType(current($docType_array)["value"])."_".current($docEntry_array)["value"];
                }
            }
            foreach ($order_docEntry_list as $doc_entry) {
                $ID = explode("_",$doc_entry);
                $docType =  $ID[0];
                $docEntry = $ID[1];
                $invoice = $this->_helper->getInvoice($docEntry, $company, $docType);
                //throw new \Magento\Framework\Exception\LocalizedException(__("ID ".$invoice->getId()));

                if($invoice) {
                    $model = $this->_objectManager->create('ITM\OutstandingPayments\Model\Sapinvoice');
                    $model->load($invoice->getId());
                    $model->setInvoiceStatus("p")->save();
                }
                
            }
            $this->_helper->disableCart();
        }
    }
}