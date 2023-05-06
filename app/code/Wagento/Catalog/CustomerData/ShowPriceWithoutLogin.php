<?php

namespace Wagento\Catalog\CustomerData;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;

/**
 * Class ShowPriceWithoutLogin
 */
class ShowPriceWithoutLogin implements SectionSourceInterface
{
    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param Session              $session
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Session $session, ScopeConfigInterface $scopeConfig)
    {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $showPrice = false;
        if ($this->session->isLoggedIn()) {
            $showPrice = !$this->isShowPrice();
        }
        return [
            'showPrice' => $showPrice
        ];
    }

    /**
     * @return bool
     */
    public function isShowPrice(): bool
    {
        return $this
            ->scopeConfig
            ->isSetFlag(
                'wagento_catalog/product_list/show_price_without_login',
                ScopeInterface::SCOPE_STORE
            ) ?? false;
    }
}
