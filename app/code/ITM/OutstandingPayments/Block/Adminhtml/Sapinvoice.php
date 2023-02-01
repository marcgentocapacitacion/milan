<?php
    
namespace ITM\OutstandingPayments\Block\Adminhtml;
    
class Sapinvoice extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_sapinvoice'; /* block grid.php directory */
        $this->_blockGroup = 'ITM_OutstandingPayments';
        $this->_headerText = __('Sapinvoice');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
