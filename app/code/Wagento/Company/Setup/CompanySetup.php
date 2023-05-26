<?php

namespace Wagento\Company\Setup;

/**
 * Class CompanySetup
 */
class CompanySetup extends \Magento\Eav\Setup\EavSetup
{
    /**
     * @return array
     */
    public function getDefaultEntities()
    {
        return [
            'company' => [
                'entity_model' => \Wagento\Company\Model\ResourceModel\Eav\Company::class,
                'attribute_model' => \Wagento\Company\Model\Eav\Company::class,
                'table' => 'company',
                'increment_model' => \Magento\Eav\Model\Entity\Increment\NumericValue::class,
                'additional_attribute_table' => 'wagento_company_eav_attribute',
                'entity_attribute_collection' => null,
                'increment_per_store' => 1,
                'attributes' => []
            ]
        ];
    }
}
