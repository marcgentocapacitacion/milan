<?php
namespace ITM\Pricing\Api\Data;

interface CustomerpriceDataInterface
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
     * @param $value id.
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
     * @return string sku.
     */
    public function getSku();

    /**
     * @api
     *
     * @param $value sku.
     * @return null
     */
    public function setSku($value);

    /**
     * @api
     *
     * @return string qty.
     */
    public function getQty();

    /**
     * @api
     *
     * @param $value qty.
     * @return null
     */
    public function setQty($value);

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
     * @return string uom_entry.
     */
    public function getUomEntry();

    /**
     * @api
     *
     * @param $value uom_entry.
     * @return null
     */
    public function setUomEntry($value);

    /**
     * @api
     *
     * @return string price.
     */
    public function getPrice();

    /**
     * @api
     *
     * @param $value price.
     * @return null
     */
    public function setPrice($value);

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
     * @return bool price.
     */
    public function getStatus();

    /**
     * @api
     *
     * @param $value price.
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
