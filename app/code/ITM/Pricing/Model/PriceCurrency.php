<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ITM\Pricing\Model;

use Magento\Framework\App\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Store\Model\Store;

/**
 * Class PriceCurrency model for convert and format price value
 */
class PriceCurrency extends \Magento\Directory\Model\PriceCurrency implements \Magento\Framework\Pricing\PriceCurrencyInterface
{
    /**
     * Round price
     *
     * @param float $price
     * @return float
     */
    public function round($price)
    {
        return round($price, 5);
    }
}
