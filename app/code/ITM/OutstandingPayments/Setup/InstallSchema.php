<?php

namespace ITM\OutstandingPayments\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->startSetup();
        
        // create tables
        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_outstandingpayments_company'))
            ->addColumn( 'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Id'
            )
            ->addColumn('database_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', ['nullable' => false ], 'Database Name')
            ->addColumn('company_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', ['nullable' => false ], 'Company Name')
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Description')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status');
        $setup->getConnection()->createTable($table);
                 
        // Invoice List
        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_outstandingpayments_sapinvoice'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Id'
            )
            ->addColumn('doc_entry', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Doc Entry')
            ->addColumn('doc_num', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Doc Num')
            ->addColumn('card_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25', ['nullable' => false ], 'Card Code')
            ->addColumn('email', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '100', ['nullable' => false ], 'Email')
            ->addColumn('doc_total', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', ['nullable' => false, 'default' => '0.0000'], 'Doc Total')
            ->addColumn('open_balance', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', ['nullable' => false, 'default' => '0.0000'], 'Open Balance')
            ->addColumn('doc_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Doc Date')
            ->addColumn('doc_due_date', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Doc Due Date')
            ->addColumn('invoice_status', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '25', ['nullable' => false ], 'Invoice Status')
            ->addColumn('sap_company', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Sap Company')
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => true ], 'File Path')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status');
        $setup->getConnection()->createTable($table);
        
        // create SAP Company Field
        $setup->getConnection()->addColumn($setup->getTable("sales_order"), 'itm_sap_company', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'SAP Company'
        ]);
        
        $setup->getConnection()->addColumn($setup->getTable("sales_order_grid"), 'itm_sap_company', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'SAP Company'
        ]);

        // create SAP Company Field
        $setup->getConnection()->addColumn($setup->getTable("sales_order"), 'itm_order_type', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 10,
            'comment' =>'Order Type'
        ]);

        $setup->getConnection()->addColumn($setup->getTable("sales_order_grid"), 'itm_order_type', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 10,
            'comment' =>'Order Type'
        ]);
        $setup->endSetup();
    }
}
