<?php

/**
 * Copyright 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\Data\UomDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class UomData implements UomDataInterface
{

    private $uom_entry;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->uom_entry = "";
        $this->uom_code = "";
        $this->uom_name = "";
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
    public function getUomCode()
    {
        return $this->uom_code;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUomCode($value)
    {
        $this->uom_code = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUomName()
    {
        return $this->uom_name;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setUomName($value)
    {
        $this->uom_name = $value;
    }
}
