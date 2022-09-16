<?php
/**
 * Rutavity checkout customization
 * @package Wagento_ModifiedCheckout
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\ModifiedCheckout\Plugin\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessor as CheckoutLayoutProcessor;

class LayoutProcessor
{
    /**
     * @param CheckoutLayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        CheckoutLayoutProcessor $subject,
        array $jsLayout
    ): array {
        $paymentLayout = $jsLayout['components']['checkout']['children']['steps']
        ['children']['billing-step']['children']['payment']['children'];

        if (isset($paymentLayout['afterMethods']['children']['billing-address-form'])) {
            $jsLayout['components']['checkout']['children']['steps']
            ['children']['billing-step']['children']['payment']['children']
            ['beforeMethods']['children']['billing-address-form']
                = $paymentLayout['afterMethods']['children']['billing-address-form'];

            unset($jsLayout['components']['checkout']['children']['steps']
                ['children']['billing-step']['children']['payment']
                ['children']['afterMethods']['children']['billing-address-form']);
        }

        return $jsLayout;
    }
}
