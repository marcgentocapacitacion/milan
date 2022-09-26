<?php

namespace Wagento\Sales\Controller\History\Page;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
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
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;

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
            \Wagento\Sales\Block\Order\History\ReorderProducts::class,
            'sales.order.history.reorders.products.items'
        )->setTemplate('Wagento_Sales::order/history/reorder/items.phtml');
        if ($block->getOrdersItems()->getLastPageNumber() < $block->getCurrentPage()) {
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
}
