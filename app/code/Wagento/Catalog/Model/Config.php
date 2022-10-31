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
    public const CONFIG_PATH = 'wagento_catalog/custom_pages_categories/custom_pages_brands';

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
            $this->setPage($page);
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
        if (!$this->title) {
            $this->setPage($page);
        }
        return $this->title ?? '';
    }
}
