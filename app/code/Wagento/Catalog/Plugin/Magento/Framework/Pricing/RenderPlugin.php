<?php

namespace Wagento\Catalog\Plugin\Magento\Framework\Pricing;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class RenderPlugin
 */
class RenderPlugin
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
     * @param Render            $subject
     * @param callable          $proceed
     * @param                   $priceCode
     * @param SaleableInterface $saleableItem
     * @param array             $arguments
     *
     * @return false
     */
    public function aroundRender(
        Render $subject,
        callable $proceed,
        $priceCode,
        SaleableInterface $saleableItem,
        array $arguments = []
    ) {
        if (!$this->request->isAjax()) {
            return $proceed($priceCode, $saleableItem, $arguments);
        }

        if (!$this->isShowPrice()) {
            if (!$this->customerSession->isLoggedIn()) {
                return '';
            }
        }
        return $proceed($priceCode, $saleableItem, $arguments);
    }

    /**
     * @return bool
     */
    public function isShowPrice(): bool
    {
        return $this->
            scopeConfig->
            isSetFlag(
                'wagento_catalog/product_list/show_price_without_login',
            ScopeInterface::SCOPE_STORE
        ) ?? false;
    }
}
