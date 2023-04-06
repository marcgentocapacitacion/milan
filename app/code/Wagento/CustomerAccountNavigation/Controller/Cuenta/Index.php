<?php

namespace Wagento\CustomerAccountNavigation\Controller\Cuenta;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

/**
 * Class Index
 */
class Index implements ActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var ResultFactory
     */
    protected ResultFactory $resultFactory;

    /**
     * @param PageFactory   $resultPageFactory
     * @param Session       $customerSession
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Session $customerSession,
        ResultFactory $resultFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->resultPageFactory->create();
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath(\Magento\Customer\Model\Url::ROUTE_ACCOUNT_LOGIN);
        return $resultRedirect;
    }
}
