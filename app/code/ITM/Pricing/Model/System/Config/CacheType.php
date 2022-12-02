<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class CacheType implements ArrayInterface
{
    /**
     * @var array
     */
    protected $_cacheTypeListArray;

    /**
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList)
    {
        foreach ($cacheTypeList->getTypes() as $key => $type) {
            $this->_cacheTypeListArray[$key] = $type['cache_type'];
        }
    }

    /**
     * ToOptionArray
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result[]  = ["value"=>"","label"=>"-- None --"];
        foreach ($this->_cacheTypeListArray as $value => $label) {
            $result[] = ["value" => $value,"label" => $label];
        }
        return  $result;
    }
}
