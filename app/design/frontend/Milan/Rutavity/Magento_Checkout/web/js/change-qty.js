
define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.changeQty', {
        options: {
        },

        /**
         * Create component
         * @private
         */
        _create: function () {
            $(this.options.addQty).on('click', $.proxy(function (){
                this.addQty();
            }, this));

            $(this.options.delQty).on('click', $.proxy(function (){
                this.delQty();
            }, this));

            $(this.options.qty).on('blur', $.proxy(function (){
                this.changeQty();
            }, this));
        },

        /**
         * Add qty
         */
        addQty: function () {
            let qty = parseInt($(this.options.qty).val());
            $(this.options.qty).val(qty + 1);
            $(this.options.form).submit();
        },

        /**
         * Discrese qty
         */
        delQty: function () {
            let qty = parseInt($(this.options.qty).val());
            if ((qty - 1) <= 0) {
                return;
            }
            $(this.options.qty).val(qty - 1);
            $(this.options.form).submit();
        },

        /**
         * Change qty directly in input text
         */
        changeQty: function () {
            let qty = parseInt($(this.options.qty).val());
            if (qty <= 0) {
                return;
            }
            $(this.options.qty).val(qty);
            $(this.options.form).submit();
        }
    });

    return $.mage.changeQty;
});
