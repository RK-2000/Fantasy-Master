app.controller('PageController', function ($scope, $http, $timeout) {
    $scope.data.pageSize = 100;
    $scope.data.ParentCategoryGUID = ParentCategoryGUID;
    /*----------------*/

    /*list*/
    $scope.applyFilter = function () {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /*list append*/
    $scope.getList = function () {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey + '&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&' + $('#filterForm').serialize();
        $http.post(API_URL + 'admin/config/getApiLogs', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data.Records) { /* success case */
                $scope.data.totalRecords = response.Data.TotalRecords;
                $scope.data.IsAPILogs = response.Data.IsAPILogs;
                for (var i in response.Data.Records) {
                    $scope.data.dataList.push(response.Data.Records[i]);
                }
                $scope.data.pageNo++;
            } else {
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
        });
    }

    /*load delete form*/
    $scope.deleteAPILog = function (Position, LogId) {
        if (confirm('Are you sure, want to delete this api log ?')) {
            $scope.addDataLoading = true;
            $http.post(API_URL + 'admin/config/deleteApiLogs', 'SessionKey=' + SessionKey + '&LogId=' + LogId, contentType).then(function (response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if (response.ResponseCode == 200) { /* success case */
                    $scope.getList();
                    location.reload();
                    alertify.success(response.Message);
                } else {
                    alertify.error(response.Message);
                }
                $scope.addDataLoading = false;
            });
        }
    }

    //delete all api logs
    $scope.deleteAll = function () {
        if (confirm('Are you sure, want to delete all api log ?')) {
            $scope.addDataLoading = true;
            $http.post(API_URL + 'admin/config/deleteAllApiLogs', 'SessionKey=' + SessionKey, contentType).then(function (response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if (response.ResponseCode == 200) { /* success case */
                    $scope.getList();
                    location.reload();
                    alertify.success(response.Message);
                } else {
                    alertify.error(response.Message);
                }
                $scope.addDataLoading = false;
            });
        }
    }

    /*load view form*/
    $scope.viewAPILog = function (Position) {
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/view_form.htm?' + Math.random();
        $scope.formData = Position
        console.log($scope.formData);
        $('#view_model').modal({ show: true });
    }

});

