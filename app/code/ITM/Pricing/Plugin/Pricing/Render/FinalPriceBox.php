<?php

namespace  ITM\Pricing\Plugin\Pricing\Render;


class FinalPriceBox
{


    protected $_helper;

    /**
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        \ITM\Pricing\Helper\Data $dataHelper
    ) {
        $this->_helper = $dataHelper;
    }


    public function afterHasSpecialPrice(\Magento\ConfigurableProduct\Pricing\Render\FinalPriceBox $subject, $result)
    {
        if ($this->_helper->isDisable() == true) {
            return $result;
        }
        $product = $subject->getSaleableItem();
        $_children = $product->getTypeInstance()->getUsedProducts($product);
        foreach ( $_children as $child) {
            if($child->getSpecialPrice()>0) {
                return true;
            }
        }
        return  $result ;
    }
}