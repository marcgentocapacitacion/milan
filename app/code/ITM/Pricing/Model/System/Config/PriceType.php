<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class PriceType implements ArrayInterface
{

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'priority',
                'label' => __('Default')
            ],
            [
                'value' => 'lowest',
                'label' => __('Lowest')
            ],
            [
                'value' => 'highest',
                'label' => __('Highest')
            ]
        ];
    }
}
