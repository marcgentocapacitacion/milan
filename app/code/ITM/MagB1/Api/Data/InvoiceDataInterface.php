<?php
namespace ITM\MagB1\Api\Data;

interface InvoiceDataInterface extends DocumentDataInterface
{


    /**
     *
     * @api
     *
     * @return \Magento\Sales\Api\Data\InvoiceItemInterface[] Array of items.
     */
    public function getItems();

    /**
     *
     * @api
     *
     * @param \Magento\Sales\Api\Data\InvoiceItemInterface[] $items
     * @return null
     */
    public function setItems($value);


    /**
     *
     * @api
     *
     * @return int
     */
    public function getIsPaid();

    /**
     *
     * @api
     *
     * @param $value int
     * @return null
     */
    public function setIsPaid($value);

}
