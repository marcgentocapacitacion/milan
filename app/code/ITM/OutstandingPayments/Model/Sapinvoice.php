<?php
    
namespace ITM\OutstandingPayments\Model;
use ITM\OutstandingPayments\Api\Data\SapinvoiceInterface;

class Sapinvoice extends \Magento\Framework\Model\AbstractModel implements SapinvoiceInterface
{

    private $code="";
    
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice');
    }
    
    /**
     * Get sapinvoice_id
     * @return string
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }
    
    /**
     * Set sapinvoice_id
     * @param string $sapinvoiceId
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setEntityeId($sapinvoiceId)
    {
        return $this->setData(self::ENTITY_ID, $sapinvoiceId);
    }
    
    
    /**
     * Get doc_entry
     * @return string
     */
    public function getDocEntry()
    {
        return $this->getData(self::DOC_ENTRY);
    }
    
    /**
     * Set doc_entry
     * @param string $doc_entry
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocEntry($doc_entry)
    {
        return $this->setData(self::DOC_ENTRY, $doc_entry);
    }
    
    /**
     * Get doc_num
     * @return string
     */
    public function getDocNum()
    {
        return $this->getData(self::DOC_NUM);
    }
    
    /**
     * Set doc_num
     * @param string $doc_num
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocNum($doc_num)
    {
        return $this->setData(self::DOC_NUM, $doc_num);
    }
    
    /**
     * Get card_code
     * @return string
     */
    public function getCardCode()
    {
        return $this->getData(self::CARD_CODE);
    }
    
    /**
     * Set card_code
     * @param string $card_code
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setCardCode($card_code)
    {
        return $this->setData(self::CARD_CODE, $card_code);
    }
    
    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }
    
    /**
     * Set email
     * @param string $email
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }
    
    /**
     * Get doc_total
     * @return string
     */
    public function getDocTotal()
    {
        return $this->getData(self::DOC_TOTAL);
    }
    
    /**
     * Set doc_total
     * @param string $doc_total
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocTotal($doc_total)
    {
        return $this->setData(self::DOC_TOTAL, $doc_total);
    }
    
    /**
     * Get open_balance
     * @return string
     */
    public function getOpenBalance()
    {
        return $this->getData(self::OPEN_BALANCE);
    }
    
    /**
     * Set open_balance
     * @param string $open_balance
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setOpenBalance($open_balance)
    {
        return $this->setData(self::OPEN_BALANCE, $open_balance);
    }

    /**
     * Get doc_type
     * @return string
     */
    public function getDocType()
    {
        return $this->getData(self::DOC_TYPE);
    }

    /**
     * Set doc_type
     * @param string $doc_type
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocType($doc_type)
    {
        return $this->setData(self::DOC_TYPE, $doc_type);
    }

    /**
     * Get doc_date
     * @return string
     */
    public function getDocDate()
    {
        return $this->getData(self::DOC_DATE);
    }
    
    /**
     * Set doc_date
     * @param string $doc_date
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocDate($doc_date)
    {
        return $this->setData(self::DOC_DATE, $doc_date);
    }
    
    /**
     * Get doc_due_date
     * @return string
     */
    public function getDocDueDate()
    {
        return $this->getData(self::DOC_DUE_DATE);
    }
    
    /**
     * Set doc_due_date
     * @param string $doc_due_date
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setDocDueDate($doc_due_date)
    {
        return $this->setData(self::DOC_DUE_DATE, $doc_due_date);
    }
    
    /**
     * Get invoice_status
     * @return string
     */
    public function getInvoiceStatus()
    {
        return $this->getData(self::INVOICE_STATUS);
    }
    
    /**
     * Set invoice_status
     * @param string $invoice_status
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setInvoiceStatus($invoice_status)
    {
        return $this->setData(self::INVOICE_STATUS, $invoice_status);
    }
    
    /**
     * Get sap_company
     * @return string
     */
    public function getSapCompany()
    {
        return $this->getData(self::SAP_COMPANY);
    }
    
    /**
     * Set sap_company
     * @param string $sap_company
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setSapCompany($sap_company)
    {
        return $this->setData(self::SAP_COMPANY, $sap_company);
    }
    
    /**
     * Get file
     * @return string
     */
    public function getPath()
    {
        return $this->getData(self::FILE);
    }
    
    /**
     * Set file
     * @param string $file_path
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setPath($file_path)
    {
        return $this->setData(self::FILE, $file_path);
    }
    
    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
    
    /**
     * Set status
     * @param string $status
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
    
    /**
     * @api
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @api
     *
     * @param $value string
     * @return null
     */
    public function setCode($value)
    {
        $this->code = $value;
    }

    /**
     * Get Info
     * @return string
     */
    public function getInfo()
    {
        return $this->getData(self::INFO);
    }

    /**
     * Set status
     * @param string $info
     * @return \ITM\OutstandingPayments\Api\Data\SapinvoiceInterface
     */
    public function setInfo($info)
    {
        return $this->setData(self::INFO, $info);
    }
}
