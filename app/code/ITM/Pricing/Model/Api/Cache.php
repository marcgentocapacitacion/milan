<?php
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\CacheInterface;

class Cache implements CacheInterface
{

    /**
     * @var \ITM\Pricing\Helper\Data
     */
    protected $_pricing_helper;

    /**
     * @param \ITM\Pricing\Helper\Data $dataHelper
     */
    public function __construct(
        \ITM\Pricing\Helper\Data $dataHelper
    ) {
        $this->_pricing_helper = $dataHelper;
    }

    /**
     * Refresh
     *
     * {@inheritdoc}
     */
    public function refresh()
    {
        return  $this->_pricing_helper->refreshCache();
    }
}
