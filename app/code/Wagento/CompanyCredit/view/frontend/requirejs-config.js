var config = {
    'map': {
        '*': {
            'Magento_CompanyCredit/template/payment/companycredit-form.html':
                'Wagento_CompanyCredit/template/payment/companycredit-form.html',
            'Magento_PurchaseOrder/template/payment/companycredit-form.html':
                'Wagento_CompanyCredit/template/payment/companycredit-form.html'
        }
    },
    config: {
        mixins: {
            'Magento_CompanyCredit/js/view/payment/method-renderer/companycredit': {
                'Wagento_CompanyCredit/js/view/payment/method-renderer/companycredit-mixins': true
            }
        }
    }
};
