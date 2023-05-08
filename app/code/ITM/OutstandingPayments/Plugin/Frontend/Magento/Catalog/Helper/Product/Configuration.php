<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Plugin\Frontend\Magento\Catalog\Helper\Product;

class Configuration
{

    /**
     * @var \ITM\OutstandingPayments\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $priceHelper;

    /**
     * @param \ITM\OutstandingPayments\Helper\Data $helper
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    public function __construct(
        \ITM\OutstandingPayments\Helper\Data $helper,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        $this->helper = $helper;
        $this->priceHelper = $priceHelper;
    }

    public function afterGetOptions(
        \Magento\Catalog\Helper\Product\Configuration $subject,
        $result,
        $item
    ) {
        $_options = $result;
        $docEntry = 0;
        $docType = "";
        $docNum = 0;
        foreach ($_options as $_option) {
            if ($_option['label'] == "DocEntry") {
                $docEntry = $_option["value"];
            }
            if ($_option['label'] == "Type") {
                $docType = $_option["value"];
            }

        }

        $invoice = $this->helper->getDocNumByDocEntry($docEntry, $docType);
        $docNum = $invoice->getDocNum();

        $_newOptions = [];
        foreach ($_options as $_option) {
            if ($_option['label'] == "DocEntry") {
                $_option["label"] = __("Doc Num");
                $_option["value"] = $docNum;
            }
            if ($_option['label'] == "Amount") {
                $_option["label"]  = __("Payment Amount");
                $_option["value"] =  $this->priceHelper->currency($_option["value"], true, false);
            }
            $_newOptions[] = $_option;
        }

        return $_newOptions;
    }
}
