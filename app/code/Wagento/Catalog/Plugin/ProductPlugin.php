<?php

namespace Wagento\Catalog\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductPlugin
 */
class ProductPlugin
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerSession      $customerSession
     */
    public function __construct(ScopeConfigInterface $scopeConfig, CustomerSession $customerSession)
    {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Product  $subject
     * @param callable $proceed
     *
     * @return bool
     */
    public function aroundIsSaleable(Product $subject, callable $proceed)
    {
        if (!$this->isShowAddToCart()) {
            if (!$this->customerSession->isLoggedIn()) {
                return false;
            }
        }
        return $proceed();
    }

    /**
     * @return bool
     */
    public function isShowAddToCart(): bool
    {
        return $this->
            scopeConfig->
            isSetFlag('wagento_catalog/product_list/use_addCart_without_login') ?? false;
    }
}
