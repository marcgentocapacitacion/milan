<?php

namespace Wagento\Company\Plugin\Block\Company;

use Magento\Company\Block\Company\CompanyProfile;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class CompanyProfilePlugin
{

    /**
     * Configuration path for enabled customer edit their information
     */
    public const XML_PATH_IS_CUSTOMER_OWN_EDIT_ENABLED = 'customer/account_information/customer_edit';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if customer information edit enabled
     *
     * @return bool
     */
    public function isCustomerOwnEditEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_IS_CUSTOMER_OWN_EDIT_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * Check if company information edit is allowed in Admin
     * @param CompanyProfile $subject
     * @param $result
     * @return bool
     */
    public function afterIsEditLinkDisplayed(CompanyProfile $subject, $result)
    {
        return $result && $this->isCustomerOwnEditEnabled();
    }
}
