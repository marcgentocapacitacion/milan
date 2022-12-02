<?php
/**
 * Copyright © 2015 ITM. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace ITM\Pricing\Block\Adminhtml\Uom\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;



class Main extends Generic implements TabInterface
{

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
        $model = $this->_coreRegistry->registry('current_itm_pricing_uom');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'uom_entry',
            'text',
            ['name' => 'uom_entry', 'label' => __('UOM Entry'), 'title' => __('UOM Entry'), 'required' => true]
        );
        $fieldset->addField(
        		'uom_code',
        		'text',
        		['name' => 'uom_code', 'label' => __('UOM Code'), 'title' => __('UOM Code'), 'required' => true]
        		);
        $fieldset->addField(
        		'uom_name',
        		'text',
        		['name' => 'uom_name', 'label' => __('UOM Name'), 'title' => __('UOM Name'), 'required' => true]
        		);
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
