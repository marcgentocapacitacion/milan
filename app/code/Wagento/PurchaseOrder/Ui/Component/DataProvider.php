<?php

namespace Wagento\PurchaseOrder\Ui\Component;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\PurchaseOrder\Ui\Component\DataProvider
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
