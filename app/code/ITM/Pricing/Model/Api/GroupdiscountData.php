<?php

/**
 * Copyright 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\Data\GroupdiscountDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class GroupdiscountData implements GroupdiscountDataInterface
{

    private $group_id;

    private $website_id;

    private $attribute_code;

    private $attribute_value;

    private $start_date;

    private $end_date;

    private $discount;

    private $status;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->group_id = "";
        $this->website_id = 0;
        $this->attribute_code = "";
        $this->attribute_value = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->discount = "";
        $this->status = true;
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
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setGroupId($value)
    {
        $this->group_id = $value;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @api
     *
     * @param $value int
     * @return null
     */
    public function setWebsiteId($value)
    {
        $this->website_id = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAttributeCode()
    {
        return $this->attribute_code;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setAttributeCode($value)
    {
        $this->attribute_code = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAttributeValue()
    {
        return $this->attribute_value;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setAttributeValue($value)
    {
        $this->attribute_value = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setStartDate($value)
    {
        $this->start_date = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setEndDate($value)
    {
        $this->end_date = $value;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setDiscount($value)
    {
        $this->discount = $value;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @api
     *
     * @param $value bool
     * @return null
     */
    public function setStatus($value)
    {
        $this->status = $value;
    }
    /**
     * @api
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setCode($value)
    {
        $this->code = $value;
    }
}
