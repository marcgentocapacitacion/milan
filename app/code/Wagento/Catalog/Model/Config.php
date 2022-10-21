<?php

namespace Wagento\Catalog\Model;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 */
class Config extends \Magento\Framework\App\Config
{
    /**
     * @var string
     */
    public const CONFIG_PATH = 'wagento_catalog/custom_pages_categories/';

    /**
     * @var string
     */
    protected string $configPath = '';

    /**
     * @var array|null
     */
    protected ?array $categories;

    /**
     * @param string $page
     *
     * @return bool
     */
    public function setPage(string $page): bool
    {
        $categories = $this->getValue(self::CONFIG_PATH . $page, ScopeInterface::SCOPE_STORE);
        if ($categories) {
            $this->categories = explode(',', $categories);
            $this->configPath = self::CONFIG_PATH . $page;
            return true;
        }
        return false;
    }

    /**
     * @param string|null $page
     *
     * @return array|null
     */
    public function getCategories(string $page = null): ?array
    {
        if (!$this->categories) {
            $path = $this->configPath ?? self::CONFIG_PATH . $page;
            $categories = $this->getValue($path, ScopeInterface::SCOPE_STORE) ?? [];
            $this->categories = explode(',', $categories);
        }

        return $this->categories;
    }

    /**
     * @param string|null $page
     *
     * @return string
     */
    public function getTitle(string $page = null): string
    {
        $path = $this->configPath ?? self::CONFIG_PATH . $page;
        return $this->getValue($path . '_title', ScopeInterface::SCOPE_STORE) ?? '';
    }
}
