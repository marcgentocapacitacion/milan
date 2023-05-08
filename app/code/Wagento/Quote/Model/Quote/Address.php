<?php

namespace Wagento\Quote\Model\Quote;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Address
 */
class Address extends \Magento\Quote\Model\Quote\Address
{
    /**
     * Validate minimum amount
     *
     * @return bool
     */
    public function validateMinimumAmount()
    {
        $storeId = $this->getQuote()->getStoreId();
        $validateEnabled = $this->_scopeConfig->isSetFlag(
            'sales/minimum_order/active',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if (!$validateEnabled) {
            return true;
        }

        if (!$this->getQuote()->getIsVirtual() xor $this->getAddressType() == self::TYPE_SHIPPING) {
            return true;
        }

        $includeDiscount = $this->_scopeConfig->getValue(
            'sales/minimum_order/include_discount_amount',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $amount = $this->_scopeConfig->getValue(
            'sales/minimum_order/amount',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $taxInclude = $this->_scopeConfig->getValue(
            'sales/minimum_order/tax_including',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $taxes = $taxInclude
            ? $this->getBaseTaxAmount() + $this->getBaseDiscountTaxCompensationAmount()
            : 0;

        $amountItem = 0;
        foreach ($this->getQuote()->getItemsCollection() as $item) {
            if ($item->getSku() == 'sap_invoice') {
                $amountItem += $includeDiscount ?
                    $item->getBaseRowTotal() - $item->getBaseDiscountAmount() + $taxes :
                    $item->getBaseRowTotal() + $taxes;
            }
        }
        $subTotal = $includeDiscount ?
            ($this->getBaseSubtotalWithDiscount() + $taxes) :
            ($this->getBaseSubtotal() + $taxes);
        if (($subTotal - $amountItem) <= 0.00) {
            return true;
        }

        if ($subTotal > ($amount - 0.0001)) {
            return true;
        }

        // Note: ($x > $y - 0.0001) means ($x >= $y) for floats
        return false;
    }
}
