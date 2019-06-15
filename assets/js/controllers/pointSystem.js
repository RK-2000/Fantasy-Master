'use strict';
app.controller('pointSystemController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', 'Upload', function($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, Upload) {
    $scope.env = environment;

    $scope.PointCategory = 'Normal';

    $scope.activeTab = 'T20';

    $scope.ChangeTab = function(tab) {
        $scope.activeTab = tab;
    }

    $scope.pointSystem = function() {
        $scope.points = [];
        var $data = {};
        $data.StatusID = 1;
        $data.PointCategory = $scope.PointCategory;
        appDB
            .callPostForm('sports/getPoints', $data)
            .then(
                function successCallback(data) {
                    if (data.ResponseCode == 200) {
                        $scope.points = data.Data.Records;
                        for (var i = 0; i < $scope.points.length; i++) {
                            $scope.points[i].Sort = parseInt($scope.points[i].Sort);
                        }
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
}]);