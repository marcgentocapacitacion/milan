define([
    'jquery',
    'ko',
    'mage/translate',
    'Magento_Customer/js/customer-data',
    'jquery-ui-modules/widget'
], function ($, ko, $t, customerData) {
    'use strict';

    $.widget('mage.filterhistoryorders', {
        options: {
            elementYear: '',
            elementSearchText: '',
            elementSearch: '',
            elementTabWihtData: ko.observable(''),
            elementAddHtml: ko.observable(''),
            urlAjax: ko.observable(''),
            tabsClear: [],
            tabs: {},
            pagination: ko.observable(''),
            containerPager: '',
            inputHiddenPageNumber: '',
            page: ko.observable(1),
            buttonPage: '',
            containerFilter: ''
        },

        /**
         * Add number of page
         */
        getChangePage: function () {
            let p = parseInt(this.options.page());
            this.options.page(p+1);
            return this.options.page();
        },

        /** @inheritdoc */
        _create: function () {
            this.isLoading(true);
            this._eventsTabs();
            this._events();
            $(this.options.containerFilter).fadeIn();
        },

        /**
         * Events
         * @private
         */
        _events: function () {
            var self = this;
            $(self.options.elementSearch).click(function () {
                self.isLoading(true);
                self.load();
            });

            $(self.options.elementYear).change(function () {
                self.isLoading(true);
                self.load();
            });

            $(self.options.buttonPage).click(function () {
                self.getChangePage();
                self.loadMore();
            });
        },

        /**
         * @param active
         */
        isLoading: function (active) {
            if (active) {
                $('body').loader('show');
            } else {
                $('body').loader('hide');
            }
        },

        /**
         * Event when click in tab
         * @return {boolean}
         * @private
         */
        _eventsTabs: function () {
            if (!this.options.tabs) {
                return false;
            }
            var self = this;
            this.options.tabs.forEach(function (element) {
                if (!element.urlAjax) {
                    return;
                }

                if (!element.elementTab) {
                    return;
                }

                if (element.default) {
                    self.options.urlAjax(element.urlAjax);
                    self.options.elementAddHtml(element.elementAddHtml);
                    self.load(element.urlAjax);
                    $(element.elementTabWihtData).fadeIn();
                } else {
                    $(element.elementTabWihtData).fadeOut();
                }
                self.options.tabsClear.push(element.elementTabWihtData);

                $(element.elementTab).click(function () {
                    self.isLoading(true);
                    self.options.elementAddHtml(element.elementAddHtml);
                    self._clearTabs($(this).attr('id'))
                    $(element.elementTabWihtData).fadeIn(500);
                    self.options.urlAjax(element.urlAjax);
                    self.load(element.urlAjax);
                });
            });
        },

        /**
         *
         * @param tabActive
         * @private
         */
        _clearTabs: function (tabClick) {
            var self = this;
            for (var i in this.options.tabsClear) {
                $(self.options.elementAddHtml()).html('');
                $(this.options.tabsClear[i]).hide();
                i++;
            }

            $('.tabs span').each(function () {
                let id = $(this).attr('id');
                if (tabClick == id) {
                    $(this).attr('class', 'active');
                } else {
                    $(this).removeAttr('class');
                }
            });
        },

        isLogged: function () {
            var cart = customerData.get('cart'),
                customer = customerData.get('customer');
        },

        /**
         * Load data
         */
        load: function () {
            var self = this;
            self.options.page(1);
            $(self.options.containerPager).fadeIn(300);
            $.ajax({
                url: self.options.urlAjax(),
                method: 'GET',
                dataType: "json",
                data: {
                    p: this.options.page(),
                    q: $(self.options.elementSearchText).val(),
                    year: $(self.options.elementYear).val()
                },
                success: function (response) {
                    self.isLoading(false);
                    if (response.size) {
                        $('#quatityhistoryorders').html(response.size);
                    } else {
                        $('#quatityhistoryorders').html('0');
                    }

                    if (!response.html) {
                        $(self.options.containerPager).fadeOut(300);
                        $(self.options.elementAddHtml()).html($t('You have placed no orders.'))
                        return;
                    }
                    $(self.options.elementAddHtml()).html(response.html);
                }
            });
        },

        /**
         * Load data
         */
        loadMore: function () {
            var self = this;
            $.ajax({
                url: self.options.urlAjax(),
                method: 'GET',
                dataType: "json",
                data: {
                    p: this.options.page()
                },
                success: function (response) {
                    self.isLoading(false);
                    if (!response.html) {
                        $(self.options.containerPager).fadeOut(300);
                        return;
                    }
                    $(self.options.elementAddHtml()).append(response.html);
                }
            });
        }
    });

    return $.mage.filterhistoryorders;
});
