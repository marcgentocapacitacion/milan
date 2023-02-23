<?php

namespace Wagento\Sales\Model\System\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class OrderOrigin
 */
class OrderOrigin implements OptionSourceInterface
{
    /**
     * @const string
     */
    public const ORDER_ORIGIN_ADMIN = '0';

    /**
     * @const string
     */
    public const ORDER_ORIGIN_ADMIN_LABEL = 'Admin Panel';

    /**
     * @const string
     */
    public const ORDER_ORIGIN_STORE = '1';

    /**
     * @const string
     */
    public const ORDER_ORIGIN_STORE_LABEL = 'Store View';

    /**
     * Get input types which use predefined source
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ORDER_ORIGIN_ADMIN, 'label' => __(self::ORDER_ORIGIN_ADMIN_LABEL)],
            ['value' => self::ORDER_ORIGIN_STORE, 'label' => __(self::ORDER_ORIGIN_STORE_LABEL)]
        ];
    }
}
