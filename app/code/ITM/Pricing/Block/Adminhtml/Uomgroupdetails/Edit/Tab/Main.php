<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace ITM\Pricing\Block\Adminhtml\Uomgroupdetails\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\Pricing\Model\System\Config\UomCode;
use ITM\Pricing\Model\System\Config\UomGroup;


class Main extends Generic implements TabInterface
{

	protected $_uom_codes;
	protected $_uom_groups;
	
	public function __construct(
			Context $context,
			Registry $registry,
			FormFactory $formFactory,
			Config $wysiwygConfig,
			UomCode $uom_codes,
			UomGroup $uom_groups,
			array $data = []
			) {
				$this->_wysiwygConfig = $wysiwygConfig;
				$this->_uom_codes = $uom_codes;
				$this->_uom_groups = $uom_groups;
				
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
        $model = $this->_coreRegistry->registry('current_itm_pricing_uomgroupdetails');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
         $fieldset->addField(
        		'ugp_entry',
        		'select',
        		[
        				'name'      => 'ugp_entry',
        				'label'     => __('UOM Group'),
        				'options'   => $this->_uom_groups->toOptionArray(),
        				'required' => true
        		]
        		);
       
         $fieldset->addField(
        		'uom_entry',
        		'select',
        		[
        				'name'      => 'uom_entry',
        				'label'     => __('Unit Of Measurement'),
        				'options'   => $this->_uom_codes->toOptionArray(),
        				'required' => true
        		]
        		);
        $fieldset->addField(
        		'alt_qty',
        		'text',
        		['name' => 'alt_qty', 'label' => __('Alt Qty'), 'title' => __('Alt Qty'), 'required' => true]
        		);
        $fieldset->addField(
        		'base_qty',
        		'text',
        		['name' => 'base_qty', 'label' => __('Base Qty'), 'title' => __('Base Qty'), 'required' => true]
        		);
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
