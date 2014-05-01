'use strict';

angular.module('App.services', [])
    .service('GridManager', function ($http, $q, $window) {
        var self = this;

        this.loadGrid = function (name) {
            var deferred = $q.defer();

            $http.get('/datagrid/' + name + '/load?params%5BdataLocale%5D=en_US').then(function(resp) {
                deferred.resolve(self.prepareGridConfig(resp.data));
            });

            return deferred.promise;
        };

        this.loadGridData = function (name, params) {
            var deferred = $q.defer();

            var url = '/datagrid/' +
                name +
                '?' +
                name +
                '%5BdataLocale%5D=en_US&product-grid%5B_pager%5D%5B_page%5D=' +
                params.state.currentPage;

            $http.get(url).then(function(resp) {
                var data = {
                    metadata: params,
                    data: resp.data
                };

                deferred.resolve(self.prepareGridConfig(data));
            });

            return deferred.promise;
        };

        this.prepareGridConfig = function (config) {

            try {
                config.data = JSON.parse(config.data);
            } catch (e) {

            }

            var columns = _.pluck(config.metadata.columns, 'name');

            config.data.data = _.map(config.data.data, function (row) {
                return _.map(columns, function (column) {
                    return {
                        value: row[column],
                        column: column
                    };
                });
            });

            return config;
        };

        return {
            load: this.loadGrid,
            loadData: this.loadGridData
        };
    })
    .service('CellManager', function($sce, $filter) {
        this.renderCell = function(cell, column) {
            if (typeof column != 'undefined') {
                switch (column.type) {
                    case 'string':
                        return cell;
                    case 'date':
                        return $filter('date')(cell, 'mediumDate');
                    case 'html':
                        return $sce.trustAsHtml(cell);
                }
            }

            return cell;
        };

        return {
            render: this.renderCell
        };
    });