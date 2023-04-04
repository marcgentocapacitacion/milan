<?php
/**
 * Add company & sales representative to order invoice collection
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\SalesRepresentative\Plugin\Framework\View\Element\UiComponent\DataProvider;

use Magento\Framework\Data\Collection;
use Magento\Framework\DB\Select;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory as OrigFactory;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection as SalesOrderInvoiceGridCollection;

/**
 * Class CollectionFactory
 */
class CollectionFactory
{
    /**
     * Const SALES_ORDER_INVOICE_GRID_DATA_SOURCE: contains source name
     */
    const SALES_ORDER_INVOICE_GRID_DATA_SOURCE = 'sales_order_invoice_grid_data_source';

    /**
     * @var SalesOrderInvoiceGridCollection
     */
    private $collection;

    /**
     * @param SalesOrderInvoiceGridCollection $collection
     */
    public function __construct(
        SalesOrderInvoiceGridCollection $collection
    ) {
        $this->collection = $collection;
    }

    /**
     * Get report collection
     *
     * @param OrigFactory $subject
     * @param \Closure $proceed
     * @param string $requestName
     *
     * @return Collection
     * @throws \Exception
     */
    public function aroundGetReport(
        OrigFactory $subject,
        \Closure $proceed,
                    $requestName
    ) {
        $result = $proceed($requestName);

        // If order_invoice grid
        if ($requestName == self::SALES_ORDER_INVOICE_GRID_DATA_SOURCE) {
            foreach ($this->getAdditionalTables() as $tableName => $tableParam) {
                $this->joinAdditionalTable(
                    $result->getSelect(),
                    $result->getTable($tableName),
                    $tableParam['alias'],
                    $tableParam['condition'],
                    $tableParam['columns']
                );
            }
        }

        return $result;
    }

    /**
     * Get additional tables.
     *
     * @return array
     */
    private function getAdditionalTables(): array
    {
        return [
            'sales_order' => [
                'alias' => 'sales_order',
                'condition' => 'sales_order.entity_id = main_table.order_id',
                'columns' => [
                ],
            ],
            'customer_entity' => [
                'alias' => 'customer',
                'condition' => 'customer.entity_id = sales_order.customer_id',
                'columns' => [
                ],
            ],
            'company_advanced_customer_entity' => [
                'alias' => 'company_customer',
                'condition' => 'company_customer.customer_id = customer.entity_id',
                'columns' => [
                ],
            ],
            'company' => [
                'alias' => 'company',
                'condition' => 'company.entity_id = company_customer.company_id',
                'columns' => [
                ],
            ],
            'admin_user' => [
                'alias' => 'sales_representative',
                'condition' => 'company.sales_representative_id = sales_representative.user_id',
                'columns' => [
                    'sales_representative_username' => 'sales_representative.username',
                ],
            ],
        ];
    }

    /**
     * Join additional table to select.
     *
     * @param Select $select
     * @param string $tableName
     * @param string $tableAlias
     * @param string $condition
     * @param array $columns
     * @return void
     */
    private function joinAdditionalTable(
        Select $select,
        string $tableName,
        string $tableAlias,
        string $condition,
        array $columns
    ) {
        $usedTables = array_column($select->getPart(Select::FROM), 'tableName');
        if (!\in_array($tableName, $usedTables, true)) {
            $select->joinLeft([$tableAlias => $tableName], $condition, $columns);
        }
    }
}
