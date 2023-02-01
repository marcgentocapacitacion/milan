<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace  ITM\OutstandingPayments\Plugin\Sales;

class Order
{
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone


    ) {
        $this->_objectManager = $objectManager;
         $this->timezone = $timezone;
        $this->localeResolver = $localeResolver;
    }


    ////////////////////////////////////////// Not Applied Now /////////////////////////////
    /**
     * {@inheritDoc}
     */
    public function afterSetTemplateId(\Magento\Sales\Model\Order\Email\Container\Template $subject, $id) {
        $vars = $subject->getTemplateVars();
        if(isset($vars["order"])) {
            $order = $vars["order"];
            $items =  $order->getAllVisibleItems();
            $isOsP = false;
            foreach ($items as $item) {
                if($item->getSku()=="sap_invoice") {
                    $isOsP = true;
                    break;
                }
            }
            if($isOsP && !isset($vars["invoice"])) {
                if ($subject->getTemplateId() != "itm_osp_order_new") {
                    $subject->setTemplateId("itm_osp_order_new");
                    $id = "itm_osp_order_new";
                }
            }
            if($isOsP && isset($vars["invoice"])) {
                if ($subject->getTemplateId() != "itm_osp_invoice_new") {
                    $subject->setTemplateId("itm_osp_invoice_new");
                    $id = "itm_osp_invoice_new";
                }
            }

        }
        return $id;
    }

}