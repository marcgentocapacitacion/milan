<?php

namespace Wagento\IntegrationERP\Plugin\Company;

use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Wagento\IntegrationERP\Model\CompanyCustomDataFactory as Model;
use Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomDataFactory as ResourceModel;
use Magento\Company\Api\Data\CompanyExtensionFactory;

/**
 * Class CompanyRepositoryPlugin
 */
class CompanyRepositoryPlugin
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var ResourceModel
     */
    protected ResourceModel $resourceModel;

    /**
     * @var CompanyExtensionFactory
     */
    protected CompanyExtensionFactory $companyExtensionFactory;

    /**
     * @param Model                   $model
     * @param ResourceModel           $resourceModel
     * @param CompanyExtensionFactory $companyExtensionFactory
     */
    public function __construct(
        Model $model,
        ResourceModel $resourceModel,
        CompanyExtensionFactory $companyExtensionFactory
    ) {
        $this->model = $model;
        $this->resourceModel = $resourceModel;
        $this->companyExtensionFactory = $companyExtensionFactory;
    }

    /**
     * After get company.
     *
     * @param CompanyRepositoryInterface $subject
     * @param CompanyInterface $company
     * @return CompanyInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CompanyRepositoryInterface $subject,
        CompanyInterface $company
    ) {
        $this->getCompanyCustomData($company);
        return $company;
    }

    /**
     * After save company.
     *
     * @param CompanyRepositoryInterface $subject
     * @param CompanyInterface $company
     * @return CompanyInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        CompanyRepositoryInterface $subject,
        CompanyInterface $company
    ) {
        $company = $this->getCompanyCustomData($company);
        return $company;
    }

    /**
     * @param CompanyInterface $company
     *
     * @return CompanyInterface
     */
    protected function getCompanyCustomData(CompanyInterface $company)
    {
        $model = $this->model->create();
        $this->resourceModel->create()->load($model, $company->getId());
        if ($model->getId()) {
            $companyExtension = $company->getExtensionAttributes();
            if ($companyExtension === null) {
                $companyExtension = $this->companyExtensionFactory->create();
            }

            foreach ($model->getData() as $code => $value) {
                $companyExtension->setData($code, $value);
            }

            $company->setExtensionAttributes($companyExtension);
        }
        return $company;
    }
}
