<?php
namespace ITM\MagB1\Api\Config;

interface ConfigInterface
{

    /**
     * Get Carrier list
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getCarrierList();

    /**
     * Get Carrier list
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getPaymentMethodList();

    /**
     * reIndex
     *
     * @return bool
     */
    public function reIndex();

    /**
     * getVersion
     *
     * @return string
     */
    public function getVersion();

    /**
     * getReport
     * @param string $type
     * @param string $id
     * @return string
     */
    public function getReport($type,$id);
}
