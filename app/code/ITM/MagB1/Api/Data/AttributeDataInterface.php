<?php
namespace ITM\MagB1\Api\Data;

interface AttributeDataInterface
{

    /**
     *
     * @api
     *
     * @return string.
     */
    public function getAttributeCode();

    /**
     *
     * @api
     *
     * @param string $value
     * @return null
     */
    public function setAttributeCode($value);

    /**
     *
     * @api
     *
     * @return string.
     */
    public function getAttributeValue();

    /**
     *
     * @api
     *
     * @param string $value
     * @return null
     */
    public function setAttributeValue($value);
}
