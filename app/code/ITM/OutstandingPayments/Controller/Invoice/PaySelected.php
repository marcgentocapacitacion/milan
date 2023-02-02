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

namespace ITM\OutstandingPayments\Controller\Invoice;

class PaySelected extends \Magento\Customer\Controller\AbstractAccount
{
    
    /**
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;
    
    /**
     *
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;
    
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;
    
    
    
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    
    
    protected $resultPageFactory;
    
    protected $_helper;
    
    
    protected $jsonHelper;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper
        ) {
            $this->resultPageFactory = $resultPageFactory;
            $this->_productRepository = $productRepository;
            $this->_cart = $cart;
            $this->_messageManager = $messageManager;
            $this->_objectManager = $objectManager;
            $this->_helper = $helper;
            $this->jsonHelper = $jsonHelper;
            parent::__construct($context);
    }
    
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $ID = explode("_",$this->getRequest()->getParam("doc_entry"));
        $doc_type =  $ID[0];
        $doc_entry = $ID[1];
        if(empty($doc_entry)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('outstanding_payments/index/open');
            return $resultRedirect;
            
        }
        
        
        $sku = "sap_invoice";
        $qty = "1";
        
        try{
            $storeId = $this->_objectManager->get(
                \Magento\Store\Model\StoreManagerInterface::class
                )->getStore()->getId();
            $company = $this->_helper->getCustomerSapCompany();
            $invoiceOpenBalance = $this->_helper->getOpenBalanceByDocEntry($doc_entry, $company, $doc_type);
            
            $_product = $this->_productRepository->get($sku, false, $storeId);
            $doc_entry_option_id = $this->_helper->getDocEntryOptionId($_product);
            $amount_option_id = $this->_helper->getAmountOptionId($_product);
            $type_option_id = $this->_helper->getDocTypeOptionId($_product);

            $type = $doc_type == "in"? "Invoice" : "Down Payment";
            $type_values = $this->_helper->getTypeValuesOptions($_product);

            $cart_params = array(
                //   'product_id' => 2056,
                'qty' => $qty,
                'options' => array(
                    $doc_entry_option_id=>$doc_entry,
                    $amount_option_id=> (float)$invoiceOpenBalance,
                    $type_option_id => $type_values[$type]
                )
            );
            
            $this->_cart->addProduct($_product,$cart_params);
            $this->_messageManager->addSuccess(__("Your %1 %2 has been successfully added to your cart.",$type,$doc_entry));
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
        $this->_cart->save();

        return;
    }
   
}