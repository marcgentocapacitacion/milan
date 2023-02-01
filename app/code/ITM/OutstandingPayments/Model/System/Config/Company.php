<?php
    
namespace ITM\OutstandingPayments\Model\System\Config;
    
use Magento\Framework\Option\ArrayInterface;
    
class Company implements ArrayInterface
{

    
    protected $_collectionFactory;
    
    protected $_options;
    public function __construct(\ITM\OutstandingPayments\Model\ResourceModel\Company\CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
        
    }
    
    
    public function getAllOptions()
    {
        $collection = $this->_collectionFactory->create();
        
        if ($this->_options === null) {
            $this->_options[] = [
                'label' => "-- None --",
                'value' => ""
            ];
            foreach ($collection as $item) {
                $this->_options[] = [
                    'label' => $item->getCompanyName(),
                    'value' => $item->getDatabaseName()
                ];
            }
        }
        return $this->_options;
    }
    
    public function toOptionArray()
    {
        $collection = $this->_collectionFactory->create();
        $options = [
            "" => "-- None --"
        ];
        foreach ($collection as $item) {
            $options[$item->getDatabaseName()] =  $item->getCompanyName();
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
