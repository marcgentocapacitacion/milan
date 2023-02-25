<?php

namespace Wagento\OfflineShipping\Plugin\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\OfflineShipping\Model\Carrier\Tablerate;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Wagento\OfflineShipping\Model\Company\Source\ShippingType;
use Magento\Company\Api\CompanyRepositoryInterface;

/**
 * Class TableratePlugin
 */
class TableratePlugin
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
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var string|null
     */
    protected ?string $shippingType = null;

    /**
     * @var CompanyRepositoryInterface
     */
    protected CompanyRepositoryInterface $companyRepository;

    /**
     * @param ScopeConfigInterface       $scopeConfig
     * @param StoreManagerInterface      $storeManager
     * @param CustomerSession            $customerSession
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        CompanyRepositoryInterface $companyRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getCostTypeHandlingType(): string
    {
        return $this->scopeConfig->getValue(
            'carriers/tablerate/cost_type_handling_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * @return string
     */
    protected function getShippingType(): string
    {
        if ($this->shippingType) {
            return $this->shippingType;
        }
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
            $this->shippingType = $company->getShippingType() ?? '';
        } catch (\Exception $e) {
            return '';
        }
        return $this->shippingType;
    }

    /**
     * @return bool
     */
    protected function isTaxRateFree(): bool
    {
        if ($this->getShippingType() == ShippingType::SHIPPING_TYPE_SPECIAL_ID) {
            return true;
        }

        if ($this->getShippingType() == ShippingType::SHIPPING_TYPE_METROPOLITAN_ID) {
            return true;
        }
        return false;
    }

    /**
     * @param Tablerate   $subject
     * @param             $result
     * @param RateRequest $request
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetRate(Tablerate $subject, $result, RateRequest $request)
    {
        if (!is_array($result)) {
            return $result;
        }

        if ($this->getCostTypeHandlingType() == 'handling_fee') {
            return $result;
        }

        if (!$request->getAllItems()) {
            return $result;
        }
        if ($this->isTaxRateFree()) {
            $result['price'] = 0.00;
        } else {
            $result['price'] = $request->getBaseSubtotalInclTax();
        }
        return $result;
    }

    /**
     * @param Tablerate $subject
     * @param float     $result
     * @param float     $cost
     *
     * @return void
     */
    public function afterGetFinalPriceWithHandlingFee(Tablerate $subject, $result, $cost)
    {
        if (!$result) {
            return $result;
        }

        if ($this->getCostTypeHandlingType() == 'handling_fee') {
            return $result;
        }

        $handlingFee = $subject->getConfigData('handling_fee') ?? false;
        if (!$handlingFee) {
            return $result;
        }

        if ($cost <= 0.00) {
            return $cost;
        }

        $handlingType = $subject->getConfigData('handling_type') ?? Tablerate::HANDLING_TYPE_FIXED;
        if ($handlingType == Tablerate::HANDLING_TYPE_PERCENT) {
            return $cost * ($handlingFee / 100);
        }
    }
}
