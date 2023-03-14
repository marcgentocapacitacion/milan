<?php

namespace Wagento\Catalog\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\InventorySales\Model\ResourceModel\StockIdResolver;
use Magento\Inventory\Model\StockSourceLinkFactory as StockSourceLinkFactoryModel;
use \Magento\Backend\Model\Session\Quote as SessionQuote;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModelCustomer;

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
     * @var SessionQuote
     */
    protected SessionQuote $sessionQuote;

    /**
     * @var CustomerFactory
     */
    protected CustomerFactory $customerFactory;

    /**
     * @var ResourceModelCustomer
     */
    protected ResourceModelCustomer $resourceCustomer;

    /**
     * @param CustomerSession             $customerSession
     * @param StockSourceLink             $sourceLink
     * @param StockSourceLinkFactoryModel $sourceLinkModelFactory
     * @param ScopeConfigInterface        $scopeConfig
     * @param SessionQuote                $sessionQuote
     * @param CustomerFactory             $customerFactory
     * @param ResourceModelCustomer       $resourceCustomer
     */
    public function __construct(
        CustomerSession $customerSession,
        StockSourceLink $sourceLink,
        StockSourceLinkFactoryModel $sourceLinkModelFactory,
        ScopeConfigInterface $scopeConfig,
        SessionQuote $sessionQuote,
        CustomerFactory $customerFactory,
        ResourceModelCustomer $resourceCustomer
    ) {
        $this->customerSession = $customerSession;
        $this->sourceLink = $sourceLink;
        $this->sourceLinkModelFactory = $sourceLinkModelFactory;
        $this->scopeConfig = $scopeConfig;
        $this->sessionQuote = $sessionQuote;
        $this->customerFactory = $customerFactory;
        $this->resourceCustomer = $resourceCustomer;
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

        if (!($customer = $this->getCustomer())) {
            return $proceed($type, $code);
        }

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

    /**
     * @return Customer|null
     */
    protected function getCustomer(): ?Customer
    {
        try {
            if ($this->customerSession->isLoggedIn()) {
                return $this->customerSession->getCustomer();
            }

            if (!$this->sessionQuote->getCustomerId()) {
                return null;
            }

            $customerId = $this->sessionQuote->getCustomerId();
            $model = $this->customerFactory->create();
            $this->resourceCustomer
                ->addAttribute(
                    $this->resourceCustomer->getAttribute('almacen')
                )
                ->load($model, $customerId,CustomerInterface::ID);
            if ($model->getId()) {
                return $model;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
