<?php

namespace Wagento\Catalog\Ui\DataProvider\Catalog\Product\Form\Modifier\Documents;

use Magento\Catalog\Api\ProductAttributeGroupRepositoryInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\Attribute\ScopeOverriddenValue;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory as EavAttributeFactory;
use Magento\Catalog\Ui\DataProvider\CatalogEavValidationRules;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Eav\CompositeConfigProcessor;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Filter\Translit;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Mapper\FormElement as FormElementMapper;
use Magento\Ui\DataProvider\Mapper\MetaProperties as MetaPropertiesMapper;

/**
 * Class Eav
 */
class Eav extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Eav
{
    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @param UrlInterface                             $urlBuilder
     * @param LocatorInterface                         $locator
     * @param CatalogEavValidationRules                $catalogEavValidationRules
     * @param Config                                   $eavConfig
     * @param RequestInterface                         $request
     * @param GroupCollectionFactory                   $groupCollectionFactory
     * @param StoreManagerInterface                    $storeManager
     * @param FormElementMapper                        $formElementMapper
     * @param MetaPropertiesMapper                     $metaPropertiesMapper
     * @param ProductAttributeGroupRepositoryInterface $attributeGroupRepository
     * @param ProductAttributeRepositoryInterface      $attributeRepository
     * @param SearchCriteriaBuilder                    $searchCriteriaBuilder
     * @param SortOrderBuilder                         $sortOrderBuilder
     * @param EavAttributeFactory                      $eavAttributeFactory
     * @param Translit                                 $translitFilter
     * @param ArrayManager                             $arrayManager
     * @param ScopeOverriddenValue                     $scopeOverriddenValue
     * @param DataPersistorInterface                   $dataPersistor
     * @param                                          $attributesToDisable
     * @param                                          $attributesToEliminate
     * @param CompositeConfigProcessor|null            $wysiwygConfigProcessor
     * @param ScopeConfigInterface|null                $scopeConfig
     * @param AttributeCollectionFactory|null          $attributeCollectionFactory
     * @param AuthorizationInterface|null              $auth
     */
    public function __construct(
        UrlInterface $urlBuilder,
        LocatorInterface $locator,
        CatalogEavValidationRules $catalogEavValidationRules,
        Config $eavConfig,
        RequestInterface $request,
        GroupCollectionFactory $groupCollectionFactory,
        StoreManagerInterface $storeManager,
        FormElementMapper $formElementMapper,
        MetaPropertiesMapper $metaPropertiesMapper,
        ProductAttributeGroupRepositoryInterface $attributeGroupRepository,
        ProductAttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        EavAttributeFactory $eavAttributeFactory,
        Translit $translitFilter,
        ArrayManager $arrayManager,
        ScopeOverriddenValue $scopeOverriddenValue,
        DataPersistorInterface $dataPersistor,
        $attributesToDisable = [],
        $attributesToEliminate = [],
        CompositeConfigProcessor $wysiwygConfigProcessor = null,
        ScopeConfigInterface $scopeConfig = null,
        AttributeCollectionFactory $attributeCollectionFactory = null,
        ?AuthorizationInterface $auth = null
    ) {
        parent::__construct(
            $locator,
            $catalogEavValidationRules,
            $eavConfig,
            $request,
            $groupCollectionFactory,
            $storeManager,
            $formElementMapper,
            $metaPropertiesMapper,
            $attributeGroupRepository,
            $attributeRepository,
            $searchCriteriaBuilder,
            $sortOrderBuilder,
            $eavAttributeFactory,
            $translitFilter,
            $arrayManager,
            $scopeOverriddenValue,
            $dataPersistor,
            $attributesToDisable,
            $attributesToEliminate,
            $wysiwygConfigProcessor,
            $scopeConfig,
            $attributeCollectionFactory,
            $auth
        );

        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        $meta = parent::modifyMeta($meta);
        $documents = $meta['content']['children']['container_documents']['children']['documents'] ?? false;
        if (!$documents) {
            return $meta;
        }

        if (!isset($documents['arguments']['data']['config'])) {
            return $meta;
        }

        $documents['arguments']['data']['config']['uploaderConfig'] = [
            'url' => $this->urlBuilder->getUrl('catalog/product_file/upload', [
                'type' => 'file',
                '_secure' => true
            ])
        ];
        $documents['arguments']['data']['config']['fileInputName'] = 'file';
        $documents['arguments']['data']['config']['componentType'] = 'fileUploader';
        $documents['arguments']['data']['config']['elementTmpl'] = 'Magento_Ui/js/form/element/file-uploader';
        $meta['content']['children']['container_documents']['children']['documents'] = $documents;
        return $meta;
    }
}
