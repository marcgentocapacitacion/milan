<?php

namespace Wagento\ModifiedCheckout\Block\PurchaseOrder;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\Helper\Data as PricingData;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\PurchaseOrder\Api\PurchaseOrderRepositoryInterface;

/**
 * Class Success
 */
class Success extends \Magento\PurchaseOrder\Block\PurchaseOrder\Success
{
    /**
     * @var PricingData
     */
    protected PricingData $priceHelper;

    /**
     * @param TemplateContext                  $context
     * @param CheckoutSession                  $checkoutSession
     * @param PurchaseOrderRepositoryInterface $purchaseOrderRepository
     * @param PricingData                      $priceHelper
     * @param array                            $data
     */
    public function __construct(
        TemplateContext $context,
        CheckoutSession $checkoutSession,
        PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        PricingData $priceHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $checkoutSession,
            $purchaseOrderRepository,
            $data
        );
        $this->priceHelper = $priceHelper;

    }

    /**
     * @return $this|Success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        $order = $this->getPurchaseOrder()->getSnapshotQuote();
        $this->addData(
            [
                'order_date'  => date('d F Y h:i A', strtotime($order->getCreatedAt())) ?? '',
                'grand_total' => $this->getFormattedPrice($order->getGrandTotal()),
                'subtotal' => $this->getFormattedPrice($order->getSubtotal()),
                'discount_amount' => $this->getFormattedPrice($order->getDiscountAmount()),
                'shipping_amount' => $this->getFormattedPrice($order->getShippingAmount()),
            ]
        );
        return $this;
    }

    /**
     * Render additional order information lines and return result html
     *
     * @return string
     */
    public function getAdditionalInfoHtml()
    {
        return $this->_layout->renderElement('order.success.additional.info');
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
