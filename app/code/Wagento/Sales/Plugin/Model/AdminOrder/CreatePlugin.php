<?php

namespace Wagento\Sales\Plugin\Model\AdminOrder;

use Magento\Sales\Model\AdminOrder\Create;
use Magento\Sales\Model\Spi\OrderResourceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Wagento\Sales\Model\System\Config\Source\OrderOrigin;

/**
 * Class CreatePlugin
 */
class CreatePlugin
{
    /**
     * @var OrderResourceInterface
     */
    protected OrderResourceInterface $orderResource;

    /**
     * @param OrderResourceInterface $orderResource
     */
    public function __construct(OrderResourceInterface $orderResource)
    {
        $this->orderResource = $orderResource;
    }

    /**
     * @param Create $subject
     * @param OrderInterface $result
     *
     * @return OrderInterface
     */
    public function afterCreateOrder(Create $subject, $result)
    {
        $result->setOrderOrigin(OrderOrigin::ORDER_ORIGIN_ADMIN);
        $this->orderResource->save($result);
        return $result;
    }
}
