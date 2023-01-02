<?php

namespace Wagento\Affiliate\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action\Context;
use Wagento\Affiliate\Model\ResourceModel\AffiliateFactory as ResourceModelAffiliate;
use Wagento\Affiliate\Model\AffiliateFactory as ModelAffiliate;

/**
 * Class Delete
 */
class Delete extends \Magento\Backend\App\Action
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
        $model = $this->model->create();
        try {
            if ($this->getRequest()->getParam('id')) {
                $this->resource->create()->load(
                    $model,
                    $this->getRequest()->getParam('id'),
                    'entity_id'
                );
                $this->resource->create()->delete($model);
                $this->messageManager->addSuccessMessage(__('You deleted the affiliate.'));
            }
            $resultRedirect->setPath('affiliate/manager/grid');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
            $resultRedirect->setPath('affiliate/manager/grid');
        }
        return $resultRedirect;
    }
}
