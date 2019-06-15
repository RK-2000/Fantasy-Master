'use strict';

app.controller('inviteController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', function($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr) {
    $scope.env = environment;
    if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
        $scope.user_details = $localStorage.user_details;
        $scope.isLoggedIn = $localStorage.isLoggedIn;
        $scope.base_url = base_url;
        $scope.referral_url = base_url + $localStorage.user_details.ReferralCode;

        $scope.activeTab = 'viaSms';
        $scope.inviteTab = function(tab) {
            $scope.inviteSubmitted = false;
            $scope.activeTab = tab;
        }

        /*function to invite friend*/
        $scope.inviteField = {};

        $scope.inviteSubmitted = false;
        $scope.InviteFriend = function(form, ReferType) {

            $scope.inviteSubmitted = true;
            if (!form.$valid) {
                return false;
            }
            var $data = {};
            $data.SessionKey = $localStorage.user_details.SessionKey;
            if (ReferType == 'Phone') {
                $data.PhoneNumber = $scope.inviteField.PhoneNumber;
            } else {
                $data.Email = $scope.inviteField.Email;
            }
            $data.ReferType = ReferType;

            appDB
                .callPostForm('users/referEarn', $data)
                .then(
                    function successCallback(data) {

                        if (data.ResponseCode == 200) {
                            var toast = toastr.success(data.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            $scope.inviteField = {};
                            $scope.inviteSubmitted = false;
                        }
                        if (data.ResponseCode == 500) {
                            var toast = toastr.warning(data.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);

                        }
                        if (data.ResponseCode == 501) {
                            var toast = toastr.warning(data.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                        }
                        if (data.ResponseCode == 502) {
                            var toast = toastr.warning(data.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            setTimeout(function() {
                                localStorage.clear();
                                window.location.href = base_url;
                            }, 1000);
                        }
                    },
                    function errorCallback(data) {

                        if (typeof data == 'object') {
                            var toast = toastr.error(data.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                        }
                    });

        }

    }
    else{
        window.location.href = base_url;
    }

}]);