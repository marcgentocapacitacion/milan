<?php

namespace Wagento\Company\Block\Company;

use Magento\Framework\View\Element\Template;

/**
 * Class LinkCreateAccount
 */
class LinkCreateAccount extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_Company::company/link_create_account.phtml';

    /**
     * @return bool
     */
    public function isShowLink(): bool
    {
        return $this->_scopeConfig->isSetFlag('company/general/allow_company_registration') ?? false;
    }
}
