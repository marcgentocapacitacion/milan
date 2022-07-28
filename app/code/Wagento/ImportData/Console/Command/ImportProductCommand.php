<?php

namespace Wagento\ImportData\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wagento\ImportData\Api\ProductInterface;

/**
 * Class ImportProductCommand
 */
class ImportProductCommand extends Command
{
    /**
     * @var ProductInterface
     */
    protected ProductInterface $productImport;

    /**
     * @param ProductInterface $productImport
     * @param string|null      $name
     */
    public function __construct(
        ProductInterface $productImport,
        string $name = null
    ) {
        parent::__construct($name);
        $this->productImport = $productImport;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('importData:product')
            ->setDescription(__('Import product data of the csv.'))
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                __('The file that will be imported.')
            );
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = $input->getArguments();
        if (!is_file($args['file'])) {
            throw new \Exception(__('File don\'t exist.'));
        }

        if (!is_readable($args['file'])) {
            throw new \Exception(__('File don\'t readable.'));
        }

        $this->productImport->setFile($args['file']);
        if ($this->productImport->import()) {
            echo __('Products imported with success.') . "\n";
        } else {
            echo __('Verify log because happen error.') . "\n";
        }
    }
}
