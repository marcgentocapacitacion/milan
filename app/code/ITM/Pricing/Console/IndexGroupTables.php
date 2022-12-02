<?php
namespace ITM\Pricing\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class IndexGroupTables extends Command
{

    /**
     * Group ID argument
     */
    const GROUPID_ARGUMENT = 'group_id';
    
    protected $_objectManager;

    protected $_resource;

    protected $_connection;
    
    protected $_output;

    protected $_pHelper;
    
    protected $_messageManager;

    protected function configure()
    {
        $this->setName('pricing:index-group-tables'); // php bin/magento pricing:index-group-tables {1}
        $this->setDescription('Pricing : Reindex Group Tables');
        $this->setDefinition([
            new InputArgument(
                self::GROUPID_ARGUMENT,
                InputArgument::OPTIONAL,
                'group_id'
                ),
        ]);
    }

    private function getGroupID($group_id)
    {
      return $this->_pHelper->getGroupID($group_id);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVariables();
        
        $group_id = $this->getGroupID($input->getArgument(self::GROUPID_ARGUMENT));

        $this->_output = $output;
        
        if (!is_null($group_id)) {
            $main_table = $this->_resource->getTableName('itm_pricing_groupprice');
            $child_table_name = $main_table . "_".$group_id ;
            $this->indexGroupTable($main_table, $child_table_name, $group_id, $output);
        } else {
            $this->executeGroupTables();
        }
    }

    public function setVariables() {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_resource = $this->_objectManager->get('\Magento\Framework\App\ResourceConnection');
        $this->_connection = $this->_resource->getConnection(
                \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->_messageManager = $this->_objectManager->get('\Magento\Framework\Message\ManagerInterface');
        $this->_pHelper = $this->_objectManager->get('\ITM\Pricing\Helper\Data');
    }
   
    public function DoIndexGroupTables(){
        $this->setVariables();
        $this->executeGroupTables();
    }
    
    private function showOutput($message) {
        if($this->_output) {
            $this->_output->writeln($message);
        } else {
            $this->_messageManager->addSuccess($message);
        }
    }
    private function executeGroupTables(){
        $table_name = $this->_resource->getTableName('itm_pricing_groupprice');
        
        $select_group = $this->_connection->select()->from([
            'entity' => $table_name
        ], [
            'group_id'
        ]);
        $select_group->where('entity.group_id >= -1');
        $select_group->group([
            'group_id'
        ]);
        
        $result = $this->_connection->fetchAll($select_group);
        
        
        $this->showOutput("Checking group tables");
        $this->createGroupTables($result);
        $this->showOutput("Checking group tables : Complete");
        $this->showOutput("Start Reindex group tables");
        $this->indexGroupTables($result);
        $this->showOutput("Reindex group tables : Complete");
    }

    private function createGroupTables($result)
    {   
        $table_name = $this->_resource->getTableName('itm_pricing_groupprice');
        
        // $params = ["group_id",$table_name];
        // array_walk($result, [$this,'addIdToItemArray'],$params);
        $child_table_name = $table_name . "_0" ;
        if ($this->_connection->isTableExists($child_table_name) != true) {
            $this->createGroupTable($child_table_name, "0");
        }
        
        foreach ($result as $item) {
            if($item["group_id"] == "0") continue;
            $group_id = $this->getGroupID($item["group_id"]);
            $child_table_name = $table_name . "_" . $group_id;
            if ($this->_connection->isTableExists($child_table_name) != true) {
                $this->createGroupTable($child_table_name, $group_id);
            }
        }
    }

    private function createGroupTable($child_table_name, $group_id)
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
            ->addColumn('group_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, 
                [
                    'nullable' => false
                ], 'Group ID')
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
            ->
        addIndex($this->_resource->getIdxName($table_name, [
            'group_id'
        ]), [
            'group_id'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'website_id'
        ]), [
            'website_id'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'sku'
        ]), [
            'sku'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'qty'
        ]), [
            'qty'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'start_date'
        ]), [
            'start_date'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'end_date'
        ]), [
            'end_date'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'uom_entry'
        ]), [
            'uom_entry'
        ])
            ->addIndex($this->_resource->getIdxName($table_name, [
            'status'
        ]), [
            'status'
        ])
            ->setComment('ITM Pricing Group Price ' . $group_id);
        $this->_connection->createTable($table);
    }

    private function indexGroupTables($result)
    {
        $main_table = $this->_resource->getTableName('itm_pricing_groupprice');
        $child_table_name = $main_table . "_0" ;
        $this->indexGroupTable($main_table, $child_table_name, 0);

        foreach ($result as $item) {
            if($item["group_id"] == "0") continue;
            $group_id = $this->getGroupID($item["group_id"]);
            $child_table_name = $main_table . "_" . $group_id;
            $this->indexGroupTable($main_table, $child_table_name, $item["group_id"]);
        }
    }

    private function indexGroupTable($main_table, $child_table, $group_id)
    {
        // Truncate table
        $truncate_query = "TRUNCATE TABLE `$child_table` ";
        $this->_connection->query($truncate_query);

        // insert new records
        $query = "INSERT INTO `$child_table`(id,group_id,website_id,sku,qty,start_date,end_date,uom_entry,price,discount,status)  (SELECT id,group_id,website_id,sku,qty,start_date,end_date,uom_entry,price,discount,status FROM `$main_table`    WHERE `group_id` = '".$group_id."')";
        $this->_connection->query($query);
        $this->showOutput($query);
        $this->showOutput("Reindex ".$child_table." Complete");
    }
}