<?php
namespace ITM\Pricing\Block\Adminhtml\Index\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{

    protected $_customers;

    protected $_websites;

    protected $_uom_codes;

    protected $_customer_groups;

    /**
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context            
     * @param \Magento\Framework\Registry $registry            
     * @param \Magento\Framework\Data\FormFactory $formFactory            
     * @param \Magento\Store\Model\System\Store $systemStore            
     * @param array $data            
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry, 
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore, 
        \ITM\Pricing\Model\System\Config\Customers $customers,
        \ITM\Pricing\Model\System\Config\Websites $websites, 
        \ITM\Pricing\Model\System\Config\UomCode $uom_codes, 
        \ITM\Pricing\Model\System\Config\CustomerGroups $customer_groups,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_customers = $customers;
        $this->_websites = $websites;
        $this->_uom_codes = $uom_codes;
        $this->_customer_groups = $customer_groups;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     * 
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('price_info_form');
        $this->setTitle(__('Price Information'));
    }

    /**
     * Prepare form
     * 
     * @return $this
     */
    protected function _prepareForm()
    {
        
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
                [
                    'data' => [
                        'id' => 'edit_form',
                        'action' => $this->getUrl('itm_pricing/index/priceinfo'),
                        'method' => 'post'
                    ]
                ]);
        
        $form->setHtmlIdPrefix('itm_');
        
        $fieldset = $form->addFieldset('base_fieldset', 
                [
                    'legend' => __('General Information'),
                    'class' => 'fieldset-wide'
                ]);
        
        $fieldset->addField('customer_id', 'text', 
                [
                    'name' => 'customer_id',
                    'label' => __('Customer'),
                    //'options' => $this->_customers->toOptionArray(),
                    'required' => true
                ]);
        $fieldset->addField('sku', 'text',
                [
                    'name' => 'sku',
                    'label' => __('SKU'),
                    'title' => __('SKU'),
                    'required' => true
                ]);
        $fieldset->addField('website_id', 'select', 
                [
                    'name' => 'website_id',
                    'label' => __('Website'),
                    'options' => $this->_websites->toOptionArray(),
                    'required' => true
                ]);
        $groups = [];
        foreach ($this->_customer_groups->toOptionArray() as $key=>$value) {
            $groups[] = $value ." (".$key.")";
        }
        $fieldset->addField('customer_group_id', 'select', 
                [
                    'name' => 'customer_group_id',
                    'label' => __('Customer Group ID'),
                    'options' => $this->_customer_groups->toOptionArray(),
                    'required' => true,
                    'after_element_html'=>'<br/>'.implode(" | ",$groups)
                ]);
        $afterElementHtml = '<p class="nm"><small>' . 'This group is the value that exist in pricing tables, you may using it as Customer Group or another attribute. If you don not have any customization please add the Customer Group Id in this field (The same as previous one).' . '</small></p>';
        
        $fieldset->addField('group_id', 'text', 
                [
                    'name' => 'group_id',
                    'label' => __('Group'),
                    'title' => __('Group'),
                    'required' => true,
                    'after_element_html' => $afterElementHtml
                ]);
        
        $fieldset->addField('uom_entry', 'select', 
            [
                'name' => 'uom_entry',
                'label' => __('UOM'),
                'options' => $this->_uom_codes->toOptionArray(),
                'required' => true
            ]);
        
        $_objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        
        $session = $_objectManager->get('Magento\Backend\Model\Session');
        $data = $session->getPageData();
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}