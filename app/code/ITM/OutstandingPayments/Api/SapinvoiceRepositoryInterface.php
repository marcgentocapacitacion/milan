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

namespace ITM\OutstandingPayments\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SapinvoiceRepositoryInterface
{


    /**
     * Save Sapinvoice
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
    );

    /**
     * @api
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
     * @param string $fileName
     * @return bool
     */
    public function saveFile($sapinvoice, $fileName);
    /**
     * Save SapinvoiceList
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface[] $sapinvoiceList
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveList($sapinvoiceList);

    /**
     * Retrieve Sapinvoice
     * @param string $sapinvoiceId
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($sapinvoiceId);

    /**
     * Retrieve Sapinvoice matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Sapinvoice
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function deleteInvoice(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
        );
    /**
     * Save SapinvoiceList
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface[] $sapinvoiceList
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteList($sapinvoiceList);

    /**
     * Delete Sapinvoice
     * @param \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface $sapinvoice
    );

    /**
     * Delete Sapinvoice by ID
     * @param string $sapinvoiceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($sapinvoiceId);
}
