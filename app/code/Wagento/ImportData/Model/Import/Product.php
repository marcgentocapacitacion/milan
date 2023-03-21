<?php

namespace Wagento\ImportData\Model\Import;

use Magento\ImportExport\Model\ResourceModel\Import\Data as DataSourceModel;

/**
 * Class Product
 */
class Product extends \Magento\CatalogImportExport\Model\Import\Product
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function importData()
    {
        return $this->_importData();
    }

    /**
     * @return \Magento\ImportExport\Model\ResourceModel\Import\Data
     */
    public function getDataSourceModel() : DataSourceModel
    {
        return $this->_dataSourceModel;
    }
}
