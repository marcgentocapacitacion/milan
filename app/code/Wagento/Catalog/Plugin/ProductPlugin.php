<?php

namespace Wagento\Catalog\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

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
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerSession      $customerSession
     * @param RequestInterface     $request
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        RequestInterface $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->request = $request;
    }

    /**
     * @param Product  $subject
     * @param callable $proceed
     *
     * @return bool
     */
    public function aroundIsSaleable(Product $subject, callable $proceed)
    {
        if (!$this->request->isAjax()) {
            return $proceed();
        }

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
            isSetFlag(
                'wagento_catalog/product_list/use_addCart_without_login',
            ScopeInterface::SCOPE_STORE
        ) ?? false;
    }
}
