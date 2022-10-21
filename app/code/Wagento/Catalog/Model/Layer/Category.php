<?php

namespace Wagento\Catalog\Model\Layer;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Wagento\Catalog\Model\Config;

/**
 * Class Category
 */
class Category extends \Magento\Catalog\Model\Layer\Category
{
    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductCollection()
    {
        $index = implode('_', $this->getCategoryFilter());
        if (isset($this->_productCollections[$index])) {
            $collection = $this->_productCollections[$index];
        } else {
            $collection = $this->collectionProvider->getCollection($this->getCurrentCategory());
            $collection->addCategoriesFilter(['eq' => $this->getCategoryFilter()]);
            $this->prepareProductCollection($collection);
            $this->_productCollections[$index] = $collection;
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function getCategoryFilter(): array
    {
        $categories = $this->registry->registry('custom_category_filter');
        if (!$categories) {
            return [$this->getCurrentCategory()];
        }

        $ids = [];
        foreach ($categories as $category) {
            try {
                $categoryModel = $this->categoryRepository->get($category);
                $ids[] = $categoryModel->getId();
                $ids += $categoryModel->getAllChildren(true);
            } catch (\Exception $exception) {
                continue;
            }
        }
        return $ids;
    }
}
