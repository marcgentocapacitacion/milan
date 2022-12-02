<?php
namespace ITM\Pricing\Model\ResourceModel\Customerprice;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define ResourceModel model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ITM\Pricing\Model\Customerprice', 'ITM\Pricing\Model\ResourceModel\Customerprice');
    }
}
