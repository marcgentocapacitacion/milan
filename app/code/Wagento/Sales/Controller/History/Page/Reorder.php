<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutInterface;

/**
 * Class Reorder
 */
class Reorder implements HttpGetActionInterface
{
    /**
     * @var LayoutInterface
     */
    protected LayoutInterface $layout;

    /**
     * @var RawFactory
     */
    protected RawFactory $rawFactory;

    /**
     * @param LayoutInterface $layout
     * @param RawFactory      $rawFactory
     */
    public function __construct(
        LayoutInterface $layout,
        RawFactory $rawFactory
    ) {
        $this->layout = $layout;
        $this->rawFactory = $rawFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $block = $this->layout->createBlock(
            \Wagento\Sales\Block\Order\History\ReorderProducts::class,
            'sales.order.history.reorders.products.items'
        )->setTemplate('Wagento_Sales::order/history/reorder/items.phtml');
        if ($block->getOrdersItems()->getLastPageNumber() < $block->getCurrentPage()) {
            $output = '';
        } else {
            $output = $block->toHtml();
        }

        /** @var Raw $result */
        $result = $this->rawFactory->create();
        return $result->setContents($output);
    }
}
