<?php

namespace ITM\Pricing\Block\Adminhtml\Index;


/**
 * Copyright © 2015 ITM. All rights reserved.
 */

// @codingStandardsIgnoreFile




class Index extends \Magento\Backend\Block\Widget\Form\Container
{
    
    const MODULE_NAME = 'ITM_Pricing';
    
    protected $_moduleList;
    
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
            \Magento\Framework\Module\ModuleListInterface $moduleList,
            array $data = []
            ) {
                $this->_moduleList = $moduleList;
                $this->_coreRegistry = $registry;
                parent::__construct($context, $data);
    }
    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Pricing Index Page'). ", Version ".$this->getVersion());
        return parent::_prepareLayout();
    }
    /**
     * Department edit block
     *
     * @return void
     */
    protected function _construct()
    {
        
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_index';
        $this->_blockGroup = 'ITM_Pricing';
        
        parent::_construct();
        
        $this->buttonList->add(
                'save_and_continue_edit',
                [
                    'class' => 'action-default scalable action-default scalable save primary',
                    'label' => __('Get Price Information'),
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
        
        $this->buttonList->remove('save');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('back');
    }

    public function getVersion()
    {
        return $this->_moduleList
        ->getOne(self::MODULE_NAME)['setup_version'];
    }
    
    /**
     * Get header with Department name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        
        return __('Pricing Index Page'). ", Version ".$this->getVersion();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
       // return $this->getUrl('jobs/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}