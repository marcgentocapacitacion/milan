<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class PricingTables implements ArrayInterface
{
    protected $_cacheTypeListArray;


    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result[]  = ["value"=>"","label"=>"-- None --"];
        $result[]  = ["value"=>"gp","label"=>"Group Price"];        //$this->getPriceListPrice($params);
        $result[]  = ["value"=>"gdp","label"=>"Customer Price"];     //$this->getGroupDiscountPrice($params);
        $result[]  = ["value"=>"cdp","label"=>"Group Discount"];     //$this->getCustomerDiscountPrice($params);
        $result[]  = ["value"=>"sp","label"=>"Customer Discount"];  //$this->getCustomerSpecialPrice($params);

        return  $result;
    }
}





