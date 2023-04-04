<?php

namespace Wagento\InventoryGraphQl\Plugin\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\InventoryGraphQl\Model\Resolver\StockStatusProvider;

/**
 * Class StockStatusProviderPlugin
 */
class StockStatusProviderPlugin
{
    /**
     * @param StockStatusProvider $subject
     * @param Field               $field
     * @param                     $result
     * @param                     $context
     * @param ResolveInfo         $info
     * @param array|null          $value
     * @param array|null          $args
     *
     * @return mixed
     */
    public function afterResolve(
        StockStatusProvider $subject,
        Field $field,
        $result,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return $result;
    }
}
