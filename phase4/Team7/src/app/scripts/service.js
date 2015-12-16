'use strict';
angular.module('team7App').factory('notification', ['$uibModal', function ($uibModal) {
    return function (title, body) {
        return $uibModal.open({
            animation: true,
            templateUrl: 'modal.html',
            controller: 'ModalInstanceCtrl',
            resolve: {
                title: function () {
                    return title;
                },
                body: function () {
                    return body;
                }
            }
        });
    };
}]).factory('sessionManager', ['$state', '$cookies', '$q', '$http','notification', function ($state, $cookies, $q, $http) {
    var func = function () {
        var employee;

        function getInfo() {
            var deferred = $q.defer();

            if ($cookies.get('PHPSESSID') === '' || $cookies.get('PHPSESSID') === undefined) {
                deferred.reject();
            }

            $http({
                method: 'GET',
                url: api+'api/userinfodetails',
                withCredentials: true
            }).then(function (response) {
                deferred.resolve(employee = !!parseInt(response.data.IsEmployee));
            }, function () {
                deferred.reject();
            });

            return deferred.promise;
        }

        var promise = getInfo();

        return promise;


    };

    return func;
}]).factory('getSCS', ['$state', '$cookies', '$q', '$http','notification', function ($state, $cookies, $q, $http) {
    var func = function () {
        var scs;

        function getInfo() {
            var deferred = $q.defer();

            if ($cookies.get('PHPSESSID') === '' || $cookies.get('PHPSESSID') === undefined) {
                deferred.reject();
            }

            $http({
                method: 'GET',
                url: api+'api/userinfodetails',
                withCredentials: true
            }).then(function (response) {
                deferred.resolve(scs = !!parseInt(response.data.TanMethod));
            }, function () {
                deferred.reject();
            });

            return deferred.promise;
        }

        var promise = getInfo();

        return promise;


    };

    return func;
}]);
