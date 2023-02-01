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

interface SapinvoiceInterface
{

    const CODE = 'code';
    const ENTITY_ID = 'entity_id';
    const DOC_ENTRY = 'doc_entry';
    const DOC_NUM = 'doc_num';
    const CARD_CODE = 'card_code';
    const EMAIL = 'email';
    const DOC_DATE = 'doc_date';
    const DOC_DUE_DATE = 'doc_due_date';
    const DOC_TOTAL = 'doc_total';
    const OPEN_BALANCE = 'open_balance';
    const DOC_TYPE = 'doc_type';
    const INVOICE_STATUS = 'invoice_status';
    const SAP_COMPANY = 'sap_company';
    const FILE = 'path';
    const STATUS = 'status';
    
    
    


    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setEntityId($entity_id);
    
   
    /**
     * Get doc_entry
     * @return string|null
     */
    public function getDocEntry();

    /**
     * Set doc_entry
     * @param string $doc_entry
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocEntry($doc_entry);

    /**
     * Get doc_num
     * @return string|null
     */
    public function getDocNum();

    /**
     * Set doc_num
     * @param string $doc_num
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocNum($doc_num);

    /**
     * Get card_code
     * @return string|null
     */
    public function getCardCode();

    /**
     * Set card_code
     * @param string $card_code
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setCardCode($card_code);

    /**
     * Get email
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     * @param string $email
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setEmail($email);

    /**
     * Get doc_total
     * @return string|null
     */
    public function getDocTotal();

    /**
     * Set doc_total
     * @param string $doc_total
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocTotal($doc_total);

    /**
     * Get open_balance
     * @return string|null
     */
    public function getOpenBalance();

    /**
     * Set open_balance
     * @param string $open_balance
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setOpenBalance($open_balance);


    /**
     * Get doc_type
     * @return string|null
     */
    public function getDocType();

    /**
     * Set card_code
     * @param string $doc_type
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocType($doc_type);

    /**
     * Get doc_date
     * @return string|null
     */
    public function getDocDate();

    /**
     * Set doc_date
     * @param string $doc_date
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocDate($doc_date);

    /**
     * Get doc_due_date
     * @return string|null
     */
    public function getDocDueDate();

    /**
     * Set doc_due_date
     * @param string $doc_due_date
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocDueDate($doc_due_date);

    /**
     * Get invoice_status
     * @return string|null
     */
    public function getInvoiceStatus();

    /**
     * Set invoice_status
     * @param string $invoice_status
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setInvoiceStatus($invoice_status);

    /**
     * Get sap_company
     * @return string|null
     */
    public function getSapCompany();

    /**
     * Set sap_company
     * @param string $sap_company
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setSapCompany($sap_company);

    /**
     * Get file
     * @return string|null
     */
    public function getPath();

    /**
     * Set file
     * @param string $file_path
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setPath($file_path);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setStatus($status);
    
    
    // Vertual Field for delete
    /**
     * @api
     *
     * @return string code.
     */
    public function getCode();
    
    /**
     * @api
     *
     * @param $value code.
     * @return null
     */
    public function setCode($value);
}