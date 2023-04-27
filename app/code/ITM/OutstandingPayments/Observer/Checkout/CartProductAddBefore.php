<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace ITM\OutstandingPayments\Observer\Checkout;

class CartProductAddBefore implements \Magento\Framework\Event\ObserverInterface
{

    protected $_messageManager;

    protected $_cart;

    protected $_helper;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Cart $cart,
        \ITM\OutstandingPayments\Helper\Data $helper
    )
    {
        $this->_messageManager = $messageManager;
        $this->_cart = $cart;
        $this->_helper = $helper;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $product = $observer->getProduct();
        $sku = trim($product->getSku());
        if ($sku != "sap_invoice") {
            if($this->_helper->cartContainInvoiceItem($this->_cart)) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Please empty your cart before you add product to cart."));
                return;
            }
        }else {

            $info = $observer->getInfo();
            if (isset($info['options'])) {
                $options = $info['options'];
                $docEntryOptionId = $this->_helper->getDocEntryOptionId($product);
                $docTypeOptionId = $this->_helper->getDocTypeOptionId($product);

                $docEntry = $options[$docEntryOptionId];
                $docTypeId = $options[$docTypeOptionId];

                $type_values = $this->_helper->getTypeValuesOptions($product, true);
                $docType = $this->_helper->getDocType($type_values[$docTypeId]);

                $docET = $docType . "_" . $docEntry;


                $cart_docEntry_list = $this->_helper->getCartDocEntryList($this->_cart);

                if (in_array($docET, $cart_docEntry_list)) {
                    if ($docType == "in") {
                        $msg = __("Invoice '%1' is already in the cart.", $docEntry);
                    } else if ($docType == "dt") {
                        $msg = __("Down Payment '%1' is already in the cart.", $docEntry);
                    }
                    throw new \Magento\Framework\Exception\LocalizedException($msg);
                }

            }
        }
    }
}
