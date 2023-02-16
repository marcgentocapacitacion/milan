<?php

namespace Wagento\Checkout\Block\Onepage;

/**
 * Class Link
 */
class Link extends \Magento\Checkout\Block\Onepage\Link
{
    /**
     * @return string
     */
    public function getMessageError(): string
    {
        return $this->_scopeConfig->getValue(
            'sales/minimum_order/error_message',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getId()
        ) ?? '';
    }
}
