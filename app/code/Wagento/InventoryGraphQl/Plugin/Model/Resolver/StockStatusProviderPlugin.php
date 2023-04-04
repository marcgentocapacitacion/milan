<?php

namespace Wagento\InventoryGraphQl\Plugin\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\Inventory\Model\StockSourceLinkFactory as StockSourceLinkFactoryModel;
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
     * @param StockSourceLinkFactoryModel $sourceLinkModelFactory
     * @param StockSourceLink             $sourceLink
     * @param ConfigInterface             $config
     * @param CustomerRepositoryInterface $customerRepository
     * @param AreProductsSalableInterface $areProductsSalable
     */
    public function __construct(
        StockSourceLinkFactoryModel $sourceLinkModelFactory,
        StockSourceLink $sourceLink,
        ConfigInterface $config,
        CustomerRepositoryInterface $customerRepository,
        AreProductsSalableInterface $areProductsSalable
    ) {
        $this->sourceLinkModelFactory = $sourceLinkModelFactory;
        $this->sourceLink = $sourceLink;
        $this->config = $config;
        $this->customerRepository = $customerRepository;
        $this->areProductsSalable = $areProductsSalable;
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
            return $proceed(
                $field,
                $context,
                $info,
                $value,
                $args
            );
        }

        /** @var \Magento\Inventory\Model\StockSourceLink $stockSouceModel */
        $stockSouceModel = $this->sourceLinkModelFactory->create();
        $this->sourceLink->load($stockSouceModel, $sourceStock->getValue(), 'source_code');
        if (!$stockSouceModel->getId()) {
            return $proceed(
                $field,
                $context,
                $info,
                $value,
                $args
            );
        }

        /* @var $product ProductInterface */
        $product = $value['model'];

        $stockId = $stockSouceModel->getStockId();
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
