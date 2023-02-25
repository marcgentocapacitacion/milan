<?php

namespace Wagento\OfflineShipping\Model\Source;

/**
 * Class CostTypeHandlingFee
 */
class CostTypeHandlingFee implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'product',
                'label' => __('Product'),
            ],
            [
                'value' => 'handling_fee',
                'label' => __('Handling Fee')
            ]
        ];
    }
}
