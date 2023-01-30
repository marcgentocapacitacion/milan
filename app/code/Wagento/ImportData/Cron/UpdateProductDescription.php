<?php

namespace Wagento\ImportData\Cron;

use Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription\Collection;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Wagento\ImportData\Api\ImportexportProductDescriptionInterface;
use Wagento\ImportData\Model\ResourceModel\ImportexportProductDescription as ResourceModelImportexportProductDescription;
use Wagento\ImportData\Block\Adminhtml\Import\MountProductDescriptionFactory as MountProductDescriptionBlockFactory;
use Wagento\ImportData\Block\Adminhtml\Import\MountProductDescription as MountProductDescriptionBlock;
use Magento\Framework\View\LayoutInterface;

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
     * @var MountProductDescriptionBlockFactory
     */
    protected MountProductDescriptionBlockFactory $mountProductDescriptionBlock;

    /**
     * Parent layout of the block
     *
     * @var LayoutInterface
     */
    protected LayoutInterface $layout;

    /**
     * @param ProductRepositoryInterface                  $productRepository
     * @param Collection                                  $collection
     * @param ResourceModelImportexportProductDescription $importexportProductDescriptionResource
     * @param MountProductDescriptionBlockFactory         $mountProductDescriptionBlock
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Collection $collection,
        ResourceModelImportexportProductDescription $importexportProductDescriptionResource,
        MountProductDescriptionBlockFactory $mountProductDescriptionBlock,
        LayoutInterface $layout
    ) {
        $this->productRepository = $productRepository;
        $this->collection = $collection;
        $this->importexportProductDescriptionResource = $importexportProductDescriptionResource;
        $this->mountProductDescriptionBlock = $mountProductDescriptionBlock;
        $this->layout = $layout;
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
            /** @var MountProductDescriptionBlock $block */
            $block = $this->layout->createBlock(
                MountProductDescriptionBlock::class,
                "import_product_description_{$item->getSku()}"
            );
            $block->setQueueProductDescription($item);
            $html = $block->toHtml();
            /** @var Product $product */
            $product = $this->productRepository->get($item->getSku());
            $product->setDescription($html);
            $this->productRepository->save($product);
            $this->importexportProductDescriptionResource->delete($item);
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
