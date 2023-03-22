<?php

namespace Wagento\Catalog\Model;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @const string
     */
    public const USE_ALMACEN_FOR_STOCK = 'wagento_catalog/product_list/use_almacen_for_stock';

    /**
     * @var string
     */
    public const CONFIG_PATH = 'wagento_catalog/custom_pages_categories/custom_pages_brands';

    /**
     * @return bool
     */
    public function getUseAlmacenForStock(): bool;
}
