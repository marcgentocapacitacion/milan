<?php

namespace Wagento\Company\Model\ResourceModel\Eav\Company;

/**
 * Class Attribute
 */
class Attribute extends \Magento\Eav\Model\ResourceModel\Attribute
{

    /**
     * @inheritDoc
     */
    protected function _getEavWebsiteTable()
    {
        return $this->getTable('wagento_company_eav_attribute_website');
    }

    /**
     * @inheritDoc
     */
    protected function _getFormAttributeTable()
    {
        return $this->getTable('wagento_company_form_attribute');
    }
}
