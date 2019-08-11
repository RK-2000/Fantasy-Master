app.controller('PageController', function ($scope, $http, $timeout) {

    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;

    $scope.getUserInfo = function(){
        $scope.userData = {};
        var UserGUID = getQueryStringValue('UserGUID');
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Email', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.userData = response.Data;
            }
        });
    }

    $scope.getUserInfo();
    
    /*list append*/
    $scope.getList = function () {
       if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        if (getQueryStringValue('UserGUID')) {
            var UserGUID = getQueryStringValue('UserGUID');
        } else {
            var UserGUID = '';
        }
        var data = 'SessionKey=' + SessionKey+'&UserGUID='+UserGUID+'&IsAdmin=No&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=' + $scope.data.OrderBy + '&Sequence=' + $scope.data.Sequence + '&' +'Params=RegisteredOn,EmailForChange,LastLoginDate, FullName, Email, Username, ProfilePic, PhoneNumber,PhoneNumberForChange, Status,EmailStatus,PhoneStatus,WalletAmount,CashBonus,WinningAmount';
        $http.post(API_URL + 'admin/users/getReferredUsers', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data.Records) { /* success case */
                $scope.data.totalRecords = response.Data.TotalRecords;
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
});