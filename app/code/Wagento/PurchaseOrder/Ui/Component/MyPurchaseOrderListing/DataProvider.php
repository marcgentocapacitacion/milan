<?php

namespace Wagento\PurchaseOrder\Ui\Component\MyPurchaseOrderListing;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @return array
     */
    public function getData()
    {
        if ($this->request->getParam('search_text')) {
            $this->addFilter(
                $this->filterBuilder->setField('order_increment_id')
                    ->setValue($this->request->getParam('search_text'))
                    ->setConditionType('eq')
                    ->create()
            );
        }
        return parent::getData();
    }
}
