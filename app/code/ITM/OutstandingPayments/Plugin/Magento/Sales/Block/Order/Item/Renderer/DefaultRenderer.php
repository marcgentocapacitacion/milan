<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ITM\OutstandingPayments\Plugin\Magento\Sales\Block\Order\Item\Renderer;

class DefaultRenderer
{
    /**
     * @var \ITM\OutstandingPayments\Helper\Data
     */
    private $helper;


    /**
     * DefaultRenderer constructor.
     * @param \ITM\OutstandingPayments\Helper\Data $helper
     */
    public function __construct(\ITM\OutstandingPayments\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    public function afterGetItemOptions(
        \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer $subject,
        $result
    ) {

        if($subject->getItem()->getSku() != "sap_invoice") {
            return $result;
        }
        $docEntry = "";
        $docType = "";
        $company = $this->helper->getCustomerSapCompany();

        $oldResult = $result;
        $newResult = [];
        foreach ($oldResult as $_option) {
            if($_option["label"] != "Type") continue;
            if($_option["label"] == "Type") {
                if($_option["value"] == "Down Payment") {
                    $docType = "dt";
                }else if($_option["value"] == "Invoice") {
                    $docType = "in";
                }
            }

        }


        foreach ($oldResult as &$_option) {
            if($_option["label"] != "DocEntry")  continue;
            $_option["label"] = "Document Number";
            $docEntry = $_option["value"] ;

            $invoice = $this->helper->getInvoice($docEntry, $company, $docType);
            $_option["value"] = $invoice->getDocNum();
           // $newResult[] = $_option;
        }
        //var_dump($oldResult);


        return $oldResult;
    }
}

