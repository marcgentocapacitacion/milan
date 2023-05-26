<?php

namespace Wagento\Company\Model\ResourceModel\Eav;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\DataObject;

/**
 * Class Company
 */
class Company extends \Magento\Eav\Model\Entity\AbstractEntity
{
    /**
     * @param $instance
     * @param $method
     * @param $args
     *
     * @return bool
     */
    protected function _isCallableAttributeInstance($instance, $method, $args)
    {
        if ($instance instanceof AbstractBackend && ($method == 'beforeSave' || ($method = 'afterSave'))) {
            $attributeCode = $instance->getAttribute()->getAttributeCode();
            if (isset($args[0])
                && $args[0] instanceof DataObject
                && $args[0]->getData($attributeCode) === false
            ) {
                return false;
            }
        }

        return parent::_isCallableAttributeInstance($instance, $method, $args);
    }
}
