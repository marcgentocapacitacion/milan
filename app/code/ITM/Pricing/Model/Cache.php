<?php
namespace ITM\Pricing\Model;

class Cache extends \Magento\Framework\Cache\Frontend\Decorator\TagScope
{

    const TYPE_IDENTIFIER = 'itm_pricing';

    const CACHE_TAG = 'PRICING';

    public function __construct(\Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool)
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }
}
