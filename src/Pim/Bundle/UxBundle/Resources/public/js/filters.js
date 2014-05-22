'use strict';

angular.module('App.filters', [])
    .filter('pluck', function() {
        return _.pluck;
    });
