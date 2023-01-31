<?php

namespace Wagento\ImportData\Block\Product;

/**
 * Class Characteristic
 */
class Characteristic extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_ImportData::product/characteristic.phtml';

    /**
     * @var array|string[]
     */
    protected array $attributeCodeAllowedToShow = [
        'marco',
        'tenedor',
        'pista',
        'manillar',
        'espiga',
        'mangos',
        'sillin',
        'tensor',
        'descarrilador',
        'plato',
        'cadena',
        'frenos',
        'manzanas',
        'radios',
        'rin',
        'llantas',
        'neumaticos',
        'pedal',
        'peso',
        'peso_maximo'
    ];

    /**
     * @param string $code
     *
     * @return string
     */
    public function getCustomAttributesLabel(string $code): string
    {
        return $this->getProduct()->getAttributes()[$code]->getFrontendLabel() ?? '';
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function getCustomAttributesValue(string $code): string
    {
        return $this->getProduct()->getData($code) ?? '';
    }

    /**
     * @return array
     */
    public function getAttributeCodeAllowedToShow(): array
    {
        return $this->attributeCodeAllowedToShow;
    }
}
