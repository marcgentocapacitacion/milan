<?php

namespace Wagento\Catalog\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Block\Product\ListProduct;

/**
 * Class MiniLogos
 */
class MiniLogos extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $config;

    /**
     * @var array|null
     */
    protected ?array $jsonPage;

    /**
     * @var PricingHelper
     */
    protected PricingHelper $priceHelper;

    /**
     * @var ImageFactory
     */
    protected ImageFactory $imageFactory;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var ListProduct
     */
    protected ListProduct $listProduct;

    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @param Template\Context            $context
     * @param ScopeConfigInterface        $config
     * @param PricingHelper               $priceHelper
     * @param ImageFactory                $imageFactory
     * @param CollectionFactory           $collectionFactory
     * @param ListProduct                 $listProduct
     * @param CategoryRepositoryInterface $categoryRepository
     * @param array                       $data
     */
    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $config,
        PricingHelper $priceHelper,
        ImageFactory $imageFactory,
        CollectionFactory $collectionFactory,
        ListProduct $listProduct,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->priceHelper = $priceHelper;
        $this->imageFactory = $imageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->listProduct = $listProduct;
        $this->categoryRepository = $categoryRepository;
        $this->jsonPage = [];
    }

    /**
     * @return array
     */
    public function getConfigBrands(): array
    {
        if ($this->jsonPage) {
            return $this->jsonPage;
        }

        $config = $this->config->getValue(
            'wagento_catalog/custom_pages_categories/custom_pages_brands',
            ScopeInterface::SCOPE_STORE
        );

        if (!$config) {
            return [];
        }

        $this->jsonPage = \json_decode($config, true);
        return $this->jsonPage;
    }

    /**
     * @param string $image
     * @param string $type
     *
     * @return string
     */
    private function getConfigUrlImages(string $image, string $type): string
    {
        return $this->getUrl(DirectoryList::MEDIA . "/customPagesBrands/$type/") . $image;
    }

    /**
     * @param string $image
     *
     * @return string
     */
    public function getConfigUrlBrandsImages(string $image): string
    {
        return $this->getConfigUrlImages($image, 'brands');
    }

    /**
     * @param string $image
     *
     * @return string
     */
    public function getConfigUrlBannerImages(string $image): string
    {
        return $this->getConfigUrlImages($image, 'banner');
    }

    /**
     * @return string|null
     */
    public function getConfigBanner(): ?string
    {
        if (!($page = $this->_request->getParam('category'))) {
            return null;
        }

        $image = $this->getConfigBrands()[$page]['image_banner'] ?? false;
        if (!$image) {
            return null;
        }
        return $this->getConfigUrlBannerImages($image);
    }

    /**
     * @return string
     */
    public function getConfigTitle(): string
    {
        if (!($page = $this->_request->getParam('category'))) {
            return '';
        }

        return $this->getConfigBrands()[$page]['title'] ?? '';
    }

    /**
     * @return false|Collection
     */
    public function getProductionCollection(): false|Collection
    {
        if (!($page = $this->_request->getParam('category'))) {
            return false;
        }

        $categoriesCarousel = explode(
            ',',
            $this->getConfigBrands()[$page]['categories_carousel']
        ) ?? false;
        if (!$categoriesCarousel) {
            return false;
        }

        $ids = [];
        foreach ($categoriesCarousel as $category) {
            try {
                $categoryModel = $this->categoryRepository->get($category);
                $ids[] = $categoryModel->getId();
                $ids += $categoryModel->getAllChildren(true);
            } catch (\Exception $exception) {
                continue;
            }
        }

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }

    /**
     * @param ProductModel $product
     * @param string       $imageId
     * @param array        $attributes
     *
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage(ProductModel $product, string $imageId, array $attributes = [])
    {
        return $this->imageFactory->create($product, $imageId, $attributes);
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
     * @return ListProduct
     */
    public function getListProduct(): ListProduct
    {
        return $this->listProduct;
    }
}
