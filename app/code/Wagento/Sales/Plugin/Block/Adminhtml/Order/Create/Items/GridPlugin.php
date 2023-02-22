<?php

namespace Wagento\Sales\Plugin\Block\Adminhtml\Order\Create\Items;

use Magento\Framework\AuthorizationInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Block\Adminhtml\Order\Create\Items\Grid;

/**
 * Class GridPlugin
 */
class GridPlugin
{
    /**
     * @var AuthorizationInterface
     */
    protected AuthorizationInterface $authorization;

    /**
     * @param AuthorizationInterface $authorization
     */
    public function __construct(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @param Grid $subject
     * @param Item $item
     * @param bool $result
     *
     * @return bool
     */
    public function afterCanApplyCustomPrice(Grid $subject, $result, $item)
    {
        if (!$this->authorization->isAllowed('Magento_Sales::can_custom_price')) {
            return false;
        }
        return $result;
    }
}
