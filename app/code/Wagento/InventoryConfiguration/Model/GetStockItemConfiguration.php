<?php
/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
declare(strict_types=1);

namespace Wagento\InventoryConfiguration\Model;

use Magento\InventoryConfiguration\Model\GetLegacyStockItem;
use Magento\InventoryConfiguration\Model\StockItemConfigurationFactory;
use Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface;
use Magento\InventoryConfigurationApi\Api\Data\StockItemConfigurationInterface;

/**
 * @inheritdoc
 */
class GetStockItemConfiguration implements GetStockItemConfigurationInterface
{
    /**
     * @var GetLegacyStockItem
     */
    protected GetLegacyStockItem $getLegacyStockItem;

    /**
     * @var StockItemConfigurationFactory
     */
    protected StockItemConfigurationFactory $stockItemConfigurationFactory;

    /**
     * @param GetLegacyStockItem            $getLegacyStockItem
     * @param StockItemConfigurationFactory $stockItemConfigurationFactory
     */
    public function __construct(
        GetLegacyStockItem $getLegacyStockItem,
        StockItemConfigurationFactory $stockItemConfigurationFactory
    ) {
        $this->getLegacyStockItem = $getLegacyStockItem;
        $this->stockItemConfigurationFactory = $stockItemConfigurationFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $sku, int $stockId): StockItemConfigurationInterface
    {
        return $this->stockItemConfigurationFactory->create(
            [
                'stockItem' => $this->getLegacyStockItem->execute($sku)
            ]
        );
    }
}
