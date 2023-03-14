<?php

namespace Wagento\SearchAutoComplete\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use WeltPixel\SearchAutoComplete\Model\Autocomplete\SearchDataProvider;
use Magento\Catalog\ViewModel\Product\Listing\PreparePostData;

/**
 * Class SearchAutoComplete
 */
class SearchAutoComplete extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var SearchDataProvider
     */
    protected SearchDataProvider $dataProvider;

    /**
     * @var PreparePostData
     */
    protected PreparePostData $preparePostData;

    /**
     * @var FormKey
     */
    protected FormKey $formKey;

    /**
     * @param SearchDataProvider                     $dataProvider
     * @param PreparePostData                        $preparePostData
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param PostHelper                             $postDataHelper
     * @param Resolver                               $layerResolver
     * @param CategoryRepositoryInterface            $categoryRepository
     * @param Data                                   $urlHelper
     * @param array                                  $data
     * @param OutputHelper|null                      $outputHelper
     */
    public function __construct(
        SearchDataProvider $dataProvider,
        PreparePostData $preparePostData,
        FormKey $formKey,
        \Magento\Catalog\Block\Product\Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        array $data = [],
        ?OutputHelper $outputHelper = null
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
        $this->dataProvider = $dataProvider;
        $this->preparePostData = $preparePostData;
        $this->formKey = $formKey;
    }

    /**
     * Retrieve Session Form Key
     *
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return array|\Magento\Search\Model\Autocomplete\ItemInterface[]
     */
    public function getItemsCollection()
    {
        $itemsCollection = $this->dataProvider->getItems();
        foreach ($itemsCollection as $id => $item) {
            if (!isset($item['id'])) {
                unset($itemsCollection[$id]);
            }
        }

        return $itemsCollection;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryCollection()
    {
        return $this->dataProvider->getCategoryItems();
    }

    /**
     * @return PreparePostData
     */
    public function getPreparePostData(): PreparePostData
    {
        return $this->preparePostData;
    }
}
