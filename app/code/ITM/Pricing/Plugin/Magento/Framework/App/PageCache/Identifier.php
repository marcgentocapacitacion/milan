<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\Pricing\Plugin\Magento\Framework\App\PageCache;

class Identifier
{

    protected $customerSession;

    protected $pricingSetting;
    /**
     * Construct
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \ITM\Pricing\Helper\Setting $pricingSetting
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \ITM\Pricing\Helper\Setting $pricingSetting
    ) {
        $this->customerSession = $customerSession;
        $this->pricingSetting = $pricingSetting;
    }

    public function afterGetValue(
        \Magento\Framework\App\PageCache\Identifier $subject,
        $result
    ) {
        if( $this->pricingSetting->disablePriceBoxCache()== true) {
            if($customer_id = $this->customerSession->getCustomer()->getId() > 0) {
                $result .= $customer_id;
            }
        }

        return $result;
    }
}
