<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml\Company;
    
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Continue" button
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_company';
        $this->_blockGroup = 'ITM_OutstandingPayments';
        parent::_construct();
        $this->buttonList->add(
            'save_and_continue_edit',
            [
            'class' => 'save',
            'label' => __('Save and Continue Edit'),
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'saveAndContinueEdit',
                        'target' => '#edit_form'
                    ]
                ]
             ]
            ],
            10
        );
    }
    
    /**
     * Getter for form header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {

        $item = $this->_coreRegistry->registry('current_itm_outstandingpayments_company');
        if ($item->getEntityId()) {
            return __("Edit Item '", $this->escapeHtml($item->getEntityId()));
        } else {
            return __('New Item');
        }
    }
}
