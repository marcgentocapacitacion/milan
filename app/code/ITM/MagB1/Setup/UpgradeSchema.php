<?php
namespace ITM\MagB1\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    private function doUpgrade()
    {
        return null;
    }


    // php bin/magento setup:upgrade
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // handle all possible upgrade versions

        if (! $context->getVersion()) {
            // no previous version found, installation, InstallSchema was just executed
            // be careful, since everything below is true for installation !
            $this->doUpgrade();
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('itm_magb1_productfiles'))
                ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ], 'Id')
                ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '64', [
                    'nullable' => false
                ], 'SKU')
                ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                    'nullable' => false
                ], 'Description')
                ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                    'nullable' => false
                ], 'Path')
                ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'nullable' => false
                ], 'Store ID')
                ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                    'nullable' => false
                ], 'Position')
                ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                    'default' => null
                ], 'Status');
            $setup->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $this->doUpgrade1_0_5($setup);
        }
        if (version_compare($context->getVersion(), '1.0.7') < 0) {
            $this->doUpgrade1_0_7($setup);
        }
        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $this->doUpgrade1_0_8($setup);
        }
        if (version_compare($context->getVersion(), '1.4.5') < 0) {
            $this->doUpgrade1_4_5($setup);
        }
        if (version_compare($context->getVersion(), '1.4.7') < 0) {
            $this->doUpgrade1_4_7($setup);
        }
        if (version_compare($context->getVersion(), '1.5.4') < 0) {
            $this->doUpgrade1_5_4($setup);
        }

        $setup->endSetup();
    }
    private function doUpgrade1_5_4($setup)
    {

        $setup->getConnection()->addColumn(
            $setup->getTable('itm_magb1_productfiles'),
            'hash_code',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 65,
                'nullable' => true,
                'comment' => 'Hash Code'
            ])
        ;
        $attachmentProducts = $setup->getConnection()->newTable(
            $setup->getTable('itm_magb1_productfile_products')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'attachment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Attachment Id'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false,'unsigned' => true],
            'Product Id'
        )->addForeignKey(
            $setup->getFkName('attach_foreign_key', 'attachment_id', 'itm_magb1_productfiles', 'entity_id'),
            'attachment_id',
            $setup->getTable('itm_magb1_productfiles'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )
            /*->addForeignKey(
            $setup->getFkName('attach_product_foreign_key', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )*/
            ->setComment(
                'ITM File Attachment Products - Product Table'
            );
        $setup->getConnection()->createTable($attachmentProducts);
    }
    private function doUpgrade1_4_7($setup)
    {
        $setup->getConnection()->addColumn($setup->getTable('sales_invoice_grid'), 'itm_sbo_docentry',  [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Invoice DocEntry'
        ]);
        $setup->getConnection()->addColumn($setup->getTable('sales_invoice_grid'), 'itm_sbo_docnum', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Invoice DocNum'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('sales_shipment_grid'), 'itm_sbo_docentry',  [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Delivery DocEntry'
        ]);
        $setup->getConnection()->addColumn($setup->getTable('sales_shipment_grid'), 'itm_sbo_docnum', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Delivery DocNum'
        ]);
    }
    private function doUpgrade1_4_5($setup)
    {
        //////////////////////////// NumAtCard
        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'itm_sbo_numatcard', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Customer Ref. No.'
        ]);
        $setup->getConnection()->addColumn($setup->getTable("sales_order_grid"),'itm_sbo_numatcard',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'Customer Ref. No.'
            ]);
        $setup->getConnection()->addColumn($setup->getTable('sales_invoice'), 'itm_sbo_numatcard', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Customer Ref. No.'
        ]);
        $setup->getConnection()->addColumn($setup->getTable('sales_shipment'), 'itm_sbo_numatcard', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Customer Ref. No.'
        ]);
        $setup->getConnection()->addColumn($setup->getTable('sales_creditmemo'), 'itm_sbo_numatcard', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Customer Ref. No.'
        ]);
        $setup->getConnection()->addColumn($setup->getTable("quote"),'itm_sbo_numatcard', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' =>'Customer Ref. No.'
        ]);
    }
    private function doUpgrade1_0_8($setup)
    {
        $quote = 'quote';
        $orderGridTable = 'sales_order_grid';

        $setup->getConnection()->addColumn($setup->getTable($quote),'itm_sbo_docentry',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'SAP DocEntry'
            ]);

        //Order Grid table
        $setup->getConnection()->addColumn($setup->getTable($orderGridTable),'itm_sbo_docentry',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'SAP DocEntry'
            ]);

        $setup->getConnection()->addColumn($setup->getTable($quote),'itm_sbo_docnum',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'SAP DocNum'
            ]);

        //Order Grid table
        $setup->getConnection()->addColumn($setup->getTable($orderGridTable),'itm_sbo_docnum',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'SAP DocNum'
            ]);
        $setup->getConnection()->addColumn($setup->getTable($quote),'itm_sbo_download_to_sap',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'itm_sbo_download_to_sap'
            ]);

        //Order Grid table
        $setup->getConnection()->addColumn($setup->getTable($orderGridTable),'itm_sbo_download_to_sap',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' =>'itm_sbo_download_to_sap'
            ]);

    }
    private function doUpgrade1_0_7($setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_magb1_categoryfiles'))
            ->addColumn( 'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Id'
            )
            ->addColumn('code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Code' )
            ->addColumn('category_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => false ], 'Category IDs' )
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Path' )
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Description' )
            ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Store ID')
            ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Position')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status');
        $setup->getConnection()->createTable($table);

    }

    private function doUpgrade1_0_5($setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_magb1_orderfiles'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Id')
            ->addColumn('increment_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '32', [
                'nullable' => false
            ], 'Increment ID')
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Path')
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Description')
            ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Store ID')
            ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Position')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                'default' => null
            ], 'Status');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_magb1_invoicefiles'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Id')
            ->addColumn('increment_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '32', [
                'nullable' => false
            ], 'Increment ID')
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Path')
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Description')
            ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Store ID')
            ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Position')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                'default' => null
            ], 'Status');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_magb1_shipmentfiles'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Id')
            ->addColumn('increment_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '32', [
                'nullable' => false
            ], 'Increment ID')
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Path')
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Description')
            ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Store ID')
            ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Position')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                'default' => null
            ], 'Status');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable('itm_magb1_customerfiles'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Id')
            ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Customer ID')
            ->addColumn('path', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Path')
            ->addColumn('description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', [
                'nullable' => false
            ], 'Description')
            ->addColumn('position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Position')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                'default' => null
            ], 'Status');
        $setup->getConnection()->createTable($table);
    }
}
