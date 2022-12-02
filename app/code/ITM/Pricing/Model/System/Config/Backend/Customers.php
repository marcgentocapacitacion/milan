<?php
namespace ITM\Pricing\Model\System\Config\Backend;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Option\ArrayInterface;

class Customers implements ArrayInterface
{

    protected $customerFactory;

    const ENABLED = 1;

    const DISABLED = 0;

    public function __construct(CustomerFactory $customerFactory)
    {
        $this->customerFactory = $customerFactory;
    }

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        $customers = $this->customerFactory->create()->getCollection();
        
        $options = [
            "" => ""
        ];
        foreach ($customers as $customer) {
            $options[$customer->getId()] = $customer->getName();
        }
        
        return $options;
    }

    /**
     *
     * @return array
     */
    public function getOptionArray()
    {
        return $this->toOptionArray();
    }
}
