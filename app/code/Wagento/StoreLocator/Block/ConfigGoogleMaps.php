<?php

namespace Wagento\StoreLocator\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigGoogleMaps
 */
class ConfigGoogleMaps extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Template\Context     $context
     * @param array                $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->scopeConfig->getValue(
            'wagento_google_maps/google_maps/api_key',
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }

    /**
     * @return string
     */
    public function getUrlGoogleAPIs(): string
    {
        return "https://maps.googleapis.com/maps/api/js?key=" . $this->getApiKey();
    }
}
