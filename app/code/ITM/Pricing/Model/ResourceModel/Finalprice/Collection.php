<?php
    
namespace ITM\Pricing\Model\ResourceModel\Finalprice ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\Pricing\Model\Finalprice', 'ITM\Pricing\Model\ResourceModel\Finalprice');
    }
}
