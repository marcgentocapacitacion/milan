<?php

namespace Wagento\CustomerAccountNavigation\Block;

use ITM\MagB1\Helper\Data;

/**
 * Class AllOrdersLink
 */
class AllOrdersLink extends \Magento\Customer\Block\Account\SortLink
{
    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface      $defaultPath
     * @param Data                                             $helper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $defaultPath, $data);
    }

    /**
     * @return string|void
     */
    protected function _toHtml()
    {
        if(!$this->helper->displayAllOrders()) {
            return;
        }
        return parent::_toHtml();
    }
}
