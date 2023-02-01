<?php
namespace ITM\OutstandingPayments\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{



    // php bin/magento setup:upgrade
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        // handle all possible upgrade versions
        
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $setup->getConnection()->addColumn($setup->getTable("itm_outstandingpayments_sapinvoice"), 'doc_type', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 5,
                'comment' =>'Document Type'
            ]);
        }
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $setup->getConnection()->addColumn($setup->getTable("itm_outstandingpayments_sapinvoice"), 'info', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'Information (json)'
            ]);
        }
        $setup->endSetup();
    }

}
