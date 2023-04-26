<?php

namespace Wagento\Customer\Plugin\Model\Data;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Data\Customer;

/**
 * Class CustomerPlugin
 */
class CustomerPlugin
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param Customer $subject
     * @param          $result
     *
     * @return mixed
     */
    public function afterGetStoreId(Customer $subject, $result)
    {
        if ($result <= 0 || !$result) {
            return $this->storeManager->getDefaultStoreView()->getId();
        }

        return $result;
    }
}
