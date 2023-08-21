<?php

namespace Wagento\OfflineShipping\Plugin\Model\Carrier;

use Magento\Backend\Model\Session\Quote as SessionQuote;
use Magento\OfflineShipping\Model\Carrier\Tablerate;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Customer\Model\Session as CustomerSession;
use Wagento\OfflineShipping\Model\Company\Source\ShippingType;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Wagento\OfflineShipping\Model\Config\ConfigInterface;

/**
 * Class TableratePlugin
 */
class TableratePlugin
{
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
     * @var SessionQuote
     */
    protected SessionQuote $sessionQuote;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @param CustomerSession            $customerSession
     * @param CompanyRepositoryInterface $companyRepository
     * @param SessionQuote               $sessionQuote
     * @param ConfigInterface            $config
     */
    public function __construct(
        CustomerSession $customerSession,
        CompanyRepositoryInterface $companyRepository,
        SessionQuote $sessionQuote,
        ConfigInterface $config
    ) {
        $this->customerSession = $customerSession;
        $this->companyRepository = $companyRepository;
        $this->sessionQuote = $sessionQuote;
        $this->config = $config;
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
            $extensionAttribute = $this->getCustomerExtensionAttributes();
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
     * @return CustomerExtensionInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getCustomerExtensionAttributes(): ?CustomerExtensionInterface
    {
        if ($this->customerSession->isLoggedIn()) {
            if ($this->customerSession->getCustomerData()) {
                return $this->customerSession->getCustomerData()->getExtensionAttributes();
            }
        }

        if (!$this->sessionQuote->getQuote()) {
            return null;
        }

        if (!$this->sessionQuote->getQuote()->getCustomer()) {
            return null;
        }

        return $this->sessionQuote->getQuote()->getCustomer()->getExtensionAttributes();
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

        if ($this->config->getCostTypeHandlingType() == 'handling_fee') {
            return $result;
        }

        if (!$request->getAllItems()) {
            return $result;
        }
        if ($this->isTaxRateFree()) {
            $result['price'] = 0.00;
        } else {
            // Get subtotal exclude tax before calculate shipping rate
            $subtotal = 0;
            foreach ($request->getAllItems() as $item) {
                $subtotal += $item->getPrice() * $item->getQty();
            }
            $result['price'] = $subtotal;
            // Disable subtotal include tax
            //$result['price'] = $request->getBaseSubtotalInclTax();
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

        if ($this->config->getCostTypeHandlingType() == 'handling_fee') {
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
