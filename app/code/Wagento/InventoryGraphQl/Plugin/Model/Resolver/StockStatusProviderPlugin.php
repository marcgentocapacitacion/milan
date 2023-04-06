<?php

namespace Wagento\InventoryGraphQl\Plugin\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\Inventory\Model\StockSourceLinkFactory as StockSourceLinkFactoryModel;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\InventoryGraphQl\Model\Resolver\StockStatusProvider;
use Magento\InventorySalesApi\Api\AreProductsSalableInterface;
use Wagento\Catalog\Model\ConfigInterface;

/**
 * Class StockStatusProviderPlugin
 */
class StockStatusProviderPlugin
{
    /**
     * @var StockSourceLinkFactoryModel
     */
    protected StockSourceLinkFactoryModel $sourceLinkModelFactory;

    /**
     * @var StockSourceLink
     */
    protected StockSourceLink $sourceLink;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var  CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var AreProductsSalableInterface
     */
    protected AreProductsSalableInterface $areProductsSalable;

    /**
     * @var StockRepositoryInterface
     */
    protected StockRepositoryInterface $stockRepository;

    /**
     * @param StockSourceLinkFactoryModel $sourceLinkModelFactory
     * @param StockSourceLink             $sourceLink
     * @param ConfigInterface             $config
     * @param CustomerRepositoryInterface $customerRepository
     * @param AreProductsSalableInterface $areProductsSalable
     * @param StockRepositoryInterface    $stockRepository
     */
    public function __construct(
        StockSourceLinkFactoryModel $sourceLinkModelFactory,
        StockSourceLink $sourceLink,
        ConfigInterface $config,
        CustomerRepositoryInterface $customerRepository,
        AreProductsSalableInterface $areProductsSalable,
        StockRepositoryInterface $stockRepository
    ) {
        $this->sourceLinkModelFactory = $sourceLinkModelFactory;
        $this->sourceLink = $sourceLink;
        $this->config = $config;
        $this->customerRepository = $customerRepository;
        $this->areProductsSalable = $areProductsSalable;
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param StockStatusProvider $subject
     * @param callable            $proceed
     * @param Field               $field
     * @param                     $context
     * @param ResolveInfo         $info
     * @param array|null          $value
     * @param array|null          $args
     *
     * @return mixed
     */
    public function aroundResolve(
        StockStatusProvider $subject,
        callable $proceed,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!$context->getUserId()) {
            return 'OUT_OF_STOCK';
        }
        if (!($customer = $this->getCustomer($context->getUserId()))) {
            return 'OUT_OF_STOCK';
        }
        $sourceStock = $customer->getCustomAttribute('almacen') ?? false;
        if (!$sourceStock || !$sourceStock->getValue()) {
            return $proceed($field, $context, $info, $value, $args);
        }

        try {
            $stock = $this->stockRepository->get($sourceStock->getValue());
            if (!$stock->getStockId()) {
                return $proceed($field, $context, $info, $value, $args);
            }
            $stockId = $stock->getStockId();
        } catch (\Exception $e) {
            return $proceed($field, $context, $info, $value, $args);
        }

        /* @var $product ProductInterface */
        $product = $value['model'];
        $result = $this->areProductsSalable->execute([$product->getSku()], $stockId);
        $result = current($result);

        return $result->isSalable() ? 'IN_STOCK' : 'OUT_OF_STOCK';
    }

    /**
     * @param $customerId
     *
     * @return CustomerInterface|null
     */
    protected function getCustomer($customerId): ?CustomerInterface
    {
        try {
            $customer = $this->customerRepository->getById($customerId);
            if (!$customer->getId()) {
                return null;
            }
            return $customer;
        } catch (\Exception $e) {
            return null;
        }
    }
}
