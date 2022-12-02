<?php
    
namespace ITM\Pricing\Block\Adminhtml\Finalprice\Edit\Tab;
    
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\Pricing\Model\System\Config\Status;
    
class Main extends Generic implements TabInterface
{

    protected $_status;
    
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        array $data = []
    ) {
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    
   /**
    *
    * {@inheritdoc}
    */
    public function getTabLabel()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function getTabTitle()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function canShowTab()
    {
        return true;
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function isHidden()
    {
        return false;
    }
                
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_itm_pricing_finalprice');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Item Information')
        ]);
        if ($model->getEntityId()) {
            $fieldset->addField('entity_id', 'hidden', [
              'name' => 'id'
             ]);
        }
        
        $fieldset->addField('website_id', 'text', [
            'name' => 'website_id',
            'required' => true,
            'label' => __('Website ID'),
            'title' => __('Website ID'),
            ]);

        $fieldset->addField('group_id', 'text', [
            'name' => 'group_id',
            'required' => true,
            'label' => __('Group ID'),
            'title' => __('Group ID'),
            ]);

        $fieldset->addField('customer_group_id', 'text', [
            'name' => 'customer_group_id',
            'required' => true,
            'label' => __('Customer Group ID'),
            'title' => __('Customer Group ID'),
            ]);

        $fieldset->addField('customer_id', 'text', [
            'name' => 'customer_id',
            'required' => true,
            'label' => __('Customer ID'),
            'title' => __('Customer ID'),
            ]);

        $fieldset->addField('sku', 'text', [
            'name' => 'sku',
            'required' => true,
            'label' => __('SKU'),
            'title' => __('SKU'),
            ]);

        $fieldset->addField('uom_entry', 'text', [
            'name' => 'uom_entry',
            'required' => true,
            'label' => __('Uom Entry'),
            'title' => __('Uom Entry'),
            ]);

        $fieldset->addField('qty', 'text', [
            'name' => 'qty',
            'required' => true,
            'label' => __('Qty'),
            'title' => __('Qty'),
            'class' => 'validate-zero-or-greater'
            ]);

        $fieldset->addField('price', 'text', [
            'name' => 'price',
            'required' => true,
            'label' => __('Price'),
            'title' => __('Price'),
            'class' => 'validate-zero-or-greater'
            ]);
                
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'options' => $this->_status->toOptionArray()
        ]);
                
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
