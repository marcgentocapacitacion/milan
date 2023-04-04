<?php
/**
 * Show companies by company's sales representative permission
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */
namespace Wagento\SalesRepresentative\Ui\Component\Company;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Wagento\SalesRepresentative\Helper\Data as SalesRepresentativeHelper;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{

    const SALES_REPRESENTATIVE_ATTRIBUTE_ID = 'sales_representative_id';

    /**
     * @var SalesRepresentativeHelper
     */
    protected $salesRepresentativeHelper;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param SalesRepresentativeHelper $salesRepresentativeHelper
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        SalesRepresentativeHelper $salesRepresentativeHelper,
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

        $this->salesRepresentativeHelper = $salesRepresentativeHelper;
        $this->salesRepresentativeFilter();
    }

    /**
     * Filter grid items by company's sales representative
     * @return void
     */
    public function salesRepresentativeFilter()
    {
        // check if sales representative user
        if ($this->salesRepresentativeHelper->isSalesRepresentative()) {
            // get current username
            if ($paramValue = $this->salesRepresentativeHelper->getUserId()) {
                $this->addFilter(
                    $this->filterBuilder->setField(self::SALES_REPRESENTATIVE_ATTRIBUTE_ID)
                        ->setValue($paramValue)
                        ->setConditionType('eq')
                        ->create()
                );
            }
        }
    }
}
