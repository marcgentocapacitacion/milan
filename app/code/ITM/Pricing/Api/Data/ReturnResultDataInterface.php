<?php
namespace ITM\Pricing\Api\Data;

interface ReturnResultDataInterface
{

    /**
     * @api
     *
     * @return bool .
     */
    public function getError();

    /**
     * @api
     *
     * @param string $value
     *            .
     * @return null
     */
    public function setError($value);

    /**
     * @api
     *
     * @return string[] Array of items.
     */
    public function getData();

    /**
     * @api
     *
     * @param string $value
     * @return null
     */
    public function setData($value);
}
