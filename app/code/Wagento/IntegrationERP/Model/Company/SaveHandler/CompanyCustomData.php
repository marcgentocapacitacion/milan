<?php

namespace Wagento\IntegrationERP\Model\Company\SaveHandler;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\SaveHandlerInterface;
use Wagento\IntegrationERP\Model\CompanyCustomDataFactory as Model;
use Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomDataFactory as ResourceModel;

/**
 * Class CompanyCustomData
 */
class CompanyCustomData implements SaveHandlerInterface
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
     * @param Model         $model
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        Model $model,
        ResourceModel $resourceModel
    ) {
        $this->model = $model;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @inheritdoc
     */
    public function execute(
        CompanyInterface $company,
        CompanyInterface $initialCompany
    ) {
        $extensionAttributes = $company->getExtensionAttributes();
        $model = $this->model->create();
        $this->resourceModel->create()->load($model, $company->getId());

        if (!$model->getId()) {
            $model->setCompanyId($company->getId());
            $model->isObjectNew(true);
        }

        $model->setUAutorizadoTemporada($this->getBolleanValue($extensionAttributes->getUAutorizadoTemporada()));
        $model->setUInicioTemporada($extensionAttributes->getUInicioTemporada() ?? '');
        $model->setUFinTemporada($extensionAttributes->getUFinTemporada() ?? '');
        $model->setUGroupNumTemporada($extensionAttributes->getUGroupNumTemporada() ?? '');
        $model->setUDiscountTemporada($extensionAttributes->getUDiscountTemporada() ?? '');
        $model->setUAutorizadoTemporadaOPT($this->getBolleanValue($extensionAttributes->getUAutorizadoTemporadaOPT()));
        $model->setUGroupNumTempoOPT($extensionAttributes->getUGroupNumTempoOPT() ?? '');
        $model->setGroupNum($extensionAttributes->getGroupNum() ?? '');
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
        $model->setFrozen($this->getBolleanValue($extensionAttributes->getFrozen()));
        $model->setSlpCode($extensionAttributes->getSlpCode() ?? '');
        $model->setTerritory($extensionAttributes->getTerritory() ?? '');
        $this->resourceModel->create()->save($model);
    }

    /**
     * @param string|null $value
     *
     * @return bool
     */
    protected function getBolleanValue(?string $value): bool
    {
        if (!$value) {
            return false;
        }

        if ($value == 'false') {
            return false;
        }

        if ($value == 'true') {
            return true;
        }

        return false;
    }
}
