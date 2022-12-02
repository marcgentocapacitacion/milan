<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class CachePoolType implements ArrayInterface
{

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     */
    public function __construct(\Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool)
    {
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * ToOptionArray
     *
     * @return array
     */
    public function toOptionArray()
    {
        foreach ($this->_cacheFrontendPool as $value => $label) {
            $result[] = ["value" => $value,"label" => $value];
        }
        return  $result;
    }
}
