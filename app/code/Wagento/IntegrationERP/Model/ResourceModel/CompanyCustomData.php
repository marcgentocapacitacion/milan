<?php

namespace Wagento\IntegrationERP\Model\ResourceModel;

/**
 * Class CompanyCustomData
 */
class CompanyCustomData extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_useIsObjectNew = true;
        $this->_isPkAutoIncrement = false;
        $this->_init('company_custom_data', 'company_id');
    }
}
