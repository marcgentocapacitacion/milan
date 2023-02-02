<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml\Sapinvoice\Edit\Tab;
    
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\OutstandingPayments\Model\System\Config\Status;
    
class Main extends Generic implements TabInterface
{

    protected $_status;
    
    protected $_invoiceStatus;
    
    protected $_company;
    
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        \ITM\OutstandingPayments\Model\System\Config\Status $status,
        \ITM\OutstandingPayments\Model\System\Config\Company $company,
        \ITM\OutstandingPayments\Model\System\Config\InvoiceStatus $invoiceStatus,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_company = $company;
        $this->_invoiceStatus = $invoiceStatus;
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
        $model = $this->_coreRegistry->registry('current_itm_outstandingpayments_sapinvoice');
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
        
        $fieldset->addField('doc_entry', 'text', [
            'name' => 'doc_entry',
            'required' => true,
            'label' => __('Doc Entry'),
            'title' => __('Doc Entry'),
            ]);

        $fieldset->addField('doc_num', 'text', [
            'name' => 'doc_num',
            'required' => true,
            'label' => __('Doc Num'),
            'title' => __('Doc Num'),
            ]);

        $fieldset->addField('card_code', 'text', [
            'name' => 'card_code',
            'required' => true,
            'label' => __('Card Code'),
            'title' => __('Card Code'),
            ]);

        $fieldset->addField('email', 'text', [
            'name' => 'email',
            'required' => true,
            'label' => __('Email'),
            'title' => __('Email'),
            ]);

        $fieldset->addField('doc_total', 'text', [
            'name' => 'doc_total',
            'required' => true,
            'label' => __('Doc Total'),
            'title' => __('Doc Total'),
            'class' => 'validate-zero-or-greater'
            ]);

        $fieldset->addField('open_balance', 'text', [
            'name' => 'open_balance',
            'required' => true,
            'label' => __('Open Balance'),
            'title' => __('Open Balance'),
            'class' => 'validate-zero-or-greater'
            ]);

        $fieldset->addField('doc_date', 'date', [
            'name' => 'doc_date',
            'required' => true,
            'label' => __('Doc Date'),
            'title' => __('Doc Date'),
            'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
            'class' => 'validate-date'
            ]);

        $fieldset->addField('doc_due_date', 'date', [
            'name' => 'doc_due_date',
            'required' => true,
            'label' => __('Doc Due Date'),
            'title' => __('Doc Due Date'),
            'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
            'class' => 'validate-date'
            ]);

        $fieldset->addField('doc_type', 'select', [
            'name' => 'doc_type',
            'required' => true,
            'label' => __('Type'),
            'title' => __('Type'),
            'options' => [""=>"-- Select --","dt"=>"Down Payment","in"=>"Invoice"]
        ]);

        $fieldset->addField('invoice_status', 'select', [
            'name' => 'invoice_status',
            'required' => true,
            'label' => __('Status'),
            'title' => __('Status'),
            'options' => $this->_invoiceStatus->toOptionArray()
            ]);

        $fieldset->addField('sap_company', 'select', [
            'name' => 'sap_company',
            'required' => true,
            'label' => __('Sap Company'),
            'title' => __('Sap Company'),
            'options' => $this->_company->toOptionArray()
            ]);

        
        $file_name = "";
        $after_element = "";
        if ($model->getData("path")!="") {
            $file_name = $model->getData("path");
            $after_element = "<p><input type=\"checkbox\" name=\"delete_file_path\">Delete ($file_name)</p>";
        }
        
        $fieldset->addField('path', 'file', [
            'name' => 'path',
            'required' => false,
            'label' => __('Select file to upload'),
            'title' => __('Select file to upload'),
            'after_element_html' => $after_element,
        ]);
        
       
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'options' => $this->_status->toOptionArray()
        ]);
        $fieldset->addField('info', 'textarea', [
            'name' => 'info',
            'required' => false,
            'label' => __('Information (json)'),
            'title' => __('Information (json)'),
        ]);


        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
