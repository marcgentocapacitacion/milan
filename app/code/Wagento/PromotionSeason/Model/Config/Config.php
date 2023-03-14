<?php

namespace Wagento\PromotionSeason\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

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
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function getEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::ENABLE) ?? false;
    }

    /**
     * @return string
     */
    public function getLabelPromotion(): string
    {
        return $this->scopeConfig->getValue(self::LABEL_PROMOTION) ?? '';
    }

    /**
     * @return float
     */
    public function getValuePromotionLimit(): float
    {
        return (float)$this->scopeConfig->getValue(self::VALUE_PROMOTION_LIMIT) ?? 0.000;
    }

    /**
     * @return string
     */
    public function getTextPromotionCartMoreThan(): string
    {
        return $this->scopeConfig->getValue(self::TEXT_PROMOTION_CART_MORE_THAN) ?? '';
    }

    /**
     * @return string
     */
    public function getTextPromotionCartLessThan(): string
    {
        return $this->scopeConfig->getValue(self::TEXT_PROMOTION_CART_LESS_THAN) ?? '';
    }

    /**
     * @return string
     */
    public function getItmProperties(): string
    {
        return $this->scopeConfig->getValue(self::ITM_PROPERTIES) ?? '';
    }

    /**
     * @return string
     */
    public function getDateLimitPay(): string
    {
        return $this->scopeConfig->getValue(self::DATE_LIMIT_PAY) ?? '';
    }
}
