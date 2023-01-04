<?php

namespace Wagento\PurchaseOrder\Ui\Component\MyPurchaseOrderListing;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Api\Search\FilterGroupBuilder;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var FilterGroupBuilder
     */
    protected FilterGroupBuilder $filterGroupBuilder;

    /**
     * @param string                $name
     * @param string                $primaryFieldName
     * @param string                $requestFieldName
     * @param ReportingInterface    $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface      $request
     * @param FilterGroupBuilder    $filterGroupBuilder
     * @param FilterBuilder         $filterBuilder
     * @param array                 $meta
     * @param array                 $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if ($this->request->getParam('search_text')) {
            $orderIncrementId = $this->filterBuilder->setField('order_increment_id')
                ->setValue($this->request->getParam('search_text'))
                ->setConditionType('eq')
                ->create();
            $incrementId = $this->filterBuilder->setField('main_table.increment_id')
                ->setValue($this->request->getParam('search_text'))
                ->setConditionType('eq')
                ->create();
            $snapshot = $this->filterBuilder->setField('main_table.snapshot')
                ->setValue('%' . $this->request->getParam('search_text') . '%')
                ->setConditionType('like')
                ->create();
            $filterGroup = $this->filterGroupBuilder
                ->setFilters([$orderIncrementId, $incrementId, $snapshot])
                ->create();
            $this->getSearchCriteria()->setFilterGroups([$filterGroup]);
        }
        return parent::getData();
    }
}
