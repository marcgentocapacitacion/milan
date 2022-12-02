<?php
    
namespace ITM\Pricing\Model\Api\Data;
    
use ITM\Pricing\Api\Data\UomweightDataInterface;
    
class UomweightData implements UomweightDataInterface
{

    private $id;
    private $sku;
    private $uom_entry;
    private $weight;
    private $status;
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function setId($value)
    {
        $this->id = $value;
    }
                
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getSku()
    {
        return $this->sku;
    }
            
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function setSku($value)
    {
        $this->sku = $value;
    }
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getUomEntry()
    {
        return $this->uom_entry;
    }
            
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function setUomEntry($value)
    {
        $this->uom_entry = $value;
    }
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getWeight()
    {
        return $this->weight;
    }
            
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function setWeight($value)
    {
        $this->weight = $value;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     *
     *
     * {@inheritdoc}
     *
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
