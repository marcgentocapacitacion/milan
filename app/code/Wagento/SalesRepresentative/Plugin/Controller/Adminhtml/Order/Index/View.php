<?php
/**
 * Check customer sales representative permission on Order view pages
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\SalesRepresentative\Plugin\Controller\Adminhtml\Order\Index;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Wagento\SalesRepresentative\Helper\Data as SalesRepresentativeHelper;

class View
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
     * @param \Magento\Sales\Controller\Adminhtml\Order\View $subject
     * @param \Closure $proceed
     * @return Redirect
     */
    public function aroundExecute(
        \Magento\Sales\Controller\Adminhtml\Order\View $subject,
        \Closure $proceed
    ) {
        $orderId = (int) $subject->getRequest()->getParam('order_id');

        // If not allowed to access for sales representative user
        if (! $this->salesRepresentativeHelper->isOrderViewAllowed($orderId)) {
            // add error message
            $this->messageManager->addErrorMessage(__('You do not have permission to view this order.'));

            // set url to redirect
            $resultRedirect = $this->resultFactory->create(
                ResultFactory::TYPE_REDIRECT
            );
            $url = $this->_backendUrl->getUrl("sales/order");

            // redirect page to the index page
            $result = $resultRedirect->setUrl($url);

        } else {
            $result = $proceed();
        }

        return $result;
    }
}
