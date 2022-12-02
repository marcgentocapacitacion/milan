<?php
namespace ITM\Pricing\Model\System\Config\Backend;

class UomCode extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * Before Attribute Save Process
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($attributeCode == 'itm_available_uom') {
            $data = $object->getData($attributeCode);
            if (! is_array($data)) {
                $data = [];
            }
            $object->setData($attributeCode, join(',', $data));
        }
        if (! $object->hasData($attributeCode)) {
            $object->setData($attributeCode, false);
        }
        return $this;
    }

    /**
     * After Load Attribute Process
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($attributeCode == 'itm_available_uom') {
            $data = $object->getData($attributeCode);
            if ($data) {
                if ( is_array($data)) {
                    $data = explode(',', $data);
                }
                $object->setData($attributeCode, $data);
            }
        }
        return $this;
    }
}
