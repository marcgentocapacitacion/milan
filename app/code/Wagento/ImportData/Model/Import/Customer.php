<?php

namespace Wagento\ImportData\Model\Import;

use Magento\CustomerImportExport\Model\Import\Customer as CustomerImport;

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
    public function getDataSourceModel()
    {
        return $this->_dataSourceModel;
    }
}
