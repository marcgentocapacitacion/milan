<?php
namespace ITM\Pricing\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class IndexCustomerTables extends Command
{

    /**
     * Group ID argument
     */
    const GROUPID_ARGUMENT = 'group_id';
    
    protected $_objectManager;

    protected $_resource;

    protected $_connection;
    
    protected $_output;
    
    protected $_messageManager;

    protected function configure()
    {
        $this->setName('pricing:index-customer-tables'); // php bin/magento pricing:index-customer-tables
        $this->setDescription('Pricing : Reindex Customer Tables');
    }

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVariables();
        $this->_output = $output;
        $this->executeCustomerTables();
        
    }
    private function executeCustomerTables(){
        $this->showOutput("Preparing customer tables : Start");
        $this->createCustomerTables();
        $this->showOutput("Preparing customer tables : Complete");
        $this->showOutput("Reindex customer tables : Start");
        $this->indexCustomerTables();
        $this->showOutput("Reindex customer tables : Complete");
    }
    private function indexCustomerTables()
    {
        $main_table =  $this->_resource->getTableName('itm_pricing_customerprice');
        $customer_index_tables =  $this->_resource->getTableName('itm_pricing_customer_index_tables');
        
        $select_query = $this->_connection->select()->from([
            'entity' => $customer_index_tables
        ], [
            'customer_id','table_name'
        ]);
        $result = $this->_connection->fetchAll($select_query);
        $copy_data_to_table=[];
        
        foreach($result as $row) {
            $child_table = $row["table_name"];
            $customer_id = $row["customer_id"];
            
            if(!in_array($child_table,$copy_data_to_table)) {
                $copy_data_to_table[] = $child_table;
                $this->showOutput("Copy data to table ".$child_table);
            }
            $query = "INSERT INTO $child_table (id,customer_id,website_id,sku,qty,start_date,end_date,uom_entry,price,discount,status)  (SELECT id,customer_id,website_id,sku,qty,start_date,end_date,uom_entry,price,discount,status  FROM $main_table    WHERE `customer_id` = $customer_id)";
            $this->_connection->query($query);
        }
    }
    private function createCustomerTables()
    {
        $table_name = $this->_resource->getTableName('itm_pricing_customerprice');
        $customer_index_tables =  $this->_resource->getTableName('itm_pricing_customer_index_tables');
        $select_query = $this->_connection->select()->from([
            'entity' => $table_name
        ], [
            'DISTINCT(customer_id)','count(*) as total'
        ]);
        
        $select_query->group([
            'customer_id'
        ]);
        
        $result = $this->_connection->fetchAll($select_query);
        
        
        
        $count = 0;
        $table = 0;
        $tables = [];
        $i = 0;
        
        foreach($result as $row) {
            $count += $row["total"];
            $child_table_name = $this->_resource->getTableName("itm_pricing_customerprice_".$i);
            $tables[$child_table_name][] = ["customer_id"=>$row["customer_id"], "total"=>$count];
            if($count>50000) {
                $i++;
                $count = 0;
            }  
        }
        $rows_total = 0;
        
        $this->truncateCustomerTable($customer_index_tables);
        
        foreach ($tables as $table_name => $table) {
            $child_table_name = $table_name;
            if ($this->_connection->isTableExists($child_table_name) != true) {
                $this->createCustomerTable($child_table_name);
            }else {
                $this->truncateCustomerTable($child_table_name);
            }
            $data = [];
            foreach ($table as $customer) {
                $data[] =  [0, $customer["customer_id"], $table_name];//$this->showOutput($customer["customer_id"]);
            }
            $columns = ['entity_id', 'customer_id', 'table_name'];
            $this->_connection->insertArray($customer_index_tables, $columns, $data);
        }
    }

    public function createCustomerTable($child_table_name)
    {
        // $name = "itm_pricing_groupprice_".$group_id;
        $name = $child_table_name;
        $table_name = $this->_resource->getTableName($name);
        
        $connection = $this->_connection;
        
        $table = $connection->newTable($table_name)
            ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id')
            ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
            [
                'nullable' => false
            ], 'Customer ID')
            ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
            [
                'nullable' => false
            ], 'Website ID')
            ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64',
            [
                'nullable' => false
            ], 'SKU')
            ->addColumn('qty', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
            [
                'nullable' => false,
                'default' => '0.0000'
            ], 'Quantity')
            ->addColumn('start_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Start Date')
            ->addColumn('end_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'End Date')
            ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25',
            [
                'nullable' => false
            ], 'UOM Entry')
            ->addColumn('price', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
            [
                'nullable' => false,
                'default' => '0.0000'
            ], 'Price')
            ->addColumn('discount', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
                [
                    'default' => '0.0000'
                ], 'Discount')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, '1', [], 'Status')
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'customer_id'
                ]), [
                'customer_id'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'website_id'
                ]), [
                'website_id'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'sku'
                ]), [
                'sku'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'qty'
                ]), [
                'qty'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'start_date'
                ]), [
                'start_date'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'end_date'
                ]), [
                'end_date'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'uom_entry'
                ]), [
                'uom_entry'
            ])
            ->addIndex(
            $this->_resource->getIdxName($table_name,
                [
                    'status'
                ]), [
                'status'
            ])
            ->setComment('ITM Pricing Customer Index Price ' . $table_name);
            $this->_connection->createTable($table);
    }
    public function truncateCustomerTable($child_table_name) {
        $truncate_query = "TRUNCATE TABLE $child_table_name ";
        $this->_connection->query($truncate_query);
    }
    public function setVariables() {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_resource = $this->_objectManager->get('\Magento\Framework\App\ResourceConnection');
        $this->_connection = $this->_resource->getConnection(
                \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->_messageManager = $this->_objectManager->get('\Magento\Framework\Message\ManagerInterface');
    }
    
    public function DoIndexCustomerTables(){
        $this->setVariables();
        $this->executeCustomerTables();
    }
    
    private function showOutput($message) {
        if($this->_output) {
            $this->_output->writeln($message);
        } else {
            $this->_messageManager->addSuccess($message);
        }
    }
   
}