<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutInterface;

/**
 * Class Pager
 */
Abstract class Pager implements HttpGetActionInterface
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
     * @var bool
     */
    protected bool $showItems = true;

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
            $this->getBlock(),
            'sales.order.history.pager'
        )->setTemplate('Wagento_Sales::order/history/orders/items.phtml')
            ->setData('show_items', $this->showItems);
        if ($block->getOrders()->getLastPageNumber() < $block->getCurrentPage()) {
            $output = '';
        } else {
            $output = $block->toHtml();
        }

        /** @var Raw $result */
        $result = $this->rawFactory->create();
        return $result->setContents($output);
    }

    /**
     * @return string
     */
    abstract protected function getBlock(): string;
}
