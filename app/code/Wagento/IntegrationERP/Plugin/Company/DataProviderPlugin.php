<?php

namespace Wagento\IntegrationERP\Plugin\Company;

use Magento\Company\Model\Company\DataProvider;
use Magento\Company\Api\Data\CompanyInterface;
use Wagento\IntegrationERP\Model\CompanyCustomDataFactory as Model;

/**
 * Class DataProviderPlugin
 */
class DataProviderPlugin
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

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
        $return['company_custom_data'] = [
            'extension_attributes' => $this->getCompanyCustomData($company)
        ];
        return $return;
    }

    /**
     * @param CompanyInterface $company
     *
     * @return array
     */
    protected function getCompanyCustomData(CompanyInterface $company): array
    {
        /** @var \Magento\Company\Model\Company $company */
        $model = $this->model->create();
        $extensionAttributes = $company->getExtensionAttributes();
        $model->setUAutorizadoTemporada((bool)$extensionAttributes->getUAutorizadoTemporada() ?? false);
        $model->setUInicioTemporada($extensionAttributes->getUInicioTemporada() ?? '');
        $model->setUFinTemporada($extensionAttributes->getUFinTemporada() ?? '');
        $model->setUGroupNumTemporada($extensionAttributes->getUGroupNumTemporada() ?? '');
        $model->setUDiscountTemporada($extensionAttributes->getUDiscountTemporada() ?? '');
        $model->setUAutorizadoTemporadaOPT((bool)$extensionAttributes->getUAutorizadoTemporadaOPT() ?? false);
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
        $model->setFrozen((bool)$extensionAttributes->getFrozen() ?? false);
        $model->setSlpCode($extensionAttributes->getSlpCode() ?? '');
        $model->setTerritory($extensionAttributes->getTerritory() ?? '');
        return $model->toArray();
    }
}
