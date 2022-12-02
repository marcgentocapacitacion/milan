<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class Websites implements ArrayInterface
{

    protected $storeManager;

    protected $_scopeConfig;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        $websites = $this->_storeManager->getWebsites();
        $ignore_websites = trim($this->_scopeConfig->getValue('itm_pricing_section/general/ignore_websites') ?? '');

        $options = [
            "" => ""
        ];

        if ($ignore_websites == 1) {
            $options["0"] = __("All");
        }
        foreach ($websites as $website) {
            $options[$website->getId()] = $website->getName();
        }

        return $options;
    }

    /**
     *
     * @return array
     */
    public function getOptionArray()
    {
        return $this->toOptionArray();
    }
}
