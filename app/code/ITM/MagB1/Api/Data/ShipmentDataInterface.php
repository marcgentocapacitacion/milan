<?php
namespace ITM\MagB1\Api\Data;

interface ShipmentDataInterface extends DocumentDataInterface
{

    /**
     *
     * @api
     *
     * @return \Magento\Sales\Api\Data\ShipmentItemInterface[] Array of items.
     */
    public function getItems();

    /**
     *
     * @api
     *
     * @param \Magento\Sales\Api\Data\ShipmentItemInterface[] $items
     * @return null
     */
    public function setItems($value);


    /**
     *
     * @api
     *
     * @return \Magento\Sales\Api\Data\ShipmentTrackInterface[] Array of tracks.
     */
    public function getTracks();

    /**
     *
     * @api
     *
     * @param \Magento\Sales\Api\Data\ShipmentTrackInterface[] $tracks
     * @return null
     */
    public function setTracks($value);



    /**
     *
     * @api
     *
     * @return string
     */
    public function getSourceCode();

    /**
     *
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setSourceCode($value);


}
