<?php
namespace ITM\Pricing\Block\Adminhtml;

class Customerprice extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_customerprice'; /* block grid.php directory */
        $this->_blockGroup = 'ITM_Pricing';
        $this->_headerText = __('Customerprice');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
