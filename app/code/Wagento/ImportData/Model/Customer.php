<?php

namespace Wagento\ImportData\Model;

use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Model\Entity\Attribute\Source\Table;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;
use Wagento\ImportData\Api\CustomerInterface;
use Wagento\ImportData\Model\Import\Customer as CustomerImport;
use Magento\ImportExport\Model\Import\Source\CsvFactory;

/**
 * Class Customer
 */
class Customer extends AbstractImport implements CustomerInterface
{
    /**
     * @var CustomerImport
     */
    protected CustomerImport $customerImport;

    /**
     * @param CustomerImport      $customerImport
     * @param CsvFactory          $csvFactory
     * @param AttributeRepository $attributeRepository
     * @param Table               $attributeTable
     * @param LoggerInterface     $logger
     * @param State               $state
     * @param Filesystem          $filesystem
     * @param array               $data
     */
    public function __construct(
        CustomerImport $customerImport,
        CsvFactory $csvFactory,
        AttributeRepository $attributeRepository,
        Table $attributeTable,
        LoggerInterface $logger,
        State $state,
        Filesystem $filesystem,
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
        $this->customerImport = $customerImport;
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
                    "Client: {$row['email']}" . " Exception: {$e->getMessage()}"
                );
            }
        }

        try {
            $this->customerImport->getErrorAggregator()->initValidationStrategy(
                'validation-skip-errors',
                1000
            );

            $this->customerImport->getDataSourceModel()->saveBunch('customer', 'add_update', $csvData);
            $this->customerImport->importData();
            $this->customerImport->getDataSourceModel()->cleanBunches();
            $errors = $this->customerImport->getErrorAggregator()->getAllErrors();
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
        $row['_website'] = $row['website'];
        return $row;
    }
}
