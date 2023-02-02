<?php
    
namespace ITM\OutstandingPayments\Model\ResourceModel;
    
class Sapinvoice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_outstandingpayments_sapinvoice', 'entity_id');
    }
}
