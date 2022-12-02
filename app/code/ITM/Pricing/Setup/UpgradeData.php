<?php

namespace ITM\Pricing\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create([
            'setup' => $setup
        ]);
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            /** @var EavSetup $eavSetup */
            
            // Create UOM Group attribute;
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'itm_ugp_entry', [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'UOM Group',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'ITM\Pricing\Model\System\Config\UomGroup',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => 'simple,virtual,downloadable',
                    'group' => 'UOM'
                ]);
            // Create UOM Code attribute;
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'itm_uom_entry', [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Unit Of Measurement',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'ITM\Pricing\Model\System\Config\UomCode',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => 'simple,virtual,downloadable',
                    'group' => 'UOM'
                ]);
            
            // Create UOM Code attribute;
            // UPDATE `eav_attribute` SET `backend_model` = 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\ArrayBackend' WHERE `attribute_code` = 'itm_available_uom';
            // old ITM\Pricing\Model\System\Config\Backend\UomCode
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'itm_available_uom', [
                    'type' => 'varchar',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'frontend' => '',
                    'label' => 'Available UOM',
                    'input' => 'multiselect',
                    'class' => '',
                    'source' => 'ITM\Pricing\Model\System\Config\UomCode',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => 'simple,virtual,downloadable',
                    'group' => 'UOM'
                ]);
            // Insert default uom
            $data = [
                'uom_entry' => '-1',
                'uom_code' => 'Manuell',
                'uom_name' => 'Manuell',
            ];
            $setup->getConnection()
            ->insert($setup->getTable('itm_pricing_uom'), $data, ['value']);
        }

        if (version_compare($context->getVersion(), '1.3.2') < 0) {
            $this->addApplyDiscountGroupsAttribute($eavSetup);
        }
    }

    private function addApplyDiscountGroupsAttribute($eavSetup)
    {
        // Create 'Do Not Apply Discount Groups' attribute;
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ignore_discount_groups', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Do Not Apply Discount Groups (Customer Discount)',
            'input' => 'boolean',
            'class' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => 0,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,virtual,downloadable',
            'group' => 'UOM'
        ]);
    }
}
