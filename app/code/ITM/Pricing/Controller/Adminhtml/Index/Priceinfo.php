<?php
namespace ITM\Pricing\Controller\Adminhtml\Index;

class Priceinfo extends \Magento\Backend\App\Action
{

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $data = $this->getRequest()->getPostValue();
                
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($data);
                
                $params = $data;
                /*
                 * $params["website_id"] = 1;
                 * $params["group_id"] = 1; // used for any type of group
                 * $params["customer_group_id"] = 1; // used for custoemr group
                 * $params["customer_id"] = 2;
                 * $params["sku"] = "simple-no-uom";
                 * $params["uom_entry"] = "-1";
                 */
                $params["qty"] = 1;
                $params["force_attribute_value"] = false;
                
                $price = $this->_objectManager->get('ITM\Pricing\Helper\Data');
                $_format_price = $this->_objectManager->get('Magento\Framework\Pricing\Helper\Data');
                
                $productRepositoryInterface = $this->_objectManager->get(
                        '\Magento\Catalog\Api\ProductRepositoryInterface');
                $product = $productRepositoryInterface->get($params["sku"]);
                if ($product->getEntity_id() > 0) {
                    $params["product"] = $product;
                    
                    $html = "";
                    $html .= __("Custom Price") . ": " .
                             $_format_price->currency($price->getFinalCustomPrice($params), true, false) . "<br/>";
                    $html .= __("Final Price") . ": " .
                             $_format_price->currency($new_price = $price->getFinalPrice($params), true, false) . "<br/>";
                    
                    $tier_price = $price->getFinalTierPrices($params, $new_price);
                    
                    $price_helper = 

                    $html .= "<h2>" . __("Tier Price") . "</h2>";
                    foreach ($tier_price as $item) {
                        $p = $new_price - $item["price"];
                        $percent = $this->_objectManager->get('ITM\Pricing\Helper\Data')->percentage($p, $new_price, 2);
                        $html .= __(
                                'Buy %1 for %2 each and <strong class="benefit">save<span class="percent tier-%3">&nbsp;%4</span>%</strong>', 
                                (int) $item["price_qty"], $_format_price->currency($item["price"], true, false), 444, 
                                "(" . $_format_price->currency($percent, true, false) . ") " .
                                         $_format_price->currency(ceil($percent), true, false)) . "<br/>";
                    }
                    
                    $this->messageManager->addSuccess($html);
                } else {
                    $this->messageManager->addError(__('Requested product doesn\'t exist.'));
                }
                // $this->messageManager->addSuccess(__('You saved the item.'));
                $this->_redirect('itm_pricing/index');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
            }
        }
        $this->_redirect('itm_pricing/index');
    }
}
