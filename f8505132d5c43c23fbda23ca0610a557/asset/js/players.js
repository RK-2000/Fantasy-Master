app.controller('PageController', function ($scope, $http, $timeout) {
    $scope.data.pageSize = 15;
    /*----------------*/
    $scope.getFilterData = function () {
        var SeriesGUID = getQueryStringValue('SeriesGUID');
        var data = 'SessionKey=' + SessionKey + '&SeriesGUID=' + SeriesGUID + '&Params=TeamName,TeamGUID&' + $('#filterPanel form').serialize();
        $http.post(API_URL + 'admin/matches/getTeamData', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) {
                /* success case */
                $scope.filterData = response.Data;
                $timeout(function () {
                    $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8 }).trigger("chosen:updated");
                }, 300);
            }
        });
    }

    /*list*/
    $scope.applyFilter = function () {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    $scope.getMatchDetail = function () {
        $scope.matchDetail = {};
        if (getQueryStringValue('MatchGUID')) {
            var MatchGUID = getQueryStringValue('MatchGUID');
            $scope.AllMatches = false;
        } else {
            var MatchGUID = '';
            $scope.AllMatches = true;
        }
        $http.post(API_URL + 'admin/matches/getMatches', 'MatchGUID=' + MatchGUID + '&Params=SeriesName,Status,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation&SessionKey=' + SessionKey, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.matchDetail = response.Data.Records[0]
            }
        });
    }
    $scope.SGUID = getQueryStringValue('SeriesGUID');
    /*list append*/
    $scope.getList = function () {
        if ($scope.data.listLoading || $scope.data.noRecords)
            return;
        $scope.data.listLoading = true;
        if (getQueryStringValue('MatchGUID')) {
            var SeriesGUID = '';
            var MatchGUID = getQueryStringValue('MatchGUID');
            var PlayerRole = 'TeamName,PlayerRole,PlayerSalary,PlayerSalaryCredit';
            $scope.getMatchDetail();
        } else {
            var MatchGUID = '';
            var SeriesGUID = getQueryStringValue('SeriesGUID');
            var PlayerRole = 'TeamName,PlayerSalary,PlayerRole,PlayerSalaryCredit';
        }
        var data = 'SessionKey=' + SessionKey + '&SeriesGUID=' + SeriesGUID + '&MatchGUID=' + MatchGUID + '&Params=' + PlayerRole + ',PlayerPic,&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&' + $('#filterForm').serialize() + '&' + $('#filterForm1').serialize();
        $http.post(API_URL + 'sports/getPlayers', data, contentType).then(function (response) {
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

    /*load add form*/
    $scope.loadFormAdd = function (Position, CategoryGUID) {
        $scope.templateURLAdd = PATH_TEMPLATE + module + '/add_form.htm?' + Math.random();
        $('#add_model').modal({ show: true });
        $timeout(function () {
            $(".chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
        }, 200);
    }


    /*load edit form*/
    $scope.loadFormEdit = function (Position, PlayerGUID) {
        if (getQueryStringValue('MatchGUID')) {
            var MatchGUID = getQueryStringValue('MatchGUID');
            $scope.AllMatches = false;
        } else {
            var MatchGUID = '';
            $scope.AllMatches = true;
        }
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/edit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'sports/getPlayers', 'PlayerGUID=' + PlayerGUID + '&MatchGUID=' + MatchGUID + '&Params=PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerSalary&SessionKey=' + SessionKey, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            console.log(response);
            if (response.ResponseCode == 200) {
                /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#edit_model').modal({ show: true });
                $timeout(function () {
                    $(".chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*load delete form*/
    $scope.loadFormDelete = function (Position, CategoryGUID) {
        $scope.data.Position = Position;
        $scope.templateURLDelete = PATH_TEMPLATE + module + '/delete_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'category/getCategory', 'SessionKey=' + SessionKey + '&CategoryGUID=' + CategoryGUID, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#delete_model').modal({ show: true });
                $timeout(function () {
                    $(".chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*edit data*/
    $scope.editData = function () {
        if (getQueryStringValue('MatchGUID')) {
            var SeriesGUID = '';
            var MatchGUID = getQueryStringValue('MatchGUID');
        } else {
            var MatchGUID = '';
            var SeriesGUID = getQueryStringValue('SeriesGUID');
        }
        $scope.editDataLoading = true;
        var data = 'SessionKey=' + SessionKey + '&SeriesGUID=' + getQueryStringValue('SeriesGUID') + '&MatchGUID=' + getQueryStringValue('MatchGUID') + '&' + $("form[name='edit_form']").serialize();
        $http.post(API_URL + 'admin/matches/updatePlayerInfo', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
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

    /*edit data*/
    $scope.updatePlayerSalary = function (PlayerGUID, MatchGUID, key) {

        $scope.editDataLoading = true;
        var concatString = '';
        if (key.hasOwnProperty('T20Credits')) {
            concatString += '&T20Credits=' + key.T20Credits;
        }
        if (key.hasOwnProperty('T20iCredits')) {
            concatString += '&T20iCredits=' + key.T20iCredits;
        }
        if (key.hasOwnProperty('ODICredits')) {
            concatString += '&ODICredits=' + key.ODICredits;
        }
        if (key.hasOwnProperty('TestCredits')) {
            concatString += '&TestCredits=' + key.TestCredits;
        }
        if (key.hasOwnProperty('PlayerSalaryCredit')) {
            concatString += '&PlayerSalaryCredit=' + key.PlayerSalaryCredit;
        }

        var data = 'SessionKey=' + SessionKey + '&PlayerGUID=' + PlayerGUID + '&MatchGUID=' + MatchGUID + concatString;
        $http.post(API_URL + 'admin/matches/updatePlayerSalary', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
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

    /*Download Salary CSV Sample*/
    $scope.downloadSalarySample = function () {
        var data = 'SessionKey=' + SessionKey + '&MatchGUID=' + getQueryStringValue('MatchGUID') + '&Params=PlayerID,PlayerRole,PlayerSalary,TeamName,PlayerName';
        $http.post(API_URL + 'admin/matches/downloadPlayerSalarySample', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                var encodedUri = encodeURI(API_URL + response.Data);
                var link = document.createElement("a");
                link.href = encodedUri;
                link.style = "visibility:hidden";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $timeout(function () {
                    $http.post(API_URL + 'admin/matches/deletePlayerSalaryCSV', 'SessionKey=' + SessionKey + '&CSVFile='+response.Data, contentType).then(function (response) {
                        console.log('response',response);
                    });
                }, 5000); // After 5 seconds
            } else {
                alertify.error(response.Message);
            }
        });
    }

    /*import Player Salary CSV*/
    $scope.importPlayerSalary = function () {

        var csv = $('#csv_file');
        var csvFile = csv[0].files[0];
        var ext = csv.val().split(".").pop().toLowerCase();
        if ($.inArray(ext, ["csv"]) === -1) {
            return false;
        }
        if (csvFile != undefined) {
            reader = new FileReader();
            reader.onload = function (e) {
                csvResult = e.target.result.split(/\r|\n|\r\n/);
                $('.csv').append(csvResult);
            }
            reader.readAsText(csvFile);
        }
        var formData = new FormData();
        formData.append("CSVFile", csvFile);
        formData.append("SessionKey", SessionKey);
        formData.append("MatchGUID", getQueryStringValue('MatchGUID'));
        $.ajax({
            url: API_URL + 'admin/matches/importPlayerSalary',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                alertify.success(response.Message);
                $('.modal-header .close').click();
                location.reload();
            },
            error:function(error){
                console.log('error',error);
                alertify.error('Error occured !');
            }
        });
    }


});
/* csv file validation */
function validateFile(input, ext) {
    var file_name = input.value;
    var split_extension = file_name.split(".").pop();
    var extArr = ext.split("|");
    if ($.inArray(split_extension.toLowerCase(), extArr) == -1) {
        $(input).val("");
        alertify.error('You Can Upload Only .' + extArr.join(", ") + ' file !');
        return false;
    }
}