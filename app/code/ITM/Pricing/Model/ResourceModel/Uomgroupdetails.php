<?php
namespace ITM\Pricing\Model\ResourceModel;

class Uomgroupdetails extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('itm_pricing_uomgroupdetails', 'id');
    }
}
