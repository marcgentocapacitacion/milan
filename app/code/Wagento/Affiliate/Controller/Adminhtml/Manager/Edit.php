<?php

namespace Wagento\Affiliate\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 */
class Edit extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wagento_Affiliate::manager';

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Wagento_Affiliate::affiliate');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Affiliate'));
        return $resultPage;
    }
}
