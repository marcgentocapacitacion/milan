<?php

/**
 * Copyright 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\Data\UomgroupdetailsDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class UomgroupdetailsData implements UomgroupdetailsDataInterface
{

    private $ugp_entry;

    private $uom_entry;

    private $alt_qty;

    private $base_qty;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->ugp_entry = "";
        $this->uom_entry = "";
        $this->alt_qty = "";
        $this->base_qty = "";
    }

    /**
     * @api
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @api
     *
     * @param $value int
     * @return null
     */
    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUgpEntry()
    {
        return $this->ugp_entry;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUgpEntry($value)
    {
        $this->ugp_entry = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUomEntry()
    {
        return $this->uom_entry;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUomEntry($value)
    {
        $this->uom_entry = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAltQty()
    {
        return $this->alt_qty;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setAltQty($value)
    {
        $this->alt_qty = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseQty()
    {
        return $this->base_qty;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setBaseQty($value)
    {
        $this->base_qty = $value;
    }
}
