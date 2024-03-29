<?php

namespace Wagento\Catalog\Model;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 */
class Config extends \Magento\Framework\App\Config implements ConfigInterface
{
    /**
     * @var string
     */
    protected string $configPath = '';

    /**
     * @var array|null
     */
    protected ?array $categories;

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $rootCategory = '';

    /**
     * @param string $page
     *
     * @return bool
     */
    public function setPage(string $page): bool
    {
        $categories = $this->getValue(self::CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        if ($categories) {
            $categories = \json_decode($categories, true);
            if (!isset($categories[$page])) {
                return false;
            }
            $this->categories = explode(',', $categories[$page]['categories'] ?? []);
            $this->title = $categories[$page]['title'] ?? '';
            $this->rootCategory = $categories[$page]['root_category'] ?? '';
            $this->configPath = self::CONFIG_PATH . $page;
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getUseAlmacenForStock(): bool
    {
        return $this->isSetFlag(self::USE_ALMACEN_FOR_STOCK) ?? false;
    }

    /**
     * @param string|null $page
     *
     * @return array|null
     */
    public function getCategories(string $page = null): ?array
    {
        if (!$this->categories) {
            $this->setPage($page);
        }

        return $this->categories;
    }

    /**
     * @param string|null $page
     *
     * @return array|null
     */
    public function getRootCategory(string $page = null): ?string
    {
        if (!$this->categories) {
            $this->setPage($page);
        }

        $rootCategory = explode(',', $this->rootCategory) ?? false;
        return $rootCategory[0] ?? $this->rootCategory;
    }

    /**
     * @param string|null $page
     *
     * @return string
     */
    public function getTitle(string $page = null): string
    {
        if (!$this->title) {
            $this->setPage($page);
        }
        return $this->title ?? '';
    }
}
