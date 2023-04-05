<?php

namespace Wagento\SocialLogin\Plugin\Block\Account;

use WeltPixel\SocialLogin\Block\Account\SocialAccounts;

/**
 * Class SocialAccountsPlugin
 */
class SocialAccountsPlugin
{
    /**
     * @param SocialAccounts $subject
     * @param callable       $proceed
     *
     * @return void
     */
    public function aroundIsRequired(SocialAccounts $subject, callable $proceed)
    {
        $customer  = $subject->getCustomer();
        if (!$customer->getId()) {
            return false;
        }
        return $proceed();
    }
}
