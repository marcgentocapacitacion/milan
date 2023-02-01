<?php


namespace ITM\OutstandingPayments\Block;


class OpenInvoicesLink extends \Magento\Framework\View\Element\Html\Link\Current
{
   
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param array $data
     */
    public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Framework\App\DefaultPathInterface $defaultPath,
            array $data = []
            
            ) {
                parent::__construct($context, $defaultPath, $data);
    }
    
    protected function _toHtml()
    {   
        return parent::_toHtml();
        
        /*if ($this->getPath()== "outstanding_payments/index" ) {
            return parent::_toHtml();
        }*/
        
        
        return ;
    }
}