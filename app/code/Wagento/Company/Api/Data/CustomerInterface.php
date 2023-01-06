<?php

namespace Wagento\Company\Api\Data;

/**
 * Interface CustomerInterface
 */
interface CustomerInterface extends \Magento\Customer\Api\Data\CustomerInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const ALMACEN = 'almacen';
    /**#@-*/

    /**
     * @return string|null
     */
    public function getAlmacen();

    /**
     * @param string $almacen
     * @return $this
     */
    public function setAlmacen($almacen);
}
