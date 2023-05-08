<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Plugin\Magento\Sales\Block\Order\Email\Items;

class DefaultItems
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

    public function afterGetItemOptions(
        \Magento\Sales\Block\Order\Email\Items\DefaultItems $subject,
        $result
    ) {

        if($subject->getItem()->getSku() != "sap_invoice") {
            return $result;
        }
        $docEntry = "";
        $docType = "";
        $docNum = "";
        $company = $this->helper->getCustomerSapCompany();

        $oldResult = $result;
        $newResult = [];
        foreach ($oldResult as $_option) {
            if ($_option["label"] == "DocEntry") {
                $docEntry = $_option["value"];
            }
            if ($_option["label"] != "Type") {
                continue;
            }
            if ($_option["label"] == "Type") {
                if ($_option["value"] == "Down Payment") {
                    $docType = "dt";
                } else {
                    if ($_option["value"] == "Invoice") {
                        $docType = "in";
                    }
                }
            }

        }
        if (!empty($docEntry) && !empty($docType)) {
            $invoice = $this->helper->getInvoice($docEntry, $company, $docType);
            $docNum = $invoice->getDocNum();
        }

        foreach ($oldResult as &$_option) {
            if ($_option["label"] == "Amount") {
                $_option["label"]  = __("Payment Amount");
                $_option["value"] = $this->priceHelper->currency($_option["value"], true, false);
            }

            if ($_option["label"] == "DocEntry") {
                $_option["label"] = "Document Number";
                $_option["value"] = $docNum;
            }
        }
        //var_dump($oldResult);


        return $oldResult;
    }
}