<?php

namespace Wagento\Catalog\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

/**
 * Class MessageVerifyStock
 */
class MessageVerifyStock extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    protected Session $session;

    public function __construct(
        Template\Context $context,
        Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->session->isLoggedIn() ?? false;
    }

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
