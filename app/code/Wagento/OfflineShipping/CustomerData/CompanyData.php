<?php

namespace Wagento\OfflineShipping\CustomerData;

use Wagento\OfflineShipping\Model\Company\Source\ShippingType;
use Wagento\OfflineShipping\Api\ConfigShippingTypeInterface;

/**
 * Class CompanyData
 */
class CompanyData extends \Magento\Framework\DataObject implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var ConfigShippingTypeInterface
     */
    protected ConfigShippingTypeInterface $configShippingType;

    /**
     * @param ConfigShippingTypeInterface $configShippingType
     * @param array                       $data
     */
    public function __construct(
        ConfigShippingTypeInterface $configShippingType,
        array $data = []
    ) {
        $this->configShippingType = $configShippingType;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        return [
            'message_shipping_type' => $this->configShippingType->getMessageShippingType()
        ];
    }
}
