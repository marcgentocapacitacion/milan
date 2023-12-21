/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

define([
    'jquery',
    'mage/mage'
], function ($) {
    'use strict';

    return function (data) {
        $($.mage.redirect(data.url, 'assign', 10));
    };
});
