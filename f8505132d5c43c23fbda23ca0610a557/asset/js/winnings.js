app.controller('PageController', function ($scope, $http,$timeout){
    $scope.data.pageSize = 100;
    $scope.data.ParentCategoryGUID = ParentCategoryGUID;
    $scope.data.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    
    /*----------------*/
    $scope.getFilterData = function ()
    {

        var data = 'SessionKey='+SessionKey+'&Params=SeriesName,SeriesGUID&StatusID=2&'+$('#filterPanel form').serialize();

        $http.post(API_URL+'admin/matches/getFilterData', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data){ 
                /* success case */
             $scope.filterData =  response.Data;
             $timeout(function(){
                $("select.chosen-select").chosen({ width: '100%',"disable_search_threshold": 8}).trigger("chosen:updated");
            }, 300);          
         } 
     });
    }

    /*list*/
    $scope.applyFilter = function ()
    {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /*list append*/
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize+'&Status=Completed&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,TeamNameLocal,TeamNameVisitor,Status,CustomizeWinning,ContestType,MatchStartDateTime,TotalJoined&Privacy=All&OrderByToday=Yes&' + $('#filterForm').serialize() + '&' + $('#filterForm1').serialize();
        $http.post(API_URL+'contest/getContests', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data.Records){ /* success case */
                $scope.data.totalRecords = response.Data.TotalRecords;
                for (var i in response.Data.Records) {
                    $scope.data.dataList.push(response.Data.Records[i]);
                }
                $scope.data.pageNo++;               
            }else{
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
            
        });
    }


    $scope.loadFormList = function (Position, ContestGUID)
    {
                
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE+module+'/list_form.htm?'+Math.random();
        
        $scope.data.pageLoading = false;
        $http.post(API_URL+'admin/contest/getContestWinningUsers', 'SessionKey='+SessionKey+'&ContestGUID='+ContestGUID+'&Params=UserWinningAmount,TotalPoints,EntryFee,ContestSize,NoOfWinners,UserTeamName,FullName,UserRank', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data.Records){ 
                $scope.formData = response.Data;
                $scope.data.pageNo++;               
            }else{
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
        });
        if($scope.formData){
            $('#list_model').modal({show:true});
        }
                

        $timeout(function(){            
           
           $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
       }, 200);
    }

     /*To get matches according to Series*/
     $scope.getMatches = function (SeriesGUID,Status = '') {
        if (!SeriesGUID) {
            $scope.MatchData = [];
            $timeout(function () {
                $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8 }).trigger("chosen:updated");
            }, 300);
            return;
        }
        $scope.MatchData = {};
        var data = 'SeriesGUID=' + SeriesGUID + '&Params=MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor&OrderBy=MatchStartDateTime&Sequence=ASC&Status='+Status;
        $http.post(API_URL + 'sports/getMatches', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) { /* success case */
                $scope.MatchData = response.Data.Records;
                $timeout(function () {
                    $("select.chosen-select").chosen({width: '100%', "disable_search_threshold": 8}).trigger("chosen:updated");
                }, 300);
            }
        });
    }

    $scope.loadContestJoinedUser = function (Position, ContestGUID) {

        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/joinedContestWinning_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getContests', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,TeamNameLocal,TeamNameShortLocal,TeamFlagLocal,TeamNameVisitor,TeamNameShortVisitor,TeamFlagVisitor,SeriesName,CustomizeWinning,ContestType,CashBonusContribution,UserJoinLimit,ContestFormat,IsConfirm,ShowJoinedContest,TotalJoined,AdminPercent,UnfilledWinningPercent,TotalAmountReceived,TotalWinningAmount,Status,MatchStartDateTime,CreatedType', contentType).then(function (response) {
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
        //$('.table').removeProperty('min-height');
    }

}); 

/* sortable - ends */