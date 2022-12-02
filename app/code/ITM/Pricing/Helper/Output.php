<?php
namespace ITM\Pricing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\Session;

/**
 * Class Data
 *
 * @package Gielberkers\Example\Helper
 */
class Output extends AbstractHelper
{


    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Data
     */
    protected $_helper;


    /**
     * Output constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param Data $dataHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \ITM\Pricing\Helper\Data $dataHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {

        $this->_helper = $dataHelper;
        $this->_logger = $logger;
        $this->_objectManager = $objectManager;
    }

    public function getRenderProductTierPrice(\Magento\Catalog\Model\Product $_product)
    {
        /**
         * <?php $_itm_helper = $this->helper('ITM\Pricing\Helper\Output');?>
         * <?php echo $_itm_helper->getRenderProductTierPrice() ?>
         */
        // get Rendered tier price
        $priceRender =      $this->_objectManager->create('Magento\Framework\Pricing\Render');
        $tier_price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\TierPrice::PRICE_CODE,
                $_product,
                [
                    'include_container' => true,
                    'display_minimal_price' => true,
                    'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
                ]
                );
        print $tier_price;
    }
    public function getSetProductAttributes(\Magento\Catalog\Model\Product $_product)
    {
        $setProductAttributes = $this->_helper->getSetProductAttributes($_product);
        $html = "";
        if(count($setProductAttributes)>0) {
            foreach($setProductAttributes as $attribute_code => $attribute_value){
                if( !empty($_product->getData($attribute_code))) {
                    $html .= "<strong>".$_product->getResource()->getAttribute($attribute_code)->getStoreLabel(). ":</strong> ".$_product->getattributeText($attribute_code)."<br/>";
                }
            }
        }
        
        
       return $html;
    }

}
