<?php

namespace Wagento\Quote\Model;

use Magento\Quote\Model\Quote\Address;

/**
 * Class Quote
 */
class Quote extends \Magento\Quote\Model\Quote
{
    /**
     * Validate minimum amount.
     *
     * @param bool $multishipping
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function validateMinimumAmount($multishipping = false)
    {
        $storeId = $this->getStoreId();
        $minOrderActive = $this->_scopeConfig->isSetFlag(
            'sales/minimum_order/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if (!$minOrderActive) {
            return true;
        }
        $includeDiscount = $this->_scopeConfig->getValue(
            'sales/minimum_order/include_discount_amount',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $minOrderMulti = $this->_scopeConfig->isSetFlag(
            'sales/minimum_order/multi_address',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $minAmount = $this->_scopeConfig->getValue(
            'sales/minimum_order/amount',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $taxInclude = $this->_scopeConfig->getValue(
            'sales/minimum_order/tax_including',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $addresses = $this->getAllAddresses();

        if (!$multishipping) {
            foreach ($addresses as $address) {
                /* @var $address Address */
                if (!$address->validateMinimumAmount()) {
                    return false;
                }
            }
            return true;
        }

        if (!$minOrderMulti) {
            foreach ($addresses as $address) {
                $taxes = $taxInclude
                    ? $address->getBaseTaxAmount() + $address->getBaseDiscountTaxCompensationAmount()
                    : 0;
                foreach ($address->getQuote()->getItemsCollection() as $item) {
                    /** @var \Magento\Quote\Model\Quote\Item $item */
                    if ($item->getSku() == 'sap_invoice') {
                        continue;
                    }
                    $amount = $includeDiscount ?
                        $item->getBaseRowTotal() - $item->getBaseDiscountAmount() + $taxes :
                        $item->getBaseRowTotal() + $taxes;

                    if ($amount < $minAmount) {
                        return false;
                    }
                }
            }
        } else {
            $baseTotal = 0;
            $amount = 0;
            foreach ($addresses as $address) {
                $taxes = $taxInclude
                    ? $address->getBaseTaxAmount() + $address->getBaseDiscountTaxCompensationAmount()
                    : 0;
                foreach ($address->getQuote()->getItemsCollection() as $item) {
                    if ($item->getSku() == 'sap_invoice') {
                        continue;
                    }
                    $amount += $includeDiscount ?
                        $item->getBaseRowTotal() - $item->getBaseDiscountAmount() + $taxes :
                        $item->getBaseRowTotal() + $taxes;
                }
                $baseTotal += $includeDiscount ?
                    $address->getBaseSubtotalWithDiscount() + $taxes :
                    $address->getBaseSubtotal() + $taxes;
            }

            if ($amount <= 0) {
                return true;
            }

            if ($baseTotal < $minAmount) {
                return false;
            }
        }
        return true;
    }
}
