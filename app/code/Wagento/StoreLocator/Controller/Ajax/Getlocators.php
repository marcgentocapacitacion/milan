<?php

namespace Wagento\StoreLocator\Controller\Ajax;

use Wagento\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory;
use Wagento\StoreLocator\Model\ResourceModel\StoreLocator\Collection;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Class Getlocators
 */
class Getlocators implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param CollectionFactory $collectionFactory
     * @param JsonFactory       $jsonFactory
     * @param RequestInterface  $request
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        JsonFactory $jsonFactory,
        RequestInterface $request
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
    }

    /**
     * @return Collection
     */
    public function getLocations(): Collection
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create();
        }
        return $this->collection;
    }

    /**
     * @return int
     */
    public function getPageLimite(): int
    {
        return 10;
    }

    /**
     * Return current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return (int)$this->request->getParam('p', 1);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->request->getParam('q')) {
            $search = $this->request->getParam('q');
            $this->getLocations()->addFieldToFilter([
                'code',
                'name',
                'full_address'
            ], [
                ['like' => "%{$search}%"],
                ['like' => "%{$search}%"],
                ['like' => "%{$search}%"],
            ]);
        }

        $this->getLocations()->addFieldToFilter('active', ['eq' => '1']);
        $this->getLocations()->setCurPage($this->getCurrentPage());
        $this->getLocations()->setPageSize($this->getPageLimite());
        $this->getLocations()->load();

        /** @var Json $result */
        $result = $this->jsonFactory->create();
        return $result->setData($this->getLocations()->toArray());
    }
}
