<?php
    
namespace ITM\OutstandingPayments\Model\ResourceModel;
    
class Company extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_outstandingpayments_company', 'entity_id');
    }
}
