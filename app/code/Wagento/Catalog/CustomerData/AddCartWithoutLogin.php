<?php

namespace Wagento\Catalog\CustomerData;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class AddCartWithoutLogin
 */
class AddCartWithoutLogin implements \Magento\Customer\CustomerData\SectionSourceInterface
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
        $showAddToCart = false;
        if ($this->session->isLoggedIn()) {
            $showAddToCart = !$this->isShowAddToCart();
        }
        return [
            'showAddToCart' => $showAddToCart
        ];
    }

    /**
     * @return bool
     */
    public function isShowAddToCart(): bool
    {
        return $this->
        scopeConfig->
        isSetFlag(
            'wagento_catalog/product_list/use_addCart_without_login',
            ScopeInterface::SCOPE_STORE
        ) ?? false;
    }
}
