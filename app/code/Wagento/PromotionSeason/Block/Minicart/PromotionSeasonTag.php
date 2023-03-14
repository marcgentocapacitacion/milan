<?php

namespace Wagento\PromotionSeason\Block\Minicart;

use Magento\Framework\Serialize\Serializer\Json;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\PromotionSeason\Model\PromotionSeasonRulesInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Block\Cart\AbstractCart;

/**
 * Class PromotionSeasonTag
 */
class PromotionSeasonTag extends AbstractCart
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
     * @var Json
     */
    protected Json $json;

    /**
     * @param Context                       $context
     * @param CustomerSession               $customerSession
     * @param CheckoutSession               $checkoutSession
     * @param ConfigInterface               $config
     * @param PromotionSeasonRulesInterface $promotionSeasonRules
     * @param Json                          $json
     * @param array                         $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        ConfigInterface $config,
        PromotionSeasonRulesInterface $promotionSeasonRules,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $customerSession, $checkoutSession, $data);
        $this->config = $config;
        $this->json = $json;
        $this->promotionSeasonRules = $promotionSeasonRules;
    }

    /**
     * @return string
     */
    public function getLabelPromotion(): string
    {
        return $this->config->getLabelPromotion();
    }

    /**
     * @return string
     */
    public function getPromotionSeasonProductAllItems(): string
    {
        $result = [];
        foreach ($this->getQuote()->getAllItems() as $item) {
            $product = $item->getProduct();
            $result[$product->getSku()] = $this->promotionSeasonRules->isPromotionSeasonProduct($product);
        }
        return $this->json->serialize($result);
    }
}
