<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Wagento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as CollectionItemFactory;
use Magento\Customer\Model\Session;
use Magento\Catalog\Block\Product\Context;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection;
use Magento\Framework\Url\EncoderInterface;

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
     * @var EncoderInterface
     */
    protected EncoderInterface $urlEncoder;

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
        EncoderInterface $urlEncoder,
        array $data = [],
        ?OutputHelper $outputHelper = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->urlEncoder = $urlEncoder;
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
        $this->setData('size', $this->getOrdersItems()->getSize());
        if ($this->getOrdersItems()) {
            $this->getOrdersItems()->setCurPage($this->getCurrentPage());
            $this->getOrdersItems()->setPageSize($this->getPageLimite());
            $this->getOrdersItems()->load();
        }

        if (!$this->getLayout()->getBlock('product.price.render.default')) {
            $this->getLayout()->createBlock(
                \Magento\Framework\Pricing\Render::class,
                'product.price.render.default',
                [
                    'data' => [
                        'price_render_handle' => 'catalog_product_prices',
                        'use_link_for_as_low_as' => true
                    ]
                ]
            );
        }
        return $this;
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
        return (int)$this->getRequest()->getParam('p', 1);
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product, [
            '_escape' => false,
            'useUencPlaceholder' => true
        ]);
        $url = str_replace('%25uenc%25', base64_encode($this->getUrl('sales/order/history')), $url);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
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
            $this->addFilterSearchText();
        }

        return $this->orderItems;
    }

    /**
     * @return $this|void
     */
    protected function addFilterSearchText()
    {
        $search = $this->getRequest()->getParam('q');
        if (!$search) {
            return $this;
        }

        $this->orderItems
            ->addFieldToFilter([
                'sales.increment_id',
                'main_table.sku',
                'main_table.name'
            ], [
                ['like' => "%{$search}%"],
                ['like' => "%{$search}%"],
                ['like' => "%{$search}%"]
            ])
            ->getSelect()->group('main_table.item_id');
    }

    /**
     * @param Collection $orderItems
     *
     * @return $this
     */
    public function setOrdersItems(Collection $orderItems)
    {
        $this->orderItems = $orderItems;
        return $this;
    }
}
