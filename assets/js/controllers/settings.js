'use strict';
app.controller('settingsController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', 'Upload', '$timeout', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, Upload, $timeout) {
        $scope.env = environment;
        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.user_details = $localStorage.user_details;
            $scope.isLoggedIn = $localStorage.isLoggedIn;

            $scope.profileDetails = {};
            $scope.panDetails = {};
            $scope.bankDetails = {};

            $scope.getProfileInfo = function () {

                var $data = {};
                $data.UserGUID = $localStorage.user_details.UserGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Params = 'EmailForChange,EmailStatus,UserTypeID,UserTypeName,FirstName, MiddleName, LastName, Email, Username, Gender, BirthDate, CountryCode, CountryName, CityName, StateName, PhoneNumber,Address,MediaPAN,MediaBANK,PanStatus,BankStatus';
                appDB
                        .callPostForm('users/getProfile', $data)
                        .then(
                                function successCallback(data) {

                                    if (data.ResponseCode == 200) {
                                        $scope.profileDetails = data.Data;
                                        $scope.PhoneNumber = ($scope.profileDetails.PhoneNumber)?$scope.profileDetails.PhoneNumber:'';
                                        if ($scope.profileDetails.hasOwnProperty('PhoneNumber')) {
                                            $localStorage.user_details.PhoneNumber = $scope.profileDetails.PhoneNumber;
                                        } else {
                                            $localStorage.user_details.PhoneNumber = '';
                                        }
                                        if($scope.profileDetails.EmailStatus === 'Verified'){
                                            $scope.Email = $scope.profileDetails.Email;
                                        }else{
                                            $scope.Email = $scope.profileDetails.EmailForChange;
                                        }
                                        $scope.panDetails = ($scope.profileDetails.MediaPAN.MediaCaption !=='') ? JSON.parse($scope.profileDetails.MediaPAN.MediaCaption) : {};

                                        $scope.bankDetails = ($scope.profileDetails.MediaBANK.MediaCaption !== '') ? JSON.parse($scope.profileDetails.MediaBANK.MediaCaption) : {};


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
                                        setTimeout(function () {
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
            $scope.getProfileInfo();
            $scope.isOtpSend = false; //to manage otp screen

            $scope.activeMenu = 'verification';
            $scope.activateTab = function (tab) {
                $scope.activeMenu = tab;
            }

            $scope.submitted = false;

            /*function to update phone number and send OTP for mobile verification*/

            $scope.updateMobileNumber = function (form) {
                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.submitted = true;
                if (!form.$valid) {
                    return false;
                }
                $data.PhoneNumber = $scope.PhoneNumber;
                $data.SessionKey = $localStorage.user_details.SessionKey;

                appDB
                        .callPostForm('users/updateUserInfo', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.profileDetails = data.Data;
                                        $scope.profileDetails.PhoneNumber = '';

                                        $scope.isOtpSend = true;
                                        $scope.submitted = false;
                                        var toast = toastr.success('OTP sent to your mobile number', {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
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
                                        setTimeout(function () {
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

            /*function to verify mobile number*/
            $scope.otpSubmitted = false;
            $scope.verifyMobileNumber = function (form) {

                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.otpSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                $data.OTP = $scope.OTP;
                $data.SessionKey = $localStorage.user_details.SessionKey;

                appDB
                        .callPostForm('signup/verifyPhoneNumber', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        delete $scope.OTP;
                                        //$scope.profileDetails = data.Data;
                                        $scope.isOtpSend = false;
                                        $scope.otpSubmitted = false;
                                        var toast = toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
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
                                        setTimeout(function () {
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
            
            $scope.emailSubmitted = false;
            $scope.isEmailSend = false;
            /**
             * Update email address for Verification
             */
            $scope.updateEmail = function (form) {
                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.emailSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                $data.Email = $scope.Email;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Type = 'Email';
                appDB
                        .callPostForm('signup/resendVerification', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.emailSubmitted = false;
                                        var toast = toastr.success('Email has been sent.', {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
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
                                        setTimeout(function () {
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


            /*PAN upload*/
            //uploadPanCardDetails
            $scope.panSubmitted = false;
            $scope.uploadPanCardDetails = function (form, files) {

                $scope.panSubmitted = true;
                $scope.helpers = Mobiweb.helpers;
                if (!form.$valid) {
                    return false;
                }
                if (files != null) {
                    var fd = new FormData();
                    fd.append('SessionKey', $localStorage.user_details.SessionKey);
                    fd.append('File', files);
                    fd.append('Section', 'PAN');
                    fd.append('MediaCaption', JSON.stringify($scope.panDetails));


                    appDB
                            .callPostImage('upload/image', fd)
                            .then(
                                    function success(data) {

                                        if (data.ResponseCode == 200) {
                                            var toast = toastr.success(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 1000);

                                        }
                                        if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.href = base_url;
                                            }, 1000);
                                        }
                                    },
                                    function error(data) {
                                        $scope.errorMsg = data.message;
                                    }
                            );

                }
            }

            /*Bank upload*/
            $scope.bankSubmitted = false;
            $scope.uploadBankDetail = function (form, files) {
                $scope.bankSubmitted = true;
                $scope.helpers = Mobiweb.helpers;
                if (!form.$valid) {
                    return false;
                }
                if (files != null) {
                    var fd = new FormData();
                    fd.append('SessionKey', $localStorage.user_details.SessionKey);
                    fd.append('File', files);
                    fd.append('Section', 'BankDetail');
                    fd.append('MediaCaption', JSON.stringify($scope.bankDetails));
                    appDB
                            .callPostImage('upload/image', fd)
                            .then(
                                    function success(data) {

                                        if (data.ResponseCode == 200) {
                                            var toast = toastr.success(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            $scope.bankDetailsForm = false;
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 1000);
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
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.href = base_url;
                                            }, 1000);
                                        }
                                    },
                                    function error(data) {
                                        $scope.errorMsg = data.message;
                                    }
                            );

                }
            }

            $scope.countryList = [];
            $scope.getCountryList = function () {

                var $data = {};
                appDB
                        .callPostForm('utilities/getCountries', $data)
                        .then(
                                function successCallback(data) {

                                    if (data.ResponseCode == 200) {

                                        $scope.countryList = data.Data;

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
                                        setTimeout(function () {
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

            $scope.StateList = [];
            $scope.getStatesList = function () {

                var $data = {};
                $data.CountryCode = 'IN';
                appDB
                        .callPostForm('utilities/getStates', $data)
                        .then(
                                function successCallback(data) {

                                    if (data.ResponseCode == 200) {
                                        $scope.StateList = data.Data;
                                        $timeout(function () {
                                            $('.selectpicker').selectpicker('render');
                                        }, 500);
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
                                        setTimeout(function () {
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
        } else {
            window.location.href = base_url;
        }




    }]);