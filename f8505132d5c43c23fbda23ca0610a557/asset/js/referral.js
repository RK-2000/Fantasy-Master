app.controller('PageController', function ($scope, $http, $timeout) {

    $scope.getUserInfo = function(){
        $scope.userData = {};
        var UserGUID = getQueryStringValue('UserGUID');
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK,Email', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.userData = response.Data;
            }
        });
    }
    /*list append*/
    $scope.getList = function () {
        $scope.getUserInfo();
       if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        if (getQueryStringValue('UserGUID')) {
            var UserGUID = getQueryStringValue('UserGUID');
        } else {
            var UserGUID = '';
        }
        var data = 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=RegisteredOn,LastLoginDate, FullName, Email, Username, ProfilePic, Gender, BirthDate, PhoneNumber, Status, ReferredCount,Tokens,EmailStatus,Points&OrderBy=FirstName&Sequence=ASC&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize;
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
            // setTimeout(function(){ tblsort(); }, 1000);
        });
    }
});