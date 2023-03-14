<?php

namespace Wagento\PromotionSeason\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Wagento\IntegrationERP\Model\CompanyCustomData as ModelCompanyCustomData;

/**
 * Interface PromotionSeasonRulesInterface
 */
interface PromotionSeasonRulesInterface
{
    /**
     * Verify if the product apply the rules for promotion season
     *
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function isPromotionSeasonProduct(ProductInterface $product): bool;

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isPromotionSeasonProductBySku(string $sku): bool;

    /**
     * @return ModelCompanyCustomData
     */
    public function getCompanyCustomData(): ModelCompanyCustomData;

    /**
     * @return bool
     */
    public function isCompanySeason(): bool;
}
