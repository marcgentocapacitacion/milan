<?php

namespace Wagento\Affiliate\Model\ResourceModel;

/**
 * Class Affiliate
 */
class Affiliate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('affiliate_entity', 'entity_id');
    }
}
