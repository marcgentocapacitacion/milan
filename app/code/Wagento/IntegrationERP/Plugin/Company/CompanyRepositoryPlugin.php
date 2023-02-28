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
        $extensionAttributes = $company->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->companyExtensionFactory->create();
        }

        $company->setExtensionAttributes($extensionAttributes);
        /** @var \Wagento\IntegrationERP\Model\CompanyCustomData $model */
        $model = $this->model->create();
        $this->resourceModel->create()->load($model, $company->getId());
        if (!$model->getId()) {
            $model->setCompanyId($company->getId());
        }
        $model->setUAutorizadoTemporada((bool)$extensionAttributes->getUAutorizadoTemporada() ?? false);
        $model->setUInicioTemporada($extensionAttributes->getUInicioTemporada() ?? '');
        $model->setUFinTemporada($extensionAttributes->getUFinTemporada() ?? '');
        $model->setUGroupNumTemporada($extensionAttributes->getUGroupNumTemporada() ?? '');
        $model->setUDiscountTemporada($extensionAttributes->getUDiscountTemporada() ?? '');
        $model->setUAutorizadoTemporadaOPT((bool)$extensionAttributes->getUAutorizadoTemporadaOPT() ?? false);
        $model->setUGroupNumTempoOPT($extensionAttributes->getUGroupNumTempoOPT() ?? '');
        $model->setGroupNum((bool)$extensionAttributes->getGroupNum() ?? false);
        $model->setUDiscountContado($extensionAttributes->getUDiscountContado() ?? '');
        $model->setUDiscountContadoOptimus($extensionAttributes->getUDiscountContadoOptimus() ?? '');
        $model->setUDiscountOptimus($extensionAttributes->getUDiscountOptimus() ?? '');
        $model->setUGroupNumOptimus($extensionAttributes->getUGroupNumOptimus() ?? '');
        $model->setUDiscountMilan($extensionAttributes->getUDiscountMilan() ?? '');
        $model->setDebitLine($extensionAttributes->getDebitLine() ?? '');
        $model->setUFechaNacimiento($extensionAttributes->getUFechaNacimiento() ?? '');
        $model->setUClienteActivoHasta($extensionAttributes->getUClienteActivoHasta() ?? '');
        $model->setPhone2($extensionAttributes->getPhone2() ?? '');
        $model->setCellular($extensionAttributes->getCellular() ?? '');
        $model->setFrozen((bool)$extensionAttributes->getFrozen() ?? false);
        $model->setSlpCode((bool)$extensionAttributes->getSlpCode() ?? false);
        $model->setTerritory((bool)$extensionAttributes->getTerritory() ?? false);
        $this->resourceModel->create()->save($model);
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
