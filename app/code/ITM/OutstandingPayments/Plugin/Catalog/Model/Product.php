<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding Payments
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace  ITM\OutstandingPayments\Plugin\Catalog\Model;

class Product
{

    public function aroundGetVisibility(
        \Magento\Catalog\Model\Product $subject,
        callable $proceed)
    {
        $returnValue = $proceed();
        if($subject->getSku() == "sap_invoice") {
            return false;
        }
        return $returnValue;
    }
}