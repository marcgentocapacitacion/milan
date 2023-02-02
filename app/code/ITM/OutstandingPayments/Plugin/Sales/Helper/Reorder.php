<?php
namespace  ITM\OutstandingPayments\Plugin\Sales\Helper;


class Reorder
{
    protected $_objectManager;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;


    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
        $this->orderRepository = $orderRepository;
        $this->quoteFactory = $quoteFactory;
    }

    ////////////////////////////////////////// Not Applied Now /////////////////////////////
    /**
     * {@inheritDoc}
     */
    public function afterCanReorder(\Magento\Sales\Helper\Reorder $subject,$result, $orderId) {

        if($result == true) {
            $order = $this->orderRepository->get($orderId);
            $order_items = $order->getAllItems();
               // $quote_items = $this->quoteFactory->create()->load($quote_id)->getAllItems();

                foreach ($order_items as $order_item) {
                    if( $order_item->getSku() == "sap_invoice"){
                        return false;
                    }
                }
            }
        return $result;
    }

}