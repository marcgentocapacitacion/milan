<?php

namespace Wagento\Catalog\Block\Footer;

use Magento\Catalog\Model\Category as ModelCategory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Helper\Category as HelperCategory;

/**
 * Class Categories
 */
class Categories extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_Catalog::footer/categories.phtml';

    /**
     * @var HelperCategory
     */
    protected HelperCategory $helperCategory;

    /**
     * @param Context $context
     * @param array   $data
     */
    public function __construct(
        Context $context,
        HelperCategory $helperCategory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperCategory = $helperCategory;
    }

    /**
     * @return array|\Magento\Framework\Data\Tree\Node\Collection
     */
    public function getCategories()
    {
        return $this->helperCategory->getStoreCategories();
    }

    /**
     * @param ModelCategory $category
     *
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->helperCategory->getCategoryUrl($category);
    }
}
