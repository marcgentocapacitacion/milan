<?php
namespace ITM\MagB1\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ClearUrlRewrites extends Command
{

    protected $helper;

    private $objectManager;

    //private $eventManager;

    private  $resource;

    protected function configure()
    {
        $this->setName('magb1:clear-url-rewrites'); // php bin/magento magb1:clear-url-rewrites
        $this->setDescription('MagB1 : Clear URL Rewrites');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVariables();
        $output->writeln("MagB1 : Clear URL Rewrites : Start");
        $this->ClearUrlRewrites($output);
        $output->writeln("MagB1 : Clear URL Rewrites : Complete");
    }

    public function setVariables() {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$this->eventManager = $this->objectManager->get('\Magento\Framework\Event\Manager');
        $this->state = $this->objectManager->get('\Magento\Framework\App\State');
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $this->helper = $this->objectManager->get('\ITM\MagB1\Helper\Data');
        $this->resource = $this->objectManager->get('\Magento\Framework\App\ResourceConnection');
    }

    public function ClearUrlRewrites($output) {

        $url_rewrite_tables =  $this->resource->getTableName('url_rewrite');
        $catalog_product_entity =  $this->resource->getTableName('catalog_product_entity');

        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $sub_query = $connection->select()->from([
            'product_entity' => $catalog_product_entity
        ], [
            'entity_id'
        ]);
        $bind[':subquery'] = $sub_query;

        $select_query = $connection->select()->from([
            'url_entity' => $url_rewrite_tables
        ], [
            'url_rewrite_id','entity_id	'
        ]);
        $select_query->where('url_entity.entity_type = "product"');
        $select_query->where('url_entity.entity_id NOT IN  ('.$sub_query.')');
        $result = $connection->fetchAll($select_query);
        $output->writeln("MagB1 :  ".count($result)." URL(s) found");
    }
}