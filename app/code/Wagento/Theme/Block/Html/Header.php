<?php

namespace Wagento\Theme\Block\Html;

use Magento\Customer\Model\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template;

/**
 * Class Header
 */
class Header extends \Magento\Framework\View\Element\Template
{
    /**
     * Customer session
     *
     * @var HttpContext
     */
    protected HttpContext $httpContext;

    public function __construct(
        Template\Context $context,
        HttpContext $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
    }

    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Wagento_Theme::html/header.phtml';

    /**
     * Retrieve welcome text
     *
     * @return string
     */
    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            $this->_data['welcome'] = $this->_scopeConfig->getValue(
                'design/header/welcome',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return __($this->_data['welcome']);
    }

    /**
     * Is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }
}
