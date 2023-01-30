<?php

namespace Wagento\ImportData\Block\Adminhtml\Import;

use Wagento\ImportData\Api\ImportexportProductDescriptionInterface;

/**
 * Class MountProductDescription
 */
class MountProductDescription extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_ImportData::import/product_description.phtml';

    /**
     * @var ImportexportProductDescriptionInterface|null
     */
    protected ?ImportexportProductDescriptionInterface $queueProductDescription = null;

    /**
     * @var array
     */
    protected array $headerData = [];

    /**
     * @var array|string[]
     */
    protected array $dataPbStyle = [
        'General' => 'ASODEOG',
        'Especificaciones' => 'GVL5KIO',
        'Beneficios' => 'UJTDMQA',
        'Garantía' => 'A8VIKLC',
        'Datos Adicionales' => 'TNCYQ2C'
    ];

    /**
     * @return array
     */
    public function getHeaderTabs(): array
    {
        if ($this->headerData) {
            return $this->headerData;
        }

        if (!$this->queueProductDescription) {
            return [];
        }

        if (!($data = $this->queueProductDescription->getDataDescription())) {
            return [];
        }

        $this->headerData = array_keys($data);
        return $this->headerData;
    }

    /**
     * @param ImportexportProductDescriptionInterface $item
     *
     * @return $this
     */
    public function setQueueProductDescription(ImportexportProductDescriptionInterface $item)
    {
        $this->queueProductDescription = $item;
        return $this;
    }
}
