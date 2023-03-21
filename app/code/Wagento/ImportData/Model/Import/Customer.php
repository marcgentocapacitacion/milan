<?php

namespace Wagento\ImportData\Model\Import;

use Magento\CustomerImportExport\Model\Import\Customer as CustomerImport;
use Magento\ImportExport\Model\ResourceModel\Import\Data as DataSourceModel;

/**
 * Class Customer
 */
class Customer extends CustomerImport
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
