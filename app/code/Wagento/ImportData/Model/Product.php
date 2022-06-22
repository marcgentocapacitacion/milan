<?php

namespace Wagento\ImportData\Model;

use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Model\Entity\Attribute\Source\Table;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem;
use Magento\ImportExport\Model\Import\Source\Csv;
use Magento\ImportExport\Model\Import\Source\CsvFactory;
use Psr\Log\LoggerInterface;
use Wagento\ImportData\Api\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture;
use Wagento\ImportData\Model\Import\Product as ProductImport;

/**
 * Class Product
 */
class Product extends DataObject implements ProductInterface
{
    /**
     * @var CsvFactory
     */
    protected CsvFactory $csvFactory;

    /**
     * @var AttributeRepository
     */
    protected AttributeRepository $attributeRepository;

    /**
     * @var Table
     */
    protected Table $attributeTable;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var State
     */
    protected State $state;

    /**
     * @var string|null
     */
    protected ?string $file;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var Countryofmanufacture
     */
    protected Countryofmanufacture $countryofmanufacture;

    /**
     * @var array
     */
    protected array $staticcountryofmanufacture = [];

    /**
     * @var ProductImport
     */
    protected ProductImport $productImport;

    /**
     * @param CsvFactory           $csvFactory
     * @param AttributeRepository  $attributeRepository
     * @param Table                $attributeTable
     * @param LoggerInterface      $logger
     * @param State                $state
     * @param Filesystem           $filesystem
     * @param Countryofmanufacture $countryofmanufacture
     * @param array                $data
     */
    public function __construct(
        CsvFactory $csvFactory,
        AttributeRepository $attributeRepository,
        Table $attributeTable,
        LoggerInterface $logger,
        State $state,
        Filesystem $filesystem,
        Countryofmanufacture $countryofmanufacture,
        ProductImport $productImport,
        array $data = []
    ) {
        parent::__construct($data);
        $this->csvFactory = $csvFactory;
        $this->attributeRepository = $attributeRepository;
        $this->attributeTable = $attributeTable;
        $this->logger = $logger;
        $this->state = $state;
        $this->filesystem = $filesystem;
        $this->countryofmanufacture = $countryofmanufacture;
        $this->productImport = $productImport;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function import(): bool
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        $file = $this->getFile();
        $csvData = [];
        foreach ($file as $row) {
            try {
                $csvData[] = $this->prepareData($row);
            } catch (\Exception $e) {
                $this->logger->error(
                    "Product SKU: {$row['sku']}" . " Exception: {$e->getMessage()}"
                );
            }
        }

        try {
            $this->productImport->getErrorAggregator()->initValidationStrategy(
                'validation-skip-errors',
                1000
            );
            $this->productImport->getDataSourceModel()->saveBunch('catalog_product', 'add_update', $csvData);
            $this->productImport->importData();
            $this->productImport->getDataSourceModel()->cleanBunches();
            $errors = $this->productImport->getErrorAggregator()->getAllErrors();
            if ($errors) {
                $errorMessage = '----------------------------------------------------------------';
                foreach ($errors as $error) {
                    $errorMessage .= "Line: " . $error->getRowNumber() . "\n";
                    $errorMessage .= "Column: " . $error->getColumnName() . "\n";
                    $errorMessage .= "Message: " . $error->getErrorMessage() . "\n";
                }
                $errorMessage .= '----------------------------------------------------------------';
                throw new \Exception($errorMessage);
            }
            return true;
        } catch (\Exception $e) {
            $this->logger->error(
                "Errors: {$e->getMessage()}"
            );
            return false;
        }
    }

    /**
     * @return Csv
     * @throws \Exception
     */
    protected function getFile(): Csv
    {
        if (!$this->file) {
            throw new \Exception(__('File is required.'));
        }

        return $this->csvFactory->create([
            'file' => $this->file,
            'directory' => $this->filesystem->getDirectoryRead(DirectoryList::ROOT),
        ]);
    }

    /**
     * @param string $file
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function prepareData(array $row): array
    {
        foreach ($row as $field => $value) {
            if (!$value) {
                $row[$field] = null;
                continue;
            }
            $row[$field] = $value;
        }
        $row['product_type'] = $row['product_type'] ?? 'simple';
        $row['updated_at'] = date('Y-m-d H:i:s');
        $row['_attribute_set'] = $row['attribute_set_code'];
        $row['weight_type'] = $row['bundle_weight_type'];
        $row['price_type'] = $row['bundle_price_type'];
        $row['sku_type'] = $row['bundle_sku_type'];
        $row['shipment_type'] = $row['bundle_shipment_type'];
        $row['price_view'] = $row['bundle_price_view'];
        $row['_product_websites'] = $row['product_websites'];
        $row['country_of_manufacture'] = $this->getCountryOfManufacture($row['country_of_manufacture']);

        if (isset($row['base_image']) && $row['base_image']) {
            $row['base_image'] = $this->getImagePath($row['base_image']);
        }

        if (isset($row['small_image']) && $row['small_image']) {
            $row['small_image'] = $this->getImagePath($row['small_image']);
        }

        if (isset($row['thumbnail_image']) && $row['thumbnail_image']) {
            $row['thumbnail_image'] = $this->getImagePath($row['thumbnail_image']);
        }

        if (isset($row['swatch_image']) && $row['swatch_image']) {
            $row['swatch_image'] = $this->getImagePath($row['swatch_image']);
        }
        return $row;
    }

    /**
     * Get path image
     *
     * @param string $path
     *
     * @return string
     */
    public function getImagePath(string $path): ?string
    {
        $imagePath = DIRECTORY_SEPARATOR . 'import';
        $imagePath .= DIRECTORY_SEPARATOR . 'catalog';
        $imagePath .= DIRECTORY_SEPARATOR . 'product';
        $productMediaPath = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        if ($productMediaPath->isExist($imagePath)) {
            return $productMediaPath->getAbsolutePath($imagePath);
        }

        return null;
    }

    /**
     * @param $value
     *
     * @return string|null
     */
    protected function getCountryOfManufacture($value): ?string
    {
        if (!$value) {
            return null;
        }

        if (!$this->staticcountryofmanufacture) {
            $countryOfManufacture = $this->countryofmanufacture->getAllOptions();
            foreach ($countryOfManufacture as $item) {
                $this->staticcountryofmanufacture[$item['label']] = $item['value'];
            }
        }
        return $this->staticcountryofmanufacture[$value] ?? null;
    }
}
