<?php
/**
 * Check company sales representative permission on Company edit pages
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\SalesRepresentative\Plugin\Controller\Adminhtml\Company\Index;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Wagento\SalesRepresentative\Helper\Data as SalesRepresentativeHelper;

class Edit
{
    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * @var SalesRepresentativeHelper
     */
    protected $salesRepresentativeHelper;

    /**
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param RedirectInterface $redirect
     * @param UrlInterface $backendUrl
     * @param SalesRepresentativeHelper $salesRepresentativeHelper
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        RedirectInterface $redirect,
        UrlInterface $backendUrl,
        SalesRepresentativeHelper $salesRepresentativeHelper
    ) {
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
        $this->_backendUrl = $backendUrl;
        $this->messageManager = $context->getMessageManager();
        $this->salesRepresentativeHelper = $salesRepresentativeHelper;
    }


    /**
     * @param \Magento\Company\Controller\Adminhtml\Index\Edit $subject
     * @param \Closure $proceed
     * @return Redirect
     */
    public function aroundExecute(
        \Magento\Company\Controller\Adminhtml\Index\Edit $subject,
        \Closure $proceed
    ) {
        $companyId = (int) $subject->getRequest()->getParam('id');

        // If not allowed to access for sales representative user
        if (! $this->salesRepresentativeHelper->isCompanyEditAllowed($companyId)) {
            // add error message
            $this->messageManager->addErrorMessage(__('You do not have permission to edit this company.'));

            // set url to redirect
            $resultRedirect = $this->resultFactory->create(
                ResultFactory::TYPE_REDIRECT
            );
            $url = $this->_backendUrl->getUrl("company/index");

            // redirect page to the index page
            $result = $resultRedirect->setUrl($url);

        } else {
            $result = $proceed();
        }

        return $result;
    }
}
