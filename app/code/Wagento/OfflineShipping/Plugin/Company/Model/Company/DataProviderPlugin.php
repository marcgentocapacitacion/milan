<?php

namespace Wagento\OfflineShipping\Plugin\Company\Model\Company;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\Company\DataProvider;

/**
 * Class DataProviderPlugin
 */
class DataProviderPlugin
{
    /**
     * @param DataProvider     $subject
     * @param                  $return
     * @param CompanyInterface $company
     *
     * @return mixed
     */
    public function afterGetCompanyResultData(
        DataProvider $subject,
        $return,
        CompanyInterface $company
    ) {
        $return['settings'] = [
            'extension_attributes' => [
                'shipping_type' => $company->getShippingType()
            ]
        ];
        return $return;
    }
}
