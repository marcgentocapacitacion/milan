<?php
namespace ITM\Pricing\Block\Adminhtml\Uom;

use ITM\Pricing\Model\System\Config\Status;
use Magento\Framework\Exception;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     *
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     *
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;

    protected $_collectionFactory;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     *
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \ITM\Pricing\Model\ResourceModel\Uom\CollectionFactory $collectionFactory,
        //\ITM\Pricing\Model\ResourceModel\Uom\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        Status $status,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     *
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        //$collection = $this->_collectionFactory->load();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     *
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()
                    ->joinField('websites', 'catalog_product_website', 'website_id', 'product_id=entity_id', null, 'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     *
     * @return $this @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]);
        $this->addColumn('uom_entry', [
                'header' => __('UOM Entry'),
                'index' => 'uom_entry',
                'class' => 'uom_entry'
            ]);
        $this->addColumn('uom_code', [
                'header' => __('UOM Code'),
                'index' => 'uom_code',
                'class' => 'uom_code'
            ]);
        $this->addColumn('uom_name', [
                'header' => __('UOM Name'),
                'index' => 'uom_name',
                'class' => 'uom_name'
            ]);
        
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        
        return parent::_prepareColumns();
    }

    /**
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        
        $this->getMassactionBlock()->addItem('delete', [
                'label' => __('Delete'),
                'url' => $this->getUrl('itm_pricing/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]);
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('itm_pricing/*/index', [
            '_current' => true
        ]);
    }

    /**
     *
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('itm_pricing/*/edit', [
            'store' => $this->getRequest()
                ->getParam('store'),
            'id' => $row->getId()
        ]);
    }
}
