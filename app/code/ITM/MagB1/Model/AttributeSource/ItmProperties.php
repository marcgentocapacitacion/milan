<?php
/**
 * Copyright © ITM-Development All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\MagB1\Model\AttributeSource;

class ItmProperties extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => 'p1', 'label' => __('Property1')],
            ['value' => 'p2', 'label' => __('Property2')],
            ['value' => 'p3', 'label' => __('Property3')],
            ['value' => 'p4', 'label' => __('Property4')],
            ['value' => 'p5', 'label' => __('Property5')],
            ['value' => 'p6', 'label' => __('Property6')],
            ['value' => 'p7', 'label' => __('Property7')],
            ['value' => 'p8', 'label' => __('Property8')],
            ['value' => 'p9', 'label' => __('Property9')],
            ['value' => 'p10', 'label' => __('Property10')],
            ['value' => 'p11', 'label' => __('Property11')],
            ['value' => 'p12', 'label' => __('Property12')],
            ['value' => 'p13', 'label' => __('Property13')],
            ['value' => 'p14', 'label' => __('Property14')],
            ['value' => 'p15', 'label' => __('Property15')],
            ['value' => 'p16', 'label' => __('Property16')],
            ['value' => 'p17', 'label' => __('Property17')],
            ['value' => 'p18', 'label' => __('Property18')],
            ['value' => 'p19', 'label' => __('Property19')],
            ['value' => 'p20', 'label' => __('Property20')],
            ['value' => 'p21', 'label' => __('Property21')],
            ['value' => 'p22', 'label' => __('Property22')],
            ['value' => 'p23', 'label' => __('Property23')],
            ['value' => 'p24', 'label' => __('Property24')],
            ['value' => 'p25', 'label' => __('Property25')],
            ['value' => 'p26', 'label' => __('Property26')],
            ['value' => 'p27', 'label' => __('Property27')],
            ['value' => 'p28', 'label' => __('Property28')],
            ['value' => 'p29', 'label' => __('Property29')],
            ['value' => 'p30', 'label' => __('Property30')],
            ['value' => 'p31', 'label' => __('Property31')],
            ['value' => 'p32', 'label' => __('Property32')],
            ['value' => 'p33', 'label' => __('Property33')],
            ['value' => 'p34', 'label' => __('Property34')],
            ['value' => 'p35', 'label' => __('Property35')],
            ['value' => 'p36', 'label' => __('Property36')],
            ['value' => 'p37', 'label' => __('Property37')],
            ['value' => 'p38', 'label' => __('Property38')],
            ['value' => 'p39', 'label' => __('Property39')],
            ['value' => 'p40', 'label' => __('Property40')],
            ['value' => 'p41', 'label' => __('Property41')],
            ['value' => 'p42', 'label' => __('Property42')],
            ['value' => 'p43', 'label' => __('Property43')],
            ['value' => 'p44', 'label' => __('Property44')],
            ['value' => 'p45', 'label' => __('Property45')],
            ['value' => 'p46', 'label' => __('Property46')],
            ['value' => 'p47', 'label' => __('Property47')],
            ['value' => 'p48', 'label' => __('Property48')],
            ['value' => 'p49', 'label' => __('Property49')],
            ['value' => 'p50', 'label' => __('Property50')],
            ['value' => 'p51', 'label' => __('Property51')],
            ['value' => 'p52', 'label' => __('Property52')],
            ['value' => 'p53', 'label' => __('Property53')],
            ['value' => 'p54', 'label' => __('Property54')],
            ['value' => 'p55', 'label' => __('Property55')],
            ['value' => 'p56', 'label' => __('Property56')],
            ['value' => 'p57', 'label' => __('Property57')],
            ['value' => 'p58', 'label' => __('Property58')],
            ['value' => 'p59', 'label' => __('Property59')],
            ['value' => 'p60', 'label' => __('Property60')],
            ['value' => 'p61', 'label' => __('Property61')],
            ['value' => 'p62', 'label' => __('Property62')],
            ['value' => 'p63', 'label' => __('Property63')],
            ['value' => 'p64', 'label' => __('Property64')]
        ];
        return $this->_options;
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => $attributeCode . ' column',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = [];

        $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = ['type' => 'index', 'fields' => [$this->getAttribute()->getAttributeCode()]];

        return $indexes;
    }

    /**
     * @param int $store
     * @return \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        return $this->eavAttrEntity->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}
