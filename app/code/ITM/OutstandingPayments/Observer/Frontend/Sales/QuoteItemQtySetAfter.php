<?php
/**
 * Copyright © dsads All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Observer\Frontend\Sales;

class QuoteItemQtySetAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        $quoteItem = $observer->getEvent()->getItem();
        $_qty = $quoteItem->getQty();

        if($quoteItem->getSku() == "sap_invoice" && $quoteItem->getQty()>1){
            $quoteItem->getQuote()->setHasError(true);
            //$result->setHasError(true);
            //$result->addMessage(__("The requested qty exceeds the maximum qty allowed in shopping cart, Maximum qty is 1, please change the qty =1  then click save"));
            $quoteItem->addMessage(
                __('The most you may purchase is %1.',1),
                \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                __('The most you may purchase is %1.',1)
            );

        }

    }
}