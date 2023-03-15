<?php

namespace Wagento\SearchAutoComplete\Block;

use Magento\Framework\Data\Form\FormKey;
use WeltPixel\SearchAutoComplete\Model\Autocomplete\SearchDataProvider;
use Magento\Catalog\ViewModel\Product\Listing\PreparePostData;

/**
 * Class SearchAutoComplete
 */
class SearchAutoComplete extends \Magento\Catalog\Block\Product\AbstractProduct
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
     * @param FormKey                                $formKey
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array                                  $data
     */
    public function __construct(
        SearchDataProvider $dataProvider,
        PreparePostData $preparePostData,
        FormKey $formKey,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
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
