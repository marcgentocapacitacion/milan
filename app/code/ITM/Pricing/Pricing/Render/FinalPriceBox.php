<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ITM\Pricing\Pricing\Render;

use Magento\Catalog\Pricing\Price;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Module\Manager;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Msrp\Pricing\Price\MsrpPrice;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;

/**
 * Class for final_price rendering
 *
 * @method bool getUseLinkForAsLowAs()
 * @method bool getDisplayMinimalPrice()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{

    /**
     * @var \ITM\Pricing\Helper\Setting
     */
    protected $pricingSetting;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param \ITM\Pricing\Helper\Setting $pricingSetting
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        \ITM\Pricing\Helper\Setting $pricingSetting,
        array $data = [],
        SalableResolverInterface $salableResolver = null
        ) {
            parent::__construct($context, $saleableItem, $price, $rendererPool, $data);
            $this->pricingSetting = $pricingSetting;
    }
    
    public function getCacheLifetime()
    {
        if( $this->pricingSetting->disablePriceBoxCache()== true) {
            return null;
        }
        return parent::getCacheLifetime();
    }
}
