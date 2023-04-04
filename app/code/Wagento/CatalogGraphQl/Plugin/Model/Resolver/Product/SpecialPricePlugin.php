<?php

namespace Wagento\CatalogGraphQl\Plugin\Model\Resolver\Product;

use Magento\Catalog\Model\Product;
use Magento\CatalogGraphQl\Model\Resolver\Product\SpecialPrice;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class SpecialPrice
 */
class SpecialPricePlugin
{
    /**
     * @param SpecialPrice $subject
     * @param Field        $field
     * @param              $context
     * @param ResolveInfo  $info
     * @param array|null   $value
     * @param array|null   $args
     *
     * @return array
     */
    public function beforeResolve(
        SpecialPrice $subject,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!$context->getUserId()) {
            if (isset($value['model'])) {
                /** @var Product $product */
                $product = $value['model'];
                $product->setData('can_show_price', false);
            }
        }
        return [
            $field,
            $context,
            $info,
            $value,
            $args
        ];
    }
}
