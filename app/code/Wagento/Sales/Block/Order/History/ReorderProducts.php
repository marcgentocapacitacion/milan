<?php

namespace Wagento\Sales\Block\Order\History;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as CollectionItemFactory;
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
     * @var FormKey
     */
    protected FormKey $formKey;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @param Context $context
     * @param CollectionItemFactory $collectionFactory
     * @param Session $customerSession
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param EncoderInterface $urlEncoder
     * @param FormKey $formKey
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param FilterBuilder $filterBuilder
     * @param array $data
     * @param OutputHelper|null $outputHelper
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
        FormKey $formKey,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        FilterBuilder $filterBuilder,
        array $data = [],
        ?OutputHelper $outputHelper = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->urlEncoder = $urlEncoder;
        $this->formKey = $formKey;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->filterBuilder = $filterBuilder;
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
     * Retrieve Session Form Key
     *
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setData('size', $this->getOrdersItems() ? $this->getOrdersItems()->getSize() : 0);
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
            // get ordeer by customer
            $customerFilter = $this->filterBuilder
                ->setField('customer_id')
                ->setValue($customerId)
                ->setConditionType('eq')
                ->create();

            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter($customerFilter)
                ->create();
            $orders = $this->orderRepository->getList($searchCriteria)->getItems();
            $orderIds = [];
            foreach ($orders as $order) {
                $orderIds[] = $order->getEntityId();
            }

            // get order items
            // TODO: confirm 502 bad gate way issue due to LiveSearch
//            $this->orderItems = $this->collectionFactory->create();
//                //->getProductDistinctPerCustomer($customerId)
//                ->addFieldToFilter('order_id', ['in' => $orderIds]);
//                ->setOrder(
//                    'created_at',
//                    'desc'
//                );
//            $this->addFilterSearchText();
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

        $incrementFilter = $this->filterBuilder
            ->setField('increment_id')
            ->setValue("%{$search}%")
            ->setConditionType('like')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter($incrementFilter)
            ->create();
        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        $orderIds = [];
        foreach ($orders as $order) {
            $orderIds[] = $order->getEntityId();
        }

        $this->orderItems
            ->addFieldToFilter([
                'order_id',
                'sku',
                'name'
            ], [
                ['in' => $orderIds],
                ['like' => "%{$search}%"],
                ['like' => "%{$search}%"]
            ])
            ->getSelect()->group('item_id');
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
