<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace ITM\Pricing\Block\Adminhtml\Customerprice\Edit\Tab;



use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\Pricing\Model\System\Config\Status;
use ITM\Pricing\Model\System\Config\Customers;
use ITM\Pricing\Model\System\Config\Websites;
use ITM\Pricing\Model\System\Config\UomCode;


class Main extends Generic implements TabInterface
{

	protected $_status;
	protected $_customers;
	protected $_uom_codes;
    protected $_helper;
	public function __construct(
			Context $context,
			Registry $registry,
			FormFactory $formFactory,
			Config $wysiwygConfig,
			Status $status,
			Customers $customers,
			Websites $websites,
			UomCode $uom_codes,
            \ITM\Pricing\Helper\Data  $helper,
			array $data = []
			)
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->_customers = $customers;
        $this->_uom_codes = $uom_codes;
        $this->_websites = $websites;
        $this->_helper = $helper;
        $this->_helper->setStoreTimezone();
        parent::__construct($context, $registry, $formFactory, $data);
    }
	
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
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
        $model = $this->_coreRegistry->registry('current_itm_pricing_customerprice');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
      
        $fieldset->addField(
        		'customer_id',
        		'text',
        		[
        				'name'      => 'customer_id',
        				'label'     => __('Customer'),
        				//'options'   => $this->_customers->toOptionArray(),
        				'required' 	=>true
        		]
        		);
        $fieldset->addField(
        		'website_id',
        		'select',
        		[
        				'name'      => 'website_id',
        				'label'     => __('Website'),
        				'options'   => $this->_websites->toOptionArray(),
        				'required' 	=>true
        		]
        		);
 		
        $fieldset->addField(
        	'sku',
        	'text',
        	['name' => 'sku', 'label' => __('SKU'), 'title' => __('SKU'), 'required' => true]
        	);
        $fieldset->addField(
        		'qty',
        		'text',
        		[
        				'name' => 'qty',
        				'label' => __('Qty'),
        				'title' => __('Qty'),
        				'required' => true,
        				'class' => ' validate-zero-or-greater '
        
        		]
        		);
        $fieldset->addField(
        		'start_date',
        		'date',
        		[
        				'name' => 'start_date',
        				'label' => __('Start Date'),
        				'title' => __('Start Date'),
        				'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
        				'class' => 'validate-date'
        		]
        		);
        $fieldset->addField(
        		'end_date',
        		'date',
        		[
        				'name' => 'end_date',
        				'label' => __('End Date'),
        				'title' => __('End Date'),
        				'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
        				'class' => 'validate-date'
        		]
        		);
       
       $fieldset->addField(
        		'uom_entry',
        		'select',
        		[
        				'name'      => 'uom_entry',
        				'label'     => __('UOM'),
        				'options'   => $this->_uom_codes->toOptionArray(),
        				'required' => true
        		]
        		);
      
        $fieldset->addField(
        		'price',
        		'text',
        		[
        				'name' => 'price',
        				'label' => __('Price'),
        				'title' => __('Price'),
        				'required' => true,
        				'class' => ' validate-zero-or-greater '
        				
        		]
        		);
        $fieldset->addField('discount', 'text',
            [
                'name' => 'discount',
                'label' => __('Discount(%)'),
                'title' => __('discount'),
                'required' => true,
                'class' => ' validate-zero-or-greater '
            ]
        );
       $fieldset->addField(
            'status',
            'select',
            [
                'name'      => 'status',
                'label'     => __('Status'),
                'options'   => $this->_status->toOptionArray()
            ]
        );
        
        
        
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
