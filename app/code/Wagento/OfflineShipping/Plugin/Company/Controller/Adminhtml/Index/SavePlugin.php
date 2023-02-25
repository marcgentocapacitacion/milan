<?php

namespace Wagento\OfflineShipping\Plugin\Company\Controller\Adminhtml\Index;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Controller\Adminhtml\Index\Save;

/**
 * Class SavePlugin
 */
class SavePlugin
{
    /**
     * @param Save             $subject
     * @param                  $result
     * @param CompanyInterface $company
     * @param array            $data
     *
     * @return mixed
     */
    public function afterSetCompanyRequestData(Save $subject, $result, CompanyInterface $company, array $data)
    {
        $result->setShippingType($result->getExtensionAttributes()->getShippingType());
        return $result;
    }
}
