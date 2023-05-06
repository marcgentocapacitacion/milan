<?php

namespace Wagento\OutstandingPayments\Block\Adminhtml\Order\View\Tab;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Context;
use Magento\Sales\Model\Order;

/**
 * Class InvoicePaymentErp
 */
class InvoicePaymentErp extends \Magento\Framework\View\Element\Text\ListText implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var Registry
     */
    protected Registry $coreRegistry;
    /**
     * @param Context  $context
     * @param Registry $coreRegistry
     * @param array    $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Retrieve order model instance
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * @inheritDoc
     */
    public function getTabLabel()
    {
        return __("ERP's Invoices");
    }

    /**
     * @inheritDoc
     */
    public function getTabTitle()
    {
        return __("ERP's Invoices");
    }

    /**
     * @inheritDoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isHidden()
    {
        return false;
    }
}
