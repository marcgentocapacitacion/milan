<?php

namespace ITM\MagB1\Controller\Adminhtml\Productfiles;


class Grid extends \ITM\MagB1\Controller\Adminhtml\Productfiles
{
    /**
     * Rowfactory
     *
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * Layoutfactory
     *
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * CoreRegistry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    protected $attachmentLoader;
    /**
     * Grid Class constructor
     *
     * @param \Magento\Backend\App\Action\Context             $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory           $layoutFactory
     * @param \Magento\Framework\Registry                     $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $coreRegistry,
        \ITM\MagB1\Model\ProductAttachment $attachmentLoader,
        \Magento\Framework\Controller\Result\Json $resultJson,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->attachmentLoader = $attachmentLoader;
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {

        $productattachment = $this->initAttachment();
        if (!$productattachment) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('catalog/*/', ['_current' => true, 'id' => null]);
        }

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                \ITM\MagB1\Block\Adminhtml\Productfiles\Tab\Product::class,
                'attachment.product.grid'
            )->toHtml()
        );
    }
    /**
     * InitAttachment
     *
     * @return mixed Attachnebtobject
     */
    protected function initAttachment()
    {
        $attachmentId = (int)$this->getRequest()->getParam('id');
        $attachmentId = $attachmentId ? $attachmentId : (int)$this->getRequest()->getParam('attachment_id');

        $productattachment = $this->attachmentLoader;

        if ($attachmentId) {
            $productattachment->load($attachmentId);
        }

        $this->coreRegistry->register('current_itm_magb1_productfiles', $productattachment);


        return $productattachment;
    }
}
