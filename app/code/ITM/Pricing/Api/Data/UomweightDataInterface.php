<?php
    
namespace ITM\Pricing\Api\Data;
    
interface UomweightDataInterface
{
    
    /**
     *
     * @api
     * @return int id.
     */
    public function getId();
    
    /**
     *
     * @api
     * @param $value id.
     * @return null
     */
    public function setId($value);
                
    /**
     *
     * Get SKU
     * @return string|null.
     */
    public function getSku();

    /**
     *
     * Set SKU
     * @param string $value.
     * @return null
     */
    public function setSku($value);
    /**
     *
     * Get UOM Entry
     * @return string|null.
     */
    public function getUomEntry();

    /**
     *
     * Set UOM Entry
     * @param string $value.
     * @return null
     */
    public function setUomEntry($value);
    
    /**
     *
     * Get Weight
     * @return float|null.
     */
    public function getWeight();
            
    /**
     *
     * Set Weight
     * @param float $value
     * @return null
     */
    public function setWeight($value);
     
    /**
     * Get Uomweight status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set Uomweight status
     *
     * @param int $status
     * @return null
     */
    public function setStatus($status);
}
