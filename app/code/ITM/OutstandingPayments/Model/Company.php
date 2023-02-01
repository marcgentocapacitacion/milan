<?php
    
namespace ITM\OutstandingPayments\Model;
    
class Company extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\OutstandingPayments\Model\ResourceModel\Company');
    }
}
