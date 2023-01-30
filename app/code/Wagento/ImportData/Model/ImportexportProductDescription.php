<?php

namespace Wagento\ImportData\Model;

use Wagento\ImportData\Api\ImportexportProductDescriptionInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ImportexportProductDescription
 */
class ImportexportProductDescription extends AbstractModel implements ImportexportProductDescriptionInterface
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription::class);
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->getData(self::SKU);
    }

    /**
     * @param string $sku
     *
     * @return mixed
     */
    public function setSku(string $sku)
    {
        $this->setData(self::SKU , $sku);
        return $this;
    }

    /**
     * @return array
     */
    public function getDataDescription(): array
    {
        $data = $this->getData(self::DATA_DESCRIPTION);
        return \json_decode($data, true) ?? [];
    }

    /**
     * @param string $dataDescription
     *
     * @return $this|mixed
     */
    public function setDataDescription(string $dataDescription)
    {
        $this->setData(self::DATA_DESCRIPTION, $dataDescription);
        return $this;
    }
}
