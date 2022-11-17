<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\Catalog\Block\Category;

/**
 * Class View
 */
class View extends \Magento\Catalog\Block\Category\View
{
    /**
     * @return bool
     */
    public function getCustomCategoryFilter(): bool
    {
        if ($this->_coreRegistry->registry('custom_category_filter')) {
            return true;
        }

        return false;
    }
}
