<?php

namespace Wagento\Company\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Wagento\Company\Setup\CompanySetupFactory;
use Wagento\Company\Setup\CompanySetup;

/**
 * Class AddCompanyAttributes
 */
class AddCompanyAttributes implements DataPatchInterface
{
    protected CompanySetupFactory $companySetupFactory;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    protected ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param CompanySetupFactory      $companySetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(CompanySetupFactory $companySetupFactory, ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->companySetupFactory = $companySetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
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

    public function apply()
    {
        /** @var CompanySetup $eavSetup */
        $eavSetup = $this->companySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->installEntities();
        $eavSetup->addAttribute(
            'company',
            'u_group_num_temporada',
            [
                'type' => 'varchar',
                'label' => 'Cod. AC Milan season',
                'input' => 'text',
                'sort_order' => 10,
                'position' => 10,
                'visible' => true,
                'system' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]
        );

        $eavSetup->addAttribute(
            'company',
            'u_group_num_tempo_o_p_t',
            [
                'type' => 'varchar',
                'label' => 'Cod. OPTIMUS season',
                'input' => 'text',
                'sort_order' => 20,
                'position' => 20,
                'visible' => true,
                'system' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]
        );

        $eavSetup->addAttribute(
            'company',
            'u_group_num_optimus',
            [
                'type' => 'varchar',
                'label' => 'Optimus Payment Condition',
                'input' => 'text',
                'sort_order' => 30,
                'position' => 30,
                'visible' => true,
                'system' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]
        );
    }
}
