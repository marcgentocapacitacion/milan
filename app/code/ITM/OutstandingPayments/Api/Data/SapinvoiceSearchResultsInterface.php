<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding payments
 * Copyright (C) 2017  
 * 
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Api\Data;

interface SapinvoiceSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Sapinvoice list.
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface[]
     */
    public function getItems();

    /**
     * Set doc_entry list.
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}