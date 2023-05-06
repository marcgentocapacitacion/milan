<?php

namespace Wagento\Catalog\CustomerData;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class MessageVerifyStock
 */
class MessageVerifyStock implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param Session              $session
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Session $session, ScopeConfigInterface $scopeConfig)
    {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $message = $this->getMessage();
        $showMessage = !$this->session->isLoggedIn();
        if (!$message) {
            $showMessage = false;
        }
        return [
            'message' => $message,
            'showMessage' => $showMessage
        ];
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this
            ->scopeConfig
            ->getValue(
                'wagento_catalog/product_list/message_verify_stock',
                ScopeInterface::SCOPE_STORE
            ) ?? '';
    }
}
