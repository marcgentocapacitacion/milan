<?php

namespace Wagento\Catalog\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CustomCategoriesPages
 */
class CustomCategoriesPages extends AbstractFieldArray
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_Catalog::system/config/form/field/array.phtml';

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param Context                 $context
     * @param StoreManagerInterface   $storeManager
     * @param array                   $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'categories',
            [
                'label'    => __('Categories'),
                'class'    => 'input-text',
                'style'    => 'width: 200px;'
            ]
        );

        $this->addColumn(
            'products_carousel',
            [
                'label'    => __('Products for Carousel'),
                'class'    => 'input-text',
                'style'    => 'width: 200px;'
            ]
        );

        $this->addColumn(
            'title',
            [
                'label'    => __('Title'),
                'class'    => 'input-text required-entry',
                'style'    => 'width: 200px;'
            ]
        );

        $block = $this->getLayout()->createBlock(
            \Wagento\Catalog\Block\System\Config\Field\Image::class
        )->setData([
            'values' => $this->getArrayRows(),
            'image_url' => '<%- image_url_brand %>',
            'image' => 'image_brand'
        ]);
        $this->addColumn(
            'image_brand',
            [
                'label'    => __('Image Brand'),
                'class'    => 'input-text required-entry',
                'renderer' => $block
            ]
        );

        $block = $this->getLayout()->createBlock(
            \Wagento\Catalog\Block\System\Config\Field\Image::class
        )->setData([
            'values' => $this->getArrayRows(),
            'image_url' => '<%- image_url_banner %>',
            'image' => 'image_banner'
        ]);
        $this->addColumn(
            'image_banner',
            [
                'label'    => __('Image Banner'),
                'class'    => 'input-text required-entry',
                'renderer' => $block
            ]
        );
        $this->_addAfter = false;
    }

    /**
     * Prepare row and set option selected
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $urlBrand = $url . '/customPagesBrands/brands/'. $row->getData('image_brand');
        $urlBanner = $url . '/customPagesBrands/banner/'. $row->getData('image_banner');
        $row->setData('image_url_brand', $urlBrand);
        $row->setData('image_url_banner', $urlBanner);
        return parent::_prepareArrayRow($row);
    }
}
