<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class PriceAttributes implements ArrayInterface
{

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => __(' ')
            ],
            [
                'value' => 'minimal_price',
                'label' => __('Minimal Price')
            ],
            [
                'value' => 'finel_price',
                'label' => __('Finel Price')
            ]
        ];
    }
}
