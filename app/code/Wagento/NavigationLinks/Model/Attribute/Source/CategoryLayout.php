<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\NavigationLinks\Model\Attribute\Source;

use WeltPixel\NavigationLinks\Model\Attribute\Source\CategoryLayout as WeltPixelCategoryLayout;

/**
 * Class CategoryLayout
 */
class CategoryLayout extends WeltPixelCategoryLayout
{
    /**
     * @const string
     */
    public const LAYOUT_IMAGES_WITH_PRODUCTS = 'subcategories_images_with_products';

    /**
     * @return array
     */
    public function getAvailableLayouts() {
        $parent = parent::getAvailableLayouts();
        $parent[self::LAYOUT_IMAGES_WITH_PRODUCTS] = 'Subcategories with images and Products';
        return $parent;
    }
}
