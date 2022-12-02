<?php
namespace ITM\Pricing\Api\Data;

interface UomgroupdetailsDataInterface
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
     * @return string ugp_entry.
     */
    public function getUgpEntry();

    /**
     * @api
     *
     * @param $value ugp_entry.
     * @return null
     */
    public function setUgpEntry($value);

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
     * @return string alt_qty.
     */
    public function getAltQty();

    /**
     * @api
     *
     * @param $value alt_qty.
     * @return null
     */
    public function setAltQty($value);

    /**
     * @api
     *
     * @return string base_qty.
     */
    public function getBaseQty();

    /**
     * @api
     *
     * @param $value base_qty.
     * @return null
     */
    public function setBaseQty($value);
}
