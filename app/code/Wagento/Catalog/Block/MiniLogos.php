<?php

namespace Wagento\Catalog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class MiniLogos
 */
class MiniLogos extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $config;

    /**
     * @param Template\Context     $context
     * @param ScopeConfigInterface $config
     * @param array                $data
     */
    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfigBrands(): array
    {
        $config = $this->config->getValue(
            'wagento_catalog/custom_pages_categories/custom_pages_brands',
            ScopeInterface::SCOPE_STORE
        );

        if (!$config) {
            return [];
        }

        return \json_decode($config, true);
    }
}
