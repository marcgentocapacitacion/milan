<?php
    
namespace ITM\Pricing\Model\ResourceModel;
    
class Uomweight extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_pricing_uomweight', 'entity_id');
    }
}
