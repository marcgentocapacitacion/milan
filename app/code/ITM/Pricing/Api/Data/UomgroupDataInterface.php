<?php
namespace ITM\Pricing\Api\Data;

interface UomgroupDataInterface
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
     * @return string uom_entry.
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
     * @return string ugp_code.
     */
    public function getUgpCode();

    /**
     * @api
     *
     * @param $value ugp_code.
     * @return null
     */
    public function setUgpCode($value);

    /**
     * @api
     *
     * @return string ugp_name.
     */
    public function getUgpName();

    /**
     * @api
     *
     * @param $value ugp_name.
     * @return null
     */
    public function setUgpName($value);

    /**
     * @api
     *
     * @return string base_uom.
     */
    public function getBaseUom();

    /**
     * @api
     *
     * @param $value base_uom.
     * @return null
     */
    public function setBaseUom($value);
}
