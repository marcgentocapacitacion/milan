<?php

namespace Wagento\ImportData\Model;

use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Model\Entity\Attribute\Source\Table;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem;
use Magento\ImportExport\Model\Import\Source\CsvFactory;
use Psr\Log\LoggerInterface;
use Wagento\ImportData\Api\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture;
use Wagento\ImportData\Model\Import\Product as ProductImport;

/**
 * Class Product
 */
class Product extends AbstractImport implements ProductInterface
{
    /**
     * @var string|null
     */
    protected ?string $file;

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
     * @param ProductImport        $productImport
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
        parent::__construct(
            $csvFactory,
            $attributeRepository,
            $attributeTable,
            $logger,
            $state,
            $filesystem,
            $data
        );
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
            if ($errors && ($formatError = $this->formatMessageError($errors))) {
                throw new \Exception($formatError);
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
        $productMediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        if ($productMediaPath->isExist('import/' . $path)) {
            return $path;
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
