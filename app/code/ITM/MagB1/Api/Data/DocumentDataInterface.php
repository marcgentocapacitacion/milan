<?php
namespace ITM\MagB1\Api\Data;

interface DocumentDataInterface
{

    /**
     *
     * @api
     * @return string increment_id.
     */
    public function getIncrementId();

    /**
     *
     * @api
     *
     * @param $value increment_id.
     * @return null
     */
    public function setIncrementId($value);


    /**
     *
     * @api
     *
     * @return string
     */
    public function getCommentText();

    /**
     *
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setCommentText($value);

    /**
     *
     * @api
     *
     * @return bool
     */
    public function getCommentCustomerNotify();

    /**
     *
     * @api
     *
     * @param $value bool
     * @return null
     */
    public function setCommentCustomerNotify($value);

    /**
     *
     * @api
     *
     * @return bool
     */
    public function getIsVisibleOnFront();

    /**
     *
     * @api
     *
     * @param $value bool
     * @return null
     */
    public function setIsVisibleOnFront($value);


    /**
     *
     * @api
     *
     * @return string
     */
    public function getDocEntry();

    /**
     *
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setDocEntry($value);

    /**
     *
     * @api
     *
     * @return string
     */
    public function getDocNum();

    /**
     *
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setDocNum($value);
}
