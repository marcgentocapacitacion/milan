<?php
namespace ITM\Pricing\Api\Data;

interface CustomerdiscountDataInterface
{

    /**
     * @api
     *
     * @return int id.
     */
    public function getId();

    /**
     * @api
     *
     * @param $value id
     * @return null
     */
    public function setId($value);

    /**
     * @api
     *
     * @return string customer_id.
     */
    public function getCustomerId();

    /**
     * @api
     *
     * @param $value customer_id.
     * @return null
     */
    public function setCustomerId($value);

    /**
     * @api
     *
     * @return string website_id.
     */
    public function getWebsiteId();

    /**
     * @api
     *
     * @param $value website_id.
     * @return null
     */
    public function setWebsiteId($value);

    /**
     * @api
     *
     * @return string attribute_code.
     */
    public function getAttributeCode();

    /**
     * @api
     *
     * @param $value attribute_code.
     * @return null
     */
    public function setAttributeCode($value);

    /**
     * @api
     *
     * @return string attribute_value.
     */
    public function getAttributeValue();

    /**
     * @api
     *
     * @param $value attribute_value.
     * @return null
     */
    public function setAttributeValue($value);

    /**
     * @api
     *
     * @return string start_date.
     */
    public function getStartDate();

    /**
     * @api
     *
     * @param $value start_date.
     * @return null
     */
    public function setStartDate($value);

    /**
     * @api
     *
     * @return string end_date.
     */
    public function getEndDate();

    /**
     * @api
     *
     * @param $value end_date.
     * @return null
     */
    public function setEndDate($value);

    /**
     * @api
     *
     * @return string discount.
     */
    public function getDiscount();

    /**
     * @api
     *
     * @param $value discount.
     * @return null
     */
    public function setDiscount($value);

    /**
     * @api
     *
     * @return bool status.
     */
    public function getStatus();

    /**
     * @api
     *
     * @param $value status.
     * @return null
     */
    public function setStatus($value);

    /**
     * @api
     *
     * @return string code.
     */
    public function getCode();

    /**
     * @api
     *
     * @param $value code.
     * @return null
     */
    public function setCode($value);
}
