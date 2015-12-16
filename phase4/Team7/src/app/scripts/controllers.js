'use strict';
function checkParameter(param) {
    return !!(param !== undefined && param !== '');
}


angular.module('team7App')
    .controller('AboutCtrl', function ($scope, $http, $state) {


    })
    .controller('MainCtrl', function ($scope, $http, $state, $cookies, $rootScope, notification, employee, scs) {
        $scope.employee = employee;
        $scope.scs = scs;
        console.log(employee);
        $scope.logout = function () {
            $http({
                method: 'GET',
                url: api+'api/logout',
                withCredentials: true
            }).then(function successCallback(response) {
                $cookies.remove('PHPSESSID',{path:'/'});
                $cookies.remove('XSRF-TOKEN',{path:'/'});
                $state.go('login');
            }, function errorCallback(response) {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };
    })
    .controller('LoginCtrl', function ($scope, $http, $state, $cookies, notification) {
        $scope.pass = '';
        $scope.email = '';
        $scope.login = function () {
            if (!checkParameter($scope.login) || !checkParameter($scope.pass)) {
                notification('Alert', 'Please fill in all fields.');
                return;
            }

            $http({
                method: 'GET',
                url: api+'api/login?email=' + $scope.email + '&password=' + $scope.pass,
                withCredentials: true
            }).then(function successCallback(response) {
                if (parseInt(response.data.IsApproved)) {
                    if (parseInt(response.data.IsEmployee)) {
                        $state.go('main.employee');
                    } else {
                        $state.go('main.user');
                    }
                }
            }, function errorCallback(response) {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };

    })
    //reset password
    .controller('ResetPassCtrl', function ($scope, $http, $state, notification) {
        // $scope.email = '';
        $scope.newpass = '';
        $scope.repass = '';
        $scope.key = '';

        $scope.resetpass = function () {
            if (!checkParameter($scope.newpass) || !checkParameter($scope.repass)) {
                notification('Alert', 'Please fill in all the fields.');
                return;
            }
            if($scope.newpass != $scope.repass) {
                notification('Alert', 'Passwords don\'t match.');
                return;
            }
            $http({
                method: 'POST',
                url: api+'api/resetpass',
                withCredentials: true,
                data: {
                    // email: $scope.email,
                    newpass: $scope.newpass,
                    repass: $scope.repass,
                    key: decodeURIComponent((new RegExp('[?|&]' + 'key' + '=' + '([^&;]+?)(&|#|;|$)').exec(location.hash)||[,""])[1].replace(/\+/g, '%20'))||null
                }
            }).then(function successCallback(response) {
                notification('Reset Password', 'You successfully reset your password.').result.then(function () {
                    $state.go('login');
                });
            }, function errorCallback(response) {
                notification('Error', 'Invalid data. '+response.data)
            });
        };

    })
    //forgot password
    .controller('ForgotPassCtrl', function ($scope, $http, $state, notification) {
        $scope.email = '';
        $scope.path = '';

        $scope.forgotpass = function () {
            if (!checkParameter($scope.email)) {
                notification('Alert', 'Please fill in your email.');
                return;
            }
            $http({
                method: 'POST',
                url: api+'api/forgotpass',
                withCredentials: true,
                data: {
                    email: $scope.email,
                    path: window.location.origin

                }
            }).then(function successCallback(response) {
                notification('Forgot Password', 'You will receive instructions to your email to reset your password.').result.then(function () {
                    $state.go('login');
                });
            }, function errorCallback(response) {
                notification('Error', 'Invalid data. '+response.data)
            });
        };

    })
    //change password
     .controller('ChangePassCtrl', function ($scope, $http, $state, notification) {
         $scope.currentpass = '';
         $scope.newpass = '';
         $scope.repass = '';

         $scope.changepass = function () {
             if (!checkParameter($scope.currentpass) || !checkParameter($scope.newpass) || !checkParameter($scope.repass)) {
                 notification('Alert', 'Please fill in all fields.');
                 return;
             }
             if($scope.newpass != $scope.repass) {
                 notification('Alert', 'Passwords don\'t match.');
                 return;
             }
             $http({
                 method: 'POST',
                 url: api+'api/changepass',
                 withCredentials: true,
                 data: {
                     currentpass: $scope.currentpass,
                     newpass: $scope.newpass,
                     repass: $scope.repass
                 }
             }).then(function successCallback(response) {
                 notification('Change Password', 'Your password changed successfully!').result.then(function () {
                      $state.go('login');
                 });
             }, function errorCallback(response) {
                 notification('Error', 'Invalid data. '+response.data)
             });
         };

     })
    .controller('RegCtrl', function ($scope, $http, $state, notification) {
        $scope.isEmployee = false;
        $scope.tanMethod = false;
        $scope.pass = '';
        $scope.repass = '';
        $scope.email = '';
        $scope.name = '';

        $scope.register = function () {
            if (!checkParameter($scope.name) || !checkParameter($scope.email) || !checkParameter($scope.pass) || !checkParameter($scope.repass)) {
                notification('Alert', 'Please fill in all fields.');
                return;
            }
            if($scope.pass != $scope.repass) {
                notification('Alert', 'Passwords don\'t match.');
                return;
            }
            $http({
                method: 'POST',
                url: api+'api/register',
                withCredentials: true,
                data: {
                    name: $scope.name,
                    email: $scope.email,
                    password: $scope.pass,
                    isEmployee: $scope.isEmployee,
                    tanMethod: $scope.tanMethod
                }
            }).then(function successCallback(response) {
                notification('Registration', 'Registration successful. Waiting for approval. You will be notified to email.').result.then(function () {
                    $state.go('login');
                });
            }, function errorCallback(response) {
                notification('Error', 'Invalid data. '+response.data)
            });
        };

    })
    .controller('CreateCtrl', function ($scope, $http, $state, FileUploader, notification, $cookies) {
        $http({
            method: 'GET',
            url: api+'api/userinfodetails',
            withCredentials: true
        }).then(function (response) {
            $scope.username = response.data.Name;
            $scope.user = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });

        var uploader = $scope.uploader = new FileUploader({
            url: api+'api/uploadFile',
            withCredentials: true,
            headers: {'X-XSRF-TOKEN': $cookies.get('XSRF-TOKEN') }
        });

        uploader.onBeforeUploadItem = function(item) {
            item.withCredentials = true;
            item.formData.push({tan:$scope.user.filetan});
            item.formData.push({desc:$scope.user.batchdescription});
        };

        uploader.onSuccessItem = function(fileItem, response, status, headers) {
            notification('Success!', 'Batch Transaction completed');
            console.info('onSuccessItem', response); //TODO remove for production
            $state.go($state.current, {}, {reload: true});
        };

        uploader.onErrorItem = function(fileItem, response, status, headers) {
            notification('Error on batch file upload', response);
            console.info('onErrorItem', fileItem, response, status, headers); //TODO remove for production
            $state.go($state.current, {}, {reload: true});
        };
        $scope.txndescription = "";
        $scope.create = function () {
            if (!checkParameter($scope.accountNumber) || !checkParameter($scope.amount) || !checkParameter($scope.TAN)) {
                notification('Alert', 'Please fill in all required fields.');
                return;
            }

            $http({
                method: 'POST',
                url: api+'api/createTx',
                withCredentials: true,
                data: {
                    amount: $scope.amount,
                    TAN: $scope.TAN,
                    receiverAccountNo: $scope.accountNumber,
                    description: $scope.txndescription
                }
            }).then(function (response) {
                notification('Application sent', 'Your transaction was successfully sent.' + ($scope.amount > 10000 ? ' It should be approved by employee.' : '')).result.then(function () {
                    $state.go('main.user');
                });

            }, function (response) {
                notification('Transaction was declined', 'Check your inputs or internet connection. ' + response.data);
            });
        };
    })
    .controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, body, title) {

        $scope.body = body;
        $scope.title = title;
        $scope.ok = function () {
            $uibModalInstance.close();
        };
    })
    .controller('UserCtrl', function ($scope, $http, $state, notification) {
        $http({
            method: 'GET',
            url: api+'api/userinfodetails',
            withCredentials: true
        }).then(function (response) {
            $scope.historyLink = api+'api/downloadTxHistory?userId=' + response.data.UserId;
            $scope.username = response.data.Name;
            $scope.accountNumber = response.data.Account_no;
            $scope.amount = response.data.Account_bal;
            $scope.pin = response.data.pin;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });

        $scope.transactions = [];
        $http({
            method: 'GET',
            url: api+'api/getHistory',
            withCredentials: true
        }).then(function (response) {
            $scope.transactions = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });


    })
    .controller('EmployeeCtrl', function ($scope, $http, $state, notification) {
        $scope.users = [];
        $http({
            method: 'GET',
            url: api+'api/getUsersToApprove',
            withCredentials: true
        }).then(function (response) {
            $scope.users = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });

        $scope.reginfo = function (userId) {
            $state.go('main.userinfo', {id: userId});
        };

        $scope.transactions = [];
        $http({
            method: 'GET',
            url: api+'api/getTxToApprove',
            withCredentials: true
        }).then(function (response) {
            $scope.transactions = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });

        $scope.txinfo = function (tnxId) {
            $state.go('main.transaction', {id: tnxId});
        };

        $scope.selected = undefined;
        $scope.states = [];
        $http({
            method: 'GET',
            url: api+'api/getUsers',
            withCredentials: true
        }).then(function (response) {
            $scope.states = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });
    })
    .controller('UserInfoCtrl', function ($scope, $http, $state, $rootScope, notification, $stateParams) {
        $http({
            method: 'GET',
            url: api+'api/userinfodetails?userId=' + $stateParams.id,
            withCredentials: true
        }).then(function (response) {
            $scope.historyLink = api+'api/downloadTxHistory?userId=' + response.data.UserId;
            $scope.username = response.data.Name;
            $scope.accountNumber = response.data.Account_no;
            $scope.amount = response.data.Account_bal;
            $scope.approve = !!parseInt(response.data.IsApproved);
            $scope.employee = !!parseInt(response.data.IsEmployee);
            //$scope.SCS = !!parseInt(response.data.TanMethod);
            
        }, function () {
            notification('Ooops', 'Error happend. Try to reload the page.')
        });

        $scope.transactions = [];
        $http({
            method: 'GET',
            url: api+'api/getHistory?userId=' + $stateParams.id,
            withCredentials: true
        }).then(function (response) {
            $scope.transactions = response.data;
        }, function () {
            notification('Alert', 'Incorrect data.');
        });


        $scope.approveUser = function () {
            $http({
                method: 'POST',
                url: api+'api/approveUser',
                withCredentials: true,
                data: {
                    userId: $stateParams.id,
                    approved: true,
                    balance: $scope.amount,
                    websiteurl: window.location.origin
                }
            }).then(function (response) {
                $state.go($state.current, {}, {reload: true});
            }, function () {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };
        $scope.rejectUser = function () {
            $http({
                method: 'POST',
                url: api+'api/approveUser',
                withCredentials: true,
                data: {
                    userId: $stateParams.id,
                    approved: false,
                    balance: $scope.amount
                }
            }).then(function (response) {
                $state.go('main.employee');
            }, function () {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };
    })
    .controller('TransactionCtrl', function ($scope, $http, $state, $rootScope, notification, $stateParams) {

        $http({
            method: 'GET',
            url: api+'api/transactionInfo?txId=' + $stateParams.id,
            withCredentials: true
        }).then(function (response) {
            $scope.data = response.data;
        }, function () {
            notification('Alert', 'Incorrect data. '+response.data);
        });

        $scope.approveTransaction = function () {
            $http({
                method: 'POST',
                url: api+'api/approveTx',
                withCredentials: true,
                data: {
                    txId: $stateParams.id,
                    approved: true
                }
            }).then(function (response) {
                $state.go('main.employee');
            }, function () {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };
        $scope.rejectTransaction = function () {
            $http({
                method: 'POST',
                url: api+'api/approveTx',
                withCredentials: true,
                data: {
                    txId: $stateParams.id,
                    approved: false
                }
            }).then(function (response) {
                $state.go('main.employee');
            }, function () {
                notification('Alert', 'Incorrect data. '+response.data);
            });
        };
    });


