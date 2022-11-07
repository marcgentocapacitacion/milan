<?php

namespace Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomData;

/**
 * Class Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Wagento\IntegrationERP\Model\CompanyCustomData::class,
            \Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomData::class
        );
    }
}
