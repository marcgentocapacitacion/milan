<?php

namespace Wagento\Catalog\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\InventorySales\Model\ResourceModel\StockIdResolver;
use Magento\Inventory\Model\StockSourceLinkFactory as StockSourceLinkFactoryModel;

/**
 * Class AlmacenStockPlugin
 */
class AlmacenStockPlugin
{
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var StockSourceLink
     */
    protected StockSourceLink $sourceLink;

    /**
     * @var StockSourceLinkFactoryModel
     */
    protected StockSourceLinkFactoryModel $sourceLinkModelFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param CustomerSession             $customerSession
     * @param StockSourceLink             $sourceLink
     * @param StockSourceLinkFactoryModel $sourceLinkModelFactory
     * @param ScopeConfigInterface        $scopeConfig
     */
    public function __construct(
        CustomerSession $customerSession,
        StockSourceLink $sourceLink,
        StockSourceLinkFactoryModel $sourceLinkModelFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->customerSession = $customerSession;
        $this->sourceLink = $sourceLink;
        $this->sourceLinkModelFactory = $sourceLinkModelFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param StockIdResolver $subject
     * @param callable        $proceed
     * @param string          $type
     * @param string          $code
     *
     * @return int
     */
    public function aroundResolve(
        StockIdResolver $subject,
        callable $proceed,
        string $type,
        string $code
    ) {
        if(!$this->scopeConfig->isSetFlag('wagento_catalog/product_list/use_almacen_for_stock')) {
            return $proceed($type, $code);
        }

        if (!$this->customerSession->isLoggedIn()) {
            return $proceed($type, $code);
        }

        /** @var \Wagento\Company\Model\Data\Customer $customer */
        $customer = $this->customerSession->getCustomer();
        $sourceStock = $customer->getAlmacen() ?? false;
        if (!$sourceStock) {
            return $proceed($type, $code);
        }

        /** @var \Magento\Inventory\Model\StockSourceLink $stockSouceModel */
        $stockSouceModel = $this->sourceLinkModelFactory->create();
        $this->sourceLink->load($stockSouceModel, $sourceStock, 'source_code');

        if (!$stockSouceModel->getId()) {
            return $proceed($type, $code);
        }
        return $stockSouceModel->getStockId();
    }
}
