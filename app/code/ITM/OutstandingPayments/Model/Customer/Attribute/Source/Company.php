<?php
/**
 * Custom Module for Magento2 to Pay Your Outstanding Payments 
 * Copyright (C) 2017  
 * 
 * This file included in ITM/OutstandingPayments is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace ITM\OutstandingPayments\Model\Customer\Attribute\Source;

class Company extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    
    protected $_company;
    
    protected $_options;
    
    public function __construct(\ITM\OutstandingPayments\Model\System\Config\Company $company)
    {
        $this->_company = $company;
        
    }
    
    
    
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = $this->_company->getAllOptions();
        }
        return $this->_options;
    }
}