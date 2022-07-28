<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\MagB1\Model\CatalogInventory;

/**
 * Class StockRegistry
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class StockRegistry extends \Magento\CatalogInventory\Model\StockRegistry implements \ITM\MagB1\Api\CatalogInventory\StockRegistryInterface
{

    /**
     * @param string $productId
     * @param int $scopeId
     * @return \Magento\CatalogInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStockItemById($productId, $scopeId = null)
    {
        $scopeId = $this->stockConfiguration->getDefaultScopeId();
        //$productId = $this->resolveProductId($productId);
        return $this->stockRegistryProvider->getStockItem($productId, $scopeId);
    }

    /**
     * @param string $productId
     * @param int $scopeId
     * @return \Magento\CatalogInventory\Api\Data\StockStatusInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStockStatusById($productId, $scopeId = null)
    {
        $scopeId = $this->stockConfiguration->getDefaultScopeId();
        //$productId = $this->resolveProductId($productSku);
        return $this->getStockStatus($productId, $scopeId);
    }
    /**
     * @inheritdoc
     */
    public function updateStockItemById($productId, \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem)
    {
        //$productId = $this->resolveProductId($productSku);
        $websiteId = $stockItem->getWebsiteId() ?: null;
        $origStockItem = $this->getStockItem($productId, $websiteId);
        $data = $stockItem->getData();
        if ($origStockItem->getItemId()) {
            unset($data['item_id']);
        }
        $origStockItem->addData($data);
        $origStockItem->setProductId($productId);
        return $this->stockItemRepository->save($origStockItem)->getItemId();
    }
}
