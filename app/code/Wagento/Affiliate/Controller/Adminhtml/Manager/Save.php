<?php

namespace Wagento\Affiliate\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action\Context;
use Wagento\Affiliate\Model\ResourceModel\AffiliateFactory as ResourceModelAffiliate;
use Wagento\Affiliate\Model\AffiliateFactory as ModelAffiliate;

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
    const ADMIN_RESOURCE = 'Wagento_Affiliate::manager';

    /**
     * @var ResourceModelAffiliate
     */
    protected ResourceModelAffiliate $resource;

    /**
     * @var ModelAffiliate
     */
    protected ModelAffiliate $model;

    /**
     * @param Context                $context
     * @param ResourceModelAffiliate $resource
     * @param ModelAffiliate         $model
     */
    public function __construct(
        Context $context,
        ResourceModelAffiliate $resource,
        ModelAffiliate $model
    ) {
        parent::__construct($context);
        $this->resource = $resource;
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $model = $this->model->create();
            $model->setData('id', $this->getRequest()->getParam('id'));
            $model->setData('name', $this->getRequest()->getParam('name'));
            $model->setData('phone', $this->getRequest()->getParam('phone'));
            $model->setData('address', $this->getRequest()->getParam('address'));
            $model->setData('city', $this->getRequest()->getParam('city'));
            $model->setData('active', $this->getRequest()->getParam('active'));
            $model->setData('image', \json_encode($this->getRequest()->getParam('image')) ?? '');
            $this->resource->create()->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the affiliate.'));
            $resultRedirect->setPath('affiliate/manager/edit', [
                'id' => $model->getId()
            ]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
            $resultRedirect->setPath('affiliate/manager/edit');
        }
        return $resultRedirect;
    }
}
