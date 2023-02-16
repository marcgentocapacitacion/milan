<?php

namespace Wagento\Checkout\Plugin\Model\Carrier;

use Magento\OfflineShipping\Model\Carrier\Tablerate;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

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
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(ScopeConfigInterface $scopeConfig, StoreManagerInterface $storeManager)
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
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
     * @param Tablerate   $subject
     * @param             $result
     * @param RateRequest $request
     *
     * @return void
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
        $result['price'] = $request->getBaseSubtotalInclTax();
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
        $handlingType = $subject->getConfigData('handling_type') ?? Tablerate::HANDLING_TYPE_FIXED;
        if ($handlingType == Tablerate::HANDLING_TYPE_PERCENT) {
            return $cost * ($handlingFee / 100);
        }
    }
}
