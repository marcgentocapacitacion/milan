define([
    'jquery',
    'ko',
    'uiComponent',
    'mage/translate',
    'jquery-ui-modules/widget'
], function ($, ko, Component, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: "Wagento_StoreLocator/store_locations",
            urlAjax: '',
            storesLocations: ko.observable([]),
            elementSearch: '',
            pagination: ko.observable(''),
            containerPager: '',
            inputHiddenPageNumber: '',
            page: ko.observable(1),
            buttonPage: '',
            map: null,
            elementFilterDistance: '',
            radius: null,
            elementSearchText: null
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();
            this.isLoading(true);
            this.load();
            this._googleMaps();
            this._events();
            this.isLoading(false);
        },

        /**
         * Open store in google maps
         */
        centerStoreInMap: function (latitude, longitude) {
            let pos = {
                lat: parseFloat(latitude.replace(",", ".")),
                lng:  parseFloat(longitude.replace(",", ".")),
            };
            this.map.setCenter(pos);
            this.map.setZoom(16);
        },

        /**
         * Events
         * @private
         */
        _events: function () {
            var self = this;
            $(self.elementSearch).click(function () {
                self.isLoading(true);
                self.load();
                self._googleMaps();
                self.isLoading(false);
            });
            $(self.elementSearchText).keyup(function(e){
                if (e.keyCode == 13) {
                    self.isLoading(true);
                    self.load();
                    self._googleMaps();
                    self.isLoading(false);
                }
            });

            $(self.buttonPage).click(function () {
                self.getChangePage();
                self.loadMore();
                self._googleMaps();
            });

            $(self.elementFilterDistance).bind('keyup', function () {
                if (!$(self.elementFilterDistance).val().trim()) {
                    self._radius(0);
                    return;
                }
                let distance = parseInt($(self.elementFilterDistance).val());
                if (distance <= 0) {
                    self._radius(0);
                    return;
                }
                self._radius(distance * 1000);
            });
        },

        /**
         * @param radius
         * @private
         */
        _radius: function (radius) {
            this.radius.setRadius(radius);
        },

        /**
         * Render google maps
         * @private
         */
        _googleMaps: function () {
            let lat = 7.119206, lng = -73.132978;
            this.map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: { lat: lat, lng: lng},
            });
            var styles = [
                {
                    stylers: [
                        { saturation: -20 }
                    ]
                }
            ];
            this.map.setOptions({styles: styles});
            this._actualLocation(this.map);
        },

        /**
         * Get Actual location of the user
         * @private
         */
        _actualLocation: function (map) {
            var self = this;
            var infoWindow = new google.maps.InfoWindow();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude.replace(",", "."),
                            lng: position.coords.longitude.replace(",", "."),
                        };

                        self.radius = new google.maps.Circle({
                            map: self.map,
                            center: new google.maps.LatLng(position.coords.latitude.replace(",", "."), position.coords.longitude.replace(",", ".")),
                            radius: 0,
                            strokeColor: "#818c99",
                            fillColor: "#ffffff",
                            fillOpacity: 0.30
                        });

                        infoWindow.setPosition(pos);
                        infoWindow.setContent($t('You stay here'));
                        infoWindow.open(map);
                        map.setCenter(pos);
                    },
                    () => {
                        //handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                // handleLocationError(false, infoWindow, map.getCenter());
            }
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
         * @param response
         * @private
         */
        _pushLocations: function (response, map){
            let marker,
                infoWindow = new google.maps.InfoWindow();
            for (var i in response) {
                let position = {lat: parseFloat(response[i].latitude.replace(",", ".")), lng: parseFloat(response[i].longitude.replace(",", "."))};
                let title = response[i].name,
                    address = response[i].full_address,
                    fone = response[i].fone ?? ''
                ;
                marker = new google.maps.Marker({
                    position,
                    map,
                    title: `${title}<br>${address}<br>${fone}`,
                    label: ``,
                    optimized: false
                });
                marker.addListener("click", () => {
                    infoWindow.close();
                    infoWindow.setContent(marker.getTitle());
                    infoWindow.open(marker.getMap(), marker);
                });
            }
        },

        /**
         * Load data
         */
        load: function () {
            var self = this;
            this._loadData(function (response) {
                if (response.totalRecords <= 0) {
                    $(self.containerPager).fadeOut(300);
                    return;
                }

                if (response.totalRecords < 10 ) {
                    $(self.containerPager).fadeOut(300);
                } else {
                    $(self.containerPager).fadeIn();
                }
                self.storesLocations(response.items);
                self._pushLocations(response.items, self.map);
            });
        },

        /**
         * Load mode data
         */
        loadMore: function () {
            var self = this;
            this._loadData(function (response) {
                if (response.totalRecords <= 0) {
                    $(self.containerPager).fadeOut(300);
                    return;
                }

                if (response.totalRecords < 10 ) {
                    $(self.containerPager).fadeOut(300);
                }
                let storesLocations = self.storesLocations();
                self.storesLocations(storesLocations.concat(response.items));
                self._pushLocations(self.storesLocations(), self.map);
            });
        },

        /**
         * @param callback
         * @private
         */
        _loadData: function (callback) {
            var self = this;

            $.ajax({
                url: self.urlAjax,
                method: 'GET',
                dataType: "json",
                data: {
                    p: self.page(),
                    q: $(self.elementSearchText).val()
                },
                success: callback
            });
        }
    });
});
