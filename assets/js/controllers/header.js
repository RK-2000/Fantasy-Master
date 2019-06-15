'use strict';
app.directive('addCash', ['$localStorage', '$sessionStorage', 'appDB', '$location', 'toastr', function ($localStorage, $sessionStorage, appDB, $location, toastr) {
        return {
            restrict: 'E',
            controller: 'headerController',
            templateUrl: 'addCashPopup.php',
            link: function (scope, element, attributes) {
                scope.openBolt = function () {
                    console.log(scope.payUData);
                    bolt.launch({
                        key: scope.payUData.MerchantKey,
                        txnid: scope.payUData.TransactionID,
                        hash: scope.payUData.Hash,
                        amount: scope.payUData.Amount,
                        firstname: scope.payUData.FirstName,
                        email: scope.payUData.Email,
                        phone: scope.payUData.PhoneNumber,
                        productinfo: scope.payUData.ProductInfo.toString(),
                        surl: scope.payUData.SuccessURL,
                        furl: scope.payUData.FailedURL,
                        lastname: '',
                        curl: '',
                        address1: '',
                        address2: '',
                        city: '',
                        state: '',
                        country: '',
                        zipcode: '',
                        udf1: '',
                        udf2: '',
                        udf3: '',
                        udf4: '',
                        udf5: '',
                        pg: '',
                        enforce_paymethod: '',
                        expirytime: ''
                    }, {
                        responseHandler: function (get) {

                            if (get.response.txnStatus == 'SUCCESS') {
                                var status = 'Success';
                            } else if (get.response.txnStatus == 'CANCEL') {
                                var status = 'Cancelled';
                            } else {
                                var status = 'Failed';
                            }

                            var $data = {
                                "SessionKey": $localStorage.user_details.SessionKey,
                                "PaymentGateway": "PayUmoney",
                                "PaymentGatewayStatus": status,
                                // "WalletID":get.response.productinfo,
                                "WalletID": scope.payUData.ProductInfo.toString(),
                                "PaymentGatewayResponse": JSON.stringify(get.response)
                            };

                            appDB
                                    .callPostForm('wallet/confirm', $data)
                                    .then(
                                            function success(data) {
                                                if (data.ResponseCode == 200) {
                                                    $localStorage.user_details.WalletAmount = parseFloat(data.Data.WalletAmount).toFixed(2);
                                                    delete $sessionStorage.CouponGUID;
                                                    if (status == 'Cancelled') {
                                                        window.location.href = base_url + 'myAccount?status=Cancelled';
                                                    }
                                                    if (status == 'Success') {
                                                        window.location.href = base_url + 'myAccount?status=Success';
                                                    }

                                                    var toast = toastr.success(data.Message, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                } else {
                                                    window.location.href = base_url + 'myAccount?status=Failed';
                                                    var toast = toastr.error(data.Message, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            },
                                            function error(data) {
                                                console.log('error', data);
                                            }
                                    );

                        },
                        catchException: function (get) {
                            alert(get.message);
                        }
                    });
                }
            }
        };
    }]);
app.controller('headerController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', '$sce', 'toastr', 'socialLoginService', '$http','$timeout', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, $sce, toastr, socialLoginService, $http,$timeout) {
        $scope.env = environment;
        $scope.paymentMode = 'payu';
        $scope.headerActiveMenu = 'lobby';
        var pathArray = window.location.pathname.split('/');
        var secondLevelLocation = pathArray[2];
        if (window.location.host == 'www.fsl11.com') {
            secondLevelLocation = pathArray[1];
        }
        $scope.type = getQueryStringValue('type');
        $scope.secondLevelLocation = secondLevelLocation;
        $scope.base_url = base_url;
        if ($scope.secondLevelLocation == 'lobby' || $scope.secondLevelLocation == 'league' || $scope.secondLevelLocation == 'createTeam') {
            $scope.headerActiveMenu = 'lobby';
        } else if ($scope.secondLevelLocation == 'auction' || $scope.secondLevelLocation == 'createAuctionTeam') {
            $scope.headerActiveMenu = 'auction';
        } else {
            $scope.headerActiveMenu = $scope.secondLevelLocation;
        }
        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.user_details = $localStorage.user_details;
            $scope.isLoggedIn = $localStorage.isLoggedIn;
            $scope.base_url = base_url;
            $scope.referral_url = base_url + $localStorage.user_details.ReferralCode;


            $rootScope.resetPromo = function (isPromoCode) {
                if (!isPromoCode) {
                    $scope.PromoCodeFlag = false;
                    $scope.PromoCode = '';
                    $scope.GotCashBonus = 0;
                    $scope.CouponData = {};
                }
            }

            /* 
             Description : To apply coupon code 
             */
            $scope.PromoCodeFlag = false;
            $scope.PromoCode = '';
            $scope.GotCashBonus = 0;
            $scope.CouponData = {};

            /*Add and validate coupon code*/
            $scope.applyPromoCode = function (PromoCode, Amount) {

                $scope.PromoCode = PromoCode;

                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.CouponCode = $scope.PromoCode;
                $data.Amount = Amount;
                appDB
                        .callPostForm('store/validateCoupon', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.PromoCodeFlag = true;
                                        $scope.CouponData = data.Data;

                                        if ($scope.CouponData.CouponType == 'Percentage') {
                                            $scope.GotCashBonus = ($scope.CouponData.CouponValue / 100) * $scope.amount;
                                            console.log('$scope.GotCashBonus ', $scope.GotCashBonus);
                                        } else {
                                            $scope.GotCashBonus = $scope.CouponData.CouponValue;
                                        }
                                        $sessionStorage.CouponGUID = $scope.CouponData.CouponGUID;
                                    }
                                    if (data.ResponseCode == 500) {
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
                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                function errorCallback(data) {
                                    var toast = toastr.warning(data.Message, {
                                        closeButton: true
                                    });
                                    toastr.refreshTimer(toast, 5000);
                                }
                        );
            }

            /*Remove applied coupon*/
            $scope.removeCoupon = function () {
                $scope.PromoCodeFlag = false;
                $scope.PromoCode = '';
                $scope.GotCashBonus = 0;
                $scope.CouponData = {};
                delete $sessionStorage.CouponGUID;
            }

            /*add cash popup*/
            $scope.addMoreCash = function (amnt) {
                $scope.removeCoupon();
                $scope.amount = (!$scope.amount) ? 0 : $scope.amount;
                $scope.amount = Number($scope.amount) + amnt;
            }
            $scope.cashSubmitted = false;
            $scope.selectPaymentMode = function (amount, form) {

                $scope.cashSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                if (parseFloat(amount) < 1) {
                    $scope.errorAmount = true;
                    $scope.errorAmountMsg = 'Min limit of adding balance is 1';
                    return false;
                }

                $scope.isWalletSubmitted = false;
                if (!form.$valid) {
                    $scope.isWalletSubmitted = true;
                    return false;
                }
                if (parseFloat($scope.amount) > 10000) {
                    $scope.errorAmount = true;
                    $scope.errorAmountMsg = 'Daily add cash limit is Rs 10000, Pls do varification of your KYC to increase limit.';
                    return false;
                }
                // if($scope.amount<100)
                // {
                //   $scope.errorAmount = true;
                //   $scope.errorAmountMsg = 'Min limit of adding balance is 100';
                //   return false;
                // }
                $rootScope.addBalance = {
                    'amount': amount
                };
                $scope.closePopup('add_money');
                $scope.closePopup('add_more_money');
                window.location.href = 'paymentMethod?amount=' + amount;
            }
            /*validate amount*/
            $scope.validateAmount = function () {
                $scope.isWalletSubmitted = false;
                $scope.errorAmount = false;
                $scope.errorAmount = '';
                if ($scope.amount.match(/^0[0-9].*$/)) {
                    $scope.amount = $scope.amount.replace(/^0+/, '');
                }
                if ($scope.amount < 1) {
                    $scope.errorAmount = true;
                    $scope.errorAmountMsg = 'Min limit of adding balance is 1';
                    return false;
                }
                if ($scope.amount > 10000) {
                    $scope.amount = 10000;
                    $scope.errorAmount = true;
                    $scope.errorAmountMsg = 'Daily add cash limit is Rs 10000, Pls do varification of your KYC to increase limit.';
                    return false;
                }
            }
            /*PayU Money Request*/
            if (getQueryStringValue('amount')) {
                $scope.amount = getQueryStringValue('amount');
            }
            $scope.payUReq = function (amount) {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.PaymentGateway = 'PayUmoney';
                $data.RequestSource = 'Web';
                $data.Amount = Number($scope.amount);
                $data.FirstName = $localStorage.user_details.FirstName;
                $data.Email = $localStorage.user_details.Email;
                $data.PhoneNumber = $localStorage.user_details.PhoneNumber;
                if ($sessionStorage.hasOwnProperty('CouponGUID')) {
                    $data.CouponGUID = $sessionStorage.CouponGUID;
                }
                $scope.isWalletSubmitted = true;

                $rootScope.payUData = {};

                appDB
                        .callPostForm('wallet/add', $data)
                        .then(
                                function success(data) {
                                    if (data.ResponseCode == 200) {
                                        $rootScope.payUData = data.Data;
                                        setTimeout(function () {
                                            $scope.openBolt();
                                        }, 100);
                                    } else if (data.ResponseCode == 500) {
                                        var toast = toastr.warning('Update phone number to add money from settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                    }

                                },
                                function error(data) {

                                    if (data.ResponseCode == 500) {
                                        console.log(data);
                                        var toast = toastr.warning('Update phone number to add money from settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                    }
                                }
                        );
            }

            $scope.payTmReq = function (amount) {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.PaymentGateway = 'Paytm';
                $data.RequestSource = 'Web';
                $data.Amount = Number($scope.amount);
                $data.FirstName = $localStorage.user_details.FirstName;
                $data.Email = $localStorage.user_details.Email;
                $data.PhoneNumber = $localStorage.user_details.PhoneNumber;
                if ($sessionStorage.hasOwnProperty('CouponGUID')) {
                    $data.CouponGUID = $sessionStorage.CouponGUID;
                }
                $scope.isWalletSubmitted = true;
                $rootScope.payData = {};
                appDB
                        .callPostForm('wallet/add', $data)
                        .then(
                                function success(data) {
                                    console.log(JSON.stringify(data));
                                    if (data.ResponseCode == 200) {

                                        $rootScope.payData = data.Data;
                                        $scope.MID = $rootScope.payData.MerchantID;
                                        $scope.ORDER_ID = $rootScope.payData.OrderID;
                                        $scope.CHANNEL_ID = $rootScope.payData.ChannelID;
                                        $scope.CHECKSUMHASH = $rootScope.payData.CheckSumHash;
                                        $scope.PAYTM_TXN_URL = $rootScope.payData.TransactionURL;
                                        $scope.CUST_ID = $rootScope.payData.CustomerID;
                                        $scope.INDUSTRY_TYPE_ID = $rootScope.payData.IndustryTypeID;
                                        $scope.WEBSITE = $rootScope.payData.Website;
                                        $scope.TXN_AMOUNT = $rootScope.payData.Amount;
                                        $scope.CALLBACK_URL = $rootScope.payData.CallbackURL;
                                        // $scope.CALLBACK_URL='http://localhost/515-FSL11/paymentMethod?amount='+$scope.TXN_AMOUNT;
                                        // console.log($rootScope.payData); return false;
                                        setTimeout(function () {
                                            $scope.submitPayTmData();
                                        }, 1000);
                                        delete $sessionStorage.CouponGUID;
                                    }
                                    if (data.ResponseCode == 500) {

                                        var toast = toastr.warning('Update phone number to add money from user settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                        $timeout(function(){
                                            window.location.href = base_url + 'settings';
                                        },2000);
                                    }
                                    if (data.ResponseCode == 502) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                function error(data) {

                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning('Update phone number to add money from user settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                        $timeout(function(){
                                            window.location.href = base_url + 'settings';
                                        },2000);
                                    }
                                }
                        );

            }

            $scope.submitPayTmData = function (payTmData) {
                angular.element('#submitPayTm').trigger('submit')
            }

            $scope.addExtraCash = function (amount) {
                 $scope.amount = parseFloat($scope.amount)+parseFloat(amount);
//                $scope.amount = parseFloat(amount);
            }

            $scope.razorPayReq = function (amount) {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.PaymentGateway = 'Razorpay';
                $data.RequestSource = 'Web';
                $data.Amount = Number($scope.amount);
                $data.FirstName = $localStorage.user_details.FirstName;
                $data.Email = $localStorage.user_details.Email;
                $data.PhoneNumber = $localStorage.user_details.PhoneNumber;
                if ($sessionStorage.hasOwnProperty('CouponGUID')) {
                    $data.CouponGUID = $sessionStorage.CouponGUID;
                }
                $scope.isWalletSubmitted = true;

                $rootScope.razorPayData = {};

                appDB
                        .callPostForm('wallet/add', $data)
                        .then(
                                function success(data) {
                                    if (data.ResponseCode == 200) {
                                        var razorPayData = data.Data;
                                        
                                        setTimeout(function () {
                                            var razorPayoptions = {
                                                'key': razorPayData.MerchantKey,
                                                'amount': razorPayData.Amount,
                                                'name': razorPayData.MerchantName,
                                                'description': 'Add funds',
//                                                'order_id':razorPayData.OrderID,
                                                'handler': function (transaction) {
                                                    $scope.razorPayTransactionHandler(transaction, razorPayData.OrderID,razorPayData.Amount);
                                                },
                                                'prefill': {
                                                    'name': $localStorage.user_details.FirstName,
                                                    'email': $localStorage.user_details.Email,
                                                    'contact': $localStorage.user_details.PhoneNumber
                                                },
                                                'notes':{
                                                    'UserID':$localStorage.user_details.UserID,
                                                    'OrderID':razorPayData.OrderID
                                                }
                                            };
                                            console.log("razor"+ JSON.stringify(razorPayoptions));
                                            $.getScript('https://checkout.razorpay.com/v1/checkout.js', function () {
                                                var rzp1 = new Razorpay(razorPayoptions);
                                                rzp1.open();
                                            });
                                        }, 100);
                                    } else if (data.ResponseCode == 500) {
                                        var toast = toastr.warning('Update phone number to add money from user settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                        $timeout(function(){
                                            window.location.href = base_url + 'settings';
                                        },2000);
                                    }

                                },
                                function error(data) {

                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning('Update phone number to add money from user settings.', {
                                            closeButton: true
                                        });

                                        toastr.refreshTimer(toast, 5000);
                                        $timeout(function(){
                                            window.location.href = base_url + 'settings';
                                        },2000);
                                    }
                                }
                        );
            }

            $scope.razorPayTransactionHandler = function (transaction, OrderID,Amount) {
                console.log('transaction', transaction);
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.PaymentGateway = 'Razorpay';
                $data.PaymentGatewayResponse = JSON.stringify(transaction);
                $data.PaymentGatewayStatus = status = (!transaction.razorpay_payment_id) ? 'Failed' : 'Success';
                $data.WalletID = OrderID;
                $data.Razor_payment_id = transaction.razorpay_payment_id;
                $data.Amount = Amount;
                appDB
                        .callPostForm('wallet/confirmWeb', $data)
                        .then(
                                function success(data) {
                                    if (data.ResponseCode == 200) {
                                        $localStorage.user_details.WalletAmount = parseFloat(data.Data.WalletAmount).toFixed(2);
                                        delete $sessionStorage.CouponGUID;
                                        if (status == 'Cancelled') {
                                            window.location.href = base_url + 'myAccount?status=Cancelled';
                                        }
                                        if (status == 'Success') {
                                            window.location.href = base_url + 'myAccount?status=Success';
                                        }

                                        var toast = toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    } else {
                                        window.location.href = base_url + 'myAccount?status=Failed';
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                },
                                function error(data) {
                                    console.log('error', data);
                                }
                        );
            }

                /*get notifications*/
                $scope.notificationList = [];
                $scope.getNotifications = function (){
                    var $data = {};
                    $data.SessionKey = $localStorage.user_details.SessionKey;
                    $data.PageNo = 1;
                    $data.PageSize = 10;
                    $data.Status = 1;
                    appDB
                            .callPostForm('notifications', $data)
                            .then(
                                    function successCallback(data) { 
                                        $scope.getNotificationCount();
                                        if (data.ResponseCode == 200 && data.Data.Records) {
                                            $scope.notificationList = data.Data.Records;
                                        }else if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.reload();
                                            }, 1000);
                                        }

                                    },
                                    function errorCallback(data) {
                                        localStorage.clear();
                                    }
                            );
                }


                /*get notification count*/
                $scope.notificationCount = 0;
                $scope.getNotificationCount = function (){
                    var $data = {};
                    $data.SessionKey = $localStorage.user_details.SessionKey;
                    appDB
                            .callPostForm('notifications/getNotificationCount', $data)
                            .then(
                                    function successCallback(data) {
                                        if (data.ResponseCode == 200) {
                                            $scope.notificationCount = Number(data.Data.TotalUnread);
                                        }else if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.reload();
                                            }, 1000);
                                        }

                                    },
                                    function errorCallback(data) {
                                        localStorage.clear();
                                    }
                            );
                }

                $scope.readNotification = function(notification_id){
                    var $data = {};
                    $data.SessionKey = $localStorage.user_details.SessionKey;
                    $data.NotificationID = notification_id;
                    appDB
                            .callPostForm('notifications/markRead', $data)
                            .then(
                                    function successCallback(data) {
                                        if (data.ResponseCode == 200) {
                                            $scope.getNotifications();
                                        }else if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.reload();
                                            }, 1000);
                                        }
                                    },
                                    function errorCallback(data) {
                                        localStorage.clear();
                                    }
                            );
                }
            
