define([
    'jquery',
    "jquery-ui-modules/widget"
], function ($) {
    'use strict';

    var mixin = {
        /**
         * @private
         */
        _create: function () {
            this._super();
            this._loadMedia();
        },

        /**
         * Load media gallery using ajax or json config.
         *
         * @private
         */
        _loadMedia: function () {
            var $main = this.inProductList ?
                    this.element.parents('.product-item-info') :
                    this.element.parents('.column.main'),
                images;

            if (this.options.useAjax) {
                this._debouncedLoadProductMedia();
            }  else {
                images = this.options.jsonConfig.images[this.getProduct()];
                if (!images) {
                    images = this.options.mediaGalleryInitial;
                }

                for (var i in this.options.jsonConfig.images) {
                    if (i == this.getProduct()) {
                        continue;
                    }
                    images.push(this.options.jsonConfig.images[i][0])
                }
                this.updateBaseImage(this._sortImages(images), $main, !this.inProductList);
            }
        }
    };

    return function (target) {
        $.widget('mage.SwatchRenderer', target, mixin);
        return $.mage.SwatchRenderer;
    };
});
