<?php

namespace Wagento\CatalogGraphQl\Plugin\Model\Resolver\Product;

use Magento\CatalogGraphQl\Model\Resolver\Product\Price;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class PricePlugin
 */
class PricePlugin
{
    /**
     * @param Price       $subject
     * @param array       $result
     * @param Field       $field
     * @param             $context
     * @param ResolveInfo $info
     * @param array|null  $value
     * @param array|null  $args
     *
     * @return array
     */
    public function afterResolve(
        Price $subject,
        array $result,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!$context->getUserId()) {
            return [
                'minimalPrice' => [
                    'amount' => [
                        'value' => null,
                        'currency' => null
                    ],
                    'adjustments' => []
                ],
                'regularPrice' => [
                    'amount' => [
                        'value' => null,
                        'currency' => null
                    ],
                    'adjustments' => []
                ],
                'maximalPrice' => [
                    'amount' => [
                        'value' => null,
                        'currency' => null
                    ],
                    'adjustments' => []
                ]
            ];
        }
        return $result;
    }
}
