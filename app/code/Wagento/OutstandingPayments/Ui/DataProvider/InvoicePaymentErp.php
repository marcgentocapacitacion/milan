<?php

namespace Wagento\OutstandingPayments\Ui\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class InvoicePaymentErp
 */
class InvoicePaymentErp extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderInterface|null
     */
    protected ?OrderInterface $currentOrder;

    /**
     * @param                          $name
     * @param                          $primaryFieldName
     * @param                          $requestFieldName
     * @param OrderRepositoryInterface $orderRepository
     * @param ReportingInterface       $reporting
     * @param SearchCriteriaBuilder    $searchCriteriaBuilder
     * @param RequestInterface         $request
     * @param FilterBuilder            $filterBuilder
     * @param array                    $meta
     * @param array                    $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        OrderRepositoryInterface $orderRepository,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->currentOrder = null;
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
    }

    /**
     * @param int $orderId
     *
     * @return OrderInterface
     */
    public function getOrder(int $orderId): OrderInterface
    {
        if (isset($this->currentOrder)) {
            return $this->currentOrder;
        }
        $this->currentOrder = $this->orderRepository->get($orderId);
        return $this->currentOrder;
    }

    /**
     * @return void
     */
    protected function prepareUpdateUrl()
    {
        if (!isset($this->data['config']['filter_url_params'])) {
            return;
        }
        foreach ($this->data['config']['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->request->getParam($paramName);
            }
            if ($paramValue) {
                $this->data['config']['update_url'] = sprintf(
                    '%s%s/%s/',
                    $this->data['config']['update_url'],
                    $paramName,
                    $paramValue
                );

                if ($paramName == 'order_id') {
                    $this->addFilter(
                        $this->filterBuilder
                            ->setField('info')
                            ->setValue('%' . $this->getOrder($paramValue)->getIncrementId() . '%')
                            ->setConditionType('like')->create()
                    );
                    continue;
                }

                $this->addFilter(
                    $this->filterBuilder->setField($paramName)->setValue($paramValue)->setConditionType('eq')->create()
                );
            }
        }
    }
}
