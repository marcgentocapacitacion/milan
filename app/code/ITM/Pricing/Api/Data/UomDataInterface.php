<?php
namespace ITM\Pricing\Api\Data;

interface UomDataInterface
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
     * @return string uom_code.
     */
    public function getUomCode();

    /**
     * @api
     *
     * @param $value uom_code.
     * @return null
     */
    public function setUomCode($value);

    /**
     * @api
     *
     * @return string uom_name.
     */
    public function getUomName();

    /**
     * @api
     *
     * @param $value uom_name.
     * @return null
     */
    public function setUomName($value);
}
