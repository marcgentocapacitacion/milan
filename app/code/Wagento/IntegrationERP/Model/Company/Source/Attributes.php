<?php

namespace Wagento\IntegrationERP\Model\Company\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Wagento\Company\Model\Eav\Company;
use Magento\Eav\Model\Entity\Attribute\Source\Table;

/**
 * Class Attributes
 */
class Attributes implements OptionSourceInterface
{
    /**
     * @var string
     */
    protected $attributeName;
    /**
     * @var Company
     */
    protected Company $companyAttributes;

    /**
     * @var Table
     */
    protected Table $table;

    /**
     * @param         $attributeName
     * @param Company $companyAttributes
     * @param Table   $table
     */
    public function __construct(
        $attributeName,
        Company $companyAttributes,
        Table $table
    ) {
        $this->attributeName = $attributeName;
        $this->companyAttributes = $companyAttributes;
        $this->table = $table;
    }

    public function toOptionArray()
    {
        $attribute = $this->companyAttributes->loadByCode('company', $this->attributeName);
        $this->table->setAttribute($attribute);
        return $this->table->toOptionArray();
    }

}
