<?php

namespace Wagento\Rutavity\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;

/**
 * Class MobileFooter
 */
class MobileFooter extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    public Session $session;

    /**
     * @param Template\Context $context
     * @param Session          $session
     * @param array            $data
     */
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
}
