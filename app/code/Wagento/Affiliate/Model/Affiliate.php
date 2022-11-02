<?php

namespace Wagento\Affiliate\Model;

/**
 * Class Affiliate
 */
class Affiliate extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Wagento\Affiliate\Model\ResourceModel\Affiliate::class);
    }
}
