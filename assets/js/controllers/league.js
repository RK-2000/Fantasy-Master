'use strict';
app.controller('leagueController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', 'Upload', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, Upload) {
        $scope.env = environment;
        /*
         Description : To get user team details on ground
         */

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            if (!localStorage.hasOwnProperty('league_back_url')) {
                localStorage.setItem('league_back_url', document.referrer);
            }
            $rootScope.pageSize = 15;
            $rootScope.pageNo = 1;
            $scope.user_details = $localStorage.user_details;
            $scope.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
            $scope.ContestGUID = getQueryStringValue('League'); //Contest GUID
            $scope.SelectedUserGUID = $scope.user_details.UserGUID;
            /*
             Description : To get user created team list
             */
            $rootScope.userTeamList = [];
            $scope.UsersTeamList = function () {
                var $data = {};

                $rootScope.userTeamList = []; //user team list array
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
                $data.Params = 'UserTeamName';
                $data.Keyword = $scope.Keyword;
                $data.UserTeamType = 'Normal';
                $data.UserGUID = $localStorage.user_details.UserGUID;
                appDB
                        .callPostForm('contest/getUserTeams', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        if (data.Data.hasOwnProperty('Records')) {
                                            for (var i in data.Data.Records) {
                                                $scope.data.dataList.push(data.Data.Records[i]);
                                                if ($rootScope.UserContestTeams.includes(data.Data.Records[i].UserTeamGUID)) {
                                                    data.Data.Records[i].checked = true;
                                                } else {
                                                    data.Data.Records[i].checked = false;
                                                }
                                                $rootScope.userTeamList.push(data.Data.Records[i]);
                                            }
                                        } else {
                                            $rootScope.userTeamList = [];
                                        }
                                        $scope.UserTeamsTotalCount = data.Data.TotalRecords;
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 501) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 502) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                function errorCallback(data) {

                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                });

            }


            /*To join Contest*/
            $scope.join = {};
            $scope.joinSubmitted = false;
            $rootScope.JoinContest = function (form) {
                $scope.joinSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                var $data = {};
                $data = $scope.join;
                $data.ContestGUID = getQueryStringValue('League');
                $data.MatchGUID = getQueryStringValue('MatchGUID');
                $data.SessionKey = $localStorage.user_details.SessionKey;

                appDB
                        .callPostForm('contest/join', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {

                                        var toast = toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            window.location.href = "league?MatchGUID=" + getQueryStringValue('MatchGUID') + "&League=" + getQueryStringValue('League');
                                        }, 200);
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 501) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 502) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                function errorCallback(data) {

                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                });
            }

            $scope.viewJoinButton = false;
            if (getQueryStringValue('Source') && getQueryStringValue('Source') == 'ViewLeague') {
                $scope.viewJoinButton = true;
            } else {
                $scope.viewJoinButton = false;
            }

            $scope.ViewTeamOnGround = function (data) {
                $scope.TeamPlayers = data;
                $scope.teamStructure = {
                    "WicketKeeper": {
                        "min": 1,
                        "max": 1,
                        "occupied": 0,
                        player: [],
                        "icon": "flaticon1-pair-of-gloves"
                    },
                    "Batsman": {
                        "min": 3,
                        "max": 5,
                        "occupied": 0,
                        player: [],
                        "icon": "flaticon1-ball"
                    },
                    "Bowler": {
                        "min": 3,
                        "max": 5,
                        "occupied": 0,
                        player: [],
                        "icon": "flaticon1-tennis-ball"
                    },
                    "AllRounder": {
                        "min": 1,
                        "max": 3,
                        "occupied": 0,
                        player: [],
                        "icon": "flaticon1-ball"
                    },
                    "Extra": {
                        "min": 3,
                        "max": 3,
                        occupied: 0,
                        player: []
                    },
                    "ready": false
                };
                angular.forEach($scope.TeamPlayers, function (value, key) {
                    if (value.PlayerRole == 'WicketKeeper') {

                        $scope.teamStructure['WicketKeeper'].player.push({
                            'PlayerGUID': value.PlayerGUID,
                            'PlayerPosition': value.PlayerPosition,
                            'PlayerName': value.PlayerName,
                            //'Points': value.Points,
                            'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PointCredits):parseFloat(value.Points),
                            'PlayerPic': value.PlayerPic,
                            'SelectedPlayerTeam': (value.TeamGUID == $scope.Contest.TeamGUIDLocal) ? 'A' : 'B'
                        });
                        $scope.teamStructure['WicketKeeper'].occupied++;
                    }
                    if (value.PlayerRole == 'Batsman') {
                        $scope.teamStructure['Batsman'].player.push({
                            'PlayerGUID': value.PlayerGUID,
                            'PlayerPosition': value.PlayerPosition,
                            'PlayerName': value.PlayerName,
                            //'Points': value.Points,
                            'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PointCredits):parseFloat(value.Points),
                            'PlayerPic': value.PlayerPic,
                            'SelectedPlayerTeam': (value.TeamGUID == $scope.Contest.TeamGUIDLocal) ? 'A' : 'B'
                        });
                        $scope.teamStructure['Batsman'].occupied++;
                    }
                    if (value.PlayerRole == 'AllRounder') {
                        $scope.teamStructure['AllRounder'].player.push({
                            'PlayerGUID': value.PlayerGUID,
                            'PlayerPosition': value.PlayerPosition,
                            'PlayerName': value.PlayerName,
                            'Points': value.Points,
                            'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PointCredits):parseFloat(value.Points),
                            'PlayerPic': value.PlayerPic,
                            'SelectedPlayerTeam': (value.TeamGUID == $scope.Contest.TeamGUIDLocal) ? 'A' : 'B'
                        });
                        $scope.teamStructure['AllRounder'].occupied++;
                    }
                    if (value.PlayerRole == 'Bowler') {
                        $scope.teamStructure['Bowler'].player.push({
                            'PlayerGUID': value.PlayerGUID,
                            'PlayerPosition': value.PlayerPosition,
                            'PlayerName': value.PlayerName,
                            'Points': value.Points,
                            'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PointCredits):parseFloat(value.Points),
                            'PlayerPic': value.PlayerPic,
                            'SelectedPlayerTeam': (value.TeamGUID == $scope.Contest.TeamGUIDLocal) ? 'A' : 'B'
                        });
                        $scope.teamStructure['Bowler'].occupied++;
                    }
                });

            }

            /*Funcion to get joined user teams*/
            $scope.userTeams = [];
            $scope.TeamPageNo = 1;
            $scope.TeamPageSize = 25;
            $rootScope.UserContestTeams = [];
            $scope.Nextdata = true;
            $scope.getUserTeam = function (status) {
                if (status) {
                    $scope.TeamPageNo = 1;
                    $scope.Contests = [];
                    $rootScope.UserContestTeams = [];
                    $scope.LoadMoreFlag = true;
                    $scope.data.noRecords = false;
                }
                if ($scope.LoadMoreFlag == false || $scope.data.noRecords == true || $scope.Nextdata == false) {
                    return false
                }
                if ($scope.Nextdata) {
                    $scope.Nextdata = false;

                    var $data = {};
                    $scope.TeamPlayers = []; //user team list array
                    $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                    $data.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
                    $data.ContestGUID = getQueryStringValue('League'); //Contest GUID
                    $data.Params = 'UserTeamName,TotalPoints,UserWinningAmount,FirstName,Username,UserGUID,UserTeamPlayers,UserTeamID,UserRank';
                    $data.OrderBy = 'UserRank';
                    $data.Sequence = 'ASC';
                    $data.PageNo = parseInt($scope.TeamPageNo);
                    $data.PageSize = parseInt($scope.TeamPageSize);
                    appDB
                            .callPostJSON('contest/getJoinedContestsUsers', $data)
                            .then(
                                    function successCallback(data) {
                                        $scope.Nextdata = true;
                                        if (data.ResponseCode == 200) {
                                            // $scope.userTeams = data.Data.Records; 
                                            $scope.data.totalRecords = data.Data.TotalRecords;
                                            if (data.Data.hasOwnProperty('Records') && data.Data.Records != '') {
                                                $scope.LoadMoreFlag = true;
                                                for (var i in data.Data.Records) {
                                                    data.Data.Records[i].TotalPoints = parseFloat(data.Data.Records[i].TotalPoints);
                                                    data.Data.Records[i].UserWinningAmount = parseFloat(data.Data.Records[i].UserWinningAmount);
                                                    $scope.userTeams.push(data.Data.Records[i]);
                                                    if (data.Data.Records[i].UserGUID == $scope.user_details.UserGUID) {
                                                        $rootScope.UserContestTeams.push(data.Data.Records[i].UserTeamGUID);
                                                    }
                                                }
                                                if ($scope.TeamPageNo == 1) {
                                                    angular.forEach($scope.userTeams, function (value, key) {
                                                        if ($scope.Contest.Status == 'Completed' && value.UserRank == 1) {
                                                            $scope.ViewTeamOnGround(value.UserTeamPlayers);
                                                        } else if ($scope.Contest.Status != 'Completed') {
                                                            if (key == 0) {
                                                                $scope.ViewTeamOnGround(value.UserTeamPlayers);
                                                            }
                                                        }
                                                    });
                                                }
                                                $scope.TeamPageNo++;
                                            } else {
                                                $scope.LoadMoreFlag = false;
                                            }
                                        } else {
                                            $scope.data.noRecords = true;
                                        }
                                        if (data.ResponseCode == 500) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            $scope.data.noRecords = true;
                                        }
                                        if (data.ResponseCode == 501) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            $scope.data.noRecords = true;
                                        }
                                        if (data.ResponseCode == 502) {
                                            var toast = toastr.warning(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            setTimeout(function () {
                                                localStorage.clear();
                                                window.location.reload();
                                            }, 1000);
                                        }
                                    },
                                    function errorCallback(data) {
                                        $scope.Nextdata = true;
                                        if (typeof data == 'object') {
                                            var toast = toastr.error(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                            $scope.data.noRecords = true;
                                        }
                                    });
                }
            }



            /*function to get contests according to matches*/
            $scope.Contest = [];
            $rootScope.Contest = [];
            $scope.getContests = function () {
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID'); // Selected MatchGUID
                $data.ContestGUID = getQueryStringValue('League'); // Selected ContestGUID
                $data.SessionKey = $localStorage.user_details.SessionKey; // User SessionKey
                $data.SessionKey = $localStorage.user_details.SessionKey; // User SessionKey
                $data.Params = 'Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,IsJoined,Status,ContestFormat,ContestType,CustomizeWinning,TotalJoined,UserInvitationCode,SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,SeriesGUID,Status,MatchScoreDetails,ShowJoinedContest,TeamGUIDVisitor,TeamGUIDLocal,MatchStartDateTimeUTC';
                $data.Keyword = $scope.Keyword;

                appDB
                        .callPostForm('contest/getContests', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.Contest = data.Data;
                                        $rootScope.Contest = data.Data;

                                        if ($scope.Contest.CustomizeWinning.length == 0) {
                                            $scope.Contest.CustomizeWinning.push({
                                                'From': 1,
                                                'To': $scope.Contest.NoOfWinners,
                                                'WinningAmount': $scope.Contest.WinningAmount,
                                                'percent': 100
                                            });
                                        }
                                        $scope.getUserTeam(true);

                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 501) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                    if (data.ResponseCode == 502) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                function errorCallback(data) {
                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                });
            }

            $scope.pointSystem = function () {
                $scope.points = [];
                var $data = {};
                $data.StatusID = 1;
                appDB
                        .callPostForm('sports/getPoints', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.points = data.Data.Records;

                                        for (var i = 0; i < $scope.points.length; i++) {
                                            $scope.points[i].Sort = parseInt($scope.points[i].Sort);
                                        }
                                        $rootScope.pointsArray = [];
                                        if ($scope.Contest.MatchType.includes('T20')) {
                                            angular.forEach($scope.points, function (value, key) {
                                                $rootScope.pointsArray.push({
                                                    'PointsTypeGUID': value.PointsTypeGUID,
                                                    'PointsTypeDescprition': value.PointsTypeDescprition,
                                                    'Points': parseFloat(value.PointsT20),
                                                    'PointsType': value.PointsType,
                                                    'PointsInningType': value.PointsInningType,
                                                    'Sort': parseInt(value.Sort)
                                                });
                                            });
                                        }
                                        if ($scope.Contest.MatchType.includes('ODI')) {
                                            angular.forEach($scope.points, function (value, key) {
                                                $rootScope.pointsArray.push({
                                                    'PointsTypeGUID': value.PointsTypeGUID,
                                                    'PointsTypeDescprition': value.PointsTypeDescprition,
                                                    'Points': parseFloat(value.PointsODI),
                                                    'PointsType': value.PointsType,
                                                    'PointsInningType': value.PointsInningType,
                                                    'Sort': parseInt(value.Sort)
                                                });
                                            });
                                        }
                                        if ($scope.Contest.MatchType.includes('Test')) {
                                            angular.forEach($scope.points, function (value, key) {
                                                $rootScope.pointsArray.push({
                                                    'PointsTypeGUID': value.PointsTypeGUID,
                                                    'PointsTypeDescprition': value.PointsTypeDescprition,
                                                    'Points': parseFloat(value.PointsTEST),
                                                    'PointsType': value.PointsType,
                                                    'PointsInningType': value.PointsInningType,
                                                    'Sort': parseInt(value.Sort)
                                                });
                                            });
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
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
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


            $scope.fantasyScoreCard = function (PlayerGUID) {
                $rootScope.fantasyScores = [];
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID');
                $data.SessionKey = $localStorage.user_details.SessionKey;
                if (PlayerGUID !== '') {
                    $data.PlayerGUID = PlayerGUID
                } else {
                    $data.IsPlaying = 'Yes';
                }
                $data.Params = 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,TotalPoints';
                appDB
                        .callPostForm('sports/getPlayers', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $rootScope.fantasyScores = data.Data;
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
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
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


            // setInterval(function () {
            //     if ($rootScope.Contest.Status == 'Running') {
            //         window.location.reload();
            //     }
            // }, 120000);

            $scope.switchTeamPopup = function () {
                $scope.UsersTeamList();
                $scope.openPopup('switchTeamModal');
            }
            $rootScope.switchTeamsButton = false;
            $rootScope.selectSwitchTeam = function (UserTeamGUID) {
                var total_switch_teams = $rootScope.UserContestTeams.length;
                var count = 0;
                for (var i in $rootScope.userTeamList) {
                    if ($rootScope.userTeamList[i].checked) {
                        count++;
                    }
                }
                if (total_switch_teams == count) {
                    $rootScope.switchTeamsButton = false;
                } else if (total_switch_teams < count) {
                    for (var i in $rootScope.userTeamList) {
                        if ($rootScope.userTeamList[i].UserTeamGUID == UserTeamGUID) {
                            $rootScope.userTeamList[i].checked = false;
                        }
                    }
                    $rootScope.switchTeamsButton = false;
                    var toast = toastr.warning('You can only select ' + total_switch_teams + ' team for switch.', {
                        closeButton: true
                    });
                    toastr.refreshTimer(toast, 5000);
                } else {
                    $rootScope.switchTeamsButton = true;
                }
            }

            $rootScope.switchTeam = function () {
                var $data = {};
                var changedTeamGUID = [];
                for (var i in $rootScope.userTeamList) {
                    if ($rootScope.userTeamList[i].checked) {
                        changedTeamGUID.push($rootScope.userTeamList[i].UserTeamGUID);
                    }
                }
                $data.UserTeamGUID = changedTeamGUID[0];
                $data.OldUserTeamGUID = $rootScope.UserContestTeams[0];
                $data.ContestGUID = $scope.ContestGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;

                appDB
                        .callPostForm('contest/switchUserTeam', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.closePopup('switchTeamModal');
                                        window.location.reload();
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
                                        setTimeout(function () {
                                            localStorage.clear();
                                            window.location.reload();
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

            $scope.getTeamName = function (str) {
                return str.substr(6, 1);
            }

            /**
             * Sort by Leaderboard 
             */
            $scope.propertyName = '';
            $scope.reverse = false;
            $scope.sortBy = function (propertyName) {
                $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
                $scope.propertyName = propertyName;
            };

            $scope.Back = function () {
                window.location.href = localStorage.getItem('league_back_url');
                localStorage.removeItem('league_back_url');
            }

            $scope.showWinningPayout = function (Winnings) {
                $scope.CustomizeWinning = Winnings;
                $scope.openPopup('PayoutBreakUp');
            }

            /**
             * Show player fanstay point
             */

            $scope.showPlayerFanstayPoint = function (PlayerGUID) {
                $scope.fantasyScoreCard(PlayerGUID);
                $scope.pointSystem();
                $scope.openPopup('fantasyScorePopup');
            }

        } else {
            window.location.href = base_url;
        }
    }]);