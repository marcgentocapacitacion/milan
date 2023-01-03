define([
    'jquery',
    'Magento_Ui/js/grid/provider'
], function ($, Element) {
    'use strict';

    return Element.extend({
        /**
         * Reloads data with current parameters.
         *
         * @returns {Promise} Reload promise object.
         */
        reload: function (options) {
            this.params['search_text'] = $('#search_text').val();
            var request = this.storage().getData(this.params, options);
            this.trigger('reload');
            request
                .done(this.onReload)
                .fail(this.onError.bind(this));

            return request;
        }
    });
});
