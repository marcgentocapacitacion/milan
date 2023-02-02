<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
     *
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    protected $resultPageFactory;

    protected $_helper;

    /**
     * Constructor
     * 
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ITM\OutstandingPayments\Helper\Data $helper)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
        $this->_messageManager = $messageManager;
        $this->_objectManager = $objectManager;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    
    /**
     * Execute view action
     * 
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        $doc_entries = $this->getRequest()->getParam("doc_entry");
        foreach ($doc_entries as $doc_entry){
			$this->addDocEntry($doc_entry);
        }
       
      // exit;
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('outstanding_payments/index/open');
        return $resultRedirect;
    }
    public function addDocEntry($doc_entry){
        
        $storeId = $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)
        ->getStore()
        ->getId();
        
        $sku = "sap_invoice";
        $qty = "1";
        $company = $this->_helper->getCustomerSapCompany();
        $_product = $this->_productRepository->get($sku, false, $storeId);
        $doc_entry_option_id = $this->_helper->getDocEntryOptionId($_product);
        $amount_option_id = $this->_helper->getAmountOptionId($_product);
        
        
        
        try
        {
            
            $invoiceOpenBalance = $this->_helper->getOpenBalanceByDocEntry($doc_entry, $company);
            $cart_params = array(
                'product_id' => $_product->getId(),
                'qty' => $qty,
                'options' => array(
                    $doc_entry_option_id => $doc_entry,
                    $amount_option_id => (float) $invoiceOpenBalance
                )
            );
            //   print_r($cart_params);
            $this->_cart->addProduct($_product, $cart_params);
            $this->_cart->save();
            $this->_messageManager->addSuccess(__("Your invoice %s has been successfully added to your cart.",$doc_entry));
            // }catch( \Magento\Framework\Exception\NoSuchEntityException $ex) {
        } catch (\Exception $e)
        {
            $this->_messageManager->addError($e->getMessage());
        }
    }




}