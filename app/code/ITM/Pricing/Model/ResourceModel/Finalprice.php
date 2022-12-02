<?php
    
namespace ITM\Pricing\Model\ResourceModel;
    
class Finalprice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_pricing_finalprice', 'entity_id');
    }
}
