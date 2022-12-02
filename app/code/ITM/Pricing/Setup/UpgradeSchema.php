<?php
namespace ITM\Pricing\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function doUpgrade()
    {

        return null;
    }

    private function addIndexGroupPriceTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['group_id']),
                ['group_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['website_id']),
                ['website_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['sku']),
                ['sku']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['qty']),
                ['qty']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['start_date']),
                ['start_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['end_date']),
                ['end_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['uom_entry']),
                ['uom_entry']
                );
        /*   
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['price']),
                ['price']
                );
        */
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupprice'),
                $installer->getIdxName('itm_pricing_groupprice', ['status']),
                ['status']
                );
    }

    private function addIndexCustomerPriceTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['customer_id']),
                ['customer_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['website_id']),
                ['website_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['sku']),
                ['sku']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['qty']),
                ['qty']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['start_date']),
                ['start_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['end_date']),
                ['end_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['uom_entry']),
                ['uom_entry']
                );
        /*
         
         $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['price']),
                ['price']
                );
        */
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerprice'),
                $installer->getIdxName('itm_pricing_customerprice', ['status']),
                ['status']
                );
    }

    private function addIndexGroupDiscountTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['group_id']),
                ['group_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['website_id']),
                ['website_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['attribute_code']),
                ['attribute_code']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['attribute_value']),
                ['attribute_value']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['start_date']),
                ['start_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['end_date']),
                ['end_date']
                );
        /*
         $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['discount']),
                ['discount']
                );
        */
        $connection->addIndex(
                $installer->getTable('itm_pricing_groupdiscount'),
                $installer->getIdxName('itm_pricing_groupdiscount', ['status']),
                ['status']
                );
    }
    private function addIndexCustomerDiscountTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['customer_id']),
                ['customer_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['website_id']),
                ['website_id']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['attribute_code']),
                ['attribute_code']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['attribute_value']),
                ['attribute_value']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['start_date']),
                ['start_date']
                );
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['end_date']),
                ['end_date']
                );
        /*
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['discount']),
                ['discount']
                );
        */
        $connection->addIndex(
                $installer->getTable('itm_pricing_customerdiscount'),
                $installer->getIdxName('itm_pricing_customerdiscount', ['status']),
                ['status']
                );
    }

    private function addWeightTable(SchemaSetupInterface $installer)
    {
        /**
         * Create table 'itm_pricing_uomweight'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('itm_pricing_uomweight'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Id')
            ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64',
                [
                    'nullable' => false
                ], 'SKU')
            ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25',
                [
                    'nullable' => false
                ], 'UOM Entry')
            ->addColumn('weight', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
                [
                    'nullable' => false,
                    'default' => '0.0000'
                ], 'Weight')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1,
                [
                    'default' => null
                ], 'Status')
            ->setComment('ITM Pricing Product UOM Weight');
            $installer->getConnection()->createTable($table);

    }
    private function addCustomerTablesTable(SchemaSetupInterface $installer)
    {
        /**
         * Create table 'itm_pricing_customer_index_tables'
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_customer_index_tables'))
        ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id')
            ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
            [
                'nullable' => false
            ], 'Customer Id')
        ->addColumn('table_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100',
            [
                'nullable' => false
            ], 'Table Name')

        ->setComment('ITM Pricing Customer Index Tables Table');
        $installer->getConnection()->createTable($table);

    }
    private function addFinalPriceTable(SchemaSetupInterface $installer)
    {
        /**
         * Create table 'itm_pricing_customer_index_tables'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('itm_pricing_finalprice'))
            ->addColumn( 'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Id'
            )
            ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['nullable' => false ], 'Website ID')
            ->addColumn('group_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['nullable' => false ], 'Group ID')
            ->addColumn('customer_group_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['nullable' => false ], 'Customer Group ID')
            ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Customer ID')
            ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', ['nullable' => false ], 'SKU' )
            ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '5', ['nullable' => false ], 'Uom Entry' )
            ->addColumn('qty', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', ['nullable' => false, 'default' => '0.0000'], 'Qty' )
            ->addColumn('price', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', ['nullable' => false, 'default' => '0.0000'], 'Price' )
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status')
        ->setComment('ITM Pricing Final Price Table');
        $installer->getConnection()->createTable($table);

    }

    private function addDiscountField(SchemaSetupInterface $installer)
    {

        $table = $installer->getConnection()
            ->addColumn($installer->getTable('itm_pricing_groupprice'), 'discount',  [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' =>'Discount',
                'after'=>'price'
            ]);
        $table = $installer->getConnection()
            ->addColumn($installer->getTable('itm_pricing_customerprice'), 'discount',  [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' =>'Discount',
                'after'=>'price'
            ]);


    }
    // php bin/magento setup:upgrade
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        // handle all possible upgrade versions

        if (! $context->getVersion()) {
            // no previous version found, installation, InstallSchema was just executed
            // be careful, since everything below is true for installation !
            $this->doUpgrade();
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $this->addIndexGroupPriceTable($installer);
            $this->addIndexCustomerPriceTable($installer);
            $this->addIndexGroupDiscountTable($installer);
            $this->addIndexCustomerDiscountTable($installer);

        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            // code to upgrade to 1.0.2
            $this->addWeightTable($installer);
        }
        if (version_compare($context->getVersion(), '1.1.1') < 0) {
            // code to upgrade to 1.1.1
            $this->addCustomerTablesTable($installer);
        }
        if (version_compare($context->getVersion(), '1.2.0') < 0) {
            $this->addFinalPriceTable($installer);
        }
        if (version_compare($context->getVersion(), '1.2.7') < 0) {
            $this->addDiscountField($installer);
        }
        $installer->endSetup();
    }
}
