<?php

namespace Wagento\Catalog\Controller\Category;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category\Attribute\LayoutUpdateManager;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Wagento\Catalog\Model\Config;

/**
 * Class View
 */
class View extends \Magento\Catalog\Controller\Category\View
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private Resolver $layerResolver;

    /**
     * @param PageFactory                 $resultPageFactory
     * @param Config                      $config
     * @param Resolver                    $layerResolver
     * @param Context                     $context
     * @param Design                      $catalogDesign
     * @param Session                     $catalogSession
     * @param Registry                    $coreRegistry
     * @param StoreManagerInterface       $storeManager
     * @param CategoryUrlPathGenerator    $categoryUrlPathGenerator
     * @param ForwardFactory              $resultForwardFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ToolbarMemorizer|null       $toolbarMemorizer
     * @param LayoutUpdateManager|null    $layoutUpdateManager
     * @param CategoryHelper|null         $categoryHelper
     * @param LoggerInterface|null        $logger
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Config $config,
        Resolver $layerResolver,
        Context $context,
        Design $catalogDesign,
        Session $catalogSession,
        Registry $coreRegistry,
        StoreManagerInterface $storeManager,
        CategoryUrlPathGenerator $categoryUrlPathGenerator,
        ForwardFactory $resultForwardFactory,
        CategoryRepositoryInterface $categoryRepository,
        ToolbarMemorizer $toolbarMemorizer = null,
        ?LayoutUpdateManager $layoutUpdateManager = null,
        CategoryHelper $categoryHelper = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct(
            $context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultPageFactory,
            $resultForwardFactory,
            $layerResolver,
            $categoryRepository,
            $toolbarMemorizer,
            $layoutUpdateManager,
            $categoryHelper,
            $logger
        );
        $this->config = $config;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $pageCategory = $this->getRequest()->getParam('category', false);
        if (!$pageCategory) {
            return parent::execute();
        }

        if (!$this->config->setPage($pageCategory)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('');
        }

        $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
        try {
            $category = $this->categoryRepository->get($rootCategoryId);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        $this->layerResolver->create(  'custom_category');
        $this->_coreRegistry->register('current_category', $category);
        $this->_coreRegistry->register('custom_category_filter', $this->config->getCategories());
        $this->layerResolver->get()->setCurrentCategory($rootCategoryId);
        $page = $this->resultPageFactory->create();
        $page->addPageLayoutHandles([
            'type' => 'layered'],
            null,
            false
        );
        $page->addPageLayoutHandles([
            'displaymode' => strtolower($category->getDisplayMode())],
            null,
            false
        );
        $page->addPageLayoutHandles(['id' => $category->getId()]);
        $page->getConfig()->addBodyClass('page-products')
            ->addBodyClass('category-' . $category->getUrlKey());
        $page->getConfig()->getTitle()->set($this->config->getTitle());
        return $page;
    }
}
