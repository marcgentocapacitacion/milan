<?php

namespace Wagento\IntegrationERP\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

/**
 * Class CatalogAttributesDropdown
 */
class CatalogAttributesDropdown implements \Magento\Framework\Setup\Patch\DataPatchInterface
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
    public static function getDependencies()
    {
        return [
            CatalogAttributes::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $eavSetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        foreach (['u_condicion_pago', 'itms_grp_cod', 's_vol_unit'] as $attributeCode) {
            $attribute = $eavSetup->getAttribute($entityTypeId, $attributeCode);
            $eavSetup->updateAttribute(
                $entityTypeId,
                $attribute['attribute_id'],
                [
                    'backend_type' => 'int',
                    'frontend_input' => 'select',
                    'source_model' => \Magento\Eav\Model\Entity\Attribute\Source\Table::class,
                    'option' => ['values' => ['Default']],
                    'validate_rules' => '[]'
                ]
            );
        }
    }
}
