'use strict';

app.controller('contactController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr) {
        $scope.env = environment;

        $scope.contactForm = {};
        $scope.submitted = false;
        $scope.contactUS = function (form) {
            var $data = {};
            $scope.submitted = true;
            if (!form.$valid)
            {
                return false;
            }
            $data = $scope.contactForm;
            appDB
                    .callPostForm('utilities/contact', $data)
                    .then(
                            function successCallback(data)
                            {
                                if (data.ResponseCode == 200){
                                    var toast = toastr.success(data.Message, {
                                        closeButton: true
                                    });
                                    toastr.refreshTimer(toast, 5000);
                                }

                            },
                            function errorCallback(data)
                            {
                                $scope.errorStatus = data.ResponseCode;
                                $scope.errorMessage = data.Message;
                            }
                    );
        }
        
        $scope.downloadFormSubmitted = false;
        $scope.SendLink = function (form) {
            var $data = {};
            $scope.downloadFormSubmitted = true;
            if (!form.$valid){
                return false;
            }
            $data = $scope.sendLinkForm;
            appDB
                    .callPostForm('utilities/sendAppLink', $data)
                    .then(
                            function successCallback(data){
                                if (data.ResponseCode == 200){
                                    var toast = toastr.success(data.Message, {
                                        closeButton: true
                                    });
                                    toastr.refreshTimer(toast, 5000);
                                    $scope.sendLinkForm.PhoneNumber = '';
                                    $scope.downloadFormSubmitted = false;
                                }else{
                                    var toast = toastr.error(data.Message, {
                                        closeButton: true
                                    });
                                    toastr.refreshTimer(toast, 5000);
                                }

                            },
                            function errorCallback(data){
                                $scope.errorStatus = data.ResponseCode;
                                $scope.errorMessage = data.Message;
                            }
                    );
        }

    }]);
