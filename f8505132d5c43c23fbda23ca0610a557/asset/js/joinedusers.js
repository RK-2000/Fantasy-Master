app.controller('PageController', function ($scope, $http, $timeout) {
    $scope.data.pageSize = 15;
    $scope.data.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    /*list append*/
    $scope.getList = function () {
        $scope.getContestDetail();
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey + '&ContestGUID=' + getQueryStringValue('ContestGUID') + '&Params=FullName,ProfilePic,UserRank,TotalPoints,UserWinningAmount,UserTeamPlayers,UserTeamName&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=' + $scope.data.OrderBy + '&Sequence=' + $scope.data.Sequence;
        $http.post(API_URL + 'admin/contest/getJoinedContestsUsers', data, contentType).then(function (response) {
            var response = response.data;
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

    $scope.getContestDetail = function () {
        $scope.seriesDetail = {};
        if (getQueryStringValue('ContestGUID')) {
            var ContestGUID = getQueryStringValue('ContestGUID');
            $scope.AllContests = false;
        } else {
            var ContestGUID = '';
            $scope.AllContests = true;
        }
        $http.post(API_URL + 'contest/getContests', 'ContestGUID=' + ContestGUID + '&Params=SeriesName,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,MatchStartDateTime,Status&SessionKey=' + SessionKey, contentType).then(function (response) {
            var response = response.data;
            if (response.ResponseCode == 200) { /* success case */
                $scope.contestDetail = response.Data
            }
        });
    }

    $scope.loadTeams = function (row) {
        $scope.playerData = row.UserTeamPlayers;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/getTeams_form.htm?' + Math.random();
        $('#viewTeams_model').modal({ show: true });
    }
}); 