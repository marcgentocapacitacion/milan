<?php

namespace Wagento\Company\Model\Eav;

/**
 * Class Company
 */
class Company extends \Magento\Eav\Model\Attribute
{
    /**
     * Name of the module
     */
    const MODULE_NAME = 'Wagento_Company';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'wagento_company_entity_attribute';

    /**
     * Prefix of model events object
     *
     * @var string
     */
    protected $_eventObject = 'attribute';

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Wagento\Company\Model\ResourceModel\Eav\Company\Attribute::class);
    }
}
