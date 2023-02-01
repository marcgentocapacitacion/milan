<?php
    
namespace ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice;
    
class Delete extends \ITM\OutstandingPayments\Controller\Adminhtml\Sapinvoice
{
    
    protected $_osp_helper;
    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \ITM\MagB1\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \ITM\OutstandingPayments\Helper\Data $data
        ) {
            $this->_osp_helper = $data;
            parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('ITM\OutstandingPayments\Model\Sapinvoice');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                
                // Delete the file.
                $destinationPath = $this->getDestinationPath() . "/".$model->getData("sap_company")."/".md5($model->getData("doc_entry"))."/";
                
               
                $destinationFilePath = $destinationPath . $model->getPath();
                
                if (file_exists($destinationFilePath)) {
                    unlink($destinationFilePath);
                    $this->messageManager->addSuccess(__('File has been deleted successfully.'));
                }
                // end delete file
                
                
                $this->_redirect('itm_outstandingpayments/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('itm_outstandingpayments/*/edit', [
                        'id' => $this->getRequest()->getParam('id')
                ]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('itm_outstandingpayments/*/');
    }
    private function getDestinationPath()
    {
        return $this->_osp_helper->getInvoiceFilesPath();
    }
}
