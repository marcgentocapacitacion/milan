<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding invoices 
 * Copyright (C) 2017  
 * 
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Block\Index;

class Closed extends \ITM\OutstandingPayments\Block\Index\Index
{
    protected $_invoiceStatus = ["c"];

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getInvoiceList($this->_invoiceStatus)) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'itm.osp.grid.invoice.pager'
            )->setCollection(
                $this->getInvoiceList($this->_invoiceStatus) // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }

    public function getInvoiceCollection() {
        
        return $this->getClosedInvoices();
    }
}