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
     * @return bool
     */
    public function getUAutorizadoTemporada(): bool
    {
        return $this->getData('u_autorizado_temporada');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setUAutorizadoTemporada(bool $value)
    {
        $this->setData('u_autorizado_temporada', $value);
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
        return $this->getData('u_inicio_temporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUInicioTemporada(string $value)
    {
        $this->setData('u_inicio_temporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUFinTemporada(): string
    {
        return $this->getData('u_fin_temporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUFinTemporada(string $value)
    {
        $this->setData('u_fin_temporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumTemporada(): string
    {
        return $this->getData('u_group_num_temporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumTemporada(string $value)
    {
        $this->setData('u_group_num_temporada', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountTemporada(): string
    {
        return $this->getData('u_discount_temporada');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountTemporada(string $value)
    {
        $this->setData('u_discount_temporada', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getUAutorizadoTemporadaOPT(): bool
    {
        return $this->getData('u_autorizado_temporada_o_p_t');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setUAutorizadoTemporadaOPT(bool $value)
    {
        $this->setData('u_autorizado_temporada_o_p_t', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumTempoOPT(): string
    {
        return $this->getData('u_group_num_tempo_o_p_t');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumTempoOPT(string $value)
    {
        $this->setData('u_group_num_tempo_o_p_t', $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function getGroupNum(): bool
    {
        return $this->getData('group_num');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setGroupNum(bool $value)
    {
        $this->setData('group_num', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountContado(): string
    {
        return $this->getData('u_discount_contado');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountContado(string $value)
    {
        $this->setData('u_discount_contado', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountContadoOptimus(): string
    {
        return $this->getData('u_discount_contado_optimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountContadoOptimus(string $value)
    {
        $this->setData('u_discount_contado_optimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountOptimus(): string
    {
        return $this->getData('u_discount_optimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountOptimus(string $value)
    {
        $this->setData('u_discount_optimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUGroupNumOptimus(): string
    {
        return $this->getData('u_group_num_optimus');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUGroupNumOptimus(string $value)
    {
        $this->setData('u_group_num_optimus', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUDiscountMilan(): string
    {
        return $this->getData('u_discount_milan');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUDiscountMilan(string $value)
    {
        $this->setData('u_discount_milan', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getDebitLine(): string
    {
        return $this->getData('debit_line');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDebitLine(string $value)
    {
        $this->setData('debit_line', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getUFechaNacimiento(): string
    {
        return $this->getData('u_fecha_nacimiento');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUFechaNacimiento(string $value)
    {
        $this->setData('u_fecha_nacimiento', $value);
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
        return $this->getData('slp_code');
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setSlpCode(bool $value)
    {
        $this->setData('slp_code', $value);
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
