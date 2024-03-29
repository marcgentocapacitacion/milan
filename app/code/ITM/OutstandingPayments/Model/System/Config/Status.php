<?php
    
namespace ITM\OutstandingPayments\Model\System\Config;
    
use Magento\Framework\Option\ArrayInterface;
    
class Status implements ArrayInterface
{

    const ENABLED = 1;
    const DISABLED = 0;
    public function toOptionArray()
    {
        $options = [
            self::ENABLED => __('Enabled'),
            self::DISABLED => __('Disabled')
        ];
        return $options;
    }

    public function toOptionArrayYesNo()
        {
            $options = [
                self::ENABLED => __('Yes'),
                self::DISABLED => __('No')
            ];
            return $options;
        }
}
