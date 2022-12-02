<?php
namespace ITM\Pricing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\Session;

/**
 * Class Data
 *
 * @package Gielberkers\Example\Helper
 */
class Setting extends AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var array
     */
    private $excluded_skus = [];

    /**
     * @var array
     */
    private $_events = [];

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    public function getAllowCurrency(){
        $allow_currency = (bool) $this->getConfiguration('itm_pricing_section/general/allow_currency');
        return $allow_currency;
    }
    public function disablePriceBoxCache(){

        $disablePriceBoxCache = (bool) $this->getConfiguration('itm_pricing_section/general/disable_pricebox_cache');
        return $disablePriceBoxCache;
    }
    public function getExcludedSkus()
    {
        if (empty($this->excluded_skus)) {
            $_skus = $this->getConfiguration('itm_pricing_section/general/excluded_skus');
            if ($_skus != "") {
                $this->excluded_skus = explode("\n", $_skus);
            }
        }
        return $this->excluded_skus;
    }

    public function getActions()
    {
        if (empty($this->excluded_skus)) {
            $disable_product_load_collection = $this->getConfiguration('itm_pricing_section/events/disable_product_load_collection');
            if ($disable_product_load_collection != "") {
                $this->_events = explode("\n", $disable_product_load_collection);
            }
        }
        return $this->_events;
    }
    public function isDisable()
    {
        $enabled = (bool) $this->getConfiguration('itm_pricing_section/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES);
        return !$enabled;
    }

    public  function enableWeight()
    {
        $enable_weight = $this->getConfiguration('itm_pricing_section/general/enable_weight');
        if ($enable_weight != 1) {
            return false;
        }
        return true;
    }
    public function getConfiguration($key, $scope = '')
    {
        $value = '';
        if(!empty($scope)) {
            $value = $this->_scopeConfig->getValue($key,$scope);
        }else {
            $value = $this->_scopeConfig->getValue($key);
        }
        if(empty($value)) {
            return $value;
        }
        return trim($value);
    }
}
