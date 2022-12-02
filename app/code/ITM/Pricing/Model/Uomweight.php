<?php
    
namespace ITM\Pricing\Model;
    
class Uomweight extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\Pricing\Model\ResourceModel\Uomweight');
    }
}
