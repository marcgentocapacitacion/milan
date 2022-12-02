<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace ITM\Pricing\Block\Adminhtml\Uomgroup\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\Pricing\Model\System\Config\UomCode;

class Main extends Generic implements TabInterface
{

	protected $_uom_codes;
	
	public function __construct(
			Context $context,
			Registry $registry,
			FormFactory $formFactory,
			Config $wysiwygConfig,
			UomCode $uom_codes,
			array $data = []
			) {
				$this->_wysiwygConfig = $wysiwygConfig;
				$this->_uom_codes = $uom_codes;
				
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
        $model = $this->_coreRegistry->registry('current_itm_pricing_uomgroup');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'ugp_entry',
            'text',
            ['name' => 'ugp_entry', 'label' => __('UGP Entry'), 'title' => __('UGP Entry'), 'required' => true]
        );
        $fieldset->addField(
        		'ugp_code',
        		'text',
        		['name' => 'ugp_code', 'label' => __('UGP Code'), 'title' => __('UGP Code'), 'required' => true]
        		);
        $fieldset->addField(
        		'ugp_name',
        		'text',
        		['name' => 'ugp_name', 'label' => __('UGP Name'), 'title' => __('UGP Name'), 'required' => true]
        		);
        $fieldset->addField(
        		'base_uom',
        		'select',
        		[
        				'name'      => 'base_uom',
        				'label'     => __('Base UOM'),
        				'options'   => $this->_uom_codes->toOptionArray(),
        				'required' => true
        		]
        		);
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
