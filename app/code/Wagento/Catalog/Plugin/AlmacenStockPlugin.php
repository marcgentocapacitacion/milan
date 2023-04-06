<?php

namespace Wagento\Catalog\Plugin;

use Magento\Backend\Model\Session\Quote as SessionQuote;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\Inventory\Model\StockSourceLinkFactory as StockSourceLinkFactoryModel;
use Magento\InventorySales\Model\ResourceModel\StockIdResolver;
use Wagento\Catalog\Model\ConfigInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;

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
     * @var SessionQuote
     */
    protected SessionQuote $sessionQuote;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var StockRepositoryInterface
     */
    protected StockRepositoryInterface $stockRepository;

    /**
     * @param CustomerSession             $customerSession
     * @param StockSourceLink             $sourceLink
     * @param StockSourceLinkFactoryModel $sourceLinkModelFactory
     * @param ConfigInterface             $config
     * @param StockRepositoryInterface    $stockRepository
     * @param SessionQuote                $sessionQuote
     */
    public function __construct(
        CustomerSession $customerSession,
        StockSourceLink $sourceLink,
        StockSourceLinkFactoryModel $sourceLinkModelFactory,
        ConfigInterface $config,
        StockRepositoryInterface $stockRepository,
        SessionQuote $sessionQuote
    ) {
        $this->customerSession = $customerSession;
        $this->sourceLink = $sourceLink;
        $this->sourceLinkModelFactory = $sourceLinkModelFactory;
        $this->config = $config;
        $this->sessionQuote = $sessionQuote;
        $this->stockRepository = $stockRepository;
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
        if(!$this->config->getUseAlmacenForStock()) {
            return $proceed($type, $code);
        }

        if (!($customer = $this->getCustomer())) {
            return $proceed($type, $code);
        }

        $sourceStock = $customer->getCustomAttribute('almacen') ?? false;
        if (!$sourceStock || !$sourceStock->getValue()) {
            return $proceed($type, $code);
        }

        try {
            $stock = $this->stockRepository->get((int)$sourceStock->getValue());
            if (!$stock->getStockId()) {
                return $proceed($type, $code);
            }
            return $stock->getStockId();
        } catch (\Exception $e) {
            return $proceed($type, $code);
        }
    }

    /**
     * @return CustomerInterface|null
     */
    protected function getCustomer(): ?CustomerInterface
    {
        try {
            if ($this->customerSession->isLoggedIn()) {
                return $this->customerSession->getCustomerData();
            }

            if (!$this->sessionQuote->getQuote()) {
                return null;
            }

            if (!$this->sessionQuote->getQuote()->getCustomer()) {
                return null;
            }

            return $this->sessionQuote->getQuote()->getCustomer();
        } catch (\Exception $e) {
            return null;
        }
    }
}
