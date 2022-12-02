<?php
namespace ITM\Pricing\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class IndexFinalPriceTables extends Command
{


    const WEBSITEID_ARGUMENT = 'website_id';
    /**
     * Customer Group ID argument
     */
    const CUSTOMER_GROUPID_ARGUMENT = 'customer_group_id';
    const UOM_ARGUMENT = 'uom_entry';

    protected $_objectManager;

    protected $_resource;

    protected $_connection;

    protected $_output;

    protected $_messageManager;

    private $state;

    private $helper;

    protected function configure()
    {
        $this->setName('pricing:index-final-price-tables'); // php bin/magento pricing:index-group-tables {1}
        $this->setDescription('Pricing : Reindex Final Price Tables');
        $this->setDefinition([
            new InputArgument(
                self::WEBSITEID_ARGUMENT,
                InputArgument::REQUIRED,
                'website_id'
            ),
            new InputArgument(
                self::CUSTOMER_GROUPID_ARGUMENT,
                InputArgument::REQUIRED,
                'customer_group_id'
            ),
            new InputArgument(
                self::UOM_ARGUMENT,
                InputArgument::OPTIONAL,
                'uom_entry'
            ),
        ]);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVariables();
        $this->_output = $output;

        $customer_group_id = $input->getArgument(self::CUSTOMER_GROUPID_ARGUMENT);
        $website_id = $input->getArgument(self::WEBSITEID_ARGUMENT);
        $uom_entry = $input->getArgument(self::UOM_ARGUMENT);

        $this->showOutput("Website ID = ".$website_id);
        $this->showOutput("Group ID = ".$customer_group_id);
        if(empty($uom_entry)) {
            $uom_entry = "-1";
        }
        $this->showOutput("UOM Entry(default -1) = ".$uom_entry);


        // Truncate table
        $table_name = $this->_resource->getTableName('itm_pricing_finalprice');
        $truncate_query = "TRUNCATE TABLE `$table_name` ";
        $this->_connection->query($truncate_query);
        $this->showOutput($truncate_query);
        $productCollectionFactory =  $this->_objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $productCollectionFactory->create();
        $collection->addAttributeToFilter("type_id","simple");
        //$collection->setCurPage(1);
        //$collection->setPageSize(1);
        $this->showOutput("Start");
        foreach ($collection->getItems() as $product){
            $params = [
                "website_id"=>$website_id,
                "group_id"=>$customer_group_id,
                "customer_group_id"=>$customer_group_id,
                "customer_id"=>0,
                "sku"=>$product->getSku(),
                "uom_entry"=>$uom_entry,
                "qty"=>1,
                "product"=>$product

            ];
            $price = $this->helper->getFinalPrice($params);


            $query = "INSERT INTO `itm_pricing_finalprice` (`website_id`, `group_id`, `customer_group_id`, `customer_id`, `sku`, `uom_entry`, `qty`, `price`, `status`)
          VALUES ('".$params['website_id']."', '".$params['group_id']."', '".$params['customer_group_id']."', ".$params['customer_id'].", '".$params['sku']."', '".$params['uom_entry']."', '".$params['qty']."', '$price', '1');";
            $this->_connection->query($query);
        }
        $this->showOutput("Finish");


    }

    public function setVariables() {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_resource = $this->_objectManager->get('\Magento\Framework\App\ResourceConnection');
        $this->_connection = $this->_resource->getConnection(
            \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->_messageManager = $this->_objectManager->get('\Magento\Framework\Message\ManagerInterface');
        $this->state = $this->_objectManager->get('\Magento\Framework\App\State');
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $this->helper = $this->_objectManager->get('\ITM\Pricing\Helper\Data');

    }



    private function showOutput($message) {
        if($this->_output) {
            $this->_output->writeln($message);
        } else {
            $this->_messageManager->addSuccess($message);
        }
    }

}