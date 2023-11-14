<?php

namespace Wagento\Sales\Block\Link;

use Magento\Framework\View\Element\Html\Link;

/**
 * Class Orders
 */
class Orders extends Link implements \Magento\Customer\Block\Account\SortLinkInterface
{
    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl('sales/order/history');
    }

    /**
     * Get Label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Web Orders');
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
