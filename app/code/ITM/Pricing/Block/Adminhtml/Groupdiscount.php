<?php
namespace ITM\Pricing\Block\Adminhtml;

class Groupdiscount extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_groupdiscount'; /* block grid.php directory */
        $this->_blockGroup = 'ITM_Pricing';
        $this->_headerText = __('Groupdiscount');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
