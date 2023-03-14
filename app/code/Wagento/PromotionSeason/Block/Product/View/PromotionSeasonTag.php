<?php

namespace Wagento\PromotionSeason\Block\Product\View;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\PromotionSeason\Model\PromotionSeasonRulesInterface;
use Magento\Framework\Registry;

/**
 * Class PromotionSeasonTag
 */
class PromotionSeasonTag extends \Wagento\PromotionSeason\Block\PromotionSeasonTag
{
    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var Json
     */
    protected Json $json;

    /**
     * @param Template\Context              $context
     * @param ConfigInterface               $config
     * @param PromotionSeasonRulesInterface $promotionSeasonRules
     * @param Registry                      $coreRegistry
     * @param Json                          $json
     * @param array                         $data
     */
    public function __construct(
        Template\Context $context,
        ConfigInterface $config,
        PromotionSeasonRulesInterface $promotionSeasonRules,
        Registry $coreRegistry,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $config, $promotionSeasonRules, $data);
        $this->coreRegistry = $coreRegistry;
        $this->json = $json;
        $this->setProduct($this->coreRegistry->registry('product'));
    }

    /**
     * @return string
     */
    public function getOptionsIdIsPromotionSeason(): string
    {
        $result = [];
        if (!$this->config->getEnable()) {
            return $this->json->serialize($result);
        }

        if ($this->isProductSimple()) {
            return $this->json->serialize($result);
        }
        $options = $this->product->getTypeInstance()->getConfigurableOptions($this->product) ?? [];
        foreach ($options as $attributeId => $attribute) {
            foreach ($attribute ?? [] as $option) {
                $result[$attributeId][$option['value_index']] = $this->promotionSeasonRules
                    ->isPromotionSeasonProductBySku($option['sku']);
            }
        }
        return $this->json->serialize($result);
    }

    /**
     * @return bool
     */
    public function isProductSimple(): bool
    {
        return $this->product->getTypeId() == 'simple';
    }
}
