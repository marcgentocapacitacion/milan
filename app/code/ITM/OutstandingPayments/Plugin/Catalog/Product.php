<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding Payments
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace  ITM\OutstandingPayments\Plugin\Catalog;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;


class Product
{
    protected $_objectManager;
    protected $_helper;
    protected $_messageManager;
    protected $_request;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->_objectManager = $objectManager;
        $this->_helper = $helper;
        $this->_messageManager = $messageManager;
        $this->_request = $request;
    }
    
   
    
  
    /**
     * beforeAddProduct
     *
     * @param      $subject
     * @param      $productInfo
     * @param null $requestInfo
     *
     * @return array
     * @throws LocalizedException
     */
    public function beforeAddProduct(
        \Magento\Checkout\Model\Cart $subject,
        $productInfo,
        $requestInfo = null)
    {


        if($productInfo->getSku() != "sap_invoice") {
            if($this->_helper->cartContainInvoiceItem($subject)) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Please empty your cart before you add product to cart."));
                return;
            }else {
                return array($productInfo, $requestInfo);
            }
        }
        
        if($this->_helper->cartContainAnotherItem($subject)) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Please empty your cart before you pay the invoice."));
            return;
        }
        $company = $this->_helper->getCustomerSapCompany();
        
        $docEntryOptionId = $this->_helper->getDocEntryOptionId($productInfo);
        $amountOptionId = $this->_helper->getAmountOptionId($productInfo);
        $typeOptionId = $this->_helper->getDocTypeOptionId($productInfo);

        $ID = explode("_",$this->_request->getParam("doc_entry"));
        $doc_type =  $ID[0];
        $doc_entry = $ID[1];


        $requested_docEntry = $requestInfo["options"][$docEntryOptionId];
        $invoiceOpenBalance = $this->_helper->getOpenBalanceByDocEntry($requested_docEntry, $company,$doc_type);
        
        $cart_docEntry_list = $this->_helper->getCartDocEntryList($subject);

        if( in_array( $requested_docEntry,$cart_docEntry_list)) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Invoice %1 is already in the cart.",$requested_docEntry));
            return;
        }
        if(!$this->_helper->isValideInvoice($requested_docEntry, $doc_type)) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Invoice %1 is not valid.",$requested_docEntry));
            return;
        }
        
        $amount = $requestInfo["options"][$amountOptionId];
        if($amount == "") {
            $requestInfo["options"][$amountOptionId]= $invoiceOpenBalance;
        } else {
            if($amount < $invoiceOpenBalance) {
                if(!$this->_helper->allowPartialPayment()) {
                    $requestInfo["options"][$amountOptionId]= $invoiceOpenBalance;
                    $this->_messageManager->addNotice(__("Partial payment is disabled for the moment, the amount set to the invoice open balance = %1",$invoiceOpenBalance));
                }
            }
        }
        return array($productInfo, $requestInfo);
    }
}