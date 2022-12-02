<?php
    
namespace ITM\Pricing\Model\ResourceModel\Uomweight ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\Pricing\Model\Uomweight', 'ITM\Pricing\Model\ResourceModel\Uomweight');
    }
}
