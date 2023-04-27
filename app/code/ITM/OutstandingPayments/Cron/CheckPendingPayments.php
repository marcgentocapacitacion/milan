<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Cron;

class CheckPendingPayments
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory
     */
    protected $_invoiceCollectionFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \ITM\OutstandingPayments\Model\ResourceModel\Sapinvoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->logger = $logger;
        $this->_invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->resource = $resource;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $collection = $this->_invoiceCollectionFactory->create();
        $invoiceCollection = $collection
            //->addFieldToFilter("sap_company", $company)
            ->addFieldToFilter("invoice_status", ["in" => "p"]);
        foreach ($invoiceCollection as $invoice) {
            \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("Checking invoice number {{$invoice->getDocEntry()}}");
            $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $salesOrder = $this->resource->getTableName('sales_order');
            $salesOrderItem = $this->resource->getTableName('sales_order_item');
            $select_query = $connection->select()->from([
                'sales_order_item' => $salesOrderItem
            ], [
                'order_id'
            ]);
            $select_query->join(
                ['sales_order_entity' => $salesOrder],
                'sales_order_entity.entity_id=sales_order_item.order_id',
                ['status','itm_sap_company']
            );
             $where = "\"label\":\"DocEntry\",\"value\":\"{$invoice->getDocEntry()}\"";
             $select_query->where("sales_order_item.product_options like '%{$where}%' and `sales_order_entity`.`itm_sap_company`  ='{$invoice->getSapCompany()}' and  `sales_order_entity`.`status`  ='complete'");
             $result = $connection->fetchAll($select_query, []);

             if(count($result) == 0) {
                 \Magento\Framework\App\ObjectManager::getInstance()->create('\ITM\MagB1\Helper\Data')->_log("Invoice status with number {$invoice->getDocEntry()} has been changed to Open");
                 $invoice->setInvoiceStatus('o');
                 $invoice->save();
             }


        }

    }
}
