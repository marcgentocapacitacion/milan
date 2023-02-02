<?php
    
namespace ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\OutstandingPayments\Model\Sapinvoice', 'ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice');
    }
}
