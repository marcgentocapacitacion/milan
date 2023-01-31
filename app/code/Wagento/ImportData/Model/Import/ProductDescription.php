<?php

/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

namespace Wagento\ImportData\Model\Import;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription as ResourceModelImportexportProductDescription;
use Wagento\ImportData\Model\ImportexportProductDescription;
use Wagento\ImportData\Api\ImportexportProductDescriptionInterfaceFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

/**
 * Class ProductDescription
 */
class ProductDescription extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    /**
     * @const string
     */
    public const COL_TAB = 'tab';

    /**
     * @const string
     */
    public const COL_SKU = 'sku';

    /**
     * @const string
     */
    public const COL_TYPE = 'type';

    /**
     * @const string
     */
    public const COL_LINE = 'line';

    /**
     * @const string
     */
    public const COL_DATA = 'data';

    /**
     * Valid column names.
     *
     * @array
     */
    protected $validColumnNames = [
        self::COL_TAB,
        self::COL_SKU,
        self::COL_LINE,
        self::COL_TYPE
    ];

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var array
     */
    protected array $productModel = [];

    /**
     * @var ResourceModelImportexportProductDescription
     */
    protected ResourceModelImportexportProductDescription $importexportProductDescriptionResource;

    /**
     * @var ImportexportProductDescriptionInterfaceFactory
     */
    protected ImportexportProductDescriptionInterfaceFactory $importexportProductDescriptionFactory;

    /**
     * @var array
     */
    protected $excludeLine = [];

    /**
     * @param \Magento\Framework\Json\Helper\Data                   $jsonHelper
     * @param \Magento\ImportExport\Helper\Data                     $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param \Magento\Eav\Model\Config                             $config
     * @param ResourceConnection                                    $resource
     * @param \Magento\ImportExport\Model\ResourceModel\Helper      $resourceHelper
     * @param \Magento\Framework\Stdlib\StringUtils                 $string
     * @param ProcessingErrorAggregatorInterface                    $errorAggregator
     * @param ProductRepositoryInterface                            $productRepository
     * @param ResourceModelImportexportProductDescription           $importexportProductDescriptionResource
     * @param ImportexportProductDescriptionInterfaceFactory        $importexportProductDescriptionFactory
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Eav\Model\Config $config,
        ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        ProductRepositoryInterface $productRepository,
        ResourceModelImportexportProductDescription $importexportProductDescriptionResource,
        ImportexportProductDescriptionInterfaceFactory $importexportProductDescriptionFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->string = $string;
        $this->errorAggregator = $errorAggregator;
        $this->_dataSourceModel = $importData;
        $this->productRepository = $productRepository;
        $this->needColumnCheck = false;
        $this->importexportProductDescriptionResource = $importexportProductDescriptionResource;
        $this->importexportProductDescriptionFactory = $importexportProductDescriptionFactory;
    }

    /**
     * @inheritDoc
     */
    protected function _importData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = [];
            foreach ($bunch as $line => $rowData) {
                if (isset($this->excludeLine[$line])) {
                    continue;
                }
                if ($rowData[self::COL_TYPE] == 'table') {
                    $rowData['dataColumns'] = $this->prepareDataForTable($bunch, $line);
                }
                $data[$rowData[self::COL_SKU]][$rowData[self::COL_TAB]][$rowData[self::COL_LINE]][] = $rowData;
            }

            foreach ($data as $sku => $row) {
                /** @var ImportexportProductDescription $model */
                $model = $this->importexportProductDescriptionFactory->create();
                $this->importexportProductDescriptionResource->load(
                    $model,
                    $sku,
                    'sku'
                );

                $model->setSku($sku);
                $model->setDataDescription(\json_encode($row));
                $this->importexportProductDescriptionResource->save($model);
            }
        }
        return true;
    }

    /**
     * @param array $bunch
     * @param       $line
     *
     * @return array
     */
    protected function prepareDataForTable(array &$bunch, $line): array
    {
        $firstTime = true;
        $dataTable = [];
        $count = count($bunch);
        for($i = $line; $i <= $count;$i++) {
            if (!isset($bunch[$i])) {
                break;
            }
            if ($bunch[$i]['type'] != 'table') {
                break;
            }
            $stop = false;
            $ii = 1;
            $dataColumns = [];
            while (!$stop) {
                if (isset($bunch[$i]['data'.$ii]) && $bunch[$i]['data'.$ii]) {
                    $dataColumns[] = $bunch[$i]['data' . $ii];
                } else {
                    $stop = true;
                }
                $ii++;
            }
            $dataTable[] = $dataColumns;
            if (!$firstTime) {
                $this->excludeLine[$i] = $i;
            }
            $firstTime = false;
        }
        return $dataTable;
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeCode()
    {
        return 'catalog_product_description';
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function isSkuExist(string $sku): bool
    {
        try {
            if (!isset($this->productModel[$sku])) {
                $this->productModel[$sku] = $this->productRepository->get($sku);
            }
            if ($this->productModel[$sku]->getId()) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add row as skipped
     *
     * @param int $rowNum
     * @param string $errorCode Error code or simply column name
     * @param string $errorLevel error level
     * @param string|null $colName optional column name
     * @return $this
     */
    private function skipRow(
        $rowNum,
        string $errorCode,
        string $errorLevel = ProcessingError::ERROR_LEVEL_NOT_CRITICAL,
        $colName = null
    ): self {
        $this->addRowError($errorCode, $rowNum, $colName, null, $errorLevel);
        $this->getErrorAggregator()->addRowToSkip($rowNum);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;
        $sku = $rowData[self::COL_SKU] ?? false;
        $line = $rowData[self::COL_LINE] ?? false;
        $tab = $rowData[self::COL_TAB] ?? false;
        $type = $rowData[self::COL_TYPE] ?? false;
        $data = $rowData[self::COL_DATA . '1'] ?? false;
        $errorLevel = ProcessingError::ERROR_LEVEL_CRITICAL;
        if (!$sku) {
            $this->skipRow($rowNum, __('sku is empty'), $errorLevel);
        }

        if (!$this->isSkuExist($sku)) {
            $this->skipRow($rowNum, __('sku not found'), $errorLevel);
        }

        if (!$line) {
            $this->skipRow($rowNum, __('like is empty'), $errorLevel);
        }

        if (!$tab) {
            $this->skipRow($rowNum, __('tab is empty'), $errorLevel);
        }

        if (!$type) {
            $this->skipRow($rowNum, __('type is empty'), $errorLevel);
        }

        if (!$data) {
            $this->skipRow($rowNum, __('data is empty'), $errorLevel);
        }
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }
}
