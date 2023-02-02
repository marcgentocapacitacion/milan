<?php

namespace Wagento\Catalog\Model\Product\File;

/**
 * Class Config
 */
class Config extends \Magento\Catalog\Model\Product\Media\Config
{
    /**
     * Get filesystem directory path for product images relative to the media directory.
     *
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return 'documents/catalog/product';
    }

    /**
     * Get web-based directory path for product images relative to the media directory.
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return 'documents/catalog/product';
    }

    /**
     * @inheritdoc
     */
    public function getBaseMediaPath()
    {
        return 'documents/catalog/product';
    }
}
