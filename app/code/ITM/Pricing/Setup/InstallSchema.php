<?php

/**
 * Copyright © 2015 ITM.
 * All rights reserved.
 */
namespace ITM\Pricing\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();
        
        /**
         * Create table 'itm_pricing_uom'
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_uom'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'uom_entry')
        ->addColumn('uom_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', [], 'uom_code')
        ->addColumn('uom_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', [], 'uom_name')
        ->setComment('ITM Pricing UOM');
        $installer->getConnection()->createTable($table);
        
        /**
         * Create table 'itm_pricing_uomgroup'
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_uomgroup'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('ugp_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'ugp_entry')
        ->addColumn('ugp_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', [], 'ugp_code')
        ->addColumn('ugp_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', [], 'ugp_name')
        ->addColumn('base_uom', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'base_uom')
        ->setComment('ITM Pricing UOM Group');
        $installer->getConnection()->createTable($table);
        
        /**
         * Create table 'itm_pricing_uomgroupdetails'
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_uomgroupdetails'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('ugp_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'ugp_entry')
        ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'uom_entry')
        ->addColumn('alt_qty', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'alt_qty')
        ->addColumn('base_qty', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '11', [], 'base_qty')
        ->setComment('ITM Pricing UOM Group Details');
        $installer->getConnection()->createTable($table);
        
        /**
         * Create table 'itm_pricing_grouprice'
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_groupprice'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('group_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Group ID')
        ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Website ID')
        ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', [
            'nullable' => false
        ], 'SKU')
        ->addColumn('qty', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Quantity')
        ->addColumn('start_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Start Date')
        ->addColumn('end_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'End Date')
        ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25', [
            'nullable' => false
        ], 'UOM Entry')
        ->addColumn('price', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Price')
        ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, '1', [], 'Status')
        /*
         * ->addIndex(
                 $installer->getIdxName('itm_pricing_groupprice', ['group_id','sku','qty','start_date','end_date'],"UNIQUE"),
                 ['group_id','sku','qty','start_date','end_date'],
                 array('type' =>"UNIQUE")
                 )
        */
        ->setComment('ITM Pricing Group Price');
        $installer->getConnection()->createTable($table);
        
        /**
         * Create table 'itm_pricing_customerprice'
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_customerprice'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Customer ID')
        ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Website ID')
        ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', [
            'nullable' => false
        ], 'SKU')
        ->addColumn('qty', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Quantity')
        ->addColumn('start_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Start Date')
        ->addColumn('end_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'End Date')
        ->addColumn('uom_entry', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25', [
            'nullable' => false
        ], 'UOM Entry')
        ->addColumn('price', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Price')
        ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, '1', [
            'nullable' => false
        ], 'Status')
        ->setComment('ITM Pricing Customer Price');
        
        $installer->getConnection()->createTable($table);
        
        /**
         * Create Discount Group Table (Group Table)
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_groupdiscount'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('group_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Group ID')
        ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Website ID')
        ->addColumn('attribute_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', [
            'nullable' => false
        ], 'Attribute Code')
        ->addColumn('attribute_value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '32', [
            'nullable' => false
        ], 'Attribute Value')
        ->addColumn('start_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Start Date')
        ->addColumn('end_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'End Date')
        ->addColumn('discount', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Discount')
        ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, '1', [], 'Status')
        ->setComment('ITM Pricing Group Discount');
        $installer->getConnection()->createTable($table);
        
        /**
         * Create Discount Group Table (Customer Table)
         */
        
        $table = $installer->getConnection()
        ->newTable($installer->getTable('itm_pricing_customerdiscount'))
        ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ], 'Id')
        ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Customer ID')
        ->addColumn('website_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
            'nullable' => false
        ], 'Website ID')
        ->addColumn('attribute_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', [
            'nullable' => false
        ], 'Attribute Ccode')
        ->addColumn('attribute_value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '32', [
            'nullable' => false
        ], 'Attribute Value')
        ->addColumn('start_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Start Date')
        ->addColumn('end_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'End Date')
        ->addColumn('discount', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', [
            'nullable' => false,
            'default' => '0.0000'
        ], 'Discount')
        ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, '1', [], 'Status')
        ->setComment('ITM Pricing Customer Discount');
        $installer->getConnection()->createTable($table);
        /* {{CedAddTable}} */
        $installer->endSetup();
    }
}
