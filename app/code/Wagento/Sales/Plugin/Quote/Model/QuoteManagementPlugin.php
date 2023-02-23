<?php

namespace Wagento\Sales\Plugin\Quote\Model;

use Magento\Quote\Model\Quote as QuoteEntity;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Spi\OrderResourceInterface;
use Wagento\Sales\Model\System\Config\Source\OrderOrigin;

/**
 * Class QuoteManagementPlugin
 */
class QuoteManagementPlugin
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
     * @param QuoteManagement $subject
     * @param QuoteEntity     $quote
     * @param OrderInterface  $orderData
     *
     * @return OrderInterface
     */
    public function afterSubmit(QuoteManagement $subject, $result, QuoteEntity $quote, $orderData = [])
    {
        if (!$result->getOrderOrigin()) {
            $result->setOrderOrigin(OrderOrigin::ORDER_ORIGIN_STORE);
        }
        $this->orderResource->save($result);
        return $result;
    }
}
