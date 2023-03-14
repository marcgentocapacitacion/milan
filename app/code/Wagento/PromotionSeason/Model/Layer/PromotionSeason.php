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
     * @param ContextInterface            $context
     * @param StateFactory                $layerStateFactory
     * @param CollectionFactory           $attributeCollectionFactory
     * @param Product                     $catalogProduct
     * @param StoreManagerInterface       $storeManager
     * @param Registry                    $registry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ConfigInterface             $config
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
            $collection->addAttributeToFilter('itm_properties', $this->config->getItmProperties());
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
        //$collection = parent::getProductCollection();
        //$collection->addAttributeToFilter('itm_properties', $this->config->getItmProperties());
        return $collection;
    }
}
