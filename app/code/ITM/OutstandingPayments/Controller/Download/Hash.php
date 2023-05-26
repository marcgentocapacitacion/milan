<?php

namespace ITM\OutstandingPayments\Controller\Download;

use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Hash extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \ITM\OutstandingPayments\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \ITM\OutstandingPayments\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ResourceConnection $resource,
        \ITM\OutstandingPayments\Helper\Data $dataHelper
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->resource = $resource;
        $this->helper = $dataHelper;
    }

    /**
     * execute
     */
    public function execute()
    {

        $type = $this->getRequest()->getParam("type");
        $doc_entry = $this->getRequest()->getParam("id");
        $token = $this->getRequest()->getParam("token");
        $uid = $this->getRequest()->getParam("uid");

        $shouldToken = $uid . "_" . $type . "-" . $doc_entry . "_" . date("Y-m-d_H");
        $shouldToken = hash('sha256', $shouldToken);

        if ($shouldToken != $token) {
            echo "error";
            return;
        }
        $company = $this->helper->getCustomerSapCompany($uid);
        $invoice = $this->helper->getInvoice($doc_entry, $company, $type);

        $customerEmail = $this->helper->getCustomerEmail($uid);
        if ($invoice->getEmail() != $customerEmail) {
            echo "error";
            return;
        }

        $company = $this->helper->getCustomerSapCompany($uid);
        $invoice = $this->helper->getInvoice($doc_entry, $company, $type);
        $destinationPath = $this->getDestinationPath() . "/" . $invoice->getSapCompany() . "/" . md5($doc_entry) . "/" . $invoice->getPath();

        $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);

        $file_name = sprintf("document-%s-%s.%s", $doc_entry, time(), $ext);

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Content-type', 'application/force-download')
            ->setHeader('Content-Length', filesize($destinationPath))
            ->setHeader('Content-Disposition', 'attachment' . '; filename=' . $file_name);
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile($destinationPath);
        exit;
    }

    private function getDestinationPath()
    {
        return $this->helper->getInvoiceFilesPath();
    }
}
