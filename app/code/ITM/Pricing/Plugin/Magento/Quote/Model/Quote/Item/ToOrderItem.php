<?php
/**
 * Copyright © d All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\Pricing\Plugin\Magento\Quote\Model\Quote\Item;

class ToOrderItem
{

    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem Item */
        $orderItem = $proceed($item, $additional);
        $orderItem->setItmUomEntry($item->getItmUomEntry());
        return $orderItem;
    }
}
