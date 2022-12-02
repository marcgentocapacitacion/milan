<?php

/**
 * Copyright 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\Data\UomgroupDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class UomgroupData implements UomgroupDataInterface
{

    private $ugp_entry;

    private $ugp_code;

    private $ugp_name;

    private $base_uom;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->ugp_entry = "";
        $this->ugp_code = "";
        $this->ugp_name = "";
        $this->base_uom = "";
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
    public function getUgpCode()
    {
        return $this->ugp_code;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUgpCode($value)
    {
        $this->ugp_code = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUgpName()
    {
        return $this->ugp_name;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUgpName($value)
    {
        $this->ugp_name = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseUom()
    {
        return $this->base_uom;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setBaseUom($value)
    {
        $this->base_uom = $value;
    }
}
