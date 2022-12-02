<?php
    
namespace ITM\Pricing\Model;
    
class Finalprice extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\Pricing\Model\ResourceModel\Finalprice');
    }
}
