<?php

namespace Wagento\Rutavity\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Pricing\Helper\Data;

class ProductSlider extends Template
{
    /**
     * @var ProductCollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var HelperImport
     */
    protected $_helperImport;

    /**
     * @var PriceHelper
     */
    protected $_priceHelper;

    /**
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Image $helperImport
     * @param Data $priceHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Image $helperImport,
        Data $priceHelper,
        array $data = []

    ){
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helperImport = $helperImport;
        $this->priceHelper = $priceHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return ProductSlider
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Get category by id.
     *
     * @return mixed
     */
    public function getCategory()
    {
        $categoryId = $this->getCategoryId();
        return $categoryId;
    }

    public function getProductImage($product) {
        $imageUrl = $this->_helperImport->init($product, 'product_page_image_small')
            ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
            ->resize(300,223)
            ->getUrl();
        return $imageUrl;
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * Get Category's products collection.
     *
     * @return mixed
     */
    public function getProductCollection()
    {
        try {
            if (!empty($this->getCategory())) {
                $id = [$this->getCategory()];
                $collection = $this->_productCollectionFactory->create();
                $collection->addAttributeToSelect('*');
                $collection->addCategoriesFilter(['in' => $id]);
                return $collection;
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
