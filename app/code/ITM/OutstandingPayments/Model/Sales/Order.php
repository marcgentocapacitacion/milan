<?php
namespace ITM\OutstandingPayments\Model\Sales;

use ITM\OutstandingPayments\Api\Sales\OrderInterface;

class Order implements OrderInterface
{

   
    /**
     *
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
   
    
    /**
     * @var  \Magento\Sales\Model\OrderRepository
     */
    protected $_orderRepository;

    /**
     * @var  \ITM\OutstandingPayments\Helper\Data
     */
    protected $_helper;
    /**
     *
     * @param
     *            * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \ITM\OutstandingPayments\Helper\Data $helper
        
    ) {
        $this->_objectManager = $objectManager;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->_orderRepository = $orderRepository;
        $this->_helper = $helper;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getOrderList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
       
        $searchResult =   $this->_orderRepository->getList($searchCriteria);
        $result = [];

        foreach ($searchResult->getItems() as $order) {
            $order_id = $order->getEntityId();
            $customer_id = $order->getData("customer_id");
            $customer_email = $order->getData("customer_email");
            $store_id = $order->getData("store_id");
            $payment_method =  $order->getPayment()->getMethod();
            
            $_order["order_id"] = $order_id;
            $_order["increment_id"] = $order->getData("increment_id");
            $_order["store_id"] = $store_id;
            $_order["grand_total"] = $order->getData("grand_total");
            $_order["total_paid"] = $order->getData("total_paid");
            $_order["created_at"] = $order->getData("created_at");
            $_order["customer_id"] = $customer_id;
            $_order["products"] = [];
            $items = $order->getItems();
            
            foreach ( $items as $item ) {
                $_item["order_id"] = $order_id;
                $_item["customer_id"] = $customer_id;
                $_item["store_id"] = $store_id;
                
                $_item["item_id"] = $item->getData("item_id");
                $_item["created_at"] = $item->getData("created_at");
                $_item["sku"] = $item->getData("sku");
                $_item["row_total"] = $item->getData("row_total");
                $_item["row_total_incl_tax"] = $item->getData("row_total_incl_tax");
                $_item["customer_email"] = $customer_email;
                $_item["payment_method"] = $payment_method;
                
                $product_options = $item->getProductOptions();
                $options = $product_options["options"];
                $doc_entry = "";
                $option_amount = "";
                $option_doctype = "";
                foreach ($options as $option) {
                    if($option["label"]=="DocEntry") {
                        $doc_entry = $option["value"];
                    }
                    if($option["label"]=="Amount") {
                        $option_amount = $option["value"];
                    }
                    if($option["label"]=="Type") {
                        $option_doctype = $option["value"];
                    }
                }
                $_item["doc_entry"] = $doc_entry;
                $_item["option_amount"] = $option_amount;
                $_item["amount"] = $item->getData("row_total");
                $_item["doc_type"] = $this->_helper->getDocType($option_doctype);
                $_order["products"][] = $_item;
            }
             
            $result[] = $_order;
        }
        
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($result);
        $searchResult->setTotalCount(count($result));
        return $searchResult;
        
    }
  
    /**
     *
     * {@inheritdoc}
     *
     */
    public function getOrderInfo($increment_id)
    {
        
        $order = $this->_objectManager->create('Magento\Sales\Model\Order');
        
        $order->loadByIncrementId($increment_id);
  
        
        $order_id = $order->getData("entity_id");
        $customer_id = $order->getData("customer_id");
        $customer_email = $order->getData("customer_email");
        $payment_method =  $order->getPayment()->getMethod();
        $items_array = [];
        foreach ($order->getItems() as $item) {
            $_item["order_id"] = $order_id;
            $_item["item_id"] = $item->getData("item_id");
            $_item["created_at"] = $item->getData("created_at");
            $_item["sku"] = $item->getData("sku");
            $_item["row_total"] = $item->getData("row_total");
            $_item["row_total_incl_tax"] = $item->getData("row_total_incl_tax");
            $_item["store_id"] = $item->getData("store_id");
            $_item["customer_id"] = $customer_id;
            $_item["customer_email"] = $customer_email;
            $_item["payment_method"] = $payment_method;
            $product_options = $item->getProductOptions();
            $options = $product_options["options"];
            $doc_entry = "";
            $option_amount = "";
            foreach ($options as $option) {
                if($option["label"]=="DocEntry") {
                    $doc_entry = $option["value"];
                }
                if($option["label"]=="Amount") {
                    $option_amount = $option["value"];
                }
            }
            $_item["doc_entry"] = $doc_entry;
            $_item["option_amount"] = $option_amount;
            $_item["amount"] = $item->getData("row_total");
            
            
            $items_array[] = $_item;
        }
               
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setItems($items_array);
        $searchResult->setTotalCount(count($items_array));
        return $searchResult;
    }
    
   
    
}
