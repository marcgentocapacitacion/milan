<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Eav\Model\Entity\Attribute\Source;

class UomGroup extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_collectionFactory;

    public function __construct(\ITM\Pricing\Model\ResourceModel\Uomgroup\Collection $collectionFactory)
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
                    'label' => __($item->getUgpCode() . " - " . $item->getUgpName()),
                    'value' => $item->getUgpEntry()
                ];
            }
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        $collection = $this->_collectionFactory->load();
        $options = [
            "" => ""
        ];
        foreach ($collection as $item) {
            $options[$item->getUgpEntry()] = $item->getUgpCode() . " - " . $item->getUgpName();
        }
        return $options;
    }
}
