'use strict';

app.controller('couponController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', function($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr) {
    $scope.env = environment;


    $scope.CouponsList = [];
    $scope.getCoupons = function() {
        var $data = {};
        $data.SessionKey = $localStorage.user_details.SessionKey;
        $data.Status = 'Active';

        appDB
            .callPostForm('store/getCoupons', $data)
            .then(
                function successCallback(data) {
                    if (data.ResponseCode == 200) {
                        $scope.CouponsList = data.Data.Records;
                    }
                },
                function errorCallback(data) {

                }
            );
    }

}]);