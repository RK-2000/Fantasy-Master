'use strict';

app.controller('createTeamController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', '$http', '$timeout', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, $http, $timeout) {
        $scope.env = environment;
        $scope.data.pageSize = 15;
        $scope.data.pageNo = 1;
        $scope.coreLogic = Mobiweb.helpers;
        $rootScope.selectedPlayers = []; // selected players array
        $rootScope.Captain = ''; // selected Captain
        $rootScope.ViceCaptain = ''; // selected Vice Captain
        $rootScope.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
        $rootScope.selectedCaptain = ''; //selected captain name
        $rootScope.selectedViceCaptain = ''; //selected vice captain name
        $scope.UserInvitationCode = getQueryStringValue('UserInvitationCode'); //UserInvitationCode
        $scope.totalCredits = parseFloat(100).toFixed(0);
        $scope.leftCredits = parseFloat(100).toFixed(1);

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.user_details = $localStorage.user_details;

            /*get url params*/
            if (!getQueryStringValue('MatchGUID')) {
                window.location.href = 'lobby'; 
            } else {

                /*Function to get mactch center details*/
                $scope.MatchesDetail = {};
                $scope.matchCenterDetails = function () {

                    var $data = {};
                    $data.MatchGUID = getQueryStringValue('MatchGUID'); //   Match GUID
                    $rootScope.MatchGUID = getQueryStringValue('MatchGUID'); //   Match GUID
                    $data.Params = 'SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,TeamGUIDLocal,TeamGUIDVisitor,MatchLocation,SeriesGUID,Status';
                    $data.Status = 'Pending';
                    appDB
                            .callPostForm('sports/getMatches', $data)
                            .then(
                                    function successCallback(data) {
                                        if (data.ResponseCode == 200) {
                                            $scope.MatchesDetail = data.Data;
                                            $rootScope.CurrentSelectedMatchDetail = data.Data; 
                                            // $scope.MatchesDetail.Status='Running';
                                            if ($scope.MatchesDetail.Status == 'Pending') {
                                                $scope.teamSize = 11;
                                            } else if ($scope.MatchesDetail.Status == 'Running') {
                                                $scope.teamSize = 6;
                                            }
                                            $scope.teamA = {
                                                'team': $scope.MatchesDetail.TeamGUIDLocal,
                                                'teamName': $scope.MatchesDetail.TeamNameShortLocal,
                                                'count': 0
                                            };
                                            $scope.teamB = {
                                                'team': $scope.MatchesDetail.TeamGUIDVisitor,
                                                'teamName': $scope.MatchesDetail.TeamNameShortVisitor,
                                                'count': 0
                                            };

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
                /*
                 Description : To get user created team list
                 */
                $rootScope.userTeams = [];
                $scope.UsersTeamList = function () {
                    var $data = {};
                    $rootScope.userTeams = []; //user team list array
                    $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                    $data.MatchGUID = getQueryStringValue('MatchGUID'); //Match GUID
                    $data.Params = 'UserTeamName';
                    $data.PageNo = 0;
                    $data.UserTeamType = ($scope.MatchesDetail.Status == 'Pending' ? 'Normal' : 'InPlay');
                    $data.UserGUID = $localStorage.user_details.UserGUID;
                    appDB
                            .callPostForm('contest/getUserTeams', $data)
                            .then(
                                    function successCallback(data) {
                                        if (data.ResponseCode == 200) {
                                            if (data.Data.hasOwnProperty('Records')) {
                                                $rootScope.userTeams = data.Data;

                                            } else {
                                                $rootScope.userTeams = [];

                                            }
                                            $scope.UserTeamsTotalCount = data.Data.TotalRecords;
                                            setTimeout(function () {
                                                $('.selectpickerJoinLeague').selectpicker('refresh');
                                            }, 1000);
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


                $scope.activeTab = 'wk';
                setTimeout(function () {

                    $scope.teamStructure = {
                        "WicketKeeper": {
                            "min": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 0),
                            "max": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 1),
                            "occupied": 0,
                            "player": [],
                            "icon": "flaticon1-pair-of-gloves"
                        },
                        "Batsman": {
                            "min": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 0),
                            "max": ($scope.MatchesDetail.Status == 'Pending' ? 5 : 6),
                            "occupied": 0,
                            "player": [],
                            "icon": "flaticon1-ball"
                        },
                        "Bowler": {
                            "min": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 0),
                            "max": ($scope.MatchesDetail.Status == 'Pending' ? 5 : 6),
                            "occupied": 0,
                            "player": [],
                            "icon": "flaticon1-tennis-ball"
                        },
                        "AllRounder": {
                            "min": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 0),
                            "max": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 6),
                            "occupied": 0,
                            "player": [],
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

                    $scope.resetTeamStructure = function () {
                        $scope.teamStructure = {
                            "WicketKeeper": {
                                "min": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 0),
                                "max": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 1),
                                "occupied": 0,
                                "player": [],
                                "icon": "flaticon1-pair-of-gloves"
                            },
                            "Batsman": {
                                "min": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 0),
                                "max": ($scope.MatchesDetail.Status == 'Pending' ? 5 : 6),
                                "occupied": 0,
                                "player": [],
                                "icon": "flaticon1-ball"
                            },
                            "Bowler": {
                                "min": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 0),
                                "max": ($scope.MatchesDetail.Status == 'Pending' ? 5 : 6),
                                "occupied": 0,
                                "player": [],
                                "icon": "flaticon1-tennis-ball"
                            },
                            "AllRounder": {
                                "min": ($scope.MatchesDetail.Status == 'Pending' ? 1 : 0),
                                "max": ($scope.MatchesDetail.Status == 'Pending' ? 3 : 6),
                                "occupied": 0,
                                "player": [],
                                "icon": "flaticon1-ball"
                            },
                            "Extra": {
                                "min": 3,
                                "max": 3,
                                occupied: 0,
                                "player": []
                            },
                            "ready": false
                        };
                        $scope.teamCount = 0;
                        $scope.totalCredits = parseFloat(100).toFixed(0);
                        $scope.leftCredits = parseFloat(100).toFixed(1);
                        $scope.players.map(function (player) {
                            player.IsAdded = false;
                            player.PlayerPosition = "Player";
                            player.Disabled = false;
                            player.HighCreditDiabled = false;
                            return player;
                        });
                        $scope.teamA = {
                            'team': $scope.MatchesDetail.TeamGUIDLocal,
                            'teamName': $scope.MatchesDetail.TeamNameShortLocal,
                            'count': 0
                        };
                        $scope.teamB = {
                            'team': $scope.MatchesDetail.TeamGUIDVisitor,
                            'teamName': $scope.MatchesDetail.TeamNameShortVisitor,
                            'count': 0
                        };
                        $rootScope.selectedPlayers = [];
                        $rootScope.Captain = '';
                        $rootScope.ViceCaptain ='';
                        $('.selectpickerCaptain').selectpicker('destroy');
                        $('.selectpickerViceCapatin').selectpicker('destroy');
                    }
                }, 1000);
                $scope.teamCount = 0; //default team count
                delete $rootScope.Captain;
                delete $rootScope.ViceCaptain;
                $scope.gotoTab = function (tab) {
                    $scope.activeTab = tab;
                }

                /*
                 Description : To get Team players
                 */
                $scope.MatchPlayers = function () {
                    $scope.data.listLoading = true;
                    $scope.players = [];
                    $scope.allPlayers = [];
                    var $data = {};
                    $data.MatchGUID = getQueryStringValue('MatchGUID'); //   Match GUID
                    $data.SessionKey = $localStorage.user_details.SessionKey;
                    $data.Params = 'PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit,TotalPointCredits,PlayerSelectedPercent,PointCredits';
                    $data.OrderBy = 'PlayerSalary';
                    $data.Sequence = 'DESC';
                    $data.PlayerSalary = 'Yes';
                    appDB
                            .callPostForm('sports/getPlayers', $data)
                            .then(
                                    function successCallback(data) {
                                        $scope.data.listLoading = false;
                                        if (data.ResponseCode == 200) {
                                            $scope.allPlayers = data.Data.Records;
                                            if (data.Data.TotalRecords > 0) {
                                                $scope.players = $scope.allPlayers;

                                                $scope.addresses = $scope.players.map(function (player) {
                                                    player.IsAdded = false;
                                                    player.PlayerPosition = "Player";
                                                    player.Disabled = false;
                                                    player.HighCreditDiabled = false;
                                                    player.PointCredits = Number(player.PointCredits);
                                                    player.PlayerSalary = Number(player.PlayerSalary);
                                                    return player;
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
                                        $scope.data.listLoading = false;
                                        if (typeof data == 'object') {
                                            var toast = toastr.error(data.Message, {
                                                closeButton: true
                                            });
                                            toastr.refreshTimer(toast, 5000);
                                        }
                                    });
                }

                /*
                 Description : To add Player 
                 */

                $scope.addRemovePlayer = function (PlayerGUID, isAdded, playerDetails) {
                    if (!isAdded) {  
                        if ($scope.teamCount == 10 && $scope.teamStructure['WicketKeeper'].occupied == 0 && playerDetails.PlayerRole != 'WicketKeeper') {
                            var toast = toastr.error('Minimum ' + $scope.teamStructure['WicketKeeper'].min + ' WicketKeeper required in team.', {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            return false;
                        } else if ($scope.teamCount == 9 && $scope.teamStructure['AllRounder'].occupied == 0 && playerDetails.PlayerRole != 'AllRounder') {
                            var toast = toastr.error('Minimum ' + $scope.teamStructure['AllRounder'].min + ' AllRounder required in team.', {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            return false;
                        }
                        //to add player from team 
                        for (var i in $scope.players) {
                            if ($scope.players[i].PlayerGUID == PlayerGUID && playerDetails.TeamID == $scope.players[i].TeamID) {
                                if (isAdded == false) {

                                    if ($scope.teamStructure['Batsman'].occupied + $scope.teamStructure['WicketKeeper'].occupied + $scope.teamStructure['AllRounder'].occupied + $scope.teamStructure['Bowler'].occupied == $scope.teamSize) {
                                        var toast = toastr.error('You cannot add more than ' + $scope.teamSize + ' players', {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        return false;
                                    } else {
                                        if (playerDetails.PlayerRole == 'WicketKeeper') {

                                            if (parseFloat($scope.leftCredits) < parseFloat(playerDetails.PlayerSalaryCredit)) {
                                                var toast = toastr.error('Insufficient credit.', {
                                                    closeButton: true
                                                });
                                                toastr.refreshTimer(toast, 5000);
                                            } else {

                                                if ($scope.teamStructure[playerDetails.PlayerRole].max > $scope.teamStructure[playerDetails.PlayerRole].occupied) {
                                                    if ($scope.teamA.count == 7 && playerDetails.TeamGUID == $scope.teamA.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;

                                                    }
                                                    if ($scope.teamB.count == 7 && playerDetails.TeamGUID == $scope.teamB.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;

                                                    }
                                                    $scope.teamStructure[playerDetails.PlayerRole].player.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerSalary': playerDetails.PlayerSalaryCredit,
                                                        'PlayerID': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'SelectedPlayerTeam': ($scope.teamA.team == $scope.players[i].TeamGUID) ? 'A' : 'B'
                                                    });
                                                    $scope.teamStructure[playerDetails.PlayerRole].occupied++;
                                                    $scope.players[i].IsAdded = true;
                                                    $rootScope.selectedPlayers.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerSalary': Number(playerDetails.PlayerSalaryCredit),
                                                        'PlayerID': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'TeamNameShort': $scope.players[i].TeamNameShort,
                                                        'IsAdded': true,
                                                        'PositionType': 'WK',
                                                        'PointCredits': Number($scope.players[i].PointCredits),
                                                        'PlayerSalaryCredit': Number($scope.players[i].PlayerSalaryCredit),
                                                        'PlayerRole':'WicketKeeper',
                                                        'TeamGUID':$scope.players[i].TeamGUID
                                                    });

                                                    $scope.leftCredits = parseFloat($scope.leftCredits).toFixed(2) - parseFloat(playerDetails.PlayerSalaryCredit).toFixed(2);

                                                    if ($scope.teamA.team == $scope.players[i].TeamGUID && $scope.teamA.count < 7) {
                                                        $scope.teamA.count++;
                                                    }
                                                    if ($scope.teamB.team == $scope.players[i].TeamGUID && $scope.teamB.count < 7) {
                                                        $scope.teamB.count++;
                                                    }
                                                } else {
                                                    var toast = toastr.error('You cannot add more than ' + $scope.teamStructure[playerDetails.PlayerRole].max + ' ' + playerDetails.PlayerRole, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            }
                                        }
                                        if (playerDetails.PlayerRole == 'Batsman') {
                                            if (parseFloat($scope.leftCredits) < parseFloat(playerDetails.PlayerSalaryCredit)) {
                                                var toast = toastr.error('Insufficient credit.', {
                                                    closeButton: true
                                                });
                                                toastr.refreshTimer(toast, 5000);
                                            } else {

                                                if ($scope.teamStructure[playerDetails.PlayerRole].max > $scope.teamStructure[playerDetails.PlayerRole].occupied) {

                                                    if ($scope.teamA.count == 7 && playerDetails.TeamGUID == $scope.teamA.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;
                                                    }
                                                    if ($scope.teamB.count == 7 && playerDetails.TeamGUID == $scope.teamB.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;
                                                    }

                                                    $scope.teamStructure[playerDetails.PlayerRole].player.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerSalary': playerDetails.PlayerSalaryCredit,
                                                        'PlayerId': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'SelectedPlayerTeam': ($scope.teamA.team == $scope.players[i].TeamGUID) ? 'A' : 'B'
                                                    });
                                                    $scope.teamStructure[playerDetails.PlayerRole].occupied++;
                                                    $scope.players[i].IsAdded = true;
                                                    $rootScope.selectedPlayers.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerSalary': Number(playerDetails.PlayerSalaryCredit),
                                                        'PlayerID': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'TeamNameShort': $scope.players[i].TeamNameShort,
                                                        'IsAdded': true,
                                                        'PositionType': 'BAT',
                                                        'PointCredits': Number($scope.players[i].PointCredits),
                                                        'PlayerSalaryCredit': Number($scope.players[i].PlayerSalaryCredit),
                                                        'PlayerRole':'Batsman',
                                                        'TeamGUID':$scope.players[i].TeamGUID
                                                    });
                                                    $scope.leftCredits = parseFloat($scope.leftCredits).toFixed(2) - parseFloat(playerDetails.PlayerSalaryCredit).toFixed(2);
                                                    if ($scope.teamA.team == $scope.players[i].TeamGUID && $scope.teamA.count < 7) {
                                                        $scope.teamA.count++;
                                                    }
                                                    if ($scope.teamB.team == $scope.players[i].TeamGUID && $scope.teamB.count < 7) {
                                                        $scope.teamB.count++;
                                                    }
                                                } else {
                                                    var toast = toastr.error('You cannot add more than ' + $scope.teamStructure[playerDetails.PlayerRole].max + ' ' + playerDetails.PlayerRole, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            }
                                        }
                                        if (playerDetails.PlayerRole == 'Bowler') {
                                            if (parseFloat($scope.leftCredits) < parseFloat(playerDetails.PlayerSalaryCredit)) {
                                                var toast = toastr.error('Insufficient credit.', {
                                                    closeButton: true
                                                });
                                                toastr.refreshTimer(toast, 5000);
                                            } else {

                                                if ($scope.teamStructure[playerDetails.PlayerRole].max > $scope.teamStructure[playerDetails.PlayerRole].occupied) {
                                                    if ($scope.teamA.count == 7 && playerDetails.TeamGUID == $scope.teamA.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;
                                                    }
                                                    if ($scope.teamB.count == 7 && playerDetails.TeamGUID == $scope.teamB.team) {
                                                        var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;

                                                    }
                                                    $scope.teamStructure[playerDetails.PlayerRole].player.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerSalary': playerDetails.PlayerSalaryCredit,
                                                        'PlayerID': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'SelectedPlayerTeam': ($scope.teamA.team == $scope.players[i].TeamGUID) ? 'A' : 'B'
                                                    });
                                                    $scope.teamStructure[playerDetails.PlayerRole].occupied++;
                                                    $scope.players[i].IsAdded = true;
                                                    $rootScope.selectedPlayers.push({
                                                        'PlayerGUID': playerDetails.PlayerGUID,
                                                        'PlayerName': playerDetails.PlayerName,
                                                        'PlayerPic': playerDetails.PlayerPic,
                                                        'PlayerPosition': playerDetails.PlayerPosition,
                                                        'PlayerSalary': Number(playerDetails.PlayerSalaryCredit),
                                                        'PlayerID': playerDetails.PlayerID,
                                                        'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                        'TeamNameShort': $scope.players[i].TeamNameShort,
                                                        'IsAdded': true,
                                                        'PositionType': 'BOWL',
                                                        'PointCredits': Number($scope.players[i].PointCredits),
                                                        'PlayerSalaryCredit': Number($scope.players[i].PlayerSalaryCredit),
                                                        'PlayerRole':'Bowler',
                                                        'TeamGUID':$scope.players[i].TeamGUID
                                                    });
                                                    $scope.leftCredits = parseFloat($scope.leftCredits).toFixed(2) - parseFloat(playerDetails.PlayerSalaryCredit).toFixed(2);
                                                    if ($scope.teamA.team == $scope.players[i].TeamGUID && $scope.teamA.count < 7) {
                                                        $scope.teamA.count++;
                                                    }
                                                    if ($scope.teamB.team == $scope.players[i].TeamGUID && $scope.teamB.count < 7) {
                                                        $scope.teamB.count++;
                                                    }
                                                } else {
                                                    var toast = toastr.error('You cannot add more than ' + $scope.teamStructure[playerDetails.PlayerRole].max + ' ' + playerDetails.PlayerRole, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            }
                                        }
                                        if (playerDetails.PlayerRole == 'AllRounder') {
                                            if (parseFloat($scope.leftCredits) < parseFloat(playerDetails.PlayerSalaryCredit)) {
                                                var toast = toastr.error('Insufficient credit.', {
                                                    closeButton: true
                                                });
                                                toastr.refreshTimer(toast, 5000);
                                            } else {

                                                if ($scope.teamStructure[playerDetails.PlayerRole].max > $scope.teamStructure[playerDetails.PlayerRole].occupied) {
                                                    if ($scope.teamStructure['Batsman'].occupied + $scope.teamStructure['WicketKeeper'].occupied + $scope.teamStructure['AllRounder'].occupied > 7) {
                                                        var toast = toastr.error('Please select atleast ' + $scope.teamStructure['Bowler'].min + ' bowler.', {
                                                            closeButton: true
                                                        });
                                                        toastr.refreshTimer(toast, 5000);
                                                        return false;
                                                    } else {

                                                        if ($scope.teamA.count == 7 && playerDetails.TeamGUID == $scope.teamA.team) {
                                                            var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                                closeButton: true
                                                            });
                                                            toastr.refreshTimer(toast, 5000);
                                                            return false;

                                                        }
                                                        if ($scope.teamB.count == 7 && playerDetails.TeamGUID == $scope.teamB.team) {
                                                            var toast = toastr.error('You cannot select more than 7 players from same team.', {
                                                                closeButton: true
                                                            });
                                                            toastr.refreshTimer(toast, 5000);
                                                            return false;

                                                        }
                                                        $scope.teamStructure[playerDetails.PlayerRole].player.push({
                                                            'PlayerGUID': playerDetails.PlayerGUID,
                                                            'PlayerPosition': playerDetails.PlayerPosition,
                                                            'PlayerName': playerDetails.PlayerName,
                                                            'PlayerPic': playerDetails.PlayerPic,
                                                            'PlayerSalary': playerDetails.PlayerSalaryCredit,
                                                            'PlayerID': playerDetails.PlayerID,
                                                            'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                            'SelectedPlayerTeam': ($scope.teamA.team == $scope.players[i].TeamGUID) ? 'A' : 'B'
                                                        });
                                                        $scope.teamStructure[playerDetails.PlayerRole].occupied++;
                                                        $scope.players[i].IsAdded = true;
                                                        $rootScope.selectedPlayers.push({
                                                            'PlayerGUID': playerDetails.PlayerGUID,
                                                            'PlayerName': playerDetails.PlayerName,
                                                            'PlayerPic': playerDetails.PlayerPic,
                                                            'PlayerPosition': playerDetails.PlayerPosition,
                                                            'PlayerSalary': Number(playerDetails.PlayerSalaryCredit),
                                                            'PlayerID': playerDetails.PlayerID,
                                                            'TeamNameShort': $scope.players[i].TeamNameShort,
                                                            'IsAdded': true,
                                                            'PositionType': 'ALL',
                                                            'PlayerShortName': $scope.getPlayerShortName(playerDetails.PlayerName),
                                                            'PointCredits': Number($scope.players[i].PointCredits),
                                                            'PlayerSalaryCredit': Number($scope.players[i].PlayerSalaryCredit),
                                                            'PlayerRole':'AllRounder',
                                                            'TeamGUID':$scope.players[i].TeamGUID
                                                        });
                                                        $scope.leftCredits = parseFloat($scope.leftCredits).toFixed(2) - parseFloat(playerDetails.PlayerSalaryCredit).toFixed(2);
                                                        if ($scope.teamA.team == $scope.players[i].TeamGUID && $scope.teamA.count < 7) {
                                                            $scope.teamA.count++;
                                                        }
                                                        if ($scope.teamB.team == $scope.players[i].TeamGUID && $scope.teamB.count < 7) {
                                                            $scope.teamB.count++;
                                                        }
                                                    }
                                                } else {
                                                    var toast = toastr.error('You cannot add more than ' + $scope.teamStructure[playerDetails.PlayerRole].max + ' ' + playerDetails.PlayerRole, {
                                                        closeButton: true
                                                    });
                                                    toastr.refreshTimer(toast, 5000);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        /**
                         * Condition for team creation
                         */
                        switch (playerDetails.PlayerRole) {
                            case 'Batsman':
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied == 5) {
                                    $scope.teamStructure['AllRounder'].max = 2;
                                    for (var i in $scope.players) {
                                        if ($scope.teamStructure['AllRounder'].occupied == $scope.teamStructure['AllRounder'].max) {
                                            if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'AllRounder') {
                                                $scope.players[i].Disabled = true;
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'Bowler':
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied == 5) {
                                    $scope.teamStructure['AllRounder'].max = 2;
                                    for (var i in $scope.players) {
                                        if ($scope.teamStructure['AllRounder'].occupied == $scope.teamStructure['AllRounder'].max) {
                                            if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'AllRounder') {
                                                $scope.players[i].Disabled = true;
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'AllRounder':
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied == 3) {
                                    $scope.teamStructure['Batsman'].max = 4;
                                    $scope.teamStructure['Bowler'].max = 4;
                                    for (var i in $scope.players) {
                                        if ($scope.teamStructure['Batsman'].occupied == $scope.teamStructure['Batsman'].max) {
                                            if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'Batsman') {
                                                $scope.players[i].Disabled = true;
                                            }
                                        }
                                        if ($scope.teamStructure['Bowler'].occupied == $scope.teamStructure['Bowler'].max) {
                                            if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'Bowler') {
                                                $scope.players[i].Disabled = true;
                                            }
                                        }
                                    }
                                }
                                break;
                        }

                        for (var i in $scope.players) {
                            switch (playerDetails.PlayerRole) {
                                case 'WicketKeeper':
                                    if ($scope.teamStructure[playerDetails.PlayerRole].occupied == $scope.teamStructure[playerDetails.PlayerRole].max) {
                                        if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'WicketKeeper') {
                                            $scope.players[i].Disabled = true;
                                        }
                                    }
                                    break;
                                case 'Batsman':
                                    if ($scope.teamStructure[playerDetails.PlayerRole].occupied == $scope.teamStructure[playerDetails.PlayerRole].max) {
                                        if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'Batsman') {
                                            $scope.players[i].Disabled = true;
                                        }
                                    }
                                    break;
                                case 'Bowler':
                                    if ($scope.teamStructure[playerDetails.PlayerRole].occupied == $scope.teamStructure[playerDetails.PlayerRole].max) {
                                        if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'Bowler') {
                                            $scope.players[i].Disabled = true;
                                        }
                                    }
                                    break;
                                case 'AllRounder':
                                    if ($scope.teamStructure[playerDetails.PlayerRole].occupied == $scope.teamStructure[playerDetails.PlayerRole].max) {
                                        if ($scope.players[i].IsAdded == false && $scope.players[i].PlayerRole == 'AllRounder') {
                                            $scope.players[i].Disabled = true;
                                        }
                                    }
                                    break;
                            }

                        }
                        $scope.teamCount = $scope.teamStructure['AllRounder'].occupied + $scope.teamStructure['Bowler'].occupied + $scope.teamStructure['Batsman'].occupied + $scope.teamStructure['WicketKeeper'].occupied;

                    } else { 
                        //to remove player from team
                        if (playerDetails.PlayerRole == 'WicketKeeper') {  

                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied > 0) {
                                $scope.teamStructure[playerDetails.PlayerRole].player.splice(0, 1);
                                if ($scope.teamA.team == playerDetails.TeamGUID && $scope.teamA.count <= 7) {
                                    $scope.teamA.count--;
                                }
                                if ($scope.teamB.team == playerDetails.TeamGUID && $scope.teamB.count <= 7) {
                                    $scope.teamB.count--;
                                }
                                $scope.leftCredits = (parseFloat($scope.leftCredits) + parseFloat(playerDetails.PlayerSalaryCredit)).toFixed(2);
                                $scope.teamStructure[playerDetails.PlayerRole].occupied--;
                                for (var i in $scope.players) {
                                    if ($scope.players[i].PlayerGUID == playerDetails.PlayerGUID) {
                                        $scope.players[i].IsAdded = false;
                                    }
                                }
                            }
                            for (var i in $scope.players) {
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied != $scope.teamStructure[playerDetails.PlayerRole].max) {
                                    if ($scope.players[i].PlayerRole == 'WicketKeeper') {
                                        $scope.players[i].Disabled = false;
                                    }
                                }
                            }
                        }
                        if (playerDetails.PlayerRole == 'Batsman') {
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied > 0) {
                                for (let j = 0; j < $scope.teamStructure[playerDetails.PlayerRole].occupied; j++) {

                                    if ($scope.teamStructure[playerDetails.PlayerRole].player[j].PlayerGUID == playerDetails.PlayerGUID) {

                                        $scope.teamStructure[playerDetails.PlayerRole].player.splice(j, 1);
                                        if ($scope.teamA.team == playerDetails.TeamGUID && $scope.teamA.count <= 7) {
                                            $scope.teamA.count--;
                                        }
                                        if ($scope.teamB.team == playerDetails.TeamGUID && $scope.teamB.count <= 7) {
                                            $scope.teamB.count--;
                                        }
                                        $scope.leftCredits = (parseFloat($scope.leftCredits) + parseFloat(playerDetails.PlayerSalaryCredit)).toFixed(2);

                                        $scope.teamStructure[playerDetails.PlayerRole].occupied--;

                                        for (var i in $scope.players) {
                                            if ($scope.players[i].PlayerGUID == playerDetails.PlayerGUID) {
                                                $scope.players[i].IsAdded = false;
                                            }
                                        }

                                    }
                                }
                            }
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied < $scope.teamStructure[playerDetails.PlayerRole].max && $scope.teamStructure['Bowler'].occupied < 5) {
                                $scope.teamStructure['AllRounder'].max = 3;
                                for (var i in $scope.players) {
                                    if ($scope.teamStructure['AllRounder'].occupied != $scope.teamStructure['AllRounder'].max) {
                                        if ($scope.players[i].PlayerRole == 'AllRounder') {
                                            $scope.players[i].Disabled = false;
                                        }
                                    }
                                }
                            }
                            for (var i in $scope.players) {
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied != $scope.teamStructure[playerDetails.PlayerRole].max) {
                                    if ($scope.players[i].PlayerRole == 'Batsman') {
                                        $scope.players[i].Disabled = false;
                                    }
                                }
                            }
                        }
                        if (playerDetails.PlayerRole == 'Bowler') {
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied > 0) {
                                for (let j = 0; j < $scope.teamStructure[playerDetails.PlayerRole].occupied; j++) {
                                    if ($scope.teamStructure[playerDetails.PlayerRole].player[j].PlayerGUID == playerDetails.PlayerGUID) {
                                        $scope.teamStructure[playerDetails.PlayerRole].player.splice(j, 1);
                                        if ($scope.teamA.team == playerDetails.TeamGUID && $scope.teamA.count <= 7) {
                                            $scope.teamA.count--;
                                        }
                                        if ($scope.teamB.team == playerDetails.TeamGUID && $scope.teamB.count <= 7) {
                                            $scope.teamB.count--;
                                        }
                                        $scope.leftCredits = (parseFloat($scope.leftCredits) + parseFloat(playerDetails.PlayerSalaryCredit)).toFixed(2);
                                        $scope.teamStructure[playerDetails.PlayerRole].occupied--;
                                        for (var i in $scope.players) {
                                            if ($scope.players[i].PlayerGUID == playerDetails.PlayerGUID) {
                                                $scope.players[i].IsAdded = false;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied < $scope.teamStructure[playerDetails.PlayerRole].max && $scope.teamStructure['Batsman'].occupied < 5) {
                                $scope.teamStructure['AllRounder'].max = 3;
                                for (var i in $scope.players) {
                                    if ($scope.teamStructure['AllRounder'].occupied != $scope.teamStructure['AllRounder'].max) {
                                        if ($scope.players[i].PlayerRole == 'AllRounder') {
                                            $scope.players[i].Disabled = false;
                                        }
                                    }
                                }
                            }
                            for (var i in $scope.players) {
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied != $scope.teamStructure[playerDetails.PlayerRole].max) {
                                    if ($scope.players[i].PlayerRole == 'Bowler') {
                                        $scope.players[i].Disabled = false;
                                    }
                                }
                            }
                        }
                        if (playerDetails.PlayerRole == 'AllRounder') {
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied > 0) {
                                for (let j = 0; j < $scope.teamStructure[playerDetails.PlayerRole].occupied; j++) {
                                    if ($scope.teamStructure[playerDetails.PlayerRole].player[j].PlayerGUID == playerDetails.PlayerGUID) {

                                        $scope.teamStructure[playerDetails.PlayerRole].player.splice(j, 1);
                                        if ($scope.teamA.team == playerDetails.TeamGUID && $scope.teamA.count <= 7) {
                                            $scope.teamA.count--;
                                        }
                                        if ($scope.teamB.team == playerDetails.TeamGUID && $scope.teamB.count <= 7) {
                                            $scope.teamB.count--;
                                        }
                                        $scope.leftCredits = (parseFloat($scope.leftCredits) + parseFloat(playerDetails.PlayerSalaryCredit)).toFixed(2);
                                        $scope.teamStructure[playerDetails.PlayerRole].occupied--;
                                        for (var i in $scope.players) {
                                            if ($scope.players[i].PlayerGUID == playerDetails.PlayerGUID) {
                                                $scope.players[i].IsAdded = false;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($scope.teamStructure[playerDetails.PlayerRole].occupied < $scope.teamStructure[playerDetails.PlayerRole].max) {
                                $scope.teamStructure['Bowler'].max = 5;
                                $scope.teamStructure['Batsman'].max = 5;
                                for (var i in $scope.players) {
                                    if ($scope.teamStructure['Bowler'].occupied != $scope.teamStructure['Bowler'].max) {
                                        if ($scope.players[i].PlayerRole == 'Bowler') {
                                            $scope.players[i].Disabled = false;
                                        }
                                    }
                                    if ($scope.teamStructure['Batsman'].occupied != $scope.teamStructure['Batsman'].max) {
                                        if ($scope.players[i].PlayerRole == 'Batsman') {
                                            $scope.players[i].Disabled = false;
                                        }
                                    }
                                }
                            }
                            for (var i in $scope.players) {
                                if ($scope.teamStructure[playerDetails.PlayerRole].occupied != $scope.teamStructure[playerDetails.PlayerRole].max) {
                                    if ($scope.players[i].PlayerRole == 'AllRounder') {
                                        $scope.players[i].Disabled = false;
                                    }
                                }
                            }
                        }

                        $scope.teamCount = $scope.teamStructure['AllRounder'].occupied + $scope.teamStructure['Bowler'].occupied + $scope.teamStructure['Batsman'].occupied + $scope.teamStructure['WicketKeeper'].occupied;
                        angular.forEach($rootScope.selectedPlayers, function (val, key) {
                            if (val.PlayerGUID == playerDetails.PlayerGUID) {
                                $rootScope.selectedPlayers.splice(key, 1);
                            }
                        });

                    }

                    for (var i in $scope.players) {
                        if ($scope.players[i].PlayerSalary > $scope.leftCredits) {
                            if (!$scope.players[i].IsAdded && !$scope.players[i].Disabled) {
                                $scope.players[i].HighCreditDiabled = true;
                            }
                        } else {
                            if ($scope.players[i].HighCreditDiabled) {
                                $scope.players[i].HighCreditDiabled = false;
                            }
                        }
                    }
                    if ($scope.teamCount == 11) {
                        $scope.openSaveteam();
                    }
                }
            }

            /*
             Description : To Select captain
             */

            $rootScope.selectCaptain = function (PlayerGUID) {
                for (var i = 0; i < $rootScope.selectedPlayers.length; i++) {
                    if ($rootScope.selectedPlayers[i].PlayerGUID == PlayerGUID) {

                        if ($rootScope.selectedPlayers[i].PlayerPosition != 'Captain') {
                            $rootScope.selectedPlayers[i].PlayerPosition = 'Captain';
                            $rootScope.selectedCaptain = $rootScope.selectedPlayers[i].PlayerName;

                            $rootScope.Captain = $rootScope.selectedPlayers[i].PlayerGUID;
                        }
                    } else {
                        if ($rootScope.selectedPlayers[i].PlayerPosition != 'ViceCaptain') {
                            $rootScope.selectedPlayers[i].PlayerPosition = 'Player';
                        }
                    }
                }
                $rootScope.selectedPlayers = $rootScope.selectedPlayers;
                $timeout(function(){ 
                    $(".selectpickerCaptain").val($rootScope.Captain);
                    $(".selectpickerCaptain").selectpicker('render');
                    $(".selectpickerViceCapatin").selectpicker('render');
                    $(".selectpickerCaptain").selectpicker('refresh');
                    $(".selectpickerViceCapatin").selectpicker('refresh');
                },500);
            }

            /*
             Description : To Select vice captain
             */

            $rootScope.selectViceCaptain = function (PlayerGUID) {

                for (var i = 0; i < $rootScope.selectedPlayers.length; i++) {
                    if ($rootScope.selectedPlayers[i].PlayerGUID == PlayerGUID) {

                        if ($rootScope.selectedPlayers[i].PlayerPosition != 'ViceCaptain') {
                            $rootScope.selectedPlayers[i].PlayerPosition = 'ViceCaptain';
                            $rootScope.selectedViceCaptain = $rootScope.selectedPlayers[i].PlayerName;

                            $rootScope.ViceCaptain = $rootScope.selectedPlayers[i].PlayerGUID;
                        }
                    } else {
                        if ($rootScope.selectedPlayers[i].PlayerPosition != 'Captain') {
                            $rootScope.selectedPlayers[i].PlayerPosition = 'Player';
                        }
                    }
                }
                $timeout(function(){
                    
                    $(".selectpickerViceCapatin").val($rootScope.ViceCaptain);
                    $(".selectpickerCaptain").val($rootScope.Captain);
                    $(".selectpickerCaptain").selectpicker('render');
                    $(".selectpickerViceCapatin").selectpicker('render');
                    $(".selectpickerCaptain").selectpicker('refresh');
                    $(".selectpickerViceCapatin").selectpicker('refresh');
                },500);
            }

            /*
             Description : To join Contest after Team Create
             */
            $scope.join = {};
            $scope.joinSubmitted = false;
            $rootScope.JoinContest = function () {
                $scope.joinSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                var $data = {};
                $data = $scope.join;
                $data.ContestGUID = $rootScope.ContestGUID;
                $data.MatchGUID = $rootScope.MatchGUID;
                //$data.UserTeamGUID = $rootScope.UserTeamGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                if ($scope.UserInvitationCode) {
                    $data.UserInvitationCode = $scope.UserInvitationCode;
                }
                appDB
                        .callPostForm('contest/join', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.closePopup('joinLeaguePopup');
                                        var toast = toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                        setTimeout(function () {
                                            window.location.href = base_url + 'lobby';
                                            $localStorage.MatchGUID = getQueryStringValue('MatchGUID');
                                        }, 1000);

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

            $scope.openSaveteam = function () {
                if ($scope.teamStructure['WicketKeeper'].occupied < $scope.teamStructure['WicketKeeper'].min || $scope.teamStructure['Batsman'].occupied < $scope.teamStructure['Batsman'].min || $scope.teamStructure['Bowler'].occupied < $scope.teamStructure['Bowler'].min || $scope.teamStructure['AllRounder'].occupied < $scope.teamStructure['AllRounder'].min) {
                    var toast = toastr.error('Selection criteria not fullfilled please change your team according to selection crieria.', {
                        closeButton: true
                    });
                    toastr.refreshTimer(toast, 5000);
                    return false;

                } else {
                    $timeout(function(){
                        $(".selectpickerViceCapatin").val($rootScope.ViceCaptain);
                        $(".selectpickerCaptain").val($rootScope.Captain);
                        $(".selectpickerCaptain").selectpicker('render');
                        $(".selectpickerViceCapatin").selectpicker('render');
                        $(".selectpickerCaptain").selectpicker('refresh');
                        $(".selectpickerViceCapatin").selectpicker('refresh');
                    },500);
                    $scope.openPopup('selectCaptainViceCaptainModal');
                    
                }
            }

            /*
             Description : To save user team 
             */
            $rootScope.SaveTeam = function () {
                $scope.data.listLoading = true;
                var $data = {};

                $data.MatchGUID = getQueryStringValue('MatchGUID'); //   Match GUID
                $data.SessionKey = $localStorage.user_details.SessionKey; //   User session key

                // $data.UserTeamPlayers       = JSON.stringify($rootScope.selectedPlayers); //   User selected players

                $data.UserTeamPlayers = $rootScope.selectedPlayers; //   User selected players
                $data.UserTeamName = $scope.UserTeamName; //   User team name
                $data.UserTeamType = 'Normal'; //   User team name


                if (getQueryStringValue('Operation') && getQueryStringValue('Operation') == 'edit') {
                    var $url = 'contest/editUserTeam';
                    $data.UserTeamGUID = getQueryStringValue('UserTeamGUID');
                } else {
                    var $url = 'contest/addUserTeam';
                }

                // var data = 'SessionKey='+$localStorage.user_details.SessionKey+'&MatchGUID='+getQueryStringValue('MatchGUID')+'&'+j$("form[name='SaveTeamForm']").serialize();

                // var data = $data.serialize();

                $http.post($scope.env.api_url + $url, $.param($data), contentType).then(function (response) {
                    var response = response.data;
                    $scope.data.listLoading = false;
                    if (response.ResponseCode == 200) {

                        /* success case */
                        if (getQueryStringValue('League')) {
                            $rootScope.ContestGUID = getQueryStringValue('League');
                            $rootScope.MatchGUID = getQueryStringValue('MatchGUID');
                            $rootScope.UserTeamGUID = response.Data.UserTeamGUID;

                            $scope.closePopup('selectCaptainViceCaptainModal');
                            $scope.UsersTeamList();

                            var toast = toastr.success(response.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            setTimeout(function () {
                                $scope.openPopup('joinLeaguePopup');
                                $('.selectpickerJoinLeague').selectpicker('render');
                            }, 1000);
                        } else {
                            var toast = toastr.success(response.Message, {
                                closeButton: true
                            });
                            toastr.refreshTimer(toast, 5000);
                            setTimeout(function () {
                                $localStorage.MatchGUID = getQueryStringValue('MatchGUID');
                                window.location.href = 'lobby';
                            }, 200);
                        }
                    }
                    if (response.ResponseCode == 500) {
                        var toast = toastr.warning(response.Message, {
                            closeButton: true
                        });
                        toastr.refreshTimer(toast, 5000);
                    }
                    if (response.ResponseCode == 501) {
                        var toast = toastr.warning(response.Message, {
                            closeButton: true
                        });
                        toastr.refreshTimer(toast, 5000);
                    }
                    if (response.ResponseCode == 502) {
                        var toast = toastr.warning(response.Message, {
                            closeButton: true
                        });
                        toastr.refreshTimer(toast, 5000);
                        setTimeout(function () {
                            localStorage.clear();
                            window.location.reload();
                        }, 1000);
                    }
                });
            }

            /*Edit Team*/
            $scope.editTeam = function () {
                $scope.data.listLoading = true;
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID');
                $data.UserTeamGUID = getQueryStringValue('UserTeamGUID');
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.UserTeamType = 'Normal';
                $data.Params = "TeamNameShort,PlayerID,PlayerSalaryCredit,PlayerGUID,PlayerName,PlayerCountry,PlayerPosition,PlayerRole,UserTeamPlayers,PlayerSalary,TeamGUID";
                $data.UserGUID = $localStorage.user_details.UserGUID;
                appDB
                        .callPostForm('contest/getUserTeams', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if (data.ResponseCode == 200) {
                                        $scope.resetTeamStructure();
                                        $rootScope.selectedPlayers = [];

                                        angular.forEach(data.Data.UserTeamPlayers, function (value, key) {
                                            value.PlayerSalaryCredit = value.PlayerSalary;
                                            $scope.addRemovePlayer(value.PlayerGUID, false, value);
                                            if (value.PlayerPosition == 'Captain') {
                                                $rootScope.Captain = value.PlayerGUID;
                                                $rootScope.selectCaptain(value.PlayerGUID);
                                            }
                                            if (value.PlayerPosition == 'ViceCaptain') {

                                                $rootScope.ViceCaptain = value.PlayerGUID;
                                                $rootScope.selectViceCaptain(value.PlayerGUID);
                                            }
                                        });
                                        $rootScope.UserTeamName = data.Data.UserTeamName;
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
                                    $scope.data.listLoading = false;
                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                });

            }

            /*Copy Team Team*/
            $scope.copyTeam = function () {
                $scope.data.listLoading = true;
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID');
                $data.UserTeamGUID = getQueryStringValue('UserTeamGUID');
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.UserTeamType = 'Normal';
                $data.Params = "PlayerID,PlayerSalaryCredit,PlayerGUID,PlayerName,PlayerCountry,PlayerPosition,PlayerRole,UserTeamPlayers,PlayerSalary,TeamGUID";
                $data.UserGUID = $localStorage.user_details.UserGUID;
                appDB
                        .callPostForm('contest/getUserTeams', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if (data.ResponseCode == 200) {
                                        $scope.resetTeamStructure();
                                        $rootScope.selectedPlayers = [];


                                        angular.forEach(data.Data.UserTeamPlayers, function (value, key) {
                                            value.PlayerSalaryCredit = value.PlayerSalary;
                                            $scope.addRemovePlayer(value.PlayerGUID, false, value);
                                        });

                                        //$scope.openPopup('selectCaptainViceCaptainModal');

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
                                    $scope.data.listLoading = false;
                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                });

            }
            if (getQueryStringValue('UserTeamGUID') && getQueryStringValue('Operation') != 'edit') {
                setTimeout(function () {
                    $scope.copyTeam();
                }, 2500);
            }
            if (getQueryStringValue('Operation') == 'edit') {
                setTimeout(function () {
                    $scope.editTeam();
                }, 2500);
            }

            /*player info*/
            $scope.playersInfo = function (playerDetails) {
                document.getElementById("playersInfoModal").style.width = "100%";
                var details = {}; 
                for(var i in $scope.players){
                    if($scope.players[i].PlayerGUID == playerDetails.PlayerGUID){
                        details = $scope.players[i];
                    }
                }
                $rootScope.playerDetails = {};
                $rootScope.playerDetails = details;
                $rootScope.PlayerBattingStats = details.PlayerBattingStats;
                $rootScope.PlayerBowlingStats = details.PlayerBowlingStats;
            }
            $rootScope.closeNav = function () {
                document.getElementById("playersInfoModal").style.width = "0";
            }

            $rootScope.activePlayerTab = 'play';
            $rootScope.playerDetailsTab = function (tab) {
                $rootScope.activePlayerTab = tab;
            }
            $scope.propertyName = 'PlayerSalary';
            $scope.reverse = true;
            $scope.sortBy = function (propertyName) {
                $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
                $scope.propertyName = propertyName;
            };
            $scope.SelectedPlayerPropertyName = 'PlayerSalary';
            $scope.SelectedPlayerReverse = true;
            $scope.sortWithBy = function (propertyName) {
                $scope.SelectedPlayerReverse = ($scope.SelectedPlayerPropertyName === propertyName) ? !$scope.SelectedPlayerReverse : false;
                $scope.SelectedPlayerPropertyName = propertyName;
            }

            
            $scope.activeView = 'PitchView';
            $scope.changeView = function (View) {
                $scope.activeView = View;
            }

            $scope.activeStepperCheck = function(number){ 
                if($rootScope.selectedPlayers.length == number || $rootScope.selectedPlayers.length > number){
                    return true;
                }else{
                    return false;
                }
                
            }
            
            /**
             * Get contest info
             */

            $scope.getContest = function() {
                $rootScope.Contest = [];
                var $data = {};
                $data.MatchGUID = getQueryStringValue('MatchGUID'); // Selected MatchGUID
                $data.ContestGUID = getQueryStringValue('League'); // Selected ContestGUID
                $data.SessionKey = $localStorage.user_details.SessionKey; // User SessionKey
                $data.Params = 'Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,IsJoined,Status,ContestFormat,ContestType,CustomizeWinning,TotalJoined,UserInvitationCode,SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,SeriesGUID,Status,MatchScoreDetails,ShowJoinedContest';
                
                appDB
                    .callPostForm('contest/getContests', $data)
                    .then(
                        function successCallback(data) {
                            if ($scope.checkResponseCode(data)) {
                                $rootScope.Contest = data.Data;
                            }
                        },
                        function errorCallback(data) {
                            $scope.checkResponseCode(data);
                        });
            }
            
            $scope.Back = function(){
                window.location.href = document.referrer;
            }

        } else {
            window.location.href = base_url;
        }
    }]);