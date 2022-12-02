<?php
namespace ITM\Pricing\Model\System\Config;

use Magento\Customer\Model\GroupFactory;
use Magento\Framework\Option\ArrayInterface;

class CustomerGroups implements ArrayInterface
{

    protected $groupFactory;

    const ENABLED = 1;

    const DISABLED = 0;

    public function __construct(GroupFactory $groupFactory)
    {
        $this->groupFactory = $groupFactory;
    }

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groups = $this->groupFactory->create()->getCollection();
        
        $options = ["-1"=>" All "];
        foreach ($groups as $group) {
            $options[$group->getId()] = $group->getCode();
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
