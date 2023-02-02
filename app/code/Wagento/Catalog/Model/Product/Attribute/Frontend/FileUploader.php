<?php

namespace Wagento\Catalog\Model\Product\Attribute\Frontend;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FileUploader
 */
class FileUploader extends \Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * Returns url to product image
     *
     * @param  \Magento\Catalog\Model\Product $product
     *
     * @return string|false
     */
    public function getUrl($product)
    {
        $this->_storeManager
            ->getStore($product->getStore())
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/';
    }
}
