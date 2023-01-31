<?php

namespace Wagento\IntegrationERP\Model;

/**
 * Class CompanyCustomData
 */
class CompanyCustomData extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomData::class);
    }

    /**
     * @return int
     */
    public function getUAutorizadoTemporada(): int
    {
        return $this->getData('u_autorizadoTemporada');
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setUAutorizadoTemporada(int $value)
    {
        $this->setData('u_autorizadoTemporada', $value);
        return $this;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->getData('company_id');
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setCompanyId(int $value)
    {
        $this->setData('company_id', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUInicioTemporada(): string
    {
        return $this->getData('u_inicioTemporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUInicioTemporada(string $value)
    {
        $this->setData('u_inicioTemporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUFinTemporada(): string
    {
        return $this->getData('u_finTemporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUFinTemporada(string $value)
    {
        $this->setData('u_finTemporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumTemporada(): string
    {
        return $this->getData('u_groupNumTemporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumTemporada(string $value)
    {
        $this->setData('u_groupNumTemporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountTemporada(): string
    {
        return $this->getData('u_discountTemporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountTemporada(string $value)
    {
        $this->setData('u_discountTemporada', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getUAutorizadoTemporadaOPT(): bool
    {
        return $this->getData('u_autorizadoTemporadaOPT');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setUAutorizadoTemporadaOPT(bool $value)
    {
        $this->setData('u_autorizadoTemporadaOPT', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumTempoOPT(): string
    {
        return $this->getData('u_groupNumTempoOPT');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumTempoOPT(string $value)
    {
        $this->setData('u_groupNumTempoOPT', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getGroupNum(): bool
    {
        return $this->getData('groupNum');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setGroupNum(bool $value)
    {
        $this->setData('groupNum', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountContado(): string
    {
        return $this->getData('u_discountContado');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountContado(string $value)
    {
        $this->setData('u_discountContado', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountContadoOptimus(): string
    {
        return $this->getData('u_discountContadoOptimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountContadoOptimus(string $value)
    {
        $this->setData('u_discountContadoOptimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountOptimus(): string
    {
        return $this->getData('u_discountOptimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountOptimus(string $value)
    {
        $this->setData('u_discountOptimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumOptimus(): string
    {
        return $this->getData('u_groupNumOptimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumOptimus(string $value)
    {
        $this->setData('u_groupNumOptimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountMilan(): string
    {
        return $this->getData('u_discountMilan');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountMilan(string $value)
    {
        $this->setData('u_discountMilan', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getDebitLine(): string
    {
        return $this->getData('debitLine');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDebitLine(string $value)
    {
        $this->setData('debitLine', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUFechaNacimiento(): string
    {
        return $this->getData('u_fechaNacimiento');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUFechaNacimiento(string $value)
    {
        $this->setData('u_fechaNacimiento', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUClienteActivoHasta(): string
    {
        return $this->getData('u_cliente_activo_hasta');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUClienteActivoHasta(string $value)
    {
        $this->setData('u_cliente_activo_hasta', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone2(): string
    {
        return $this->getData('phone2');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPhone2(string $value)
    {
        $this->setData('phone2', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getCellular(): string
    {
        return $this->getData('cellular');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCellular(string $value)
    {
        $this->setData('cellular', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getFrozen(): bool
    {
        return $this->getData('frozen');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setFrozen(bool $value)
    {
        $this->setData('frozen', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getSlpCode(): bool
    {
        return $this->getData('slpCode');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setSlpCode(bool $value)
    {
        $this->setData('slpCode', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getTerritory(): bool
    {
        return $this->getData('territory');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setTerritory(bool $value)
    {
        $this->setData('territory', $value);
        return $this;
    }
}
