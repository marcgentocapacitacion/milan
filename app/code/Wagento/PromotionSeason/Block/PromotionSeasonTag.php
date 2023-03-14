<?php

namespace Wagento\PromotionSeason\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\PromotionSeason\Model\PromotionSeasonRulesInterface;

/**
 * Class PromotionSeasonTag
 */
class PromotionSeasonTag extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var Product|null
     */
    protected ?Product $product = null;

    /**
     * @var PromotionSeasonRulesInterface
     */
    protected PromotionSeasonRulesInterface $promotionSeasonRules;

    /**
     * @param Template\Context              $context
     * @param ConfigInterface               $config
     * @param PromotionSeasonRulesInterface $promotionSeasonRules
     * @param array                         $data
     */
    public function __construct(
        Template\Context $context,
        ConfigInterface $config,
        PromotionSeasonRulesInterface $promotionSeasonRules,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->promotionSeasonRules = $promotionSeasonRules;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function setProduct(Product $product): PromotionSeasonTag
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelPromotion(): string
    {
        return $this->config->getLabelPromotion();
    }

    /**
     * @return bool
     */
    public function isPromotionSeasonProduct(): bool
    {
        if (!$this->config->getEnable()) {
            return false;
        }

        if (!$this->product) {
            return false;
        }
        return $this->promotionSeasonRules->isPromotionSeasonProduct($this->product);
    }
}
