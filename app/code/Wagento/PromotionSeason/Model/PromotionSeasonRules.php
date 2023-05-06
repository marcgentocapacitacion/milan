<?php

namespace Wagento\PromotionSeason\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\IntegrationERP\Model\ResourceModel\CompanyCustomData as ResourceModelCompanyCustomData;
use Wagento\IntegrationERP\Model\CompanyCustomDataFactory as ModelCompanyCustomDataFactory;
use Wagento\IntegrationERP\Model\CompanyCustomData as ModelCompanyCustomData;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class PromotionSeasonRules
 */
class PromotionSeasonRules implements PromotionSeasonRulesInterface
{
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var ResourceModelCompanyCustomData
     */
    protected ResourceModelCompanyCustomData $resourceModelCompanyCustomData;

    /**
     * @var ModelCompanyCustomDataFactory
     */
    protected ModelCompanyCustomDataFactory $modelCompanyCustomDataFactory;

    /**
     * @var ModelCompanyCustomData|null
     */
    protected ?ModelCompanyCustomData $modelCompanyCustomData = null;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var array|null
     */
    protected ?array $isCompanySeason = null;

    /**
     * @param CustomerSession                $customerSession
     * @param ConfigInterface                $config
     * @param ResourceModelCompanyCustomData $resourceModelCompanyCustomData
     * @param ModelCompanyCustomDataFactory  $modelCompanyCustomDataFactory
     */
    public function __construct(
        CustomerSession $customerSession,
        ConfigInterface $config,
        ResourceModelCompanyCustomData $resourceModelCompanyCustomData,
        ModelCompanyCustomDataFactory $modelCompanyCustomDataFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->customerSession = $customerSession;
        $this->config = $config;
        $this->resourceModelCompanyCustomData = $resourceModelCompanyCustomData;
        $this->modelCompanyCustomDataFactory = $modelCompanyCustomDataFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * Verify if the product apply the rules for promotion season
     *
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function isPromotionSeasonProduct(ProductInterface $product): bool
    {
        if (!$product->isSaleable()) {
            return false;
        }

        if (!$this->isItmProperties($product)) {
            return false;
        }

        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }

        $companyId = $this->getCustomerId();
        if (isset($this->isCompanySeason[$companyId])) {
            return $this->isCompanySeason[$companyId];
        }

        $this->isCompanySeason[$companyId] = false;
        if ($this->isCompanySeason()) {
            $this->isCompanySeason[$companyId] = true;
            return $this->isCompanySeason[$companyId];
        }
        return $this->isCompanySeason[$companyId];
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function isItmProperties(ProductInterface $product): bool
    {
        if ($product->getTypeId() !== 'simple') {
            $productChildreens = $product->getTypeInstance()->getChildrenIds($product->getId()) ?? [];
            $productIds = [];
            foreach ($productChildreens as $items) {
                $productIds = array_merge($productIds, $items);
            }

            foreach ($productIds as $productId) {
                $product = $this->productRepository->getById($productId);
                if($this->isItmProperties($product)) {
                    return true;
                }
            }
            return false;
        }

        if ($product->getItmProperties() !== $this->config->getItmProperties()) {
            return false;
        }
        return true;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isPromotionSeasonProductBySku(string $sku): bool
    {
        try {
            $product = $this->productRepository->get($sku, false, null, true);
            return $this->isPromotionSeasonProduct($product);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return int|null
     */
    protected function getCustomerId(): ?int
    {
        try {
            $customerData = $this->customerSession->getCustomerData() ?? false;
            $companyAttributes = $customerData->getExtensionAttributes()->getCompanyAttributes() ?? false;
            if (!$companyAttributes) {
                return null;
            }

            if (!$companyAttributes->getCompanyId()) {
                return null;
            }

            return $companyAttributes->getCompanyId();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return ModelCompanyCustomData
     */
    public function getCompanyCustomData(): ModelCompanyCustomData
    {
        if ($this->modelCompanyCustomData) {
            return $this->modelCompanyCustomData;
        }
        $companyId = $this->getCustomerId();
        $this->modelCompanyCustomData = $this->modelCompanyCustomDataFactory->create();
        if (!$companyId) {
            return $this->modelCompanyCustomData;
        }
        $this->resourceModelCompanyCustomData->load($this->modelCompanyCustomData, $companyId, 'company_id');
        return $this->modelCompanyCustomData;
    }

    /**
     * @return bool
     */
    public function isCompanySeason(): bool
    {
        try {
            if (!$this->customerSession->isLoggedIn()) {
                return false;
            }

            $companyCustomData = $this->getCompanyCustomData();
            if (!$companyCustomData->getUAutorizadoTemporada()) {
                return false;
            }
            $datetime = new \DateTime();
            $uInicioTemporada = new \DateTime($companyCustomData->getUInicioTemporada());
            $uFimTemporada = new \DateTime($companyCustomData->getUFinTemporada());
            if ($datetime >= $uInicioTemporada && $datetime <= $uFimTemporada) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
