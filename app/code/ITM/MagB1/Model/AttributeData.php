<?php

/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\MagB1\Model;

use ITM\MagB1\Api\Data\AttributeDataInterface;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class AttributeData implements AttributeDataInterface
{

    private $attributeCode;

    private $AttributeValue;



    /**
     *
     * {@inheritdoc}
     *
     */
    public function getAttributeCode()
    {
        return $this->attributeCode;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function setAttributeCode($value)
    {
        $this->attributeCode = $value;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getAttributeValue()
    {
        return $this->AttributeValue;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function setAttributeValue($value)
    {
        $this->AttributeValue = $value;
    }
}
