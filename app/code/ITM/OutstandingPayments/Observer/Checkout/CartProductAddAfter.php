<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Observer\Checkout;

class CartProductAddAfter implements \Magento\Framework\Event\ObserverInterface
{

    protected $_messageManager;

    protected $_cart;

    protected $_helper;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Cart $cart,
        \ITM\OutstandingPayments\Helper\Data $helper
    ) {
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

        $item = $observer->getQuoteItem();
        if (empty($item)) {
            $item = $observer->getProduct();
        }
        if ($item->getSku() == "sap_invoice") {
            $options = $item->getProduct()
                ->getTypeInstance(true)
                ->getOrderOptions($item->getProduct());
            if (isset($options['options'])) {
                $customOptions = $options['options'];

                // get DocEntry from Quote Item
                $docEntry_array = array_filter($customOptions, function ($var) {
                    return ($var['label'] == 'DocEntry');
                });
                $docEntry = current($docEntry_array)["value"];

                //get Amount from Quote Item
                $amount_array = array_filter($customOptions, function ($var) {
                    return ($var['label'] == 'Amount');
                });
                $requested_amount = current($amount_array)["value"];

                // get DocType from Quote Item
                $docType_array = array_filter($customOptions, function ($var) {
                    return ($var['label'] == 'Type');
                });

                $docType = current($docType_array)["value"];
                $final_amount = 0;
                if ($this->_helper->isValideInvoice($docEntry, $docType)) {
                    $company = $this->_helper->getCustomerSapCompany();
                    $invoiceOpenBalance = $this->_helper->getOpenBalanceByDocEntry($docEntry, $company, $docType);
                    $final_amount = $invoiceOpenBalance;

                    if ($requested_amount > $invoiceOpenBalance) {
                        $final_amount = $invoiceOpenBalance;
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __("Request amount is more than invoice open balance, Invoice open balance = %1", $invoiceOpenBalance)
                        );
                        return;
                    }
                    if ((float)$requested_amount == (float)$invoiceOpenBalance) {
                        $final_amount = $requested_amount;
                    }


                    if ($requested_amount < $invoiceOpenBalance) {
                        if (!$this->_helper->allowPartialPayment()) {
                            throw new \Magento\Framework\Exception\LocalizedException(
                                __("Partial payment is disabled for the moment, please use the total payment amount, open balance = %1", $invoiceOpenBalance)
                            );
                            return;
                        } else {
                            $final_amount = $requested_amount;
                        }
                    }

                    if ($final_amount == 0) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Amount is not valid for invoice %1.",
                            $docEntry));
                        return;
                    }

                    $item->setCustomPrice($final_amount);
                    $item->setOriginalCustomPrice($final_amount);
                } else {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Invoice %1 is not valid.",
                        $docEntry));
                    return;
                }
            }
        }
    }
}
