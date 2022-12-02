<?php
namespace ITM\Pricing\Model\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class SidebarFilter
{
    /**
     * aroundAddFieldToFilter method
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Closure                                                $proceed
     * @param                                                         $fields
     * @param null                                                    $condition
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function aroundAddFieldToFilter(ProductCollection $collection, \Closure $proceed, $fields, $condition = null)
    {
        // Here we can modify the collection
        
        //$collection->addAttributeToFilter('special_price',1000);

        return $fields ? $proceed($fields, $condition) : $collection;
    }
}