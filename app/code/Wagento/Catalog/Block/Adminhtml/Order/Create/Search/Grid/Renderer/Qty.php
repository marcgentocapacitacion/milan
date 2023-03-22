<?php

namespace Wagento\Catalog\Block\Adminhtml\Order\Create\Search\Grid\Renderer;

/**
 * Class Qty
 */
class Qty extends \Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid\Renderer\Qty
{
    /**
     * Render product qty field
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if (!$this->getColumn()->getIsSalable()($row)) {
            return __('Out of Stock');
        }
        return parent::render($row);
    }
}
