<?php

namespace ITM\OutstandingPayments\Model\Config\Source;
use Magento\Store\Model\ScopeInterface;

class PaymentMethods implements \Magento\Framework\Option\ArrayInterface
{
      /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Config\Model\Config\ScopeDefiner $configScope
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
     
        $result = [];
        foreach ($this->_scopeConfig->getValue('payment', ScopeInterface::SCOPE_STORE, null) as $code => $data) {
            if (isset($data['active']) && (bool) $data['active'] && isset($data['model'])) {
                $result[] = [
                    "code" => $code,
                    "model" => $data
                ];
            }
        }
        

        $options = [
            ['value' => '', 'label' => __('None')]
        ];

        /** @var \Magento\Payment\Model\Method\AbstractMethod $method */
        foreach ($result as $method) {
            $options[] = [
                'value' => $method["code"],
                'label' => $method["model"]["title"] //. " ({$method["code"]})"
            ];
        }
        
        return $options;
    }
}
