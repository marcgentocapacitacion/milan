<?php

namespace Wagento\OfflineShipping\Model;

use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Wagento\OfflineShipping\Api\ConfigShippingTypeInterface;
use Wagento\OfflineShipping\Model\Company\Source\ShippingType;

/**
 * Class ConfigShippingType
 */
class ConfigShippingType implements ConfigShippingTypeInterface
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
     * @var CompanyRepositoryInterface
     */
    protected CompanyRepositoryInterface $companyRepository;

    /**
     * @param ScopeConfigInterface       $scopeConfig
     * @param CustomerSession            $customerSession
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        CompanyRepositoryInterface $companyRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @return string
     */
    public function getMessageShippingType(): string
    {
        try {
            $extensionAttribute = $this->customerSession->getCustomerData()->getExtensionAttributes() ?? false;
            if (!$extensionAttribute) {
                return '';
            }
            $companyId = $extensionAttribute->getCompanyAttributes()->getCompanyId() ?? false;
            if (!$companyId) {
                return '';
            }
            $company = $this->companyRepository->get($companyId);
            if ($company->getShippingType() == ShippingType::SHIPPING_TYPE_SPECIAL_ID) {
                return $this->scopeConfig->getValue('carriers/tablerate/message_shipping_type');
            }
        } catch (\Exception $e) {
            return '';
        }
        return '';
    }
}
