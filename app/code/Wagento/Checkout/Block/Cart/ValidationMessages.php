<?php

namespace Wagento\Checkout\Block\Cart;

/**
 * Class ValidationMessages
 */
class ValidationMessages extends \Magento\Checkout\Block\Cart\ValidationMessages
{
    /**
     * Validate minimum amount and display notice in error
     *
     * @return void
     */
    protected function validateMinimumAmount()
    {
        return;
    }
}
