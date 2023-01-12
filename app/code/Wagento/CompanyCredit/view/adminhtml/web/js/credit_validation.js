require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'Magento_Ui/js/lib/validation/utils',
        'jquery',
        'mage/translate'
    ], function(validator, utils, $ ) {
        // Extend "validate-zero-or-greater" validation that allows comma
        validator.addRule(
            'validate-zero-or-greater-with-comma',
            function (value) {
                value = value.replace(/,/g, ''); // remove comma from string
                return utils.isEmptyNoTrim(value) || !isNaN(utils.parseNumber(value))
                    && value >= 0;
            },
            $.mage.__('Please enter a number 0 or greater.')
        );
    }
);
