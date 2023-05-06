<?php

namespace Wagento\OutstandingPayments\Ui\Component\Listing\Column\InvoiceStatus;

use ITM\OutstandingPayments\Model\System\Config\InvoiceStatus;

/**
 * Class Options
 */
class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var InvoiceStatus
     */
    protected InvoiceStatus $invoiceStatus;

    /**
     * @var array|null
     */
    protected ?array $options;

    /**
     * @param InvoiceStatus $invoiceStatus
     */
    public function __construct(InvoiceStatus $invoiceStatus)
    {
        $this->invoiceStatus = $invoiceStatus;
        $this->options = null;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            foreach ($this->invoiceStatus->toOptionArray() as $id => $invoiceStatus) {
                $this->options[] = [
                    'value' => $id,
                    'label' => $invoiceStatus
                ];
            }
        }
        return $this->options;
    }
}
