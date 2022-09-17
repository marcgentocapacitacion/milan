<?php
namespace Wagento\ModifiedCheckout\Block\Onepage;

use Magento\Framework\Exception\LocalizedException;

class Success extends \Magento\Checkout\Block\Onepage\Success
{

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $priceHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        array $data = []
    ) {
        $this->priceHelper = $priceHelper;
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
    }

    /**
     * Prepares block data
     *
     * @return void
     */
    protected function prepareBlockData()
    {
        $order = $this->_checkoutSession->getLastRealOrder();

        try {
            $paymentMethod = $order->getPayment()->getMethodInstance()->getTitle();
        } catch (LocalizedException $e) {
            $paymentMethod = '';
        }

        $this->addData(
            [
                'is_order_visible' => $this->isVisible($order),
                'view_order_url' => $this->getUrl(
                    'sales/order/view/',
                    ['order_id' => $order->getEntityId()]
                ),
                'print_url' => $this->getUrl(
                    'sales/order/print',
                    ['order_id' => $order->getEntityId()]
                ),
                'can_print_order' => $this->isVisible($order),
                'can_view_order'  => $this->canViewOrder($order),
                'order_id'  => $order->getIncrementId(),
                'order_date'  => date('d F Y h:i A', strtotime($order->getCreatedAt())),
                /* Add payment detail */
                'payment_title' => $paymentMethod,
                'payment_additional' => ''
            ]
        );

        // add billing information
        if ($order->getBillingAddress()) {
            $this->addData(
                [
                    'billing_name' => $order->getBillingAddress()->getFirstname() . ' ' . $order->getBillingAddress()->getLastname(),
                    'billing_street' => implode(', ', $order->getBillingAddress()->getStreet()),
                    'billing_city_country' => $order->getBillingAddress()->getCity() . ', ' . $order->getBillingAddress()->getRegion() . ', ' .
                        $order->getBillingAddress()->getPostcode() . ' ' . $order->getBillingAddress()->getCountryId(),
                    'billing_phone' => $order->getBillingAddress()->getTelephone(),
                ]
            );
        }

        // add shipping information
        if ($order->getShippingAddress()) {
            $this->addData(
                [
                    'shipping_name' => $order->getShippingAddress()->getFirstname() . ' ' . $order->getShippingAddress()->getLastname(),
                    'shipping_street' => implode(', ', $order->getShippingAddress()->getStreet()),
                    'shipping_city_country' => $order->getShippingAddress()->getCity() . ', ' . $order->getShippingAddress()->getRegion() . ', ' .
                        $order->getShippingAddress()->getPostcode() . ' ' . $order->getShippingAddress()->getCountryId(),
                    'shipping_phone' => $order->getShippingAddress()->getTelephone(),
                ]
            );
        }

        // add order items
        if ($order->getShippingAddress()) {
            $this->addData(
                [
                    'order_items' => $order->getAllItems(),
                ]
            );
        }

        // add order total
        if ($order->getShippingAddress()) {
            $this->addData(
                [
                    'grand_total' => $this->getFormattedPrice($order->getGrandTotal()),
                    'subtotal' => $this->getFormattedPrice($order->getSubtotal()),
                    'discount_amount' => $this->getFormattedPrice($order->getDiscountAmount()),
                    'shipping_amount' => $this->getFormattedPrice($order->getShippingAmount()),
                ]
            );
        }
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }
}
