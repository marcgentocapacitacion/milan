<?php

namespace Wagento\Company\Model\Data;

use Wagento\Company\Api\Data\CustomerInterface;

/**
 * Class Customer
 */
class Customer extends \Magento\Customer\Model\Data\Customer implements CustomerInterface
{
    /**
     * @return string|null
     */
    public function getAlmacen()
    {
        return $this->_get(self::ALMACEN);
    }

    /**
     * @param string $almacen
     * @return $this
     */
    public function setAlmacen($almacen)
    {
        return $this->setData(self::ALMACEN, $almacen);
    }
}
