<?php

namespace Wagento\PromotionSeason\Controller\Category;

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
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Wagento\Catalog\Model\Config;
use Wagento\PromotionSeason\Model\Config\ConfigInterface;
use Wagento\PromotionSeason\Model\PromotionSeasonRulesInterface;

/**
 * Class View
 */
class View extends \Wagento\Catalog\Controller\Category\View
{
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $configPromotionSeason;

    /**
     * @var PromotionSeasonRulesInterface
     */
    protected PromotionSeasonRulesInterface $promotionSeasonRulesInterface;

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
     * @param ConfigInterface             $configPromotionSeason
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
        ConfigInterface $configPromotionSeason,
        PromotionSeasonRulesInterface $promotionSeasonRulesInterface,
        ToolbarMemorizer $toolbarMemorizer = null,
        ?LayoutUpdateManager $layoutUpdateManager = null,
        CategoryHelper $categoryHelper = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct(
            $resultPageFactory,
            $config,
            $layerResolver,
            $context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultForwardFactory,
            $categoryRepository,
            $toolbarMemorizer,
            $layoutUpdateManager,
            $categoryHelper,
            $logger
        );
        $this->layerResolver = $layerResolver;
        $this->configPromotionSeason = $configPromotionSeason;
        $this->promotionSeasonRulesInterface = $promotionSeasonRulesInterface;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $pageCategory = $this->getRequest()->getParam('promoseasontemporada', false);
        if (!$pageCategory) {
            return parent::execute();
        }

        if (!$this->configPromotionSeason->getEnable()) {
            return parent::execute();
        }

        if (!$this->promotionSeasonRulesInterface->isCompanySeason()) {
            return parent::execute();
        }

        $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
        $category = $this->categoryRepository->get($rootCategoryId);
        $this->layerResolver->create(  'promoseasontemporada_layer');
        $this->_coreRegistry->register('current_category', $category);
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
        $page->getConfig()->getTitle()->set(__('Season'));
        return $page;
    }
}
