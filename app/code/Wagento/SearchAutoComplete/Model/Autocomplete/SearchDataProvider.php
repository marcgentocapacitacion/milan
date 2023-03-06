<?php

namespace Wagento\SearchAutoComplete\Model\Autocomplete;

use Magento\Search\Model\ResourceModel\Query\Collection;
use Magento\Search\Model\Autocomplete\Item;

/**
 * Class SearchDataProvider
 */
class SearchDataProvider extends \WeltPixel\SearchAutoComplete\Model\Autocomplete\SearchDataProvider
{
    /**
     * @return array|\Magento\Search\Model\Autocomplete\Item|\Magento\Search\Model\Autocomplete\ItemInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItems()
    {
        $results = $items = [];
        $queryFactory = $this->queryFactory->get();
        $query = $queryFactory->getQueryText();
        $collection = $queryFactory->getSuggestCollection();

        if (!$this->_helper->isEnabled()) {
            return $this->getSearchAutoCompleteDisable($collection, $query);
        }

        $maxItemsDisplayed = $this->_helper->getMaxNumberItemsDisplayed();
        $productIds = $this->searchProducts($query);
        $resultItem[] = $this->itemFactory->create([
            'title' => __('No suggestions found'),
            'num_results' => 0,
        ]);

        $isPopularSuggestionEnabled = $this->_helper->isEnablePopularSuggestions();
        $suggestionMaxItemsDisplayed = $this->_helper->getMaxNumberOfPopularSuggestionsDisplayed();
        if ($isPopularSuggestionEnabled && $suggestionMaxItemsDisplayed) {
            $collection->setPageSize($suggestionMaxItemsDisplayed)
                ->setCurPage(1);
        }

        if ($productIds) {
            $irelevantProductIds = array_slice($productIds, $maxItemsDisplayed);
            $productIds = array_slice($productIds, 0, $maxItemsDisplayed);
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in')->create();
            $products = $this->productRepository->getList($searchCriteria);
            foreach ($products->getItems() as $product) {
                if (!$product->isSalable()) {
                    continue;
                }
                $items[] = $this->getMountArrayProductsData($product);
            }

            $items = $this->getIrelevantProductIds($irelevantProductIds, $items);

            if ($collection->getSize() >= 1) {
                $result = [];
                foreach ($collection as $item) {
                    $resultItem = $this->itemFactory->create([
                        'title' => $item->getQueryText(),
                        'num_results' => $item->getNumResults(),
                    ]);
                    if ($resultItem->getTitle() == $query) {
                        array_unshift($result, $resultItem);
                    } else {
                        $result[] = $resultItem;
                    }
                }
            } else {
                $result = [];
                $resultItem = $this->itemFactory->create([
                    'title' => 'No suggestions found',
                    'num_results' => ''
                ]);
                array_unshift($result, $resultItem);
            }
            $results = array_merge($items, $result);
        }
        $response = (!empty($results)) ? $results : $resultItem;
        return $response;
    }

    /**
     * @param Collection $collection
     * @param mixed      $query
     *
     * @return array
     */
    protected function getSearchAutoCompleteDisable(Collection $collection, mixed $query): array
    {
        $result = [];
        foreach ($collection as $item) {
            $resultItem = $this->itemFactory->create([
                'title' => $item->getQueryText(),
                'num_results' => $item->getNumResults(),
            ]);
            if ($resultItem->getTitle() == $query) {
                array_unshift($result, $resultItem);
            } else {
                $result[] = $resultItem;
            }
        }
        return ($this->limit) ? array_splice($result, 0, $this->limit) : $result;
    }

    /**
     * @param mixed $product
     *
     * @return Item
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getMountArrayProductsData(mixed $product): Item
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $imageHelper = $this->imageHelper->init($product, 'product_page_image_small');
        $image = $imageHelper->resize($this->_helper->getWidthOfTheImage($storeId))->getUrl();
        return $this->itemFactory->create([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $this->priceCurrency->format($product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue(), false),
            'special_price' => $this->priceCurrency->format($product->getPriceInfo()->getPrice('special_price')->getAmount()->getValue(), false),
            'final_price' => $this->priceCurrency->format($product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue(), false),
            'has_special_price' => $product->getSpecialPrice() > 0 ? true : false,
            'image' => $image,
            'description' => $product->getDescription(),
            'url' => $product->getProductUrl(),
            'object' => $product
        ]);
    }

    /**
     * @param array $irelevantProductIds
     * @param array $items
     *
     * @return array
     */
    protected function getIrelevantProductIds(array $irelevantProductIds, array $items): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $irelevantProductIds, 'in')->create();
        $products = $this->productRepository->getList($searchCriteria);

        foreach ($products->getItems() as $product) {
            if (!$product->isSalable()) {
                continue;
            }
            $items[] = $this->itemFactory->create([
                'id' => $product->getId(),
            ]);
        }
        return $items;
    }
}