//            setInterval(function () {
//                $scope.getNotifications();
//            }, 15000);

            /*Logout*/
            $scope.logout = function () {
                $http.jsonp('https://accounts.google.com/logout');
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                appDB
                        .callPostForm('signin/signout/', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        localStorage.clear();
                                        socialLoginService.logout();
                                        $http.jsonp('https://accounts.google.com/logout');
                                        window.location.href = base_url;
                                    }
                                },
                                function errorCallback(data) {
                                    localStorage.clear();
                                }
                        );
            }
        }


    }]);

app.directive('addMoreCash', ['$localStorage', '$sessionStorage', 'appDB', '$location', 'toastr', function ($localStorage, $sessionStorage, appDB, $location, toastr) {
        return {
            restrict: 'E',
            controller: 'headerController',
            templateUrl: 'balance.php',
            link: function (scope, element, attributes) {
                scope.openBolt = function () {
                    bolt.launch({
                        key: scope.payUData.MerchantKey,
                        txnid: scope.payUData.TransactionID,
                        hash: scope.payUData.Hash,
                        amount: scope.payUData.Amount,
                        firstname: scope.payUData.FirstName,
                        email: scope.payUData.Email,
                        phone: scope.payUData.PhoneNumber,
                        productinfo: scope.payUData.ProductInfo.toString(),
                        surl: scope.payUData.SuccessURL,
                        furl: scope.payUData.FailedURL,
                        lastname: '',
                        curl: '',
                        address1: '',
                        address2: '',
                        city: '',
                        state: '',
                        country: '',
                        zipcode: '',
                        udf1: '',
                        udf2: '',
                        udf3: '',
                        udf4: '',
                        udf5: '',
                        pg: '',
                        enforce_paymethod: '',
                        expirytime: ''
                    }, {
                        responseHandler: function (get) {

                            if (get.response.txnStatus == 'SUCCESS') {
                                var status = 'Success';
                            } else if (get.response.txnStatus == 'CANCEL') {
                                var status = 'Cancelled';
                            } else {
                                var status = 'Failed';
                            }

                            var $data = {
                                "SessionKey": $localStorage.user_details.SessionKey,
                                "PaymentGateway": "PayUmoney",
                                "PaymentGatewayStatus": status,
                                // "WalletID":get.response.productinfo,
                                "WalletID": scope.payUData.ProductInfo.toString(),
                                "PaymentGatewayResponse": JSON.stringify(get.response)
                            };

                            appDB
                                    .callPostForm('wallet/confirm', $data)
                                    .then(
                                            function success(data) {
                                                console.log(data);
                                                if (data.ResponseCode == 200) {
                                                    $localStorage.user_details.WalletAmount = parseFloat(data.Data.WalletAmount).toFixed(2);
                                                    delete $sessionStorage.CouponGUID;
                                                    if (status == 'Cancelled') {
                                                        window.location.href = base_url + 'myAccount?status=Cancelled';
                                                    }
                                                    if (status == 'Success') {
                                                        window.location.href = base_url + 'myAccount?status=Success';
                                                    }

                                                    var toast = toastr.success(data.Message, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                } else {
                                                    window.location.href = base_url + 'myAccount?status=Failed';
                                                    var toast = toastr.error(data.Message, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            },
                                            function error(data) {
                                                console.log('error', data);
                                            }
                                    );

                        },
                        catchException: function (get) {
                            alert(get.message);
                        }
                    });
                }
            }
        };
    }]);

app.directive('addWithdrawalRequest', ['$localStorage', '$sessionStorage', 'appDB', '$location', 'toastr', function ($localStorage, $sessionStorage, appDB, $location, toastr) {
        return {
            restrict: 'E',
            controller: 'headerController',
            templateUrl: 'WithdrawalRequest.php',
            link: function (scope, element, attributes) {
                //$scope.getWalletDetails
                scope.withdrawSubmitted = false;
                scope.PaytmPhoneNumber = $localStorage.user_details.PhoneNumber;

                scope.showOtp = false;

                scope.withdrawRequest = function (form, amount, PaymentGateway) {
                    scope.helpers = Mobiweb.helpers;
                    scope.withdrawSubmitted = true;
                    if (!form.$valid) {
                        return false;
                    }
                    var $data = {};
                    // $data.PaymentGateway = 'Bank';
                    $data.PaymentGateway = PaymentGateway;
                    // if (PaymentGateway == 'Paytm') {
                    $data.PaytmPhoneNumber = $localStorage.user_details.PhoneNumber;
                    // $data.PaytmPhoneNumber = '7777777777';
                    // }

                    $data.Amount = amount;
                    $data.SessionKey = $localStorage.user_details.SessionKey;
                    $data.UserGUID = $localStorage.user_details.UserGUID;
                    appDB
                            .callPostForm('wallet/withdrawal', $data)
                            .then(
                                    function successCallback(data) {

                                        if (data.ResponseCode == 200) {
                                            var toast = toastr.success(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            scope.getWalletDetails();
                                            scope.closePopup('withdrawPopup');
//                                            scope.showOtp = true;

//                                            scope.WithdrawalID = data.Data.WithdrawalID;
//                                            scope.WithdrawalAmount = amount;

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
                                            localStorage.clear();
                                            window.location.reload();
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

                scope.withdrawlConfirm = function (withdrawalID, OTP, Mode) {
                    var $walletConfirm = {};
                    $walletConfirm.WithdrawalID = withdrawalID;
                    $walletConfirm.OTP = OTP;
                    $walletConfirm.PaymentGateway = Mode;
                    $walletConfirm.SessionKey = $localStorage.user_details.SessionKey;
                    $walletConfirm.PaytmPhoneNumber = $localStorage.user_details.PhoneNumber;
                    $walletConfirm.Amount = scope.WithdrawalAmount;
                    appDB

                            .callPostForm('wallet/withdrawal_confirm', $walletConfirm)
                            .then(
                                    function successCallback(data) {

                                        if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            localStorage.clear();
                                            window.location.reload();
                                        }
                                        if (data.ResponseCode == 200) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            scope.closePopup('withdrawPopup');
                                            scope.showOtp = false;

                                            delete scope.WithdrawalID;
                                        }

                                    },
                                    function errorCallback(data) {

                                    }
                            );

                }
            }
        };
    }]);