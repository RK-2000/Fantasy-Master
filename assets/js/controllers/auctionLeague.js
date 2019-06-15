'use strict';
app.controller('auctionLeagueController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', 'Upload', '$window', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, Upload, $window) {
        $scope.env = environment;
        /*
         Description : To get user team details on ground
         */

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            
            $rootScope.pageSize = 10;
            $rootScope.pageNo = 1;
            $scope.user_details = $localStorage.user_details;
            $scope.SeriesGUID = getQueryStringValue('SeriesGUID'); //Series GUID
            $scope.ContestGUID = getQueryStringValue('League'); //Contest GUID
            $scope.SelectedUserGUID = $scope.user_details.UserGUID;
            
            $scope.ViewTeamOnGround = function (UserTeamGUID, UserGUID) {
                $scope.BestTeam = false;
                $scope.SelectedUserGUID = UserGUID;
                $scope.SelectedUserTeamGUID = UserTeamGUID;
                $scope.teamStructure = {};
                var $data = {};
                $data.UserGUID = UserGUID;
                $scope.TeamPlayers = []; //user team list array
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.UserTeamGUID = UserTeamGUID; //User Team GUID
                $data.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
                $data.UserTeamType = 'Normal';
                $data.Params = "PlayerGUID,PlayerName,PlayerCountry,PlayerPosition,PlayerRole,UserTeamPlayers,UserRank,UserGUID";
                appDB
                        .callPostForm('contest/getUserTeams', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.TeamPlayers = data.Data;
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
                                        angular.forEach($scope.TeamPlayers.UserTeamPlayers, function (value, key) {
                                            if (value.PlayerRole == 'WicketKeeper') {

                                                $scope.teamStructure['WicketKeeper'].player.push({
                                                    'PlayerGUID': value.PlayerGUID,
                                                    'PlayerPosition': value.PlayerPosition,
                                                    'PlayerName': value.PlayerName,
                                                    'Points': value.PointCredits,
                                                    // 'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PlayerSalaryCredit):parseFloat(value.Points),
                                                    'PlayerPic': value.PlayerPic
                                                });
                                                $scope.teamStructure['WicketKeeper'].occupied++;
                                            }
                                            if (value.PlayerRole == 'Batsman') {
                                                $scope.teamStructure['Batsman'].player.push({
                                                    'PlayerGUID': value.PlayerGUID,
                                                    'PlayerPosition': value.PlayerPosition,
                                                    'PlayerName': value.PlayerName,
                                                    'Points': value.PointCredits,
                                                    // 'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PlayerSalaryCredit):parseFloat(value.Points),
                                                    'PlayerPic': value.PlayerPic
                                                });
                                                $scope.teamStructure['Batsman'].occupied++;
                                            }
                                            if (value.PlayerRole == 'AllRounder') {
                                                $scope.teamStructure['AllRounder'].player.push({
                                                    'PlayerGUID': value.PlayerGUID,
                                                    'PlayerPosition': value.PlayerPosition,
                                                    'PlayerName': value.PlayerName,
                                                    'Points': value.PointCredits,
                                                    // 'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PlayerSalaryCredit):parseFloat(value.Points),
                                                    'PlayerPic': value.PlayerPic
                                                });
                                                $scope.teamStructure['AllRounder'].occupied++;
                                            }
                                            if (value.PlayerRole == 'Bowler') {
                                                $scope.teamStructure['Bowler'].player.push({
                                                    'PlayerGUID': value.PlayerGUID,
                                                    'PlayerPosition': value.PlayerPosition,
                                                    'PlayerName': value.PlayerName,
                                                    'Points': value.PointCredits,
                                                    // 'Points': ($scope.Contest.Status == 'Pending')?parseFloat(value.PlayerSalaryCredit):parseFloat(value.Points),
                                                    'PlayerPic': value.PlayerPic
                                                });
                                                $scope.teamStructure['Bowler'].occupied++;
                                            }
                                        });
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }

            /*Funcion to get joined user teams*/
            $scope.userTeams = [];
            $scope.TeamPageNo = 1;
            $scope.TeamPageSize = 15;
            $rootScope.UserContestTeams = [];
            $scope.getUserTeam = function () {
                var $data = {};
                $scope.TeamPlayers = []; //user team list array
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.SeriesGUID; //Series GUID
                $data.ContestGUID = $scope.ContestGUID; //Contest GUID
                $data.Params = 'UserTeamName,TotalPoints,UserWinningAmount,FirstName,Username,UserGUID,UserTeamPlayers,UserTeamID,UserRank';
                $data.OrderBy = 'UserRank';
                $data.Sequence = 'ASC';
                $data.PageNo = $scope.TeamPageNo;
                $data.PageSize = $scope.TeamPageSize;
                appDB
                        .callPostForm('auctionDrafts/getJoinedContestsUsers', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        // $scope.userTeams = data.Data.Records; 
                                        $scope.data.totalRecords = data.Data.TotalRecords;
                                        if (data.Data.hasOwnProperty('Records')) {
                                            for (var i in data.Data.Records) {
                                                data.Data.Records[i].TotalPoints = parseFloat(data.Data.Records[i].TotalPoints);
                                                data.Data.Records[i].UserWinningAmount = parseFloat(data.Data.Records[i].UserWinningAmount);
                                                $scope.userTeams.push(data.Data.Records[i]);
                                                if (data.Data.Records[i].UserGUID == $scope.user_details.UserGUID) {
                                                    $rootScope.UserContestTeams.push(data.Data.Records[i].UserTeamGUID);
                                                }
                                            }
                                            
                                                // angular.forEach($scope.userTeams, function (value, key) {
                                                //     if ($scope.Contest.Status == 'Completed' && value.UserRank == 1) {
                                                //         $scope.ViewTeamOnGround(value.UserTeamGUID, value.UserGUID);
                                                //     } else if (value.UserGUID == $localStorage.user_details.UserGUID && $scope.Contest.Status != 'Completed') {
                                                //         $scope.ViewTeamOnGround(value.UserTeamGUID, $localStorage.user_details.UserGUID);
                                                //     }
                                                // });
                                        } 
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }

            /*function to get contests according to matches*/
            $scope.Contest = [];
            $scope.getContest = function () {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.SeriesGUID; //Series GUID
                $data.ContestGUID = $scope.ContestGUID; //Contest GUID
                $data.Params = 'SeriesName,LeagueJoinDateTime,GameType,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,IsJoined,Status,ContestFormat,ContestType,CustomizeWinning,TotalJoined,UserInvitationCode,TeamNameLocal,TeamNameVisitor,IsConfirm,CashBonusContribution,GameTimeLive'
                appDB
                        .callPostForm('auctionDrafts/getContest', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.Contest = data.Data;
                                        $scope.getUserTeam();
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data)
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
                                    if ($scope.checkResponseCode(data)) {
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
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }


            $scope.fantasyScoreCard = function () {
                $rootScope.fantasyScores = [];
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID');
                $data.IsPlaying = 'Yes';
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Params = 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,TotalPoints';
                appDB
                        .callPostForm('sports/getPlayers', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $rootScope.fantasyScores = data.Data;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data)
                                });
            }

            // $scope.fantasyScoreCard();

            
            $scope.Back = function () {
                window.location.href = document.referrer;
            }

            $scope.showWinningPayout = function (Winnings) {
                $scope.CustomizeWinning = Winnings;
                $scope.openPopup('PayoutBreakUp');
            }

            $scope.showPlaying11 = function(Players,PlayerGUID){
                $scope.SelectedUserGUID = PlayerGUID;
                $scope.Playing11  = Players;
                $scope.openPopup('showPlayerList');
            }

            $rootScope.numDifferentiation = function (value) {
                var val = Math.abs(value)
                if (val >= 10000000) {
                    val = (val / 10000000) + ' Crs';
                } else if (val >= 100000) {
                    val = (val / 100000) + ' Lacs';
                } else if (val == 1000000000) {
                    val = (val / 10000000) + ' Crs';
                }
                return val;
            }

        } else {
            window.location.href = base_url;
        }
    }]);