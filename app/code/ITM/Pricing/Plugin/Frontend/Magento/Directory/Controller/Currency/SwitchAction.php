<?php
/**
 * Copyright © ITM Development All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\Pricing\Plugin\Frontend\Magento\Directory\Controller\Currency;

class SwitchAction
{

    /**
     * @var \ITM\Pricing\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \ITM\Pricing\Helper\Setting
     */
    protected $_setting;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var bool
     */
    protected $alllow_currency;

    /**
     * @var
     */
    protected $_storeCurrentCurrencyCode;

    /**
     * @var
     */
    protected $_storeBaseCurrencyCode;

    /**
     * @var string
     */
    protected $_currency;
    /**
     * @param \ITM\Pricing\Helper\Data $dataHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \ITM\Pricing\Helper\Setting $setting
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\Currency $currency
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        \ITM\Pricing\Helper\Data $dataHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \ITM\Pricing\Helper\Setting $setting,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency

    )
    {
        $this->_helper = $dataHelper;
        $this->_objectManager = $objectManager;
        $this->_checkoutSession = $checkoutSession;
        $this->_setting = $setting;
        $this->_storeManager = $storeManager;
        $this->_storeCurrentCurrencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        $this->_storeBaseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrencyCode();
        $this->_currency = $currency->load($this->_storeBaseCurrencyCode);
        $this->alllow_currency =  $this->_setting->getAllowCurrency();
    }
    public function afterExecute(\Magento\Directory\Controller\Currency\SwitchAction $subject, $result) {
        if($this->alllow_currency) {
            $this->updateCart();
        }
        return $result;
    }

    private function getProductPriceForCurrency($price)
    {
        if( $this->alllow_currency) {
            $correct_price = $this->_currency->convert($price, $this->_storeCurrentCurrencyCode);
            return $correct_price;
        }
        return $price;
    }
    private function updateCart()
    {

        $quote = $this->_checkoutSession->getQuote();
        if (empty($quote->getId())) {
            return;
        }
        $cartItems = $quote->getAllVisibleItems();
        $need_update = false;
        foreach ($cartItems as $key => $item) {
            if (in_array($item->getSku(), $this->_helper->getExcludedSkus())) {
                //  continue;
            }
            $buyRequestArr = [];
            if ($buyRequest = $item->getProduct()->getCustomOption('info_buyRequest')) {
                $buyRequestArr = $this->_helper->itmUnserialize($buyRequest->getValue());
                if (isset($buyRequestArr['bundle_option'])) {
                    continue;
                }
            }


            if ($item->getSku() == "sap_invoice") {
                continue;
            }

            if ($item->getProduct()->getTypeId() == "bundle") {
                continue;
            }

            if ($item->getBuyRequest()->getData("cus_price")) {
                continue;
            }
            $_product = $item->getProduct();
            $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
            $product = $productRepository->get($_product->getSku());

            //  $item->getProduct()->load($item->getProduct()->getId());
            $uom_entry = $item->getBuyRequest()->getData("itm_uom_entry");
            $qty = $item->getQty();
            $new_price = $this->_helper->getItemPrice($product, $uom_entry, $qty);

            if ($new_price >= 0) {

                $item->setCustomPrice($this->getProductPriceForCurrency($new_price));
                $item->setOriginalCustomPrice($this->getProductPriceForCurrency($new_price));
            } else {
                $_finalPrice = $this->getProductPriceForCurrency($item->getProduct()->getFinalPrice());
                $item->setCustomPrice($_finalPrice);
                $item->setOriginalCustomPrice($_finalPrice);
            }

            //$item->setCustomPrice(6);
            //$item->setOriginalCustomPrice(6);
            $item->calcRowTotal();
            $item->save();
            $need_update = true;
        }
        if ($need_update) {
            $quote->collectTotals();
            $quote->save();
        }
    }
}
