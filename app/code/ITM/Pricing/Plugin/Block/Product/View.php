<?php

namespace ITM\Pricing\Plugin\Block\Product;

class View
{

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;
    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $_jsonDecoder;

    protected $_helper;

    /**
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \ITM\Pricing\Helper\Data $dataHelper
    ) {

        $this->_jsonEncoder = $jsonEncoder;
        $this->_jsonDecoder = $jsonDecoder;
        $this->_helper = $dataHelper;
    }

    public function afterGetJsonConfig(\Magento\Catalog\Block\Product\View $subject, $result)
    {
        if ($this->_helper->isDisable() == true) {
            return $result;
        }
        $product = $subject->getProduct();
        if ($product->getTypeId() != "configurable") {
            return $result;
        }

        $_children = $product->getTypeInstance()->getUsedProducts($product);
        $finalPrice = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
        $basePrice = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount();
        $oldPrice = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();

        foreach ($_children as $child) {
            if ($child->getFinalPrice() < $finalPrice) {
                $oldPrice = (float)$child->getPrice();
                $basePrice = (float)$child->getFinalPrice();
                $finalPrice = (float)$child->getFinalPrice();
            }
        }
        $final_prices = [];
        $final_prices["oldPrice"] = $oldPrice;
        $final_prices["basePrice"] = $basePrice;
        $final_prices["finalPrice"] = $finalPrice;

        $config_de = $this->_jsonDecoder->decode($result);

        $prices = $config_de["prices"];
        $new_prices = [];
        foreach ($prices as $key => $price) {
            if(isset($final_prices[$key])) {
                $new_price = $price;
                $new_price["amount"] = $final_prices[$key];
                $new_prices[$key] = $new_price;
            }
        }
        $config_de["prices"] = $new_prices;
        $config = $this->_jsonEncoder->encode($config_de);
        return $config;
    }
}