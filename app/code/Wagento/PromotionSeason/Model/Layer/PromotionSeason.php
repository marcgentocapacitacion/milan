<?php

namespace Wagento\PromotionSeason\Model\Layer;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Layer\ContextInterface;
use Magento\Catalog\Model\Layer\StateFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class PromotionSeason
 */
class PromotionSeason extends \Magento\Catalog\Model\Layer\Category
{
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $productCollectionFactory;

    /**
     * @var ConfigurableFactory
     */
    protected ConfigurableFactory $configurableFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var array
     */
    protected array $productLoaded;

    /**
     * @param ContextInterface            $context
     * @param StateFactory                $layerStateFactory
     * @param CollectionFactory           $attributeCollectionFactory
     * @param Product                     $catalogProduct
     * @param StoreManagerInterface       $storeManager
     * @param Registry                    $registry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ConfigInterface             $config
     * @param ProductCollectionFactory    $productCollectionFactory
     * @param ConfigurableFactory         $configurableFactory
     * @param ProductRepositoryInterface  $productRepository
     * @param array                       $data
     */
    public function __construct(
        ContextInterface $context,
        StateFactory $layerStateFactory,
        CollectionFactory $attributeCollectionFactory,
        Product $catalogProduct,
        StoreManagerInterface $storeManager,
        Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        ConfigInterface $config,
        ProductCollectionFactory $productCollectionFactory,
        ConfigurableFactory $configurableFactory,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
        $this->config = $config;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->configurableFactory = $configurableFactory;
        $this->productRepository = $productRepository;
        $this->productLoaded = [];
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = $this->collectionProvider->getCollection($this->getCurrentCategory());
            $collection->addAttributeToFilter('sku', ['in' => [$this->getProductSku()]]);
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
        return $collection;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getProductSku(): array
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('itm_properties', $this->config->getItmProperties());
        $collection->addAttributeToFilter('status', '1');
        $ids = [];
        foreach ($collection->getItems() as $item) {
            if (isset($ids[$item->getSku()])) {
                continue;
            }
            $ids[$item->getSku()] = $item->getSku();
            $configurable = $this->configurableFactory->create();
            $parentIds = $configurable->getParentIdsByChild($item->getId());
            if (!$parentIds) {
                continue;
            }
            foreach ($parentIds as $parentId) {
                try {
                    if (!isset($this->productLoaded[$parentId])) {
                        $this->productLoaded[$parentId] = $this->productRepository->getById($parentId);
                    }
                    $sku = $this->productLoaded[$parentId]->getSku();
                    if (isset($ids[$sku])) {
                        continue;
                    }
                    $ids[$sku] = $sku;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return array_values($ids);
    }
}
