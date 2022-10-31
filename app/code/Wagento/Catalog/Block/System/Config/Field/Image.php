<?php

namespace Wagento\Catalog\Block\System\Config\Field;

use Magento\Framework\View\Element\Template\Context;

/**
 * Class Image
 */
class Image extends \Magento\Framework\View\Element\Template
{
    /**
     * Override this method in descendants to produce html
     *
     * @return string
     */
    protected function _toHtml()
    {
        $name = $this->getInputName();
        $html = '';
        if ($this->getValues()) {
            $html .= "<% if({$this->getData('image')}) { %>";
            $html .= "<img style='max-height: 25px;' src='{$this->getData('image_url')}'>";
            $html .= "<br>";
            $html .= __('Delete Image');
            $html .= ": <input type='checkbox' name='{$name}[delete]'/>";
            $html .= "<input type='hidden' name='{$name}[hidden]' value='<%- {$this->getData('image')} %>'/>";
            $html .= "<% } %>";
        }
        $html .= "<input type='file' name='$name' class='input-file'/>";
        return $html;
    }
}
