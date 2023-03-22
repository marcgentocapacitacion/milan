<?php

namespace Wagento\OfflineShipping\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 */
class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }


    /**
     * @inheritDoc
     */
    public function getCostTypeHandlingType(): string
    {
        return $this->scopeConfig->getValue(
            self::COST_TYPE_HANDLING_TYPE,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        ) ?? '';
    }
}
