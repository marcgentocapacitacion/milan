<?php
namespace ITM\Pricing\Block\Adminhtml\Uomgroupdetails;

use ITM\Pricing\Model\System\Config\Status;
use ITM\Pricing\Model\System\Config\UomCode;
use ITM\Pricing\Model\System\Config\UomGroup;
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

    protected $_uom_codes;

    protected $_uom_groups;

    /**
     *
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    protected $resource;
    
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
        \ITM\Pricing\Model\ResourceModel\Uomgroupdetails\CollectionFactory $collectionFactory,
        //\ITM\Pricing\Model\ResourceModel\Uomgroupdetails\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\App\ResourceConnection $resource,
        Status $status,
        UomCode $uom_codes,
        UomGroup $uom_groups,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_uom_codes = $uom_codes;
        $this->_uom_groups = $uom_groups;
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->resource = $resource;
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
        $collection->getSelect()->join(array(
            'group' =>  $this->resource->getTableName('itm_pricing_uomgroup')
        ), '`main_table`.ugp_entry=`group`.ugp_entry', array(
            'group.base_uom'
        ));
        
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
        $this->addColumn('ugp_entry', [
                'header' => __('UOM Group'),
                'index' => 'ugp_entry',
                'class' => 'ugp_entry',
                'type' => 'options',
                'options' => $this->_uom_groups->toOptionArray()
            ]);
        $this->addColumn('alt_qty', [
                'header' => __('Alt Qty'),
                'index' => 'alt_qty',
                'class' => 'alt_qty'
            ]);
        $this->addColumn('uom_entry', [
                'header' => __('Unit Of Measurement'),
                'index' => 'uom_entry',
                'class' => 'uom_entry',
                'type' => 'options',
                'options' => $this->_uom_codes->toOptionArray()
            ]);
        
        $this->addColumn('base_qty', [
                'header' => __('Base Qty'),
                'index' => 'base_qty',
                'class' => 'base_qty'
            ]);
        $this->addColumn('base_uom', [
                'header' => __('Base Uom'),
                'index' => 'base_uom',
                'class' => 'base_uom',
                'type' => 'options',
                'options' => $this->_uom_codes->toOptionArray()
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
