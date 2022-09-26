<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Class Orders
 */
class Orders extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ImageFactory
     */
    protected ImageFactory $imageFactory;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_orderCollectionFactory;

    /**
     * @var Session
     */
    protected Session $_customerSession;

    /**
     * @var Config
     */
    protected Config $_orderConfig;

    /**
     * @var Collection|null
     */
    protected $orders = null;

    /**
     * @var CollectionFactoryInterface
     */
    protected CollectionFactoryInterface $orderCollectionFactory;

    /**
     * @param Context           $context
     * @param CollectionFactory $orderCollectionFactory
     * @param Session           $customerSession
     * @param Config            $orderConfig
     * @param ImageFactory      $imageFactory
     * @param array             $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        Session $customerSession,
        Config $orderConfig,
        ImageFactory $imageFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->imageFactory = $imageFactory;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setData('size', $this->getOrders()->getSize());
        if ($this->getOrders()) {
            $this->getOrders()->setCurPage($this->getCurrentPage());
            $this->getOrders()->setPageSize($this->getPageLimite());
            $this->getOrders()->load();
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getPageLimite(): int
    {
        return 10;
    }

    /**
     * Return current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return (int)$this->getRequest()->getParam('p', 1);
    }

    /**
     * Get customer orders
     *
     * @return bool|OrderCollection
     */
    public function getOrders()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'main_table.status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->setOrder(
                'main_table.created_at',
                'desc'
            );

            $this->addFilterDate();
            $this->addFilterSearchText();
        }
        return $this->orders;
    }

    public function getShowItems()
    {
        return $this->hasData('show_items') ? $this->getData('show_items') : true;
    }

    /**
     * @param OrderCollection $orders
     *
     * @return $this
     */
    public function setOrders(OrderCollection $orders)
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Retrieve product image
     *
     * @param Product $product
     * @param string $imageId
     * @param array $attributes
     * @return Image
     */
    public function getImage(Product $product, string $imageId, array $attributes = []): Image
    {
        return $this->imageFactory->create($product, $imageId, $attributes);
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
     * Get item options.
     * @param OrderItem $item
     *
     * @return array
     */
    public function getItemOptions(OrderItem $item): array
    {
        $result = [];
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result[] = $options['options'];
            }
            if (isset($options['additional_options'])) {
                $result[] = $options['additional_options'];
            }
            if (isset($options['attributes_info'])) {
                $result[] = $options['attributes_info'];
            }
        }
        return array_merge([], ...$result);
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getInvoiceUrl($order)
    {
        return $this->getUrl('sales/order/invoice', ['order_id' => $order->getId()]);
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

    /**
     * Provide order collection factory
     *
     * @return CollectionFactoryInterface
     */
    protected function getOrderCollectionFactory()
    {
        return $this->orderCollectionFactory;
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * @return $this|void
     */
    protected function addFilterSearchText()
    {
        $search = $this->getRequest()->getParam('q');
        if (!$search) {
            return $this;
        }

        $this->orders
            ->join(
                ['item' => $this->orders->getTable('sales_order_item')],
                'item.order_id = main_table.entity_id'
            )
            ->addFieldToFilter([
                'main_table.increment_id',
                'item.sku',
                'item.name'
        ], [
            ['like' => "%{$search}%"],
            ['like' => "%{$search}%"],
            ['like' => "%{$search}%"]
        ])
        ->getSelect()->group('main_table.entity_id');
    }

    /**
     * @return $this
     */
    protected function addFilterDate()
    {
        $year = $this->getRequest()->getParam('year');
        if (!$year) {
            $year = '30 days';
        }

        if ($year == '30 days' || $year == '3 months') {
            $date = date('Y-m-d', strtotime("-{$year}"));
            $this->orders->addFieldToFilter(
                'main_table.created_at',
                ['gteq' => $date]
            );
            return $this;
        }

        $start = $year . '-01-01 00:00:00';
        $end = $year . '-12-31 23:59:59';
        $this->orders->addFieldToFilter(
            'main_table.created_at',
            ['gteq' => $start]
        )
        ->addFieldToFilter(
            'main_table.created_at',
            ['lteq' => $end]
        );
        return $this;
    }
}
