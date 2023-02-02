<?php
    
namespace ITM\OutstandingPayments\Model\System\Config;
    
use Magento\Framework\Option\ArrayInterface;
    
class InvoiceStatus implements ArrayInterface
{

    const OPEN = "o";
    const CLOSED = "c";
    const PROCESSING = "p";
    public function toOptionArray()
    {
        $options = [
            self::OPEN => __('Open'),
            self::PROCESSING => __('Processing'),
            self::CLOSED => __('Closed'),
        ];
        return $options;
    }
    
    public function getLabel($code){
        $status_list  = $this->toOptionArray();
        return $status_list[$code];
    }

}
