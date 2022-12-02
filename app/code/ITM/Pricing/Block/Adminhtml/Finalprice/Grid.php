<?php
    
namespace ITM\Pricing\Block\Adminhtml\Finalprice;
    
use ITM\Pricing\Model\System\Config\Status;
use Magento\Framework\Exception;
    
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
     
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_collectionFactory;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \ITM\Pricing\Model\ResourceModel\Finalprice\CollectionFactory $collectionFactory,
        //\ITM\Pricing\Model\ResourceModel\Finalprice\Collection $collectionFactory,
        Status $status,
        array $data = []
    ) {

        $this->_status = $status;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setId('finalpriceGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }
                
    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId =(int )$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }
                
    /**
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
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'entity_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        
        $this->addColumn('website_id', [
            'header' => __('Website ID'),
            'index' => 'website_id',
            'class' => 'website_id'
            ]);

        $this->addColumn('group_id', [
            'header' => __('Group ID'),
            'index' => 'group_id',
            'class' => 'group_id'
            ]);

        $this->addColumn('customer_group_id', [
            'header' => __('Customer Group ID'),
            'index' => 'customer_group_id',
            'class' => 'customer_group_id'
            ]);

        $this->addColumn('customer_id', [
            'header' => __('Customer ID'),
            'index' => 'customer_id',
            'class' => 'customer_id'
            ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'sku'
            ]);

        $this->addColumn('uom_entry', [
            'header' => __('Uom Entry'),
            'index' => 'uom_entry',
            'class' => 'uom_entry'
            ]);

        $this->addColumn('qty', [
            'header' => __('Qty'),
            'index' => 'qty',
            'class' => 'qty'
            ]);

        $this->addColumn('price', [
            'header' => __('Price'),
            'index' => 'price',
            'class' => 'price'
            ]);
                
        $this->addColumn('status', [
            'header' => __('Status'),
            'index' => 'status',
            'class' => 'status',
            'type' => 'options',
            'options' => $this->_status->toOptionArray()
        ]);
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        return parent::_prepareColumns();
    }
                
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_id');
        $this->getMassactionBlock()->addItem('delete', [
            'label' => __('Delete'),
            'url' => $this->getUrl('itm_pricing/*/massDelete'),
            'confirm' => __('Are you sure?')
        ]);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('itm_pricing/*/index', ['_current' => true]);
    }
    
    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('itm_pricing/*/edit', [
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getEntityId()
        ]);
    }
}
