<?php
namespace  ITM\OutstandingPayments\Plugin\Sales\Helper;


class History
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

    /**
     * {@inheritDoc}
     */
    public function afterGetOrders(\Magento\Sales\Block\Order\History $subject,$result) {

        $result->addFieldToFilter(
            'itm_order_type',
            ['null' => true]
        );
        return $result;
    }

}