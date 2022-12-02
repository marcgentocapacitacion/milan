<?php

namespace ITM\Pricing\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Frontend implements ObserverInterface
{

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \ITM\Pricing\Helper\Data
     */
    protected $_helper;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var string
     */
    protected $useNewPriceAsPrice;

    /**
     * @var array
     */
    protected $setProductAttributes = [];

    /**
     * @var int
     */
    protected $itmUomEntryId;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

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
     * @var \ITM\Pricing\Helper\Setting
     */
    protected $_setting;

    /**
     * @var bool
     */
    protected $alllow_currency;

    /**
     * @param LoggerInterface $logger
     * @param \ITM\Pricing\Helper\Data $dataHelper
     * @param RequestInterface $request
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Eav\Model\AttributeRepository $attributeRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\Currency $currency
     * @param \ITM\Pricing\Helper\Setting $setting
     */

    public function __construct(
        LoggerInterface                                    $logger,
        \ITM\Pricing\Helper\Data                           $dataHelper,
        RequestInterface                                   $request,
        \Magento\Framework\ObjectManagerInterface          $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Model\AttributeRepository             $attributeRepository,
        \Magento\Checkout\Model\Session                    $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Directory\Model\Currency                  $currency,
        \ITM\Pricing\Helper\Setting                        $setting
    )
    {
        $this->_logger = $logger;
        $this->_helper = $dataHelper;
        $this->_request = $request;
        $this->_setting = $setting;
        $this->_objectManager = $objectManager;
        $this->_scopeConfig = $scopeConfig;
        $this->attributeRepository = $attributeRepository;
        $this->_checkoutSession = $checkoutSession;

        $this->useNewPriceAsPrice = $this->_setting->getConfiguration('itm_pricing_section/general/use_new_price_as_price',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
        $this->setProductAttributes = $this->_helper->getSetProductAttributes();

        $entityTypeId = $this->_objectManager
            ->create('Magento\Eav\Model\Config')
            ->getEntityType(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE)
            ->getEntityTypeId();
        $attribute = $this->attributeRepository->get($entityTypeId, "itm_uom_entry");

        $this->itmUomEntryId = $attribute->getAttributeId();
        $this->_storeManager = $storeManager;
        $this->_storeCurrentCurrencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        $this->_storeBaseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrencyCode();

        $this->_currency = $currency->load($this->_storeBaseCurrencyCode);
        $this->alllow_currency = $this->_setting->getAllowCurrency();
    }

    private function getProductPriceForCurrency($price)
    {
        if ($this->alllow_currency) {
            $correct_price = $this->_currency->convert($price, $this->_storeCurrentCurrencyCode);
            return $correct_price;
        }
        return $price;
    }

    private function catalogProductLoadAfter(\Magento\Framework\Event\Observer $observer, $areaCode)
    {
        $product = $observer->getProduct();

        if (in_array($product->getSku(), $this->_helper->getExcludedSkus())) {
            return;
        }

        if ($product->getSku() == "sap_invoice") {
            return;
        }
        if (!$this->_helper->allowBundleProduct() && $product->getTypeId() == "bundle") {
            return;
        }
        if ($product->getTypeId() == "bundle" && $product->getPriceType() != 1) {
            return;
        }
        /*
      if($product->getPrice() != $product->getFinalPrice()) {
          return ;
      }
      */


        $uom_entry = "-1";
        if (!$this->_helper->ignoreUom() && $product->getTypeId() != "bundle") {
            $uom_entry = $product->getItmUomEntry();
        }


        $new_price = $this->_helper->getProductPrice($product, $uom_entry);
        $custom_price = $this->_helper->getCustomPrice($product, $uom_entry);

        if ($custom_price == -1) {
            $custom_price = $product->getFinalPrice();
        }

        if ($custom_price >= 0) {
            if ($this->useNewPriceAsPrice == 1) {
                $product->setPrice($custom_price);
            } else {
                $custom_price = $product->getFinalPrice();
            }
        }
        if ($this->_helper->setPriceWhenSpecialHigher()) {
            if ($new_price >= $custom_price && $new_price != -1) {
                $product->setPrice($new_price);
            }
        }
        if ($this->_helper->setPriceEqualtoSpecialPrice()) {
            if ($new_price > 0) {
                $product->setPrice($new_price);
            }
        }

        //if ($new_price >= 0) {
        if ($new_price >= 0 && $new_price < $custom_price) {
            if ($new_price != $custom_price) {
                if ($product->getTypeId() == "bundle") {
                    $_price = $product->getPrice();
                    $final_discount = $this->_helper->getPercentage($_price, $new_price);
                    $product->setSpecialPrice($final_discount);
                } else {
                    $product->setSpecialPrice($new_price);
                }
                if (count($this->setProductAttributes) > 0) {
                    foreach ($this->setProductAttributes as $attribute_code => $attribute_value) {
                        $product->setData($attribute_code, $attribute_value);
                    }
                }
            }
            $price_atts = $this->_helper->getPriceAttributes();
            if (count($price_atts) > 0) {
                foreach ($price_atts as $att) {
                    $product->setData($att, $new_price);
                }
            }
        }

        if ($areaCode != "webapi_rest") {
            $tierPrices = $this->_helper->getTierPrices($product, $uom_entry, $new_price);
            if (count($tierPrices) > 0) {
                $product->setTierPrice($tierPrices);
            }
        }

        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($new_price." - ".$custom_price);
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($new_price);
    }

    private function catalogProductCollectionLoadAfter(\Magento\Framework\Event\Observer $observer)
    {

        $collection = $observer->getEvent()->getCollection();
        $collection->addPriceData();
        foreach ($collection as $product) {


            if (in_array($product->getSku(), $this->_helper->getExcludedSkus())) {
                continue;
            }
            if ($product->getSku() == "sap_invoice") {
                continue;
            }
            if ($product->getTypeId() == "configurable") {
                continue;
            }
            if (!$this->_helper->allowBundleProduct() && $product->getTypeId() == "bundle") {
                continue;
            }
            if ($product->getTypeId() == "bundle" && $product->getPriceType() != 1) {
                continue;
            }
            /*
             if($product->getPrice() != $product->getFinalPrice()) {
                continue;
            }
            */

            $uom_entry = "-1";
            if (!$this->_helper->ignoreUom() && $product->getTypeId() != "bundle") {
                $uom_entry = $product->getItmUomEntry();
                if (is_null($uom_entry)) {
                    $uom_entry = $this->_helper->getProductAttributeValue($product->getEntityId(),
                        $this->itmUomEntryId);

                }
            }

            $new_price = $this->_helper->getProductPrice($product, $uom_entry);
            $custom_price = $this->_helper->getCustomPrice($product, $uom_entry);

            if ($custom_price == -1) {
                $custom_price = $product->getFinalPrice();
            }

            if ($custom_price >= 0) {
                if ($this->useNewPriceAsPrice == 1) {
                    $product->setPrice($custom_price);
                } else {
                    $custom_price = $product->getFinalPrice();
                }
            }
            if ($this->_helper->setPriceWhenSpecialHigher()) {
                if ($new_price >= $custom_price && $new_price != -1) {
                    $product->setPrice($new_price);
                }
            }
            if ($this->_helper->setPriceEqualtoSpecialPrice()) {
                if ($new_price > 0) {
                    $product->setPrice($new_price);
                }
            }
            //if ($new_price >= 0) {
            if ($new_price >= 0 && $new_price < $custom_price) {
                if ($new_price != $custom_price) {
                    if ($product->getTypeId() == "bundle") {
                        $_price = $product->getPrice();
                        $final_discount = $this->_helper->getPercentage($_price, $new_price);
                        $product->setSpecialPrice($final_discount);
                    } else {
                        $product->setSpecialPrice($new_price);
                    }

                    if (count($this->setProductAttributes) > 0) {
                        foreach ($this->setProductAttributes as $attribute_code => $attribute_value) {
                            if (in_array($attribute_code, $product->getData())) {
                                $product->setData($attribute_code, $attribute_value);
                            }
                        }
                    }
                }
                $price_atts = $this->_helper->getPriceAttributes();
                if (count($price_atts) > 0) {
                    foreach ($price_atts as $att) {
                        $product->setData($att, $new_price);
                    }
                }
                //$product->setData("minimal_price",$new_price);
                // $product->setData("finel_price",$new_price);
                // Apply the price for other attributes
                //$this->_logger->debug(print_r($product->getData(),true));
            }
            $tierPrices = $this->_helper->getTierPrices($product, $uom_entry, $new_price);
            if (count($tierPrices) > 0) {
                $product->setTierPrice($tierPrices);
            }
        }
    }

    private function checkoutCartUpdateItemsAfter(\Magento\Framework\Event\Observer $observer)
    {
        $cart = $observer->getCart();
        $cartItems = $cart->getItems();
        foreach ($cartItems as $key => $item) {
            if (in_array($item->getSku(), $this->_helper->getExcludedSkus())) {
                continue;
            }

            if ($item->getSku() == "sap_invoice") {
                continue;
            }
            if (!$this->_helper->allowBundleProduct() && $item->getProduct()->getTypeId() == "bundle") {
                continue;
            }
            if ($item->getProduct()->getTypeId() == "bundle" && $item->getProduct()->getPriceType() != 1) {
                continue;
            }

            /*
             if($item->getProduct()->getPrice() != $item->getProduct()->getFinalPrice()) {
                continue;
            }
            */

            /*  if ($item->getProduct()->getTypeId() == "bundle") {
                  continue;
              }*/
            if ($item->getBuyRequest()->getData("cus_price")) {
                continue;
            }
            $item->getProduct()->load($item->getProduct()->getId());
            $uom_entry = $item->getBuyRequest()->getData("itm_uom_entry");
            $qty = $item->getQty();
            $product = $item->getProduct();


            if (is_null($uom_entry)) {
                $uom_entry = "-1";
                if (!$this->_helper->ignoreUom() && $product->getTypeId() != "bundle") {
                    $productRepository = $this->_objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
                    $uom_entry = $productRepository
                        ->get($product->getSku())
                        ->getItmUomEntry();
                }
            }
            if ($product->getTypeId() == "configurable") {
                $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
                $product = $productRepository->get($product->getSku());
            }
            $new_price = $this->_helper->getItemPrice($product, $uom_entry, $qty);

            if ($new_price >= 0) {
                $item->setCustomPrice($this->getProductPriceForCurrency($new_price));
                $item->setOriginalCustomPrice($this->getProductPriceForCurrency($new_price));
            } else {
                $_finalPrice = $this->getProductPriceForCurrency($item->getProduct()->getFinalPrice());
                $item->setCustomPrice($_finalPrice);
                $item->setOriginalCustomPrice($_finalPrice);
            }
        }
    }

    private function checkoutCartProductAddAfter(\Magento\Framework\Event\Observer $observer)
    {

        $item = $observer->getQuoteItem();

        if (in_array($item->getSku(), $this->_helper->getExcludedSkus())) {
            return;
        }
        if ($item->getSku() == "sap_invoice") {
            return;
        }
        if (!$this->_helper->allowBundleProduct() && $item->getProduct()->getTypeId() == "bundle") {
            return;
        }
        if ($item->getProduct()->getTypeId() == "bundle" && $item->getProduct()->getPriceType() != 1) {
            return;
        }
        if ($item->getBuyRequest()->getData("cus_price")) {
            return;
        }
        // to do
        /*
        if($item->getProduct()->getPrice() != $item->getProduct()->getFinalPrice()) {
            return ;
        }
        */

        $params = $this->_request->getParams();
        $product = $item->getProduct();

        $productRepository = $this->_objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');

        $uom_entry = "-1";
        if (!$this->_helper->ignoreUom() && $product->getTypeId() != "bundle") {
            $uom_entry = $productRepository->get($item->getProduct()->getSku())->getItmUomEntry(); // "default";
        }
        if ($product->getTypeId() == "configurable") {
            $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
            $product = $productRepository->get($product->getSku());
        }


        if (isset($params["itm_uom_entry"])) {
            $uom_entry = $params["itm_uom_entry"];
        }
        $qty = 1;
        if (isset($params["qty"])) {
            $qty = $params["qty"];
        }
        if (is_array($qty)) {
            $qty = current($qty);
        }
        $buyRequestArr = [];
        if ($buyRequest = $item->getProduct()->getCustomOption('info_buyRequest')) {
            $buyRequestArr = $this->_helper->itmUnserialize($buyRequest->getValue());
        }
        $buyRequestArr['itm_uom_entry'] = $uom_entry;
        $buyRequest->setValue($this->_helper->itmSerialize($buyRequestArr));
        // end adding uom

        // $this->_logger->debug ( "add to cart start" );

        $item->setItmUomEntry($uom_entry);
        $new_price = $this->_helper->getItemPrice($product, $uom_entry, $qty, true, "addtocart");

        /*
         * if ($new_price > 0) {
         * $item->setCustomPrice ( $new_price );
         * $item->setOriginalCustomPrice ( $new_price );
         * }
         */
        if ($new_price >= 0) {
            $item->setCustomPrice($this->getProductPriceForCurrency($new_price));
            $item->setOriginalCustomPrice($this->getProductPriceForCurrency($new_price));
        } else {
            $_finalPrice = $this->getProductPriceForCurrency($item->getProduct()->getFinalPrice());
            $item->setCustomPrice($_finalPrice);
            $item->setOriginalCustomPrice($_finalPrice);
        }

        $this->updateCart();
    }

    private function updateCart()
    {

        $quote = $this->_checkoutSession->getQuote();
        //$quote = $this->_objectManager->create('\Magento\Checkout\Model\Session')->getQuote();

        if (empty($quote->getId())) {
            return;
        }

        $cartItems = $quote->getAllVisibleItems();
        $need_update = false;
        foreach ($cartItems as $key => $item) {
            if(in_array($item->getSku(), $this->_helper->getExcludedSkus())) {
                //  continue;
            }
            $buyRequestArr = [];
            if ($buyRequest = $item->getProduct()->getCustomOption('info_buyRequest')) {
                $buyRequestArr = $this->_helper->itmUnserialize($buyRequest->getValue());

                if (!$this->_helper->allowBundleProduct() && $item->getProduct()->getTypeId() == "bundle") {
                    if (isset($buyRequestArr['bundle_option'])) {
                        continue;
                    }
                }
            }

            if ($item->getSku() == "sap_invoice") {
                continue;
            }

            if (!$this->_helper->allowBundleProduct() && $item->getProduct()->getTypeId() == "bundle") {
                continue;
            }
            if ($item->getProduct()->getTypeId() == "bundle" && $item->getProduct()->getPriceType() != 1) {
                continue;
            }

            //if ($item->getProduct()->getTypeId() == "bundle") {
//                continue;
//            }

            if ($item->getBuyRequest()->getData("cus_price")) {
                continue;
            }

            /*
            if($item->getProduct()->getPrice() != $item->getProduct()->getFinalPrice()) {
               continue;
           }
           */

            $_product = $item->getProduct();
            $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');

            //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($_product->getSku());

            if ($_product->getTypeId() == "bundle") {
                $product = $productRepository->getById($item->getProductId());
            } else {
                $product = $productRepository->get($_product->getSku());
            }

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

    private function changeItemWight(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helper->enableWeight()) {
            return $this;
        }
        $quote_item = $observer->getQuoteItem();

        if (in_array($quote_item->getSku(), $this->_helper->getExcludedSkus())) {
            return;
        }

        if ($quote_item->getSku() == "sap_invoice") {
            return;
        }
        $product_weight = $quote_item->getProduct()->getWeight();
        $sku = $quote_item->getSku();
        $uom_entry = $quote_item->getBuyRequest()->getData("itm_uom_entry");

        $weight = $this->_helper->getProductUomWeight($sku, $uom_entry);

        if ($weight == -1) {
            $quote_item->setWeight($product_weight);
        } else {
            $quote_item->setWeight($weight);
        }
        // $quote_item->setWeight(7);
        // $quote_item->save();

    }

    private function catalogProductGetFinalPrice(\Magento\Framework\Event\Observer $observer)
    {

    }

    private function salesQuoteProductAddAfter(\Magento\Framework\Event\Observer $observer, $areaCode)
    {

        $items = $observer->getItems();

        foreach ($items as $item) {
            if (in_array($item->getSku(), $this->_helper->getExcludedSkus())) {
                return;
            }
            if ($item->getSku() == "sap_invoice") {
                return;
            }
            if (!$this->_helper->allowBundleProduct() && $item->getProduct()->getTypeId() == "bundle") {
                return;
            }
            if ($item->getProduct()->getTypeId() == "bundle" && $item->getProduct()->getPriceType() != 1) {
                return;
            }
            if ($item->getBuyRequest()->getData("cus_price")) {
                return;
            }

            $params = $this->_request->getParams();
            $product = $item->getProduct();

            $productRepository = $this->_objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');

            $uom_entry = "-1";
            if (!$this->_helper->ignoreUom() && $product->getTypeId() != "bundle") {
                $uom_entry = $productRepository->get($item->getProduct()->getSku())->getItmUomEntry(); // "default";
            }
            if ($product->getTypeId() == "configurable") {
                $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
                $product = $productRepository->get($product->getSku());
            }
            if ($product->getTypeId() == "bundle") {
                $productRepository = $this->_objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
                $product = $productRepository->getByID($item->getProductId());
            }

            if (isset($params["itm_uom_entry"])) {
                $uom_entry = $params["itm_uom_entry"];
            }
            $qty = 1;
            if (isset($params["qty"])) {
                $qty = $params["qty"];
            }
            if (is_array($qty)) {
                $qty = current($qty);
            }
            $buyRequestArr = [];
            if ($buyRequest = $item->getProduct()->getCustomOption('info_buyRequest')) {
                $buyRequestArr = $this->_helper->itmUnserialize($buyRequest->getValue());
            }
            $buyRequestArr['itm_uom_entry'] = $uom_entry;
            $buyRequest->setValue($this->_helper->itmSerialize($buyRequestArr));
            // end adding uom

            $new_price = $this->_helper->getItemPrice($product, $uom_entry, $qty);

            if ($new_price >= 0) {
                $item->setCustomPrice($new_price);
                $item->setOriginalCustomPrice($new_price);
            } else {
                $item->setCustomPrice($item->getProduct()
                    ->getFinalPrice());
                $item->setOriginalCustomPrice($item->getProduct()
                    ->getFinalPrice());
            }
        }
        if($areaCode == "graphql"){
            $this->updateCart();
        }
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        // \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($observer->getEvent()->getName());
        // \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log(print_r($_POST,true));
        // \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("----------------------------------------------------");
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log($observer->getEvent()->getName()." - ".$this->_request->getFullActionName());
        //\Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log(print_r($this->_setting->getActions(),true));
        if ($this->_helper->isDisable() == true) {
            return $this;
        }

        $state = $this->_objectManager->get('Magento\Framework\App\State');

        $areaCodes = ["graphql", "frontend", "webapi_rest", "webapi_soap"];
        if (!in_array($state->getAreaCode(), $areaCodes)) {
            return $this;
        }
        $ignorePostPutinAreaCodes = ["webapi_rest", "webapi_soap"];
        $requestType = strtolower($_SERVER['REQUEST_METHOD']);
        if (in_array($state->getAreaCode(), $ignorePostPutinAreaCodes) && $requestType != "get") {
            return $this;
        }

        $event_name = $observer->getEvent()->getName();
        //$this->_logger->debug ($event_name);
        //echo  $event_name."<br>";
        switch ($event_name) {
            case "checkout_cart_update_item_complete":
                $this->updateCart();
                break;
            case "catalog_product_collection_load_after":
                $action_array = $this->_setting->getActions();
                if (!in_array($this->_request->getFullActionName(), $action_array)) {
                    $this->catalogProductCollectionLoadAfter($observer);
                }
                break;
            case "catalog_product_load_after":
                $action_array = $this->_setting->getActions();
                if (!in_array($this->_request->getFullActionName(), $action_array)) {
                    $this->catalogProductLoadAfter($observer, $state->getAreaCode());
                }
                break;
            case "checkout_cart_update_items_after":
                $this->checkoutCartUpdateItemsAfter($observer);
                break;
            case "checkout_cart_product_add_after":
                $this->checkoutCartProductAddAfter($observer);
                break;
            case "sales_quote_product_add_after":
                $this->salesQuoteProductAddAfter($observer, $state->getAreaCode());
                break;
            case "customer_login":
                $this->updateCart();
                break;
            /* case "sales_quote_item_set_product":
                 $this->changeItemWight($observer);
                 break;
             case "catalog_product_get_final_price":
                 $this->catalogProductGetFinalPrice($observer);
                 break;
            */
        }
        return $this;
    }
}
