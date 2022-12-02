<?php
namespace ITM\Pricing\Model\Quote;

class Item extends \Magento\Quote\Model\Quote\Item
{

    /**
     * Check product representation in item
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function representProduct($product)
    {
        $append_existin_line = parent::representProduct($product);

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $pricing_helper = $om->create('\ITM\Pricing\Helper\Data');
        if ($pricing_helper->isDisable()) {
            return $append_existin_line;
        }
        $ignoreUom = $pricing_helper->ignoreUom();


        $product_types = [
            "simple",
            "configurable"
        ];
        if (in_array($product->getTypeId(), $product_types)) {
            if ($append_existin_line == true && !$ignoreUom) {
                $om = \Magento\Framework\App\ObjectManager::getInstance();
                
                $params = $om->create('\Magento\Framework\App\RequestInterface')->getParams();
                $new_uom = "-1";

                if(!$ignoreUom) {
                    if($product->getTypeId()=="simple") {
                        $new_uom = $product->getItmUomEntry();
                    }
                    if($product->getTypeId()=="configurable") {
                        $buyRequest =  $product->getCustomOption('info_buyRequest');
                        $buyRequestArr = $pricing_helper->itmUnserialize($buyRequest->getValue());
                        $super_attribute = $buyRequestArr["super_attribute"];
                        $childProduct = $om->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable')
                            ->getProductByAttributes($super_attribute, $product);
                        $new_uom = $childProduct->getItmUomEntry();
                    }
                }
                if (isset($params["itm_uom_entry"])) {
                    $new_uom = $params["itm_uom_entry"];
                }

                $item_uom = $this->getBuyRequest()->getData("itm_uom_entry");


                if(intval ($item_uom) == intval ( $new_uom))
                {
                    return true;
                }
                if ($new_uom != $item_uom) {
                    $append_existin_line = false;
                }
            }
        }
        return $append_existin_line;
    }
    
    /**
     * Setup product for quote item
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    public function setProduct($product)
    {
        
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $pricing_helper = $om->create('\ITM\Pricing\Helper\Data');
        if (!$pricing_helper->enableWeight() || $pricing_helper->isDisable()) {
            parent::setProduct($product);
            return $this;
        }
        
        if ($this->getQuote()) {
            $product->setStoreId($this->getQuote()->getStoreId());
            $product->setCustomerGroupId($this->getQuote()->getCustomerGroupId());
        }
        $this->setData('product', $product)
        ->setProductId($product->getId())
        ->setProductType($product->getTypeId())
        ->setSku($this->getProduct()->getSku())
        ->setName($product->getName())
        //->setWeight($this->getProduct()->getWeight())
        ->setTaxClassId($product->getTaxClassId())
        ->setBaseCost($product->getCost());
    
        $stockItem = $this->stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
        $this->setIsQtyDecimal($stockItem->getIsQtyDecimal());
    
        $this->_eventManager->dispatch(
                'sales_quote_item_set_product',
                ['product' => $product, 'quote_item' => $this]
                );
    
        return $this;
    }
    
}
