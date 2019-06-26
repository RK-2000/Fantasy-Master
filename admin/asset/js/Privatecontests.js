app.controller('PageController', function ($scope, $http,$timeout){
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
        var data = 'SessionKey='+SessionKey+'&Privacy=Yes&UserGUID='+getQueryStringValue('UserGUID')+'&Params=GameType,AdminPercent,TotalJoined,MatchStartDateTime,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,Status,TeamNameLocal,TeamNameVisitor,CustomizeWinning&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=EntryDate&Sequence=DESC&' + $('#filterForm1').serialize() +'&' + $('#filterForm').serialize();
        $http.post(API_URL+'admin/contest/getPrivateContest', data, contentType).then(function(response) {
        var response = response.data;
        console.log(response);
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

    $scope.loadContestJoinedUser = function (Position, ContestGUID)
    {

        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + 'contests/joinedContest_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getJoinedContestsUsers', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=UserTeamName,TotalPoints,UserWinningAmount,Email,PhoneNumber,FirstName,Username,UserGUID,UserTeamPlayers,UserTeamID,UserRank', contentType).then(function (response) {
            var response = response.data;
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data;
                console.log($scope.contestData)
                $('#contestJoinedUsers_model').modal({show: true});

                $timeout(function () {

                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        }); 

        $http.post(API_URL + 'contest/getContest', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,SeriesID,MatchID,SeriesGUID,TeamNameLocal,TeamNameVisitor,SeriesName,CustomizeWinning,ContestType,CashBonusContribution,UserJoinLimit,ContestFormat,IsConfirm,ShowJoinedContest,TotalJoined', contentType).then(function (response) {
            var response = response.data;
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.contestData = response.Data;
                console.log($scope.contestData)
                $('#contestJoinedUsers_model').modal({show: true});

                $timeout(function () {

                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
        $('.table').removeProperty('min-height');
    }

    $scope.loadFormStatus = function (Position, ContestGUID)
    {

        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + 'contests/updateStatus_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getContest', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=ContestName,ContestType,Status,StatusID', contentType).then(function (response) {
            var response = response.data;
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data

                $('#status_model').modal({show: true});
                $timeout(function () {
                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*To get matches according to Series*/
    $scope.getMatches = function(SeriesGUID,Status){
        $scope.MatchData = {};
        //&StatusID=1
        var data  = 'SeriesGUID='+SeriesGUID+'&Params=MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor&OrderBy=MatchStartDateTime&Sequence=ASC&Status='+Status;
        $http.post(API_URL+'sports/getMatches', data, contentType).then(function(response) {
            var response = response.data;
            if(response.ResponseCode == 200 && response.Data){ /* success case */
             $scope.MatchData =  response.Data.Records;
             $timeout(function(){
                $('.matchSelect2').select2();
                $("select.chosen-select").chosen({ width: '100%',"disable_search_threshold": 8}).trigger("chosen:updated");
            }, 400);
         }
        });
    }

    /*edit status*/
    $scope.editStatus = function (Status, contestGUID)
    {

        if (Status == 'Cancelled') {
            var req = 'SessionKey=' + SessionKey + '&ContestGUID=' + contestGUID;
            $http.post(API_URL + 'admin/contest/cancel', req, contentType).then(function (response) {
                var response = response.data;
                if (response.ResponseCode == 200) { /* success case */
                    $scope.editDataLoading = true;
                    var data = 'SessionKey=' + SessionKey + '&' + $("form[name='update_form']").serialize();
                    $http.post(API_URL + 'admin/contest/changeStatus', data, contentType).then(function (response) {
                        var response = response.data;
                        if (response.ResponseCode == 200) { /* success case */
                            alertify.success(response.Message);
                            $scope.data.dataList[$scope.data.Position] = response.Data;
                            $('.modal-header .close').click();
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);

                        } else {
                            alertify.error(response.Message);
                        }
                        $scope.editDataLoading = false;
                    });
                }
            });
        } else {
            $scope.editDataLoading = true;
            var data = 'SessionKey=' + SessionKey + '&ContestGUID=' + contestGUID + '&Status=' + Status;
            $http.post(API_URL + 'admin/contest/changeStatus', data, contentType).then(function (response) {
                var response = response.data;
                if (response.ResponseCode == 200) { /* success case */
                    alertify.success(response.Message);
                    $scope.data.dataList[$scope.data.Position] = response.Data;
                    $('.modal-header .close').click();
                } else {
                    alertify.error(response.Message);
                }
                $scope.editDataLoading = false;
            });
        }
    }

    //export private contest list
    $scope.exportPrivateContests = function () { 
        if ($scope.data.dataList.length > 0) {
            var varArr = [];
            for (var i = 0; i < $scope.data.dataList.length; i++) {
                var row = {};
                row.GameType = $scope.data.dataList[i]['GameType'];
                row.ContestName = $scope.data.dataList[i]['ContestName'];
                row.IsPaid = $scope.data.dataList[i]['IsPaid'];
                row.ContestSize = $scope.data.dataList[i]['ContestSize'];
                row.Privacy = $scope.data.dataList[i]['Privacy'];
                row.AdminPercent = $scope.data.dataList[i]['AdminPercent'];
                row.EntryFee = $scope.data.dataList[i]['EntryFee'];
                row.EntryType = $scope.data.dataList[i]['EntryType'];
                row.NoOfWinners = $scope.data.dataList[i]['NoOfWinners'];
                row.WinningAmount = $scope.data.dataList[i]['WinningAmount'];
                row.MatchStartDateTime = $scope.data.dataList[i]['MatchStartDateTime'];
                row.TotalJoined = $scope.data.dataList[i]['TotalJoined'];
                row.TotalAmountReceived = $scope.data.dataList[i]['TotalAmountReceived'];
                row.TotalWinningAmount = $scope.data.dataList[i]['TotalWinningAmount'];
                row.Status = $scope.data.dataList[i]['Status'];
                varArr.push(row);
            }
            $scope.JSONToCSVConvertor(varArr, 'export-privatcontest-list', true);
        }
        else {
            alertify.error('Private Contest Not Found');
        }
    }

                /* To generate CSV File */
                $scope.JSONToCSVConvertor = function (JSONData, ReportTitle, ShowLabel) {
                    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
                    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
                    var CSV = '';
                    if (ShowLabel) {
                        var row = "";
            
                        //This loop will extract the label from 1st index of on array
                        for (var index in arrData[0]) {
            
                            //Now convert each value to string and comma-seprated
                            let indexStr = index.replace(/([A-Z]+)*([A-Z][a-z])/g, "$1 $2");
                            indexStr = (!!indexStr) ? indexStr.charAt(0).toUpperCase() + indexStr.substr(1).toLowerCase() : '';
                            row += indexStr + ',';
                        }
            
                        row = row.slice(0, -1);
            
                        //append Label row with line break
                        CSV += row + '\r\n';
                    }
            
                    //1st loop is to extract each row
                    for (var i = 0; i < arrData.length; i++) {
                        var row = "";
            
                        //2nd loop will extract each column and convert it in string comma-seprated
                        for (var index in arrData[i]) {
                            row += '"' + arrData[i][index] + '",';
                        }
            
                        row.slice(0, row.length - 1);
            
                        //add a line break after each row
                        CSV += row + '\r\n';
                    }
            
                    if (CSV == '') {
                        alert("Invalid data");
                        return;
                    }
            
                    //Generate a file name
                    //this will remove the blank-spaces from the title and replace it with an underscore
                    var fileName = ReportTitle.replace(/ /g, "-");
            
                    //Initialize file format you want csv or xls
                    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
            
                    //this trick will generate a temp <a /> tag
                    var link = document.createElement("a");
                    link.href = uri;
            
                    //set the visibility hidden so it will not effect on your web-layout
                    link.style = "visibility:hidden";
                    link.download = fileName.toLowerCase() + ".csv";
            
                    //this part will append the anchor tag and remove it after automatic click
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }



}); 
