<?php
    
namespace ITM\Pricing\Block\Adminhtml\Uomweight\Edit\Tab;
    
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\Pricing\Model\System\Config\Status;
use ITM\Pricing\Model\System\Config\UomCode;
class Main extends Generic implements TabInterface
{

    protected $_status;
    
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        UomCode $uom_codes,
        array $data = []
    ) {
        $this->_uom_codes = $uom_codes;
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
        $model = $this->_coreRegistry->registry('current_itm_pricing_uomweight');
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
        
        $fieldset->addField('sku', 'text', [
            'name' => 'sku',
            'required' => true,
            'label' => __('SKU'),
            'title' => __('SKU'),
            ]);

        $fieldset->addField('uom_entry', 'select', [
            'name' => 'uom_entry',
            'required' => true,
            'label' => __('UOM'),
            'title' => __('UOM'),
            'options'   => $this->_uom_codes->toOptionArray(),
            ]);

        $fieldset->addField('weight', 'text', [
            'name' => 'weight',
            'required' => true,
            'label' => __('Weight'),
            'title' => __('Weight'),
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
