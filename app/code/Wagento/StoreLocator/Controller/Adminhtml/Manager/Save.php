<?php

namespace Wagento\StoreLocator\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Wagento\StoreLocator\Model\ResourceModel\StoreLocatorFactory as ResourceModel;
use Wagento\StoreLocator\Model\StoreLocatorFactory;

/**
 * Class Save
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wagento_StoreLocator::manager';

    /**
     * @var ResourceModel
     */
    protected ResourceModel $resourceModel;

    /**
     * @var StoreLocatorFactory
     */
    protected StoreLocatorFactory $storeLocator;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        ResourceModel $resourceModel,
        StoreLocatorFactory $storeLocator
    ) {
        parent::__construct($context);
        $this->resourceModel = $resourceModel;
        $this->storeLocator = $storeLocator;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $model = $this->storeLocator->create()->setData($this->getRequest()->getParams());
            $this->resourceModel->create()->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the location.'));
            $resultRedirect->setPath('locations/manager/edit', [
                'id' => $model->getId()
            ]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
            $resultRedirect->setPath('locations/manager/edit');
        }
        return $resultRedirect;
    }
}
