<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices
 * Copyright (C) 2017
 *
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Block\Index;

class Index extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory
     */
    protected $_invoiceCollectionFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var \ITM\OutstandingPayments\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_pricing_helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \ITM\OutstandingPayments\Helper\Data $helper
     * @param \Magento\Framework\Pricing\Helper\Data $pricing_helper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\Pricing\Helper\Data $pricing_helper
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectManager;
        $this->_invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->_customerRepository = $customerRepository;
        $this->_helper = $helper;
        $this->_pricing_helper = $pricing_helper;
    }

    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getLimit()
    {
        $pager = $this->_objectManager->get('Magento\Theme\Block\Html\Pager');
        return $pager->getLimit();
    }

    public function getDocTypeLabel($docType)
    {
        return $this->_helper->getDocTypeLabel($docType);
    }

    public function getAvailableLimit()
    {
        $pager = $this->_objectManager->get('Magento\Theme\Block\Html\Pager');
        return $pager->getAvailableLimit();
    }

    public function getInvoiceList($invoiceStatus = [])
    {
        //get values of current page
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $limit = ($this->getRequest()->getParam('limit'));
        if (in_array($limit, $this->getAvailableLimit())) {
            $pageSize = $limit;
        } else {
            $pageSize = $this->getLimit();
        }

        $company = $this->_helper->getCustomerSapCompany();
        $email = $this->_helper->getCustomerEmail();
        $collection = $this->_invoiceCollectionFactory->create();
        $invoiceCollection = $collection
            ->addFieldToFilter("sap_company", $company)
            ->addFieldToFilter("email", $email)
            ->addFieldToFilter("invoice_status", ["in" => $invoiceStatus]);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $collection->setOrder("doc_due_date", "desc");
        $collection->setOrder("doc_num", "desc");
        return $collection;
    }

    public function formatPrice($price)
    {
        return $this->_pricing_helper->currency((float)$price, true, false);

    }

    public function getInvoiceStatusLabel($invoice)
    {
        return $this->_helper->getInvoiceStatusLabel($invoice);
    }

    public function displayInfoTab()
    {
        return $this->_helper->displayInfoTab();
    }

    public function allowPartialPayment()
    {
        return $this->_helper->allowPartialPayment();
    }

    public function getOpenInvoices()
    {
        return $this->getInvoiceList(["o", "p"]);
        //return $this->getInvoiceList(["o"]);
    }

    public function getClosedInvoices()
    {
        return $this->getInvoiceList(["c"]);
    }
}
