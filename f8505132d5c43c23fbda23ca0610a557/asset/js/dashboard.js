app.controller('PageController', function ($scope, $http,$timeout){
    $scope.data.pageSize = 15;
    $scope.data.pageNo = 1;
    /*----------------*/

    $scope.applyFilter = function ()
    {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
    }

    /*list append*/
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey;
        $http.post(API_URL+'utilities/dashboardStatics', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */
                $scope.data.dataList = response.Data;
             $scope.data.pageNo++;               

         }else{
            $scope.data.noRecords = true;
        }
        $scope.data.listLoading = false;
        // setTimeout(function(){ tblsort(); }, 1000);
        $scope.getMatchesList();
    });
    }

    /*match list append*/
    $scope.getMatchesList = function ()
    {
        $scope.matches = [];
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        /*  */
        var data = 'SessionKey='+SessionKey+'&OrderBy=MatchStartDateTime&Sequence=ASC&existingContests=2&Params=SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status&PageNo=1&PageSize=5&Status=Running';
        $http.post(API_URL+'sports/getMatches', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */
                $scope.matches = response.Data;
             $scope.data.pageNo++;               
             }else{
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
            // setTimeout(function(){ tblsort(); }, 1000);
        });
    }

    $scope.usersList = function()
    {
    }

    $scope.LoadDepositsList = function(Type)
    {
        window.open(BASE_URL + 'depositHistory?Type='+Type);
    }

    $scope.LoadUserList = function(Type)
    {
        if(Type == 'Today'){
            window.open(BASE_URL + 'user?Type=Today');
        }else{
            window.open(BASE_URL + 'user');
        }
    }

    $scope.withdrawalsList = function()
    {
        window.open(BASE_URL + 'withdrawals');
    }



}); 
