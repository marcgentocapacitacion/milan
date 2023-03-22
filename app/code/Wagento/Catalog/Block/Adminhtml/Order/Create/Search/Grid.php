<?php

namespace Wagento\Catalog\Block\Adminhtml\Order\Create\Search;

use Magento\Catalog\Helper\Product as HelperProduct;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid\DataProvider\ProductCollection;

/**
 * Class Grid
 */
class Grid extends \Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid
{
    /**
     * @var HelperProduct
     */
    protected HelperProduct $helperProduct;

    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Sales\Model\Config $salesConfig,
        HelperProduct $helperProduct,
        EntityManager $entityManager,
        array $data = [],
        ProductCollection $productCollectionProvider = null
    ) {
        parent::__construct(
            $context,
            $backendHelper,
            $productFactory,
            $catalogConfig,
            $sessionQuote,
            $salesConfig,
            $data,
            $productCollectionProvider
        );
        $this->helperProduct = $helperProduct;
        $this->entityManager = $entityManager;
    }

    /**
     * Prepare columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $verifyStock = function($row) {
            $this->helperProduct->setSkipSaleableCheck();
            if (!$row->hasData('is_salable')) {
                $row = $this->entityManager->load($row, $row->getId());
            }
            $isSalable = $row->isSalable() ?? false;
            $this->helperProduct->setSkipSaleableCheck(true);
            return $isSalable;
        };

        $this->addColumn(
            'in_products',
            [
                'header' => __('Select'),
                'type' => 'checkbox',
                'name' => 'in_products',
                'values' => $this->_getSelectedProducts(),
                'renderer' => \Wagento\Catalog\Block\Adminhtml\Order\Create\Search\Grid\Renderer\Checkbox::class,
                'index' => 'entity_id',
                'sortable' => false,
                'is_salable' => $verifyStock,
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );

        $this->addColumn(
            'qty',
            [
                'filter' => false,
                'sortable' => false,
                'header' => __('Quantity'),
                'renderer' => \Wagento\Catalog\Block\Adminhtml\Order\Create\Search\Grid\Renderer\Qty::class,
                'name' => 'qty',
                'inline_css' => 'qty',
                'type' => 'input',
                'validate_class' => 'validate-number',
                'index' => 'qty',
                'is_salable' => $verifyStock,
            ]
        );
        return $this;
    }
}
