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

/**
 * Class Orders
 */
class Orders extends \Magento\Sales\Block\Order\History
{
    /**
     * @var ImageFactory
     */
    protected ImageFactory $imageFactory;

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
            $orderCollectionFactory,
            $customerSession,
            $orderConfig,
            $data
        );
        $this->imageFactory = $imageFactory;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        if ($this->getOrders()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.order.pager'
            )->setCollection(
                $this->getOrders()
            );
            $this->setChild('pager-order', $pager);
            $this->getOrders()->load();
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
        return $this->getChildHtml('pager-order');
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
}
