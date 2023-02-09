<?php

namespace Wagento\Catalog\Plugin\Magento\Framework\Pricing;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\SaleableInterface;

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
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerSession      $customerSession
     */
    public function __construct(ScopeConfigInterface $scopeConfig, CustomerSession $customerSession)
    {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
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
            isSetFlag('wagento_catalog/product_list/show_price_without_login') ?? false;
    }
}
