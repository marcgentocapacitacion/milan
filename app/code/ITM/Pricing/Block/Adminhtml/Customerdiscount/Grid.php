<?php
namespace ITM\Pricing\Block\Adminhtml\Customerdiscount;

use ITM\Pricing\Model\System\Config\Status;
use ITM\Pricing\Model\System\Config\Customers;
use ITM\Pricing\Model\System\Config\Websites;

use Magento\Framework\Exception;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     *
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     *
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    private $setsFactory;

    /**
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $_productFactory;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Type
     */
    private $_type;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    private $_status;

    private $_customers;

    private $_websites;

    private $_collectionFactory;

    /**
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    private $_visibility;

    /**
     *
     * @var \Magento\Store\Model\WebsiteFactory
     */
    private $_websiteFactory;


    protected $_helper;

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
        \ITM\Pricing\Model\ResourceModel\Customerdiscount\CollectionFactory $collectionFactory,
        //\ITM\Pricing\Model\ResourceModel\Customerdiscount\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        Status $status,
        Customers $customers,
        Websites $websites,
        \ITM\Pricing\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_status = $status;
        $this->_customers = $customers;
        $this->_websites = $websites;
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_helper = $helper;
        $this->_helper->setStoreTimezone();
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Generate list of grid buttons
     *
     * @return string
     */
    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();

        $addButton = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData([
                'label' => __("Refresh the Cache"),
                'onclick' => "setLocation('" . $this->getUrl('*/cache/refresh', ["model" => "customerdiscount"]) . "')",
                'class' => 'action-default scalable action-reset action-tertiary'
            ])->toHtml();
        $html .= $addButton;

        return $html;
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
        $storeId = (int)$this->getRequest()->getParam('store', 0);
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
     * @return $this @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'customer_id',
            [
                'header' => __('Customer'),
                'index' => 'customer_id',
                'class' => 'customer_id',
                // 'type' => 'options',
                //'options' => $this->_customers->getOptionArray()
            ]
        );
        $this->addColumn(
            'website_id',
            [
                'header' => __('Website'),
                'index' => 'website_id',
                'class' => 'website_id',
                'type' => 'options',
                'options' => $this->_websites->getOptionArray()
            ]
        );
        $this->addColumn(
            'attribute_code',
            [
                'header' => __('Attribute Code'),
                'index' => 'attribute_code',
                'class' => 'attribute_code'
            ]
        );
        $this->addColumn(
            'attribute_value',
            [
                'header' => __('Attribute Value'),
                'index' => 'attribute_value',
                'class' => 'attribute_value'
            ]
        );
        $this->addColumn(
            'start_date',
            [
                'header' => __('Start date'),
                'type' => 'date',
                'align' => 'center',
                'index' => 'start_date',
                'default' => ' ---- '
            ]
        );
        $this->addColumn(
            'end_date',
            [
                'header' => __('End Date'),
                'type' => 'date',
                'align' => 'center',
                'index' => 'end_date',
                'gmtoffset' => true,
                'default' => ' ---- '
            ]
        );
        $this->addColumn(
            'discount',
            [
                'header' => __('Discount(%)'),
                'index' => 'discount',
                'class' => 'discount'
            ]
        );
        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'class' => 'status',
                'type' => 'options',
                'options' => $this->_status->getOptionArray()
            ]
        );

        /* {{CedAddGridColumn}} */

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

        $this->getMassactionBlock()
            ->addItem(
                'delete',
                [
                    'label' => __('Delete'),
                    'url' => $this->getUrl('itm_pricing/*/massDelete'),
                    'confirm' => __('Are you sure?')
                ]
            );
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
        return $this->getUrl(
            'itm_pricing/*/edit',
            [
                'store' => $this->getRequest()
                    ->getParam('store'),
                'id' => $row->getId()
            ]
        );
    }
}