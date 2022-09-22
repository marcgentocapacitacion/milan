define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict'

    return Component.extend({
        urlAjax: '',
        elementAppendData: '',
        page: ko.observable(1),
        container: '',
        defaults: {
            template: 'Wagento_Sales/pagination'
        },

        /**
         * Init
         */
        initialize: function () {
            this._super();
        },

        /**
         * Add number of page
         */
        getChangePage: function () {
            let p = parseInt(this.page());
            this.page(p+1);
            return this.page();
        },

        /**
         * Load more data
         */
        loadMore: function () {
            var self = this;
            $.ajax({
                url: self.urlAjax,
                method: 'GET',
                dataType: "html",
                data: {
                    p: self.getChangePage()
                },
                success: function (response) {
                    if (!response) {
                        $(self.container).html('');
                        return;
                    }
                    $(self.elementAppendData).append(response);
                }
            });
        }
    });
});
