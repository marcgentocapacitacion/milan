<?php

namespace Wagento\Affiliate\Block\Adminhtml\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Wagento\Affiliate\Model\AffiliateFactory as ModelAffiliate;
use Wagento\Affiliate\Model\ResourceModel\Affiliate as ResourceModelAffiliate;

/**
 * Class DeleteButton.
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var ModelAffiliate
     */
    protected ModelAffiliate $affiliateFactory;

    /**
     * @var ResourceModelAffiliate
     */
    protected ResourceModelAffiliate $resource;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var int
     */
    protected $affiliateId;

    /**
     * @param ModelAffiliate         $affiliateFactory
     * @param ResourceModelAffiliate $resource
     * @param UrlInterface           $urlBuilder
     * @param RequestInterface       $request
     */
    public function __construct(
        ModelAffiliate $affiliateFactory,
        ResourceModelAffiliate $resource,
        UrlInterface $urlBuilder,
        RequestInterface $request
    ) {
        $this->affiliateFactory = $affiliateFactory;
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
    }

    /**
     * @return mixed|null
     */
    public function getAffiliateId()
    {
        if ($this->affiliateId) {
            return $this->affiliateId;
        }
        $model = $this->affiliateFactory->create();
        $this->resource->load(
            $model,
            $this->request->getParam('id'),
            'entity_id'
        );

        $this->affiliateId = $model->getId() ?: null;
        return $this->affiliateId;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getAffiliateId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->urlBuilder->getUrl('affiliate/manager/delete', ['id' => $this->getAffiliateId()]);
    }
}
