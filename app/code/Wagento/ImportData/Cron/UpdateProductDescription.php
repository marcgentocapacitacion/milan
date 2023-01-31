<?php

namespace Wagento\ImportData\Cron;

use Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription\Collection;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Wagento\ImportData\Api\ImportexportProductDescriptionInterface;
use Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription as ResourceModelImportexportProductDescription;
use Wagento\ImportData\Api\ProductDescriptionInterface;

/**
 * Class UpdateProductDescription
 */
class UpdateProductDescription
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var Collection
     */
    protected Collection $collection;

    /**
     * @var ResourceModelImportexportProductDescription
     */
    protected ResourceModelImportexportProductDescription $importexportProductDescriptionResource;

    /**
     * @var ProductDescriptionInterface
     */
    protected ProductDescriptionInterface $productDescription;

    /**
     * @param ProductRepositoryInterface                  $productRepository
     * @param Collection                                  $collection
     * @param ResourceModelImportexportProductDescription $importexportProductDescriptionResource
     * @param ProductDescriptionInterface                 $productDescription
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Collection $collection,
        ResourceModelImportexportProductDescription $importexportProductDescriptionResource,
        ProductDescriptionInterface $productDescription
    ) {
        $this->productRepository = $productRepository;
        $this->collection = $collection;
        $this->importexportProductDescriptionResource = $importexportProductDescriptionResource;
        $this->productDescription = $productDescription;
    }

    /**
     * Mount HTML and save in product description.
     * @return void
     */
    public function execute()
    {
        $queue = $this->getQueue();
        if ($queue->getSize() <= 0) {
            return $this;
        }

        /** @var ImportexportProductDescriptionInterface $item */
        foreach ($queue->getItems() as $item) {
            if (!($description = $item->getDataDescription())) {
                continue;
            }
            /** @var Product $product */
            $product = $this->productRepository->get($item->getSku());
            $product->setDescription($this->productDescription->getHtml($description));
            $this->productRepository->save($product);
            //$this->importexportProductDescriptionResource->delete($item);
        }
    }

    /**
     * @return Collection
     */
    private function getQueue(): Collection
    {
        $this->collection->getSelect()->limit(50);
        return $this->collection;
    }
}
