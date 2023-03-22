<?php

namespace Wagento\Catalog\Block\Adminhtml\Order\Create\Search\Grid\Renderer;

/**
 * Class Checkbox
 */
class Checkbox extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Checkbox
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
