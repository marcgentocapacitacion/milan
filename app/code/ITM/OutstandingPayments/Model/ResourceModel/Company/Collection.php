<?php
    
namespace ITM\OutstandingPayments\Model\ResourceModel\Company ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\OutstandingPayments\Model\Company', 'ITM\OutstandingPayments\Model\ResourceModel\Company');
    }
}
