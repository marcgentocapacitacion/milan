<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Plugin\Frontend\Magento\Checkout\Model;

class Session
{


    public function afterGetQuote(
        \Magento\Checkout\Model\Session $subject,
        $result
    )
    {
        $quote = $result;
        $itemsVisible = $quote->getAllVisibleItems();

        foreach ($itemsVisible as $item) {
            if($item->getSku() == "sap_invoice" && $item->getQty()>1){
                $result->setHasError(true);
                $result->addMessage(__("The requested qty exceeds the maximum qty allowed in shopping cart, Maximum qty is 1, please change the qty =1  then click save"));
                return $result;
            }
        }

        return $result;
    }
}
