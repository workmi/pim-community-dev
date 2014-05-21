'use strict';

angular.module('App.grid', [])
    .directive('grid', function() {
        return {
            scope: {
                name: '@'
            },
            templateUrl: '/bundles/pimux/templates/grid/grid.html',
            controller: function(GridManager, $scope) {
                $scope.loading = true;

                GridManager.load($scope.name).then(function (data) {
                    $scope.metadata = data.metadata;
                    $scope.data     = data.data;
                    $scope.loading  = false;
                });

                $scope.applyFilter = function(filterName, value) {
                    var config = {};
                    config[filterName] = value;

                    GridManager.applyFilter($scope.name, config);
                    $scope.$broadcast('grid.need.reload');
                };

                $scope.$watch('metadata.state', function (newValue, oldValue) {
                    if (oldValue) {
                        $scope.$broadcast('grid.need.reload');
                    }
                }, true);

                $scope.$on('grid.need.reload', function () {
                    $scope.loading = true;
                    GridManager.loadData($scope.name, $scope.metadata).then(function (data) {
                        $scope.data    = data.data;
                        $scope.loading = false;
                    });
                });
            }
        };
    })
    .directive('gridHeader', function() {
        return {
            templateUrl: '/bundles/pimux/templates/grid/header.html',
        };
    })
    .directive('gridRow', function() {
        return {
            templateUrl: '/bundles/pimux/templates/grid/row.html',
            controller: function($scope) {
                $scope.getCellConfig = function (columnName) {
                    return _.find($scope.metadata.columns, {name: columnName});
                };
            }
        };
    })
    .directive('gridCell', function(CellManager) {
        return {
            scope: {
                cell: '=',
                column: '='
            },
            templateUrl: '/bundles/pimux/templates/grid/cell.html',
            controller: function($scope) {
                $scope.renderCell = function (cell, column) {
                    return CellManager.render(cell, column);
                };
            }
        };
    })
    .directive('gridFilters', function() {
        return {
            templateUrl: '/bundles/pimux/templates/grid/filters.html'
        };
    })
    .directive('gridFilter', function() {
        return {
            template: '<div ng-include="\'/bundles/pimux/templates/grid/filter/\' + filter.type + \'.html\'"></div>'
        };
    })
    .directive('gridSelection', function() {
        return {
            templateUrl: '/bundles/pimux/templates/grid/selection.html'
        };
    })
    .service('GridManager', function ($http, $q) {
        var self = this;

        this.gridParams = {};

        var loadGridDataCanceler;

        this.initializeGridParams = function(name) {
            self.gridParams[name] = {
                dataLocale: 'en_US',
                params: {
                    dataLocale: 'en_US'
                }
            };
        };

        this.applyGridParams = function(name, params) {
            self.gridParams[name] = _.assign(
                self.gridParams[name],
                {
                    _pager: {
                        _page: params.state.currentPage,
                        _per_page: params.state.pageSize
                    },
                    _sort_by: params.state.sorters
                }
            );
        };

        this.applyFilter = function(name, filterConfig) {
            self.gridParams[name] = _.assign(self.gridParams[name], { _filter: filterConfig });
        };

        this.loadGrid = function (name) {
            self.initializeGridParams(name);
            var deferred = $q.defer();

            $http.get('/datagrid/' + name + '/load?' + $.param(self.gridParams[name])).then(function(resp) {
                deferred.resolve(self.prepareGridConfig(resp.data));
            });

            return deferred.promise;
        };

        this.loadGridData = function (name, params) {
            //we cancel the previous request
            if (loadGridDataCanceler) {
                loadGridDataCanceler.resolve();
            }

            loadGridDataCanceler = $q.defer();
            var deferred = $q.defer();

            self.applyGridParams(name, params);

            var urlParams = {};
            urlParams[name] = self.gridParams[name];

            var url = '/datagrid/' +
                name +
                '?' +
                $.param(urlParams);

            $http({method:'GET', url: url, timeout: loadGridDataCanceler.promise}).then(function(resp) {
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
                return {
                    row: _.map(columns, function (column) {
                        return {
                            value: row[column],
                            column: column
                        };
                    }),
                    entity: row
                };
            });

            return config;
        };

        return {
            load: this.loadGrid,
            loadData: this.loadGridData,
            applyFilter: this.applyFilter
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
    });;