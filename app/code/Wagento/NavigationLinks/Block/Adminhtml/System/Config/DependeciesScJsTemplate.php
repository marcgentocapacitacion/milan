<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\NavigationLinks\Block\Adminhtml\System\Config;

use WeltPixel\NavigationLinks\Block\Adminhtml\System\Config\DependeciesScJsTemplate as WeltPixelDependeciesScJsTemplate;
use Wagento\NavigationLinks\Model\Attribute\Source\CategoryLayout;

/**
 * Class DependeciesScJsTemplate
 */
class DependeciesScJsTemplate extends WeltPixelDependeciesScJsTemplate
{
    /**
     * @return bool
     */
    public function areSubcategoryOptionsVisible() {
        $currentCategory = $this->_registry->registry('current_category');
        if (!$currentCategory->getId()) {
            $parentRequestCategId = $this->getRequest()->getParam('parent');
            $storeId = $this->getRequest()->getParam('store');
            $parentCategory = $this->_categoryRepository->get($parentRequestCategId, $storeId);
            $subcategoriesLayout = $parentCategory->getData('weltpixel_sc_layout');
        } else {
            $subcategoriesLayout = $currentCategory->getParentCategory()->getData('weltpixel_sc_layout');
        }

        if ($subcategoriesLayout == CategoryLayout::LAYOUT_IMAGES) {
            return true;
        }

        if ($subcategoriesLayout == CategoryLayout::LAYOUT_IMAGES_WITH_PRODUCTS) {
            return true;
        }

        return false;
    }
}
