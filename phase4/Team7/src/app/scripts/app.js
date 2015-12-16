'use strict';

/**
 * @ngdoc overview
 * @name team7App
 * @description
 * # team7App
 *
 * Main module of the application.
 */

//generating the path to the api, if app and api are in team7 folder on server
var api = window.location.origin + "/";


angular
    .module('team7App', [
        'ngAnimate',
        'ngCookies',
        'ngResource',
        'ngRoute',
        'ngSanitize',
        'ngTouch',
        'ui.bootstrap',
        'ui.router',
        'angularFileUpload'
    ])
    .config(function ($stateProvider, $urlRouterProvider) {
        $stateProvider.state('login', {
            url: '/login',
            templateUrl: 'views/login.html',
            controller: 'LoginCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        } else {
                            $state.go('main.user');
                        }
                    });
                }
            }
        })
        .state('main.changepass', {
            url: '/changepass',
            templateUrl: 'views/changepass.html',
            controller: 'ChangePassCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        })
        .state('resetpass', {
            url: '/resetpass',
            templateUrl: 'views/resetpass.html',
            controller: 'ResetPassCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        } else {
                            $state.go('main.user');
                        }
                    });
                }
            }
        })
        .state('forgotpass', {
            url: '/forgotpass',
            templateUrl: 'views/forgotpass.html',
            controller: 'ForgotPassCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        } else {
                            $state.go('main.user');
                        }
                    });
                }
            }
        })
        .state('register', {
            url: '/register',
            templateUrl: 'views/register.html',
            controller: 'RegCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        } else {
                            $state.go('main.user');
                        }
                    });
                }
            }
        })
        .state('main', {
            url: '/home',
            templateUrl: 'views/main.html',
            controller: 'MainCtrl',
            resolve: {
                employee: function (sessionManager, $state) {
                    return sessionManager().then(function (employee) {
                        return employee;
                    }, function () {
                        $state.go('login');
                    });
                },
                scs: function(getSCS) {
                    return getSCS().then(function(scs) {
                        return scs;
                    });
                }
            }
        }).state('main.about', {
            url: '/about',
            templateUrl: 'views/about.html',
            controller: 'AboutCtrl'
        }).state('main.create', {
            url: '/create',
            templateUrl: 'views/create.html',
            controller: 'CreateCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        }
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        }).state('main.user', {
            url: '/user',
            templateUrl: 'views/user.html',
            controller: 'UserCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (employee) {
                            $state.go('main.employee');
                        }
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        }).state('main.employee', {
            url: '/employee',
            templateUrl: 'views/employee.html',
            controller: 'EmployeeCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (!employee) {
                            $state.go('main.user');
                        }
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        }).state('main.userinfo', {
            url: '/userinfo/:id',
            templateUrl: 'views/userinfo.html',
            controller: 'UserInfoCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (!employee) {
                            $state.go('main.user');
                        }
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        }).state('main.transaction', {
            url: '/transaction/:id',
            templateUrl: 'views/transaction.html',
            controller: 'TransactionCtrl',
            resolve: {
                check: function (sessionManager, $state) {
                    sessionManager().then(function (employee) {
                        if (!employee) {
                            $state.go('main.user');
                        }
                    }, function () {
                        $state.go('login');
                    });
                }
            }
        });
        $urlRouterProvider.otherwise('/login');
    });
