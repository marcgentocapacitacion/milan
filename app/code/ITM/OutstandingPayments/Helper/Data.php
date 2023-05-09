<?php

namespace ITM\OutstandingPayments\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory
     */
    protected $_invoiceCollectionFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var \ITM\OutstandingPayments\Model\System\Config\InvoiceStatus
     */
    protected $_invoiceStatus;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * @var array
     */
    protected $_allowed_payment_methods = [];

    /**
     * @var string
     */
    protected $_default_company;

    /**
     * Data constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \ITM\OutstandingPayments\Model\System\Config\InvoiceStatus $invoiceStatus
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \ITM\OutstandingPayments\Model\System\Config\InvoiceStatus $invoiceStatus,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->_objectManager = $objectManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->_customerRepository = $customerRepository;
        $this->_invoiceStatus = $invoiceStatus;
        $this->_fileSystem = $fileSystem;
        $this->_cart = $cart;

        $this->_allowed_payment_methods = $this->_scopeConfig->getValue('itm_outstanding_payments_section/general/allowed_payment_methods');

        $this->_default_company = $this->_scopeConfig->getValue('itm_outstanding_payments_section/general/default_company');
    }

    public function HideOrders()
    {
        return (bool)$this->_scopeConfig->getValue('itm_outstanding_payments_section/general/hide_osp_orders');
    }

    public function allowPartialPayment()
    {
        return false;
    }

    public function disableCart()
    {
        $this->_cart->truncate();
        //$quote = $this->_objectManager->create('\Magento\Checkout\Model\Session')->getQuote();
        //$quote->setIsActive(false)->save();
    }

    public function getInvoiceStatusLabel($invoice)
    {
        return $this->_invoiceStatus->getLabel($invoice->getInvoiceStatus());
    }

    public function getDocNumByDocEntry($docEntry, $docType){

       $invoice =  $this->getInvoice($docEntry,$this->getCustomerSapCompany(), $this->getDocType($docType));
        return $invoice;
    }
    public function getDocEntryOptionId($product)
    {

        $customOption = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionInterface');
        $option_collection = $customOption->getProductOptionCollection($product);
        $docEntryOptionId = 0;
        foreach ($option_collection as $item) {
            if ($item->getTitle() == "DocEntry") {
                $docEntryOptionId = $item->getOptionId();
                break;
            }
        }
        return $docEntryOptionId;
    }

    public function getAmountOptionId($product)
    {

        $customOption = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionInterface');
        $option_collection = $customOption->getProductOptionCollection($product);
        $optionId = 0;
        foreach ($option_collection as $item) {
            if ($item->getTitle() == "Amount") {
                $optionId = $item->getOptionId();
                break;
            }
        }
        return $optionId;
    }

    public function getDocTypeOptionId($product)
    {

        $customOption = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionInterface');
        $option_collection = $customOption->getProductOptionCollection($product);
        $optionId = 0;
        foreach ($option_collection as $item) {
            if ($item->getTitle() == "Type") {
                $optionId = $item->getOptionId();
                break;
            }
        }
        return $optionId;
    }

    public function getTypeValuesOptions($product, $idIndex = false)
    {
        $customOption = $this->_objectManager->create('Magento\Catalog\Api\Data\ProductCustomOptionInterface');
        $option_collection = $customOption->getProductOptionCollection($product);
        $_values = [];
        foreach ($option_collection as $item) {
            if ($item->getTitle() == "Type") {
                $values = $item->getValues();
                foreach ($values as $value) {
                    if ($idIndex) {
                        $_values[$value->getOptionTypeId()] = $value->getTitle();
                    } else {
                        $_values[$value->getTitle()] = $value->getOptionTypeId();
                    }
                }
                break;
            }
        }
        return $_values;
    }

    public function orderContainInvoiceItem($order)
    {
        $orderData = $order->getAllItems();
        foreach ($orderData as $item) {
            if ($item->getSku() == "sap_invoice") {
                return true;
            }
        }
        return false;
    }

    public function getCartDocEntryList($cart)
    {

        $cart_docEntry_list = [];
        $cartData = $cart->getQuote()->getAllVisibleItems();

        foreach ($cartData as $item) {
            if ($item->getSku() != "sap_invoice") continue;
            $product = $item->getProduct();
            $options = $item->getProduct()
                ->getTypeInstance(true)
                ->getOrderOptions($item->getProduct());
            if (isset($options['options'])) {
                $customOptions = $options['options'];
                $docEntry_array = array_filter($customOptions, function ($var) {
                    return ($var['label'] == 'DocEntry');
                });
                $docType_array = array_filter($customOptions, function ($var) {
                    return ($var['label'] == 'Type');
                });
                $cart_docEntry_list[] = $this->getDocType(current($docType_array)["value"]) . "_" . current($docEntry_array)["value"];
            }
        }
        return $cart_docEntry_list;
    }

    public function getAllowedPaymentMethods()
    {

        $result = [];

        $_allowed_payment_methods = trim($this->_allowed_payment_methods);
        $_allowed_payment_methods = trim($_allowed_payment_methods, ",");

        if ($_allowed_payment_methods != "") {
            $result = explode(",", $_allowed_payment_methods);
        }

        return $result;
    }

    public function currentCartContainInvoiceItem()
    {
        return $this->cartContainInvoiceItem($this->_cart);

    }

    public function cartContainInvoiceItem($cart)
    {
        $cartData = $cart->getQuote()->getAllItems();

        $result = false;
        foreach ($cartData as $item) {
            if ($item->getSku() == "sap_invoice") {
                $result = true;
            }
        }

        return $result;
    }

    public function cartContainAnotherItem($cart)
    {
        $cartData = $cart->getQuote()->getAllItems();

        $result = false;
        foreach ($cartData as $item) {
            if ($item->getSku() != "sap_invoice") {
                $result = true;
            }
        }

        return $result;
    }

    public function getCustomerEmail($customer_id = "")
    {
        $email = "";
        if ($customer_id > 0) {
            $oCustomer = $this->_customerRepository->getById($customer_id);
            $email = $oCustomer->getEmail();
        } else {
            $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
            $ocustomer = $_customerSession->getCustomer();
            $email = $ocustomer->getEmail();
        }


        return $email;
    }

    public function getCustomerSapCompany($customer_id = "")
    {
        $company = "";
        if ($customer_id > 0) {
            $oCustomer = $this->_customerRepository->getById($customer_id);
            $customAttributes = $oCustomer->getCustomAttributes();
            if (isset($customAttributes["itm_sap_company"])) {
                $company = $customAttributes["itm_sap_company"]->getValue();
            }
        } else {
            $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
            $ocustomer = $_customerSession->getCustomer();
            $company = $ocustomer->getItmSapCompany();
        }

        if (empty($company)) {
            $company = $this->_default_company;
        }

        return $company;
    }

    public function isValideInvoice($docEntry, $docType, $check_status = true)
    {
        $doc_type = $this->getDocType($docType);
        //$_messageManager = $this->_objectManager->get('\Magento\Framework\Message\ManagerInterface');
        $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');

        if ($_customerSession->isLoggedIn()) {
            $customer_email = $_customerSession->getCustomer()->getEmail();
            $company = $this->getCustomerSapCompany();// $_customerSession->getCustomer()->getItmSapCompany();
            $invoice = $this->getInvoice($docEntry, $company, $doc_type);
            if ($invoice) {
                if ($check_status == true) {
                    if (($invoice->getEmail() == $customer_email) && $invoice->getStatus() == 1 && $invoice->getInvoiceStatus() == "o") {
                        return true;
                    }
                } else {
                    if (($invoice->getEmail() == $customer_email) && $invoice->getStatus() == 1) {
                        return true;
                    }
                }

            }
        }

        return false;
    }

    public function getDocTypeLabel($docType)
    {
        $label = "";
        switch ($docType) {
            case "in" :
                $label = "Invoice";
                break;
            case "dt" :
                $label = "Down Payment";
                break;
            default :
                $label = "Unknowen Type";
                break;
        }

        return $label;
    }

    public function getInvoice($docEntry, $company, $docType)
    {
        $collection = $this->_invoiceCollectionFactory->create();
        $invoiceCollection = $collection
            ->addFieldToFilter("sap_company", $company)
            ->addFieldToFilter("doc_type", $docType)
            ->addFieldToFilter("doc_entry", $docEntry);

        if ($invoiceCollection->getSize() > 0) {
            $invoice = $invoiceCollection->getFirstItem();
            return $invoice;
        }
        return null;
    }

    public function getDocType($docType)
    {
        $doc_type  = "";

        if ($docType == "Invoice") {
            $doc_type = "in";
        } else if ($docType == "Down Payment") {
            $doc_type = "dt";
        } else if ($docType == "dt") {
            $doc_type = "dt";
        } else if ($docType == "in") {
            $doc_type = "in";
        }
        return $doc_type;
    }

    public function getOpenBalanceByDocEntry($docEntry, $company, $docType)
    {
        $doc_type = $this->getDocType($docType);
        $amount = 0;
        $invoice = $this->getInvoice($docEntry, $company, $doc_type);
        if ($invoice) {
            $amount = $invoice->getOpenBalance();
        }
        return (float)$amount;
    }

    public function getInvoiceFilesPath()
    {
        return $this->getModuleFilesPath() . "SapInvoicefiles";
    }

    public function getModuleFilesPath()
    {
        return $this->_fileSystem
                ->getDirectoryWrite(DirectoryList::MEDIA)
                ->getAbsolutePath('/') . "itm/magb1/";
    }
}
