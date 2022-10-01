<?php

namespace Wagento\StoreLocator\Controller\Index;

use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 */
class Index implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     *
     * @return void
     */
    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
