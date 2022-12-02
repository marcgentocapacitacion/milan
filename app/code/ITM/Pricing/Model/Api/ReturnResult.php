<?php

/**
 * Copyright 2015 Magento.
 * All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model\Api;

use ITM\Pricing\Api\Data\ReturnResultDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class ReturnResult implements ReturnResultDataInterface
{

    private $error;

    private $data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->error = false;
        $this->data = array();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @api
     *
     * @param $value bool
     * @return null
     */
    public function setError($value)
    {
        $this->error = $value;
    }

    /**
     * @api
     *
     * @return string[] Array of items.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @api
     *
     * @param
     *            $items
     * @return null
     */
    public function setData($value)
    {
        $this->data = $value;
    }
}
