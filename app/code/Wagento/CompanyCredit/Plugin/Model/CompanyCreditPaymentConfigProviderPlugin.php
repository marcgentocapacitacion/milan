<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\CompanyCredit\Plugin\Model;

use Magento\CompanyCredit\Model\CompanyCreditPaymentConfigProvider;
use Magento\Company\Api\AuthorizationInterface;

/**
 * Class CompanyCreditPaymentConfigProviderPlugin
 */
class CompanyCreditPaymentConfigProviderPlugin
{
    /**
     * @const string
     */
    const ACL_RESOURCE = 'Magento_Company::credit';
    /**
     * @var AuthorizationInterface
     */
    protected AuthorizationInterface $authorization;

    /**
     * @param AuthorizationInterface $authorization
     */
    public function __construct(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @param CompanyCreditPaymentConfigProvider $subject
     * @param                                    $result
     *
     * @return array
     */
    public function afterGetConfig(
        CompanyCreditPaymentConfigProvider $subject,
        $result
    ) {
        $result['payment']['is_allowed_show_credit'] = $this->authorization->isAllowed(self::ACL_RESOURCE) ?? false;
        return $result;
    }
}
