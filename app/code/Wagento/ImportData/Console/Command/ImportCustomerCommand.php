<?php

namespace Wagento\ImportData\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wagento\ImportData\Api\CustomerInterface;

/**
 * Class ImportCustomerCommand
 */
class ImportCustomerCommand extends Command
{
    /**
     * @var CustomerInterface
     */
    protected CustomerInterface $customerImport;

    /**
     * @param CustomerInterface $customerImport
     */
    public function __construct(
        CustomerInterface $customerImport,
        string $name = null
    ) {
        parent::__construct($name);
        $this->customerImport = $customerImport;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('importData:customer')
            ->setDescription(__('Import customer data of the csv.'))
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

        $this->customerImport->setFile($args['file']);
        if ($this->customerImport->import()) {
            echo __('Customers imported with success.') . "\n";
        } else {
            echo __('Verify log because happen error.') . "\n";
        }
    }
}
