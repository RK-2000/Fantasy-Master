app.controller('HomeController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', '$sce', 'toastr', 'socialLoginService', '$http', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, $sce, toastr, socialLoginService, $http) {
        $scope.env = environment;
        if (!$localStorage.hasOwnProperty('user_details')){
        /*Function to get matches */
        $scope.MatchesList = [];
        $scope.getMatches = function () {
            var $data = {};
            $scope.silder_visible = false;
            $data.Params = 'SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status,StatusID';
            $data.Status = 'Pending';
            $data.PageSize = 15;
            $data.PageNo = 1;
            appDB
                    .callPostForm('sports/getMatches', $data)
                    .then(
                            function successCallback(data) {
                                if (data.ResponseCode == 200) {
                                    $scope.MatchesList = data.Data.Records;
                                    $scope.silder_visible = true;
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

        $scope.TestimonialList = [];
        $scope.getTestimonial = function () {
            var $data = {};
            $scope.testimonial_silder_visible = false;
            $data.PostType = 'Testimonial';
            appDB
                    .callPostForm('utilities/getPosts', $data)
                    .then(
                            function successCallback(data) {
                                if (data.ResponseCode == 200) {
                                    $scope.TestimonialList = data.Data.Records;
                                    $scope.testimonial_silder_visible = true;
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
        }else{
            window.location.href = base_url + 'lobby';
        }
    }]);

app.directive('slickCustomCarousel', ["$timeout", function ($timeout) {
        return {
            restrict: "A",
            link: {
                post: function (scope, elem, attr) {
                    $timeout(function () {
                        $('.slider').slick({
                            dots: false,
                            infinite: false,
                            speed: 300,
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        slidesToShow: 3,
                                        slidesToScroll: 3,
                                        infinite: true,
                                        dots: true
                                    }
                                },
                                {
                                    breakpoint: 768,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 2
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });

                    }, 1);

                }
            }
        }
    }]);

app.directive('testimonialSlider', ["$timeout", function ($timeout) {
    return {
        restrict: "A",
        link: {
            post: function (scope, elem, attr) {
                $timeout(function () {
                    $('#clientSlider').slick({
                        dots: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true,
                                    dots: true
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });

                }, 1);

            }
        }
    }
}]);