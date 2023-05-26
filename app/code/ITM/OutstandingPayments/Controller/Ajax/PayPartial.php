<?php

namespace ITM\OutstandingPayments\Controller\Ajax;

use Magento\Framework\Controller\ResultFactory;
use function GuzzleHttp\Psr7\str;

class PayPartial extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \ITM\OutstandingPayments\Model\SapinvoiceRepository
     */
    protected $sapinvoiceRepository;

    /**
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var \ITM\OutstandingPayments\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     *
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;


    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \ITM\OutstandingPayments\Model\SapinvoiceRepository $sapinvoiceRepository
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \ITM\OutstandingPayments\Model\SapinvoiceRepository $sapinvoiceRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
        $this->sapinvoiceRepository = $sapinvoiceRepository;
        $this->_productRepository = $productRepository;
        $this->_helper = $helper;
        $this->_messageManager = $messageManager;
        $this->_cart = $cart;
        parent::__construct($context);
    }

    public function execute()
    {
        $messages = [];
        $quote = $this->checkoutSession->getQuote();
        $invoice_id = $this->getRequest()->getParam("invoice_id");
        $amount = $this->getRequest()->getParam("amount");
        $sku = "sap_invoice";
        $qty = "1";
        try {
            $storeId = $this->storeManager->getStore()->getId();
            $invoice = $this->sapinvoiceRepository->getById($invoice_id);
            $response = [];
            if ($invoice->getStatus() != true) {
                $response = [
                    "status" => "error",
                ];
            }
            if (strtolower($invoice->getInvoiceStatus()) != 'o') {
                $response = [
                    "status" => "error",
                ];
            }
            $openBalance = $invoice->getOpenBalance();
            if ($amount> $openBalance) {
                $response = [
                    "status" => "error",
                    "message"=> __("The amount should be less or equal than the Open Balance")
                ];
            }
            $_product = $this->_productRepository->get($sku, false, $storeId);
            $doc_entry_option_id = $this->_helper->getDocEntryOptionId($_product);
            $amount_option_id = $this->_helper->getAmountOptionId($_product);
            $type_option_id = $this->_helper->getDocTypeOptionId($_product);
            $type_values = $this->_helper->getTypeValuesOptions($_product);
            $type = $invoice->getDocType() == "in" ? "Invoice" : "Down Payment";
            $doc_entry = $invoice->getDocEntry();
            $cart_params = array(
                //   'product_id' => 2056,
                'qty' => $qty,
                'options' => array(
                    $doc_entry_option_id => $doc_entry,
                    $amount_option_id => (float)$amount,
                    $type_option_id => $type_values[$type]
                )
            );

            $this->_cart->addProduct($_product, $cart_params);
            // log files
            \Magento\Framework\App\ObjectManager::getInstance()->create('ITM\MagB1\Helper\Data')->_log(__("Your %1 %2 has been successfully added to your cart.",
                $type, $doc_entry));
            $this->_messageManager->addSuccess(__("Your %1 %2 has been successfully added to your cart.", $type,
                $doc_entry));
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }

        $this->cartRepository->save($quote);
        $response = [
            "status" => "success",
        ];
        return $this->jsonResponse($response);
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}