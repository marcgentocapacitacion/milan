<?php

namespace Wagento\Affiliate\Model\ResourceModel\Affiliate;

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
            \Wagento\Affiliate\Model\Affiliate::class,
            \Wagento\Affiliate\Model\ResourceModel\Affiliate::class
        );
    }
}
