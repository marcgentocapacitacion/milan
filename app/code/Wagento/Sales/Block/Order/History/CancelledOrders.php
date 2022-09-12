<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Customer\Model\Session;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

/**
 * Class CancelledOrders
 */
class CancelledOrders extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Collection|null
     */
    protected ?Collection $ordersCancelled = null;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var CollectionFactoryInterface
     */
    protected CollectionFactoryInterface $orderCollectionFactory;

    /**
     * @param Context           $context
     * @param CollectionFactory $orderCollectionFactory
     * @param Session           $customerSession
     * @param array             $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        Session $customerSession,
        array $data = []
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve formatting date
     *
     * @param null|string|\DateTimeInterface $date
     * @param int $format
     * @param bool $showTime
     * @param null|string $timezone
     * @return string
     */
    public function formatDate(
        $date = null,
        $format = \IntlDateFormatter::SHORT,
        $showTime = false,
        $timezone = null
    ) {
        $date = $date instanceof \DateTimeInterface ? $date : new \DateTime($date ?? 'now');
        return $this->_localeDate->formatDateTime(
            $date,
            $format,
            $showTime ? $format : \IntlDateFormatter::NONE,
            null,
            $timezone,
            'MMMM dd, YYYY'
        );
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        if ($this->getOrdersCancelled()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.cancelled.order.pager'
            )->setCollection(
                $this->getOrdersCancelled()
            );
            $this->setChild('pager-cancelled-order', $pager);
            $this->getOrdersCancelled()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager-cancelled-order');
    }

    /**
     * Get customer orders
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrdersCancelled()
    {
        if (!($customerId = $this->customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->ordersCancelled) {
            $this->ordersCancelled = $this->orderCollectionFactory->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                Order::STATE,
                ['in' => [
                    Order::STATE_CANCELED,
                    Order::STATE_CLOSED
                ]]
            )->setOrder(
                'created_at',
                'desc'
            );
        }
        return $this->ordersCancelled;
    }

    /**
     * Get message for no orders.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyOrdersMessage()
    {
        return __('You have placed no orders.');
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getCreditMemoUrl($order)
    {
        return $this->getUrl('sales/order/creditmemo', ['order_id' => $order->getId()]);
    }
}
