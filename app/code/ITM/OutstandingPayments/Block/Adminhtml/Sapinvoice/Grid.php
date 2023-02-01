<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml\Sapinvoice;
    
use ITM\OutstandingPayments\Model\System\Config\Status;
use Magento\Framework\Exception;
    
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
     
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    
    protected $_invoiceStatus;
    
    protected $_collectionFactory;
    
    protected $_company;
    
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
        \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $collectionFactory,
        \ITM\OutstandingPayments\Model\System\Config\Status $status,
        \ITM\OutstandingPayments\Model\System\Config\Company $company,
        \ITM\OutstandingPayments\Model\System\Config\InvoiceStatus $invoiceStatus,
        array $data = []
    ) {

        $this->_status = $status;
        $this->_company = $company;
        $this->_invoiceStatus = $invoiceStatus;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setId('sapinvoiceGrid');
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
        
        $this->addColumn('doc_entry', [
            'header' => __('Doc Entry'),
            'index' => 'doc_entry',
            'class' => 'doc_entry'
            ]);

        $this->addColumn('doc_num', [
            'header' => __('Doc Num'),
            'index' => 'doc_num',
            'class' => 'doc_num'
            ]);

        $this->addColumn('card_code', [
            'header' => __('Card Code'),
            'index' => 'card_code',
            'class' => 'card_code'
            ]);

        $this->addColumn('email', [
            'header' => __('Email'),
            'index' => 'email',
            'class' => 'email'
            ]);

        $this->addColumn('doc_total', [
            'header' => __('Doc Total'),
            'index' => 'doc_total',
            'type'  => 'price',
            'currency_code' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
            'class' => 'doc_total'
            ]);

        $this->addColumn('open_balance', [
            'header' => __('Open Balance'),
            'index' => 'open_balance',
            'type'  => 'price',
            'currency_code' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
            'class' => 'open_balance'
            ]);

        $this->addColumn('doc_date', [
            'header' => __('Doc Date'),
            'type' => 'date',
            'align' => 'center',
            'index' => 'doc_date',
            'default' => ' ---- '
            ]);

        $this->addColumn('doc_due_date', [
            'header' => __('Doc Due Date'),
            'type' => 'date',
            'align' => 'center',
            'index' => 'doc_due_date',
            'default' => ' ---- '
            ]);
        $this->addColumn('doc_type', [
            'header' => __('Type'),
            'index' => 'doc_type',
            'class' => 'doc_type'
        ]);

        $this->addColumn('invoice_status', [
            'header' => __('Status'),
            'index' => 'invoice_status',
            'class' => 'invoice_status',
            'type' => 'options',
            'options' => $this->_invoiceStatus->toOptionArray()
            ]);

        $this->addColumn('sap_company', [
            'header' => __('Sap Company'),
            'index' => 'sap_company',
            'class' => 'sap_company',
            'type' => 'options',
            'options' => $this->_company->toOptionArray()
            ]);

     /*   $this->addColumn('file', [
            'header' => __('File'),
            'index' => 'file',
            'class' => 'file'
            ]);
       */         
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
            'url' => $this->getUrl('itm_outstandingpayments/*/massDelete'),
            'confirm' => __('Are you sure?')
        ]);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('itm_outstandingpayments/*/index', ['_current' => true]);
    }
    
    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('itm_outstandingpayments/*/edit', [
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getEntityId()
        ]);
    }
}
