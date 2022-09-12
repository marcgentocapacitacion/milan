<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Wagento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as CollectionItemFactory;
use Magento\Customer\Model\Session;
use Magento\Catalog\Block\Product\Context;
use Magento\Sales\Model\Order\Item;

/**
 * Class ReorderProducts
 */
class ReorderProducts extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var CollectionItemFactory
     */
    protected CollectionItemFactory $collectionFactory;

    /**
     * @var Item
     */
    protected $orderItems;

    /**
     * @param Context                     $context
     * @param CollectionItemFactory       $collectionFactory
     * @param Session                     $customerSession
     * @param PostHelper                  $postDataHelper
     * @param Resolver                    $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data                        $urlHelper
     * @param array                       $data
     * @param OutputHelper|null           $outputHelper
     */
    public function __construct(
        Context $context,
        CollectionItemFactory $collectionFactory,
        Session $customerSession,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        array $data = [],
        ?OutputHelper $outputHelper = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        if ($this->getOrdersItems()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.cancelled.reorder.pager'
            )->setCollection(
                $this->getOrdersItems()
            );
            $this->setChild('pager-reorder', $pager);
            $this->getOrdersItems()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager-reorder');
    }

    /**
     * @return false|Item
     */
    public function getOrdersItems()
    {
        if (!($customerId = $this->customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->orderItems) {
            $this->orderItems = $this->collectionFactory->create()
                ->getProductDistinctPerCustomer($customerId)
                ->setOrder(
                    'sales.created_at',
                    'desc'
                );
        }

        return $this->orderItems;
    }
}
