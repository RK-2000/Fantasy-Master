"use strict";
app.factory('$remember', function () {
    return function (name, values) {
        var cookie = name + '=';

        cookie += values + ';';

        var date = new Date();
        date.setDate(date.getDate() + 1);

        cookie += 'expires=' + date.toString() + ';';

        document.cookie = cookie;
    }
});
app.directive('featureHome', function (environment) {
    return {
        restrict: 'A',

        link: function (scope, element, attribute) {
            scope.env = environment;
            setTimeout(function () {

                $(".testimonSliderPar").slick({
                    dots: false,
                    infinite: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 2000,
                    arrows: true,
                    responsive: [
                        {
                            breakpoint: 700,
                            settings: {
                                slidesToShow: 1,

                            }
                        }
                    ]
                });
                $(".testimonSliderPar").slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    loop: true,
                    arrows: true,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2,
                                arrows: false,
                                autoplay: true,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                arrows: false,
                            }
                        }, {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                arrows: false,
                                autoplay: true,
                            }
                        }]
                });
            });
        }
    }
});

app.controller('authController', ['$scope', '$localStorage', '$sessionStorage', '$rootScope', '$location', 'toastr', 'appDB', 'environment', '$remember', '$cookies', '$cookieStore', '$timeout', function ($scope, $localStorage, $sessionStorage, $rootScope, $location, toastr, appDB, environment, $remember, $cookies, $cookieStore, $timeout) {
        if (!$localStorage.hasOwnProperty('user_details')) {
            $scope.loginType = 'email';
            $scope.activeTab = getQueryStringValue('type');
            $scope.changeTab = function (tab) {
                $scope.activeTab = tab;
            }

            $scope.loginData = {};
            if ($cookies.get('remeber_me')) {
                var rem_info = JSON.parse($cookies.get('remeber_me'));
                $scope.loginData.Keyword = rem_info.Keyword;
                $scope.loginData.Password = rem_info.Password;
                $scope.loginData.remeber_me = rem_info.remeber_me;
            }
            /**
             * Login with Phone number
             */
            $scope.isOtp = false;
            $scope.loginDataPhone = {};
            $scope.OtpSignIn = function (form) {
                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.login_error = false;
                $scope.login_message = ''; //login message
                $scope.LoginSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                $scope.loginDataPhone.DeviceType = 'Native';
                $scope.loginDataPhone.Source = 'Otp';
                var $data = $scope.loginDataPhone;

                appDB
                        .callPostForm('signin/OtpSignIn', $data)
                        .then(
                                function successCallback(data)
                                {

                                    if (data.ResponseCode == 200) {
                                        var toast = toastr.success('OTP has been sent successfully.', {
                                            closeButton: true
                                        });
                                        $scope.LoginSubmitted = false;
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.isOtp = true;
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
                                },
                                function errorCallback(data)
                                {

                                    if (typeof data == 'object')
                                    {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                });
            }


            /*Login*/

            $scope.LoginSubmitted = false;
            $scope.signIn = function (form) {
                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.login_error = false;
                $scope.login_message = ''; //login message
                $scope.LoginSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                if ($scope.loginType == 'phone') {
                    $scope.loginDataPhone.DeviceType = 'Native';
                    $scope.loginDataPhone.Source = 'Otp';
                    var $data = $scope.loginDataPhone;
                } else {
                    $scope.loginData.DeviceType = 'Native';
                    $scope.loginData.Source = 'Direct';
                    var $data = $scope.loginData;
                }

                appDB
                        .callPostForm('signin', $data)
                        .then(
                                function successCallback(data)
                                {

                                    if (data.ResponseCode == 200)
                                    {
                                        if ($scope.loginData.remeber_me) {
                                            $remember('remeber_me', JSON.stringify($data));
                                        } else {
                                            $cookies.remove('remeber_me');
                                        }
                                        $localStorage.user_details = data.Data;
                                        $localStorage.isLoggedIn = true;
                                        $sessionStorage.walletBalance = data.Data.WalletAmount;

                                        window.location.href = base_url + 'lobby';
                                        //  $scope.loginData = {};
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
                                },
                                function errorCallback(data)
                                {

                                    if (typeof data == 'object')
                                    {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                });

            }
            $scope.formData = {};

            if (getQueryStringValue('referral')) {
                $scope.formData.ReferralCode = getQueryStringValue('referral');
                $scope.activeTab = 'signup';
            }

            /*signUp*/
            $scope.signpOTP = false;
            $scope.signupSubmitted = false;
            $scope.signUp = function (form) {
                var $data = {};
                $scope.helpers = Mobiweb.helpers;
                $scope.signup_error = false;
                $scope.signup_message = ''; //login message
                $scope.signupSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                $scope.formData.UserTypeID = 2;
                $scope.formData.Source = 'Direct';
                $scope.formData.DeviceType = 'Native';
                var data = $scope.formData;
                appDB
                        .callPostForm('signup', data)
                        .then(
                                function success(data)
                                {
                                    if (data.ResponseCode == 200)
                                    {
                                        $scope.openPopup('verifyMobile');
                                        $scope.formData = {};
                                        $scope.signupSubmitted = false;
                                        $scope.LoginSubmitted = false;
                                        $scope.confrim_password = '';
                                    }

                                    if (data.ResponseCode == 500)
                                    {
                                        var toast = toastr.warning(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                    if (data.ResponseCode == 501)
                                    {
                                        var toast = toastr.error(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data)
                                {
                                    if (typeof data == 'object')
                                    {

                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                    }
                                });
            }

            // Verify mobile on signup
            $scope.verifyOTPSubmitted = false;
            $scope.verifySignupOTP = function (form) {
                $scope.helpers = Mobiweb.helpers;
                $scope.verifyOTPSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                $scope.formData.OTP = $scope.OTP;
                var data = $scope.formData;
                appDB
                        .callPostForm('signup/verifyPhoneNumber', data)
                        .then(
                                function success(data){
                                    if (data.ResponseCode == 200){
                                        $scope.closePopup('verifyMobile');
                                        toastr.success('Your account is verified,Please Signin.', {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.activeTab = 'login';
                                    }
                                    if (data.ResponseCode == 500){
                                        var toast = toastr.warning(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                    if (data.ResponseCode == 501){
                                        var toast = toastr.error(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data)
                                {
                                    if (typeof data == 'object')
                                    {

                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                    }
                                });
            }

            /* send forgot password email */
            $scope.forgotPasswordData = {};
            $scope.forgotEmailSubmitted = false;
            $scope.sendEmailForgotPassword = function (form) {
                $scope.forgotEmailSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                $scope.data.listLoading = true;
                $scope.forgotPasswordData.type = ($scope.CheckEmail($scope.forgotPasswordData.Keyword))?'Email':'Phone';
                var data = $scope.forgotPasswordData;
                appDB
                        .callPostForm('recovery', data)
                        .then(
                                function success(data)
                                {
                                    $scope.data.listLoading = false;
                                    if (data.ResponseCode == 200)
                                    {
                                        $scope.closePopup('forgotPassword');
                                        $scope.openPopup('verifyForgotPassword');
                                        toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.forgotPasswordData = {};
                                    }

                                    if (data.ResponseCode == 500)
                                    {
                                        var toast = toastr.warning(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                    if (data.ResponseCode == 501)
                                    {
                                        var toast = toastr.error(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data)
                                {
                                    $scope.data.listLoading = false;
                                    if (typeof data == 'object')
                                    {

                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                    }
                                });
            }

            /* verify forgot password & create new password */
            $scope.forgotPassword = {};
            $scope.forgotPasswordSubmitted = false;
            $scope.verifyForgotPassword = function (form) {
                $scope.forgotPasswordSubmitted = true;
                if (!form.$valid)
                {
                    return false;
                }
                $scope.data.listLoading = true;
                var data = $scope.forgotPassword;
                appDB
                        .callPostForm('recovery/setPassword', data)
                        .then(
                                function success(data)
                                {
                                    $scope.data.listLoading = false;
                                    if (data.ResponseCode == 200)
                                    {
                                        $scope.closePopup('verifyForgotPassword');
                                        toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.forgotPassword = {};
                                    }

                                    if (data.ResponseCode == 500)
                                    {
                                        var toast = toastr.warning(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                    if (data.ResponseCode == 501)
                                    {
                                        var toast = toastr.error(data.Message);
                                        toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data)
                                {
                                    $scope.data.listLoading = false;
                                    if (typeof data == 'object')
                                    {

                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                    }
                                });
            }

            /*resend otp for account verification*/

            /*Social Login*/
            $scope.SocialLogin = function (Source) {

                $rootScope.$on('event:social-sign-in-success', function (event, userDetails) {
                    var $data = {};
                    $scope.formData = {};

                    $scope.formData.UserTypeID = 2;
                    $scope.formData.Source = Source;
                    $scope.formData.Password = userDetails.uid;
                    $scope.formData.DeviceType = 'Native';
                    var $data = $scope.formData;
                    appDB
                            .callPostForm('signin', $data)
                            .then(
                                    function successCallback(data)
                                    {

                                        if (data.ResponseCode == 200)
                                        {

                                            $localStorage.user_details = data.Data;
                                            $localStorage.isLoggedIn = true;
                                            $localStorage.SocialLogin = true;
                                            $sessionStorage.walletBalance = data.Data.WalletAmount;
                                            $scope.loginData = {};

                                            window.location.href = base_url + 'lobby';
                                        }
                                        if (data.ResponseCode == 500) {

                                            var $data = {};
                                            delete $scope.formData;
                                            $scope.formData = {};

                                            $scope.formData.UserTypeID = 2;
                                            $scope.formData.Source = Source;
                                            $scope.formData.SourceGUID = userDetails.uid;
                                            $scope.formData.FirstName = userDetails.name;
                                            $scope.formData.DeviceType = 'Native';
                                            $scope.formData.Email = userDetails.email;
                                            var $data = $scope.formData;
                                            appDB
                                                    .callPostForm('signup', $data)
                                                    .then(
                                                            function success(data)
                                                            {
                                                                if (data.ResponseCode == 200)
                                                                {
                                                                    $localStorage.SocialLogin = true;
                                                                    $localStorage.user_details = data.Data;
                                                                    $localStorage.isLoggedIn = true;
                                                                    $sessionStorage.walletBalance = data.Data.WalletAmount;

                                                                    window.location.href = base_url + 'lobby';
                                                                }

                                                                if (data.ResponseCode == 500)
                                                                {
                                                                    var toast = toastr.warning(data.Message);
                                                                    toastr.refreshTimer(toast, 5000);
                                                                }

                                                                if (data.ResponseCode == 501)
                                                                {
                                                                    var toast = toastr.error(data.Message);
                                                                    toastr.refreshTimer(toast, 5000);
                                                                }

                                                            },
                                                            function error(data)
                                                            {
                                                                if (typeof data == 'object')
                                                                {

                                                                    var toast = toastr.error(data.Message, {
                                                                        closeButton: true
                                                                    });
                                                                    toastr.refreshTimer(toast, 5000);

                                                                }
                                                            });
                                        }
                                    },
                                    function errorCallback(data)
                                    {
                                        delete $scope.formData;
                                        var $data = {};
                                        $scope.formData = {};
                                        $scope.formData.UserTypeID = 2;
                                        $scope.formData.Source = Source;
                                        $scope.formData.SourceGUID = userDetails.uid;
                                        $scope.formData.FirstName = userDetails.name;
                                        $scope.formData.DeviceType = 'Native';
                                        $scope.formData.Email = userDetails.email;
                                        var $data = $scope.formData;

                                        appDB
                                                .callPostForm('signup', $data)
                                                .then(
                                                        function success(data)
                                                        {
                                                            if (data.ResponseCode == 200)
                                                            {
                                                                $localStorage.user_details = data.Data;
                                                                $localStorage.isLoggedIn = true;
                                                                $sessionStorage.walletBalance = data.Data.WalletAmount;
                                                                window.location.href = base_url + 'lobby';
                                                            }

                                                            if (data.ResponseCode == 500)
                                                            {
                                                                var toast = toastr.warning(data.Message);
                                                                toastr.refreshTimer(toast, 5000);
                                                            }

                                                            if (data.ResponseCode == 501)
                                                            {
                                                                var toast = toastr.error(data.Message);
                                                                toastr.refreshTimer(toast, 5000);
                                                            }

                                                        },
                                                        function error(data)
                                                        {
                                                            if (typeof data == 'object')
                                                            {

                                                                var toast = toastr.error(data.Message, {
                                                                    closeButton: true
                                                                });
                                                                toastr.refreshTimer(toast, 5000);

                                                            }
                                                        });
                                    });

                });
            }

            /*Testimonials*/
            $scope.Testimonials = [];
            $scope.getTestimonials = function () {
                var $data = {};
                $data.PostType = 'Testimonial';
                $data.SessionKey = '18599164-ac6a-9eb6-2f8b-b5df43ab1fe1';
                appDB
                        .callPostForm('utilities/getPosts', $data)
                        .then(
                                function success(data)
                                {

                                    if (data.ResponseCode == 200)
                                    {

                                        $scope.Testimonials = data.Data.Records;

                                    }

                                    if (data.ResponseCode == 500)
                                    {
                                        //           var toast = toastr.warning(data.Message);
                                        // toastr.refreshTimer(toast, 5000);
                                    }

                                    if (data.ResponseCode == 501)
                                    {
                                        //           var toast = toastr.error(data.Message);
                                        // toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data)
                                {
                                    if (typeof data == 'object')
                                    {

                                        // var toast =  toastr.error(data.Message, {
                                        //   	closeButton: true
                                        // });
                                        // toastr.refreshTimer(toast, 5000);

                                    }
                                });
            }
            $scope.getTestimonials();

            // Check email
            $scope.CheckEmail = function(mail){
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
                    return true;
                }else{
                    return false;
                }
            }
        } else {
            window.location.href = base_url + 'lobby';
        }
    }]);