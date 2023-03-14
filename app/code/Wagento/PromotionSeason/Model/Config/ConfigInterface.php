<?php

namespace Wagento\PromotionSeason\Model\Config;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @const string
     */
    public const ENABLE = "wagento_season_promotion/general/enable";

    /**
     * @const string
     */
    public const LABEL_PROMOTION = "wagento_season_promotion/general/label_promotion";

    /**
     * @const string
     */
    public const VALUE_PROMOTION_LIMIT = "wagento_season_promotion/general/value_promotion_limit";

    /**
     * @const string
     */
    public const TEXT_PROMOTION_CART_MORE_THAN = "wagento_season_promotion/general/text_promotion_cart_more_than";

    /**
     * @const string
     */
    public const TEXT_PROMOTION_CART_LESS_THAN = "wagento_season_promotion/general/text_promotion_cart_less_than";

    /**
     * @const string
     */
    public const ITM_PROPERTIES = "wagento_season_promotion/general/itm_properties";

    /**
     * @const string
     */
    public const DATE_LIMIT_PAY = "date_limit_pay";

    /**
     * @return bool
     */
    public function getEnable(): bool;

    /**
     * @return string
     */
    public function getLabelPromotion(): string;

    /**
     * @return float
     */
    public function getValuePromotionLimit(): float;

    /**
     * @return string
     */
    public function getTextPromotionCartMoreThan(): string;

    /**
     * @return string
     */
    public function getTextPromotionCartLessThan(): string;

    /**
     * @return string
     */
    public function getItmProperties(): string;

    /**
     * @return string
     */
    public function getDateLimitPay(): string;
}
