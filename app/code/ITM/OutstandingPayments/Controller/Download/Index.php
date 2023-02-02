<?php
namespace ITM\OutstandingPayments\Controller\Download;

use Psr\Log\LoggerInterface;
use \Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    
    
    protected $resource;
    
    protected $helper;
    
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
        $backURL = 'customer/account';

        
        if(!$this->helper->isValideInvoice($doc_entry,$type,false)) {
            $this->_redirect($backURL);
            return;
        }


        $company = $this->helper->getCustomerSapCompany();
        $invoice = $this->helper->getInvoice($doc_entry, $company,$type);
        $destinationPath = $this->getDestinationPath()."/".$invoice->getSapCompany()."/".md5($doc_entry)."/".$invoice->getPath();
        
        $ext = pathinfo($destinationPath, PATHINFO_EXTENSION);
        
        $file_name = sprintf("document-%s-%s.%s",$doc_entry,time(),$ext );
        
        
        
        $this->getResponse ()
        ->setHttpResponseCode ( 200 )
        ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
        ->setHeader ( 'Pragma', 'public', true )
        ->setHeader ( 'Content-type', 'application/force-download' )
        ->setHeader ( 'Content-Length', filesize($destinationPath) )
        ->setHeader ('Content-Disposition', 'attachment' . '; filename=' . $file_name);
        $this->getResponse ()->clearBody ();
        $this->getResponse ()->sendHeaders ();
        readfile ( $destinationPath);
        exit;
   
    }
    
    private function getDestinationPath()
    {
        return $this->helper->getInvoiceFilesPath();
    }
}