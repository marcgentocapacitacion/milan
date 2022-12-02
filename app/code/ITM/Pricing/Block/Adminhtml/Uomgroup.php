<?php
namespace ITM\Pricing\Block\Adminhtml;

class Uomgroup extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_uomgroup'; /* block grid.php directory */
        $this->_blockGroup = 'ITM_Pricing';
        $this->_headerText = __('UOM group');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
