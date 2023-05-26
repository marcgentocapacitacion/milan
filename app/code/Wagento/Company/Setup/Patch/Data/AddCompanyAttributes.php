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
                'type' => 'int',
                'label' => 'Cod. AC Milan season',
                'input' => 'select',
                'sort_order' => 10,
                'position' => 10,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Table::class,
                'system' => false,
                'option' => ['values' => ['Default']],
                'validate_rules' => '[]',
            ]
        );

        $eavSetup->addAttribute(
            'company',
            'u_group_num_tempo_o_p_t',
            [
                'type' => 'int',
                'label' => 'Cod. OPTIMUS season',
                'input' => 'select',
                'sort_order' => 20,
                'position' => 20,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Table::class,
                'system' => false,
                'option' => ['values' => ['Default']],
                'validate_rules' => '[]',
            ]
        );

        $eavSetup->addAttribute(
            'company',
            'u_group_num_optimus',
            [
                'type' => 'int',
                'label' => 'Optimus Payment Condition',
                'input' => 'select',
                'sort_order' => 30,
                'position' => 30,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Table::class,
                'system' => false,
                'option' => ['values' => ['Default']],
                'validate_rules' => '[]',
            ]
        );
    }
}
