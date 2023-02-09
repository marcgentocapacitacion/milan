<?php

namespace Wagento\Catalog\Block;

/**
 * Class MessageVerifyStock
 */
class MessageVerifyStock extends \Magento\Framework\View\Element\Template
{
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this
            ->_scopeConfig
            ->getValue('wagento_catalog/product_list/message_verify_stock') ?? '';
    }
}
