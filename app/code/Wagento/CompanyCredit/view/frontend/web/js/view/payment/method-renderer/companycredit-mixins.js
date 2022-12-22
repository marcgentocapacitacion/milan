/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
define(function () {
    'use strict';

    var mixin = {
        /**
         * Verify if you have permission
         * @return boolean
         */
        isAllowedShowCredit: function () {
            return window.checkoutConfig.payment.is_allowed_show_credit;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
