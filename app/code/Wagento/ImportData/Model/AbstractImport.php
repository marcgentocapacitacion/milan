<?php

namespace Wagento\ImportData\Model;

use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Model\Entity\Attribute\Source\Table;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem;
use Magento\ImportExport\Model\Import\Source\Csv;
use Psr\Log\LoggerInterface;
use Magento\ImportExport\Model\Import\Source\CsvFactory;

/**
 * Class AbstractImport
 */
class AbstractImport extends DataObject
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
     * @param CsvFactory          $csvFactory
     * @param AttributeRepository $attributeRepository
     * @param Table               $attributeTable
     * @param LoggerInterface     $logger
     * @param State               $state
     * @param Filesystem          $filesystem
     * @param array               $data
     */
    public function __construct(
        CsvFactory $csvFactory,
        AttributeRepository $attributeRepository,
        Table $attributeTable,
        LoggerInterface $logger,
        State $state,
        Filesystem $filesystem,
        array $data = []
    ) {
        parent::__construct($data);
        $this->csvFactory = $csvFactory;
        $this->attributeRepository = $attributeRepository;
        $this->attributeTable = $attributeTable;
        $this->logger = $logger;
        $this->state = $state;
        $this->filesystem = $filesystem;
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
     * @param array $errors
     *
     * @return string
     */
    protected function formatMessageError(array $errors): ?string
    {
        $errorsArray = [];
        foreach ($errors as $error) {
            if ($error->getRowNumber() == 0) {
                //continue;
            }
            $errorsArray[] .= "Line: " . $error->getRowNumber() . "\n"
                . "Column: " . $error->getColumnName() . "\n"
                . "Message: " . $error->getErrorMessage() . "\n";
        }
        if (!$errorsArray) {
            return null;
        }
        $errorMessage = "----------------------------------------------------------------\n";
        $errorMessage .= implode($errorsArray);
        $errorMessage .= "----------------------------------------------------------------\n";
        return $errorMessage;
    }
}
