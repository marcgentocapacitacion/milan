<?php
    
namespace ITM\Pricing\Block\Adminhtml\Uomweight;
    
use ITM\Pricing\Model\System\Config\Status;
use Magento\Framework\Exception;
use ITM\Pricing\Model\System\Config\UomCode;
    
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
     
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_collectionFactory;
    protected $_uom_codes;
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
        \ITM\Pricing\Model\ResourceModel\Uomweight\CollectionFactory $collectionFactory,
        //\ITM\Pricing\Model\ResourceModel\Uomweight\Collection $collectionFactory,
        Status $status,
        UomCode $uom_codes,
        array $data = []
    ) {
        $this->_uom_codes = $uom_codes;
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
        $this->setId('uomweightGrid');
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
        
        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'sku'
            ]);

        $this->addColumn('uom_entry', 
            [
                'header' => __('Uom'),
                'index' => 'uom_entry',
                'class' => 'uom_entry',
                'type' => 'options',
                'options' => $this->_uom_codes->toOptionArray()
            ]);

        $this->addColumn('weight', [
            'header' => __('Weight'),
            'index' => 'weight',
            'class' => 'weight'
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
