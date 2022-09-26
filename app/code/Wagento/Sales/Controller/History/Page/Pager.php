<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
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
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;

    /**
     * @var bool
     */
    protected bool $showItems = true;

    /**
     * @param LayoutInterface $layout
     * @param JsonFactory     $jsonFactory
     */
    public function __construct(
        LayoutInterface $layout,
        JsonFactory $jsonFactory
    ) {
        $this->layout = $layout;
        $this->jsonFactory = $jsonFactory;
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

        /** @var Json $result */
        $result = $this->jsonFactory->create();
        return $result->setData([
            'html' => $output,
            'size' => $block->getSize()
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getBlock(): string;
}
