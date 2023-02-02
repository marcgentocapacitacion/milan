<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding Payments
 * Copyright (C) 2017
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace  ITM\OutstandingPayments\Plugin\Block\Order;


class History
{
    
    protected $_osp_helper;
    
    
    public function __construct(
        \ITM\OutstandingPayments\Helper\Data $datar
        ) {
            $this->_osp_helper = $datar;
    }
    
    public function afterGetOrders(\Magento\Sales\Block\Order\History $subject, $result)
    {
        if($this->_osp_helper->HideOrders()) {
            $result->getSelect()
            ->where("((`itm_order_type` ='' ||`itm_order_type` IS NULL ))");
        }
        return  $result ;
    }
}