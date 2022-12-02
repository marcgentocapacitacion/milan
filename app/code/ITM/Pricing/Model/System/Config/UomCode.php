<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Eav\Model\Entity\Attribute\Source;

class UomCode extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_collectionFactory;

    public function __construct(\ITM\Pricing\Model\ResourceModel\Uom\Collection $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    public function getAllOptions()
    {
        $collection = $this->_collectionFactory->load();
        
        if ($this->_options === null) {
            $this->_options[] = [
                'label' => "-- None --",
                'value' => "0"
            ];
            foreach ($collection as $item) {
                $this->_options[] = [
                    'label' => __($item->getUomCode() . " - " . $item->getUomName()),
                    'value' => $item->getUomEntry()
                ];
            }
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        $collection = $this->_collectionFactory->load();
        $options = [
            "0" => "-- None --"
        ];
        foreach ($collection as $item) {
            $options[$item->getUomEntry()] = $item->getUomCode() . " - " . $item->getUomName();
        }
        return $options;
    }
    
    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
    
        return [
            $attributeCode => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 50,
                'unsigned' => false,
                'nullable' => true,
                'default' => null,
                'extra' => null,
                'comment' => $attributeCode . ' column',
            ],
        ];
    }
}
