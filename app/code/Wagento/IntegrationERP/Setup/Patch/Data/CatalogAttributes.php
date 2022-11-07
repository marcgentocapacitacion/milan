<?php

namespace Wagento\IntegrationERP\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 * Class CatalogAttributes
 */
class CatalogAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $eavSetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        foreach ($this->getProductAttributes() as $code => $params) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $code,
                $params
            );

            $eavSetup->updateAttribute(
                Product::ENTITY,
                'custom_layout_update',
                $code,
                false
            );
        }
    }

    /**
     * @return array
     */
    protected function getProductAttributes(): array
    {
        return [
            'u_modelo' => [
                'type' => 'varchar',
                'label' => 'Model',
                'input' => 'text',
                'required' => false,
                'sort_order' => 100,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            'u_condicion_pago' => [
                'type' => 'int',
                'label' => 'Payment Condition',
                'input' => 'text',
                'required' => false,
                'sort_order' => 110,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            'itms_grp_cod' => [
                'type' => 'varchar',
                'label' => 'Item Group',
                'input' => 'text',
                'required' => false,
                'sort_order' => 120,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            's_volume' => [
                'type' => 'decimal',
                'label' => 'Volume',
                'input' => 'text',
                'required' => false,
                'sort_order' => 130,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            's_vol_unit' => [
                'type' => 'varchar',
                'label' => 'SVolUnit',
                'input' => 'text',
                'required' => false,
                'sort_order' => 140,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            's_height1' => [
                'type' => 'decimal',
                'label' => 'Height',
                'input' => 'text',
                'required' => false,
                'sort_order' => 150,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            's_width1' => [
                'type' => 'decimal',
                'label' => 'Width',
                'input' => 'text',
                'required' => false,
                'sort_order' => 160,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ],
            's_length1' => [
                'type' => 'decimal',
                'label' => 'Length',
                'input' => 'text',
                'required' => false,
                'sort_order' => 170,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
