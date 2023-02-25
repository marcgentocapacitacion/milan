<?php

namespace Wagento\OfflineShipping\Model\Company\Source;

/**
 * Class ShippingType
 */
class ShippingType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @const string
     */
    public const SHIPPING_TYPE_METROPOLITAN_ID = 1;

    /**
     * @const string
     */
    public const SHIPPING_TYPE_METROPOLITAN_LABEL = 'Metropolitan';

    /**
     * @const string
     */
    public const SHIPPING_TYPE_STANDARD_ID = 2;

    /**
     * @const string
     */
    public const SHIPPING_TYPE_STANDARD_LABEL = 'Standard';

    /**
     * @const string
     */
    public const SHIPPING_TYPE_SPECIAL_ID = 3;

    /**
     * @const string
     */
    public const SHIPPING_TYPE_SPECIAL_LABEL = 'Special';

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Select'), 'value' => ''],
            ['label' => self::SHIPPING_TYPE_METROPOLITAN_LABEL, 'value' => __(self::SHIPPING_TYPE_METROPOLITAN_ID)],
            ['label' => self::SHIPPING_TYPE_STANDARD_LABEL, 'value' => __(self::SHIPPING_TYPE_STANDARD_ID)],
            ['label' => self::SHIPPING_TYPE_SPECIAL_LABEL, 'value' => __(self::SHIPPING_TYPE_SPECIAL_ID)]
        ];
    }
}
