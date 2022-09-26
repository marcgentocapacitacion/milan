<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutInterface;
use function Wagento\Sales\Controller\Page\create;

/**
 * Class Cancelled
 */
class Cancelled extends Pager implements HttpGetActionInterface
{
    /**
     * @var bool
     */
    protected bool $showItems = false;

    /**
     * @return string
     */
    protected function getBlock(): string
    {
        return \Wagento\Sales\Block\Order\History\CancelledOrders::class;
    }
}
