<?php

namespace WeltPixel\AdvanceCategorySorting\Plugin\Frontend\Model\ResourceModel\Fulltext;

use Magento\Framework\DB\Select;

class Collection extends \WeltPixel\AdvanceCategorySorting\Plugin\Frontend\Model\ResourceModel\AbstractCollection
{
    /**
     * @param \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $subject
     * @param string $attribute
     * @param string $dir
     * @return array
     */
    public function beforeSetOrder($subject, $attribute, $dir = Select::SQL_DESC)
    {
        return parent::_beforeSetOrder($subject, $attribute, $dir);
    }
}
