<?php

namespace Wagento\Company\Block\Company\Account;

/**
 * Class Create
 */
class Create extends \Magento\Company\Block\Company\Account\Create
{
    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('company/account/createPost');
    }
}
