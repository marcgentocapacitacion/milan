<?php

namespace Wagento\ModifiedCheckout\Block\PurchaseOrder;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\Helper\Data as PricingData;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\PurchaseOrder\Api\PurchaseOrderRepositoryInterface;
use Wagento\OfflineShipping\Api\ConfigShippingTypeInterface;

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
     * @var ConfigShippingTypeInterface
     */
    protected ConfigShippingTypeInterface $configShippingType;


    /**
     * @param TemplateContext                  $context
     * @param CheckoutSession                  $checkoutSession
     * @param PurchaseOrderRepositoryInterface $purchaseOrderRepository
     * @param PricingData                      $priceHelper
     * @param ConfigShippingTypeInterface      $configShippingType
     * @param array                            $data
     */
    public function __construct(
        TemplateContext $context,
        CheckoutSession $checkoutSession,
        PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        PricingData $priceHelper,
        ConfigShippingTypeInterface $configShippingType,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $checkoutSession,
            $purchaseOrderRepository,
            $data
        );
        $this->priceHelper = $priceHelper;
        $this->configShippingType = $configShippingType;

    }

    /**
     * @return $this|Success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        $order = $this->getPurchaseOrder()->getSnapshotQuote();
        $day = date('d', strtotime($order->getCreatedAt())) ?? '';
        $month = __(date('F', strtotime($order->getCreatedAt()))) ?? '';
        $this->addData(
            [
                'order_date'  => __("%1 of %2", $day, $month) . date(' Y h:i A', strtotime($order->getCreatedAt())) ?? '',
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

    /**
     * @return string
     */
    public function getMessageShippingType(): string
    {
        return $this->configShippingType->getMessageShippingType();
    }
}
