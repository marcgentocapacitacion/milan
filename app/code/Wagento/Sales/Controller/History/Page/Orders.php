<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutInterface;

/**
 * Class Orders
 */
class Orders extends Pager implements HttpGetActionInterface
{
    /**
     * @return string
     */
    protected function getBlock(): string
    {
        return \Wagento\Sales\Block\Order\History\Orders::class;
    }
}
