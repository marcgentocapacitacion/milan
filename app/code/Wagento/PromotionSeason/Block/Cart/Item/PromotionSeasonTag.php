<?php

namespace Wagento\PromotionSeason\Block\Cart\Item;

use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\PromotionSeason\Model\PromotionSeasonRulesInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Framework\View\Element\Template;
use Wagento\IntegrationERP\Model\CompanyCustomData;

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
     * @var PromotionSeasonRulesInterface
     */
    protected PromotionSeasonRulesInterface $promotionSeasonRules;

    /**
     * @var ItemInterface|null
     */
    protected ?ItemInterface $item;

    /**
     * @var CompanyCustomData|null
     */
    protected ?CompanyCustomData $companyCustomData;

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
        $this->item = null;
        $this->companyCustomData = null;
    }

    /**
     * @param ItemInterface $item
     *
     * @return $this
     */
    public function setItem(ItemInterface $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getText(): string
    {
        if ($this->isRowTotalExccedLimit()) {
            return sprintf($this->config->getTextPromotionCartMoreThan(), $this->config->getDateLimitPay());;
        }
        return sprintf($this->config->getTextPromotionCartLessThan(), $this->config->getDateLimitPay());
    }

    /**
     * @return bool
     */
    public function isRowTotalExccedLimit(): bool
    {
        // $rowTotal = (float)$this->item->getQuote()->getSubtotal();
        $rowTotal = (float) ($this->item->getPrice() * $this->item->getQty());
        $limit = $this->config->getValuePromotionLimit();
        return $rowTotal > $limit;
    }

    /**
     * @return bool
     */
    public function isPromotionSeasonProduct(): bool
    {
        if (!$this->item->getProduct()) {
            return false;
        }
        return $this->promotionSeasonRules->isPromotionSeasonProduct($this->item->getProduct());
    }

    /**
     * @return CompanyCustomData
     */
    public function getCompanyCustomData(): CompanyCustomData
    {
        if (!$this->companyCustomData) {
            $this->companyCustomData = $this->promotionSeasonRules->getCompanyCustomData();
        }
        return $this->companyCustomData;
    }
}
