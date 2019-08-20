app.controller('PageController', function ($scope, $http,$timeout){
    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    $scope.data.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    $scope.data.pageSize = 15;
    /*----------------*/
    /*list*/
    $scope.getFilterData = function ()
    {
        var data = 'SessionKey='+SessionKey+'&Params=SeriesName,SeriesGUID&StatusID=2&'+$('#filterPanel form').serialize();

        $http.post(API_URL+'admin/matches/getFilterData', data, contentType).then(function(response) {
            var response = response.data;
            if(response.ResponseCode==200 && response.Data){ 
            /* success case */
                $scope.filterData =  response.Data;
                $timeout(function(){
                    $("select.chosen-select").chosen({ width: '100%',"disable_search_threshold": 8}).trigger("chosen:updated");
                }, 300);          
            }
        });
    }

    $scope.applyFilter = function() {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    $scope.getUserInfo = function(){
        $scope.userData = {};
        var UserGUID = getQueryStringValue('UserGUID');
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.userData = response.Data;
            }
        });
    }

    /*list append*/
    $scope.getList = function ()
    {
        $scope.getUserInfo();
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&UserGUID='+getQueryStringValue('UserGUID')+'&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,Status,TeamNameLocal,UserTeamPlayers,TeamNameVisitor&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=' + $scope.data.OrderBy + '&Sequence=' + $scope.data.Sequence + '&' + $('#filterForm').serialize();
        $http.post(API_URL+'admin/contest/getUserJoinedContests', data, contentType).then(function(response) {
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

    $scope.loadContestJoinedUser = function (Position, ContestGUID) {

        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + 'contests/joinedContest_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getContests', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,TeamNameLocal,TeamNameShortLocal,TeamFlagLocal,TeamNameVisitor,TeamNameShortVisitor,TeamFlagVisitor,SeriesName,CustomizeWinning,ContestType,CashBonusContribution,UserJoinLimit,ContestFormat,IsConfirm,ShowJoinedContest,TotalJoined,AdminPercent,UnfilledWinningPercent,TotalAmountReceived,TotalWinningAmount,Status,MatchStartDateTime', contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.contestData = response.Data;
                $('#contestJoinedUsers_model').modal({ show: true });
                $timeout(function () {
                    $(".chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*load winners form*/
    $scope.loadWinnersForm = function(Position, ContestGUID) {
        console.log(ContestGUID);
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + 'user/winners_list_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getContests', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=CustomWinnings', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#edit_model').modal({
                    show: true
                });
                $timeout(function() {
                    $(".chosen-select").chosen({
                        width: '100%',
                        "disable_search_threshold": 8,
                        "placeholder_text_multiple": "Please Select",
                    }).trigger("chosen:updated");
                }, 200);
            }
        });

    }

    /*load participated teams form*/
    $scope.loadParticipatedTeamForm = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + 'user/participated_team_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#edit_model').modal({
                    show: true
                });
                $timeout(function() {
                    $(".chosen-select").chosen({
                        width: '100%',
                        "disable_search_threshold": 8,
                        "placeholder_text_multiple": "Please Select",
                    }).trigger("chosen:updated");
                }, 200);
            }
        });

    }

    $scope.loadTeams = function (row) {
        $scope.playerData = row.UserTeamPlayers;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/getTeams_form.htm?' + Math.random();
        $('#viewTeams_model').modal({ show: true });
    }


}); 
