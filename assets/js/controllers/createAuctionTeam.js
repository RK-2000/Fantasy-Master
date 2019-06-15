app.controller('createAuctionTeamController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', '$timeout', '$filter', '$http', 'socket', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, $timeout, $filter, $http, socket) {
        $scope.env = environment;
        $scope.data.pageSize = 15;
        $scope.data.pageNo = 1;
        $scope.coreLogic = Mobiweb.helpers;

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.user_details = $localStorage.user_details;
            $scope.SeriesGUID = getQueryStringValue('SeriesGUID');
            $scope.ContestGUID = getQueryStringValue('League');
            $scope.TimerStatus = 'hold';
            $scope.SearchSquadPlayer = '';
            $rootScope.TotalPlayers = 20;
            $rootScope.TotalAssistantPlayers = 0;
            $scope.counter = 20;
            $scope.BidAmount = 0;
            $scope.UserHoldTime = 0;
            $scope.BidUserName = '-';
            $scope.FullBidAmount = 0;
            $scope.BidDisabled = false;
            $scope.BreakTime = 0;
            $scope.TotalUserAvailableBudget = 0;
            $scope.TimerHoldeUserGUID = '';
            $scope.bid = 0;
            $scope.count = 1;
            $rootScope.BidOptiones = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 60, 70, 80, 90];
            /**
             * Socket code start here
             */

            socket.on('connect', function () {
                console.log("Connected");
                var UserInfo = {
                    UserGUID: $scope.user_details.UserGUID,
                    ContestGUID: $scope.ContestGUID,
                    SeriesGUID: $scope.SeriesGUID
                }
                socket.emit('AuctionName', UserInfo);
                socket.on('AuctionPlayerStatus', function (data) {
                    $rootScope.players = data.auctionGetPlayer.Data.Records;


                    if (data.auctionPlayerStausData.Data.AuctionStatus == 'Completed') {
                        $scope.ContestInfo.AuctionStatus = 'Completed';
                        $scope.BidDisabled = true;
                        $scope.successMessageShow("Auction is successfully Completed");
                        // return true;
                    } else {
                        $scope.ContestInfo.AuctionStatus = 'Running';
                        $scope.BidDisabled = false;
                    }
                    $scope.$apply();
                });

                // socket.on('TimeDifference',function(data){ console.log(data.timer);
                //     if($scope.LivePlayerStatus == 1){
                //         $scope.timer_reset(data.timer);
                //     }
                //     $scope.$apply();
                // })

                socket.on('AuctionPlayerChange', function (data) {
                    var bidInfo = {
                        'FullName': '-',
                        'BidCredit': 0,
                        'UserGUID': ''
                    };
                    $scope.counter = 15
                    // console.log(JSON.stringify(data.getBidPlayerData.Data));
                    console.log("Sta +++++ " + data.sta);
                    console.log("Contest GUID +++++ " + data.ContestGUID);
                    console.log("PlayerStatus +++++" + data.PlayerStatus);
                    if ($scope.count == 1 && localStorage.getItem('player_count_' + $scope.ContestGUID) == 1) {
                        $scope.counter = 20;
                    }
                    if (($scope.livePlayerInfo.PlayerGUID != data.getBidPlayerData.Data.PlayerGUID || $scope.count == 1) && data.PlayerStatus == 1) {
                        $scope.bidDetail(bidInfo, $scope.counter);
                        console.log("New player");
                        $scope.getMySquad();
                    }

                    $scope.selectPlayer(data.getBidPlayerData.Data);
                    $scope.count++;
                    localStorage.setItem('player_count_' + $scope.ContestGUID, $scope.count);
                    $scope.$apply();
                });
                socket.on('AuctionBidSuccess', function (data) {
                    var NewBidInfo = data.responseData.Data;
                    console.log("New bid" + NewBidInfo);
                    if ($scope.BidUserInfo.UserGUID != NewBidInfo.UserGUID && $scope.BidUserInfo.BidCredit != NewBidInfo.BidCredit) {
                        $scope.bidDetail(NewBidInfo, 15);
                    }
                    $scope.$apply();
                });

                socket.on('auctionJoinedContestUser', function (data) {
                    if (!localStorage.hasOwnProperty('Hold_datetime_' + $scope.ContestGUID) || data.IsHold == 'No') {
                        $scope.ContestUserList = data.auctionJoinedContestUser.Data.Records;
                        for (var i in $scope.ContestUserList) {
                            if ($scope.ContestUserList[i].UserGUID == data.UserGUID && data.IsHold == 'Yes') {
                                $scope.UserHoldTime = $scope.ContestUserList[i].AuctionTimeBank;
                                $scope.BidDisabled = true;
                                $scope.TotalUserAvailableBudget = $scope.ContestUserList[i].AuctionBudget;
                            } else if ($scope.ContestUserList[i].UserGUID == $scope.user_details.UserGUID && data.IsHold == 'Yes') {
                                $scope.UserHoldTime1 = $scope.ContestUserList[i].AuctionTimeBank;
                                $scope.TotalUserAvailableBudget = $scope.ContestUserList[i].AuctionBudget;
                            } else if ($scope.ContestUserList[i].UserGUID == $scope.user_details.UserGUID) {
                                $scope.UserHoldTime = $scope.ContestUserList[i].AuctionTimeBank;
                                $scope.UserHoldTime1 = $scope.ContestUserList[i].AuctionTimeBank;
                                $scope.TotalUserAvailableBudget = $scope.ContestUserList[i].AuctionBudget;
                            }
                        }
                        if (data.IsHold == 'Yes' && $scope.TimerHoldeUserGUID != data.UserGUID) {
                            if (!localStorage.hasOwnProperty('Hold_datetime_' + $scope.ContestGUID)) {
                                $scope.TimerHoldeUserGUID = data.UserGUID;
                                localStorage.setItem('TimerHoldUserGUID_' + $scope.ContestGUID, $scope.TimerHoldeUserGUID);
                                localStorage.setItem('Hold_datetime_' + $scope.ContestGUID, new Date());
                                $scope.onHoldTimeOut();
                            }
                        } else if (data.IsHold == 'No') {
                            if (localStorage.hasOwnProperty('Hold_datetime_' + $scope.ContestGUID)) {
                                $scope.TimerHoldeUserGUID = '';
                                localStorage.removeItem('TimerHoldUserGUID_' + $scope.ContestGUID);
                                localStorage.removeItem('Hold_datetime_' + $scope.ContestGUID);
                                localStorage.removeItem('Hold_time_' + $scope.ContestGUID);
                                $scope.holdTimer_stop();
                            }
                        }
                    }
                    $scope.$apply();
                });
                $scope.IsBreak = 'No';
                socket.on('breakTimeStart', function (data) {
                    $scope.IsBreak = 'Yes';
                    if ($scope.IsBreak == 'Yes' && $scope.BreakTime == 0) {
                        $scope.BreakTime = data.breakTime.Data.BreakTime;
                        localStorage.setItem('Break_time_' + $scope.ContestGUID, $scope.BreakTime);
                        localStorage.setItem('Break_datetime_' + $scope.ContestGUID, new Date());
                        $scope.BidDisabled = true;
                        $scope.BreakTimer_stop();
                        $scope.onBreakTimeout();
                        $scope.timer_stop();
                    }
                });
                socket.on('breakTimeEnd', function (data) {
                    $scope.IsBreak = 'No';
                    $scope.BidDisabled = false;
                    $scope.BreakTime = 0;
                    $scope.BreakTimer_stop();
                    localStorage.removeItem('Break_time_' + $scope.ContestGUID);
                    localStorage.removeItem('Break_datetime_' + $scope.ContestGUID);
                });

            });

            $scope.holdTimer = function () {
                var UserInfo = {
                    UserGUID: $scope.user_details.UserGUID,
                    ContestGUID: $scope.ContestGUID,
                    SeriesGUID: $scope.SeriesGUID,
                    Time: $scope.UserHoldTime,
                    IsHold: 'Yes'
                }
                if (parseInt($scope.UserHoldTime) == 0) {
                    $scope.errorMessageShow("Sorry, You can't hold timer because your holding time is 0.");
                    return true;
                } else {
                    socket.emit('TimerHold', UserInfo);
                }
            }
            $scope.resumeTimer = function () {
                var UserInfo = {
                    UserGUID: $scope.user_details.UserGUID,
                    ContestGUID: $scope.ContestGUID,
                    SeriesGUID: $scope.SeriesGUID,
                    Time: $scope.UserHoldTime,
                    IsHold: 'No'
                }
                socket.emit('TimerHold', UserInfo);
            }
            /**
             * Bid timer
             */
            var mytimeout = '';
            $scope.onTimeout = function () {
                if ($scope.counter > 0) {
                    $scope.counter--;
                    mytimeout = $timeout($scope.onTimeout, 1000);
                    localStorage.setItem('Timer_' + $scope.ContestGUID, $scope.counter);
                }
                if ($scope.counter == 0) {
                    $scope.timer_stop();
                }
            }

            $scope.timer_stop = function () {
                $timeout.cancel(mytimeout);
            }
            $scope.timer_reset = function (time) {
                delete mytimeout;
                if (mytimeout != '') {
                    $scope.timer_stop();
                }
                $scope.counter = time;
                $timeout($scope.onTimeout($scope), 1000);
            }

            var holdTimeOut = '';
            $scope.onHoldTimeOut = function () {
                $scope.timer_stop();
                if ($scope.TimerHoldeUserGUID == $scope.user_details.UserGUID) {
                    $scope.TimerStatus = 'resume';
                }
                if ($scope.UserHoldTime > 0) {
                    localStorage.setItem('Hold_time_' + $scope.ContestGUID, $scope.UserHoldTime);
                    $scope.UserHoldTime--;
                    holdTimeOut = $timeout($scope.onHoldTimeOut, 1000);
                }
                if ($scope.UserHoldTime == 0) {
                    $timeout.cancel(holdTimeOut);
                }
            }

            $scope.holdTimer_stop = function () {
                console.log("Hold time out");
                $scope.TimerStatus = 'hold';
                $timeout.cancel(holdTimeOut);
                $scope.BidDisabled = false;
                $scope.TimerHoldeUserGUID = '';
                $scope.onTimeout();
                delete holdTimeOut;
            }

            $scope.getTimeInSecond = function (date) {
                var date1 = new Date();
                var date2 = new Date(date);
                var diffInSeconds = Math.abs(date1 - date2) / 1000;
                var seconds = parseInt(diffInSeconds % 60);
                return seconds;
            }

            /**
             * Break time timer
             */
            var breakTimeOut = ''
            $scope.onBreakTimeout = function () {
                if ($scope.BreakTime > 0) {
                    $scope.BreakTime--;
                    breakTimeOut = $timeout($scope.onBreakTimeout, 1000);
                    localStorage.setItem('Break_time_' + $scope.ContestGUID, $scope.BreakTime);
                }
                if ($scope.BreakTime == 0) {
                    $scope.BreakTimer_stop();
                }
            }

            $scope.BreakTimer_stop = function () {
                delete breakTimeOut;
                $timeout.cancel(breakTimeOut);
                localStorage.removeItem('Break_time_' + $scope.ContestGUID);
                $timeout($scope.onTimeout($scope), 1000);
            }


            $scope.auctionBidTimeManagement = function () {
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.ContestGUID = $scope.ContestGUID;
                appDB
                        .callPostForm('auctionDrafts/auctionBidTimeManagement', $data)
                        .then(
                                function successCallback(data) {
                                    if (data.ResponseCode == 200) {
                                        $scope.setRunningValues(data.Data[0]);
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.data.listLoading = false;
                                    // $scope.checkResponseCode(data)
                                });

            }
            $scope.setRunningValues = function (liveAuctionInfo) {
                if (localStorage.hasOwnProperty('Timer_' + $scope.ContestGUID)) {
                    $scope.counter = (localStorage.getItem('Timer_' + $scope.ContestGUID) != 0) ? localStorage.getItem('Timer_' + $scope.ContestGUID) : 15;
                    $scope.onTimeout();
                }

                if (liveAuctionInfo.AuctionIsBreakTimeStatus == 'Yes' && liveAuctionInfo.AuctionTimeBreakAvailable == 'No') {
                    $scope.counter = liveAuctionInfo.TimeDifference;
                    if (liveAuctionInfo.TimeDifference >= 14) {
                        localStorage.setItem('player_count_' + $scope.ContestGUID, 2);
                    } else {
                        localStorage.setItem('player_count_' + $scope.ContestGUID, 1);
                    }
                    if (liveAuctionInfo.BreakTimeInSec < 300) {
                        $scope.BreakTime = 300 - liveAuctionInfo.BreakTimeInSec;
                        $scope.onBreakTimeout();
                        $scope.BidDisabled = true;
                    } else {
                        $scope.BidDisabled = false;
                        $scope.onTimeout();
                    }
                } else if (localStorage.hasOwnProperty('Hold_datetime_' + $scope.ContestGUID)) {
                    var HoldTime = localStorage.getItem('Hold_time_' + $scope.ContestGUID);
                    var HoldDateTime = localStorage.getItem('Hold_datetime_' + $scope.ContestGUID);
                    var second = $scope.getTimeInSecond(HoldDateTime);
                    $scope.TimerHoldeUserGUID = localStorage.getItem('TimerHoldUserGUID_' + $scope.ContestGUID);
                    $scope.counter = localStorage.getItem('Timer_' + $scope.ContestGUID);
                    if (second < HoldTime) {
                        if (localStorage.getItem('TimerHoldUserGUID_' + $scope.ContestGUID) == $scope.user_details.UserGUID) {
                            $scope.UserHoldTime = HoldTime - second;
                            $scope.BidDisabled = true;
                            $scope.onHoldTimeOut();

                        } else {
                            $scope.UserHoldTime = HoldTime - second;
                            $scope.BidDisabled = true;
                            $scope.onHoldTimeOut();
                        }
                    } else {
                        $scope.BidDisabled = false;
                        $scope.TimerHoldeUserGUID = '';
                        // $scope.onTimeout();
                        localStorage.removeItem('TimerHoldUserGUID_' + $scope.ContestGUID);
                        localStorage.removeItem('Hold_datetime_' + $scope.ContestGUID);
                        localStorage.removeItem('Hold_time_' + $scope.ContestGUID);
                    }
                } else {
                    $scope.BidDisabled = false;
                }
            }
            /**
             * Bid code start here
             */
            $scope.BidUserInfo = {};
            $scope.bidDetail = function (CurrentBidInfo, Time) {
                $scope.BidUserName = CurrentBidInfo.FullName;
                $scope.BidAmount = $rootScope.numDifferentiation(CurrentBidInfo.BidCredit);
                if (CurrentBidInfo.BidCredit == 0) {
                    $scope.BidValue = 0;
                    $scope.BidCurrency = 'lac';
                } else if (CurrentBidInfo.BidCredit >= 10000000) {
                    $scope.BidValue = (CurrentBidInfo.BidCredit / 10000000);
                    $scope.BidCurrency = 'cr';
                } else if (CurrentBidInfo.BidCredit >= 100000) {
                    $scope.BidValue = (CurrentBidInfo.BidCredit / 100000);
                    $scope.BidCurrency = 'lac';
                } else if (CurrentBidInfo.BidCredit == 1000000000) {
                    $scope.BidValue = (CurrentBidInfo.BidCredit / 10000000);
                    $scope.BidCurrency = 'cr';
                }
                // socket.emit('AuctionBid',CurrentBidInfo);
                $scope.BidUserInfo = CurrentBidInfo;
                localStorage.setItem('Timer_datetime' + $scope.ContestGUID, new Date());
                localStorage.setItem('BidUserInfo_' + $scope.ContestGUID, JSON.stringify($scope.BidUserInfo));
                $scope.setNextBidAmount();
                $scope.timer_reset(Time);
            }

            $scope.setNextBidAmount = function () {
                if ($scope.BidCurrency == 'lac') {
                    if ($scope.BidValue < 10) {
                        value = ($scope.BidValue + 1) * 100000;
                    } else if ($scope.BidValue >= 10 && $scope.BidValue < 100) {
                        value = ($scope.BidValue + 10) * 100000;
                        if ($scope.BidValue == 100) {
                            $scope.BidValue = 1;
                            $scope.BidCurrency = 'cr';
                        }
                    }
                    $scope.bid = value;
                } else if ($scope.BidCurrency == 'cr' && $scope.BidValue < 100) {
                    if ($scope.BidValue < 10) {
                        value = ($scope.BidValue + 1) * 10000000;
                    } else if ($scope.BidValue >= 10 && $scope.BidValue < 100) {
                        value = ($scope.BidValue + 10) * 10000000;
                    }
                    $scope.bid = value;
                } else if ($scope.BidCurrency == 'cr' && $scope.BidValue == 100) {
                    // $scope.errorMessageShow("You can't add more than 100 Crs. bid.");
                } else {
                    $scope.BidCurrency = 'lac'
                    $scope.BidValue = 1;
                    value = $scope.BidValue * 100000;
                    $scope.bid = value;
                }
                $scope.FullBidAmount = value;
                $('.selectpicker').val(value);
                $('.selectpicker').selectpicker('render');
                $('.selectpicker').selectpicker('refresh');
            }
            $scope.checkBidAmountInDropDown = function (val) {
                if (Number($scope.FullBidAmount) <= Number(val)) {
                    return true;
                } else {
                    return false;
                }
            }
            $scope.raiseBid = function (value) {
                var value = value;
                if ($scope.BidUserInfo.UserGUID == $scope.user_details.UserGUID) {
                    return true;
                }
                if (!$scope.checkBidAmount(value)) {
                    $scope.bid = $scope.FullBidAmount;
                    $('.selectpicker').val(Number($scope.FullBidAmount));
                    $('.selectpicker').selectpicker('render');
                    $scope.errorMessageShow("Sorry, You can't add bid less than current bid.");
                    return true;
                } else if (parseInt($scope.TotalUserAvailableBudget) < parseInt(value)) {
                    $scope.bid = $scope.FullBidAmount;
                    $('.selectpicker').val(Number($scope.FullBidAmount));
                    $('.selectpicker').selectpicker('render');
                    $scope.errorMessageShow("Sorry, You can't add bid because your budget is less than current bid.");
                    return true;
                } else {
                    if ($scope.checkFiveTimeBid(value)) {
                        var confirmStatus = confirm('Are you sure,You want to add ' + $rootScope.numDifferentiation(value) + ' bid?');
                        if (confirmStatus) {
                            var BidData = {
                                SessionKey: $localStorage.user_details.SessionKey,
                                UserGUID: $scope.user_details.UserGUID,
                                ContestGUID: $scope.ContestGUID,
                                SeriesGUID: $scope.SeriesGUID,
                                BidCredit: value,
                                PlayerGUID: $scope.livePlayerInfo.PlayerGUID,
                                FullName: $localStorage.user_details.FullName
                            }
                            // $scope.bidDetail(BidData);
                            socket.emit('AuctionBid', BidData);
                        } else {
                            $('.selectpicker').val(Number($scope.FullBidAmount));
                            $('.selectpicker').selectpicker('render');
                            $('.selectpicker').selectpicker('refresh');
                        }
                    } else {
                        var BidData = {
                            SessionKey: $localStorage.user_details.SessionKey,
                            UserGUID: $scope.user_details.UserGUID,
                            ContestGUID: $scope.ContestGUID,
                            SeriesGUID: $scope.SeriesGUID,
                            BidCredit: value,
                            PlayerGUID: $scope.livePlayerInfo.PlayerGUID,
                            FullName: $localStorage.user_details.FullName
                        }
                        // $scope.bidDetail(BidData);
                        socket.emit('AuctionBid', BidData);
                    }
                }
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

            $scope.checkBidAmount = function (amount) {
                if (Number(amount) < Number($scope.FullBidAmount)) {
                    return false;
                } else {
                    return true;
                }
            }

            /**
             * Confirmation when user add 5 times bid amount
             */
            $scope.checkFiveTimeBid = function (amount) {

                if ($scope.FullBidAmount == 0) {
                    return false;
                }
                var FiveTimeAmount = $scope.FullBidAmount * 5;
                if (Number(amount) >= Number(FiveTimeAmount)) {
                    return true;
                } else {
                    return false;
                }
            }

            /**
             * Get bid player deta
             */
            $scope.getBidPlayer = function () {
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.ContestGUID = $scope.ContestGUID;
                $data.Params = 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit';
                appDB
                        .callPostForm('auctionDrafts/getPlayerBid', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.selectPlayer(data.Data);
                                        $scope.BidAmount = 0;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }
            /**
             * End here
             */
            $rootScope.players = [];
            $scope.AllPlayers = [];
            $scope.seriesPlayers = function (MySquardStatus) {
                $scope.data.listLoading = true;
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.ContestGUID = $scope.ContestGUID;
                // $data.PlayerBidStatus = 'Yes';
                $data.Params = 'BidSoldCredit,PlayerStatus,PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit';
                appDB
                        .callPostForm('auctionDrafts/getPlayers', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;

                                    if ($scope.checkResponseCode(data) && data.Data.TotalRecords > 0) {
                                        $rootScope.players = data.Data.Records;
                                        $scope.AllPlayers = data.Data.Records;
                                        $scope.AllPlayers.forEach((element, index) => {
                                            element.IsAdded = false;
                                            element.BidCredit = '';
                                            element.PlayerPosition = "Player";
                                        });
                                        $scope.getMyPreSquad();
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.data.listLoading = false;
                                    $scope.checkResponseCode(data);
                                });
            }
            $scope.livePlayerInfo = {};
            $scope.selectPlayer = function (player) {
                player.PlayerBattingStyle = (player.PlayerBattingStyle) ? player.PlayerBattingStyle : '-';
                player.PlayerBowlingStyle = (player.PlayerBowlingStyle) ? player.PlayerBowlingStyle : '-';
                player.Matches = '0';
                player.Runs = '0';
                player.Hundreds = '0';
                player.Fifties = '0';
                player.Average = '0';
                player.StrikeRate = '0';
                if (player.PlayerBattingStats) {
                    if (player.PlayerBattingStats.T20i) {
                        player.Matches = (player.PlayerBattingStats.T20i.Matches) ? player.PlayerBattingStats.T20i.Matches : '0';
                        player.Runs = (player.PlayerBattingStats.T20i.Runs) ? player.PlayerBattingStats.T20i.Runs : '0';
                        player.Hundreds = (player.PlayerBattingStats.T20i.Hundreds) ? player.PlayerBattingStats.T20i.Hundreds : '0';
                        player.Fifties = (player.PlayerBattingStats.T20i.Fifties) ? player.PlayerBattingStats.T20i.Fifties : '0';
                        player.Average = (player.PlayerBattingStats.T20i.Average) ? player.PlayerBattingStats.T20i.Average : '0';
                        player.StrikeRate = (player.PlayerBattingStats.T20i.StrikeRate) ? player.PlayerBattingStats.T20i.StrikeRate : '0';
                    } else if (player.PlayerBattingStats.T20) {
                        player.Matches = (player.PlayerBattingStats.T20.Matches) ? player.PlayerBattingStats.T20.Matches : '0';
                        player.Runs = (player.PlayerBattingStats.T20.Runs) ? player.PlayerBattingStats.T20.Runs : '0';
                        player.Hundreds = (player.PlayerBattingStats.T20.Hundreds) ? player.PlayerBattingStats.T20.Hundreds : '0';
                        player.Fifties = (player.PlayerBattingStats.T20.Fifties) ? player.PlayerBattingStats.T20.Fifties : '0';
                        player.Average = (player.PlayerBattingStats.T20.Average) ? player.PlayerBattingStats.T20.Average : '0';
                        player.StrikeRate = (player.PlayerBattingStats.T20.StrikeRate) ? player.PlayerBattingStats.T20.StrikeRate : '0';
                    } else if (player.PlayerBattingStats.ODI) {
                        player.Matches = (player.PlayerBattingStats.ODI.Matches) ? player.PlayerBattingStats.ODI.Matches : '0';
                        player.Runs = (player.PlayerBattingStats.ODI.Runs) ? player.PlayerBattingStats.ODI.Runs : '0';
                        player.Hundreds = (player.PlayerBattingStats.ODI.Hundreds) ? player.PlayerBattingStats.ODI.Hundreds : '0';
                        player.Fifties = (player.PlayerBattingStats.ODI.Fifties) ? player.PlayerBattingStats.ODI.Fifties : '0';
                        player.Average = (player.PlayerBattingStats.ODI.Average) ? player.PlayerBattingStats.ODI.Average : '0';
                        player.StrikeRate = (player.PlayerBattingStats.ODI.StrikeRate) ? player.PlayerBattingStats.ODI.StrikeRate : '0';
                    }
                }
                player.Wickets = '0';
                player.BowlAverage = '0';
                player.Economy = '-';
                if (player.PlayerBowlingStats) {
                    if (player.PlayerBowlingStats.T20i) {
                        player.Wickets = (player.PlayerBowlingStats.T20i.Wickets) ? player.PlayerBowlingStats.T20i.Wickets : '0';
                        player.BowlAverage = (player.PlayerBowlingStats.T20i.Average) ? player.PlayerBowlingStats.T20i.Average : '0';
                        player.Economy = (player.PlayerBowlingStats.T20i.Economy) ? player.PlayerBowlingStats.T20i.Economy : '-';
                    } else if (player.PlayerBowlingStats.T20) {
                        player.Wickets = (player.PlayerBowlingStats.T20.Wickets) ? player.PlayerBowlingStats.T20.Wickets : '0';
                        player.BowlAverage = (player.PlayerBowlingStats.T20.Average) ? player.PlayerBowlingStats.T20.Average : "0";
                        player.Economy = (player.PlayerBowlingStats.T20.Economy) ? player.PlayerBowlingStats.T20.Economy : '-';
                    } else if (player.PlayerBowlingStats.ODI) {
                        player.Wickets = (player.PlayerBowlingStats.ODI.Wickets) ? player.PlayerBowlingStats.ODI.Wickets : '0';
                        player.BowlAverage = (player.PlayerBowlingStats.ODI.Average) ? player.PlayerBowlingStats.ODI.Average : '0';
                        player.Economy = (player.PlayerBowlingStats.ODI.Economy) ? player.PlayerBowlingStats.ODI.Economy : '-';
                    }
                }
                $scope.livePlayerInfo = player;
            }

            $scope.openSquardPlayerModal = function () {
                $scope.searchSquadPlayer = "";
                $rootScope.TotalAssistantPlayers = 0;
                $scope.AllPlayers.forEach((element, index) => {
                    element.IsAdded = false;
                    element.BidCredit = '';
                });

                angular.forEach($scope.Squadplayers, function (value, key) {
                    $rootScope.TotalAssistantPlayers++;
                    for (i in $scope.AllPlayers) {
                        if ($scope.AllPlayers[i].PlayerGUID == value.PlayerGUID) {
                            $scope.AllPlayers[i].IsAdded = true;
                            $scope.AllPlayers[i].BidCredit = value.BidCredit;
                        }
                    }
                });
                $scope.openPopup('select_pre_draft_player');
            }
            /**
             * Pre-Draft Team Player
             */
            $scope.Squadplayers = [];
            $scope.getMyPreSquad = function () {
                $scope.data.listLoading = true;
                $rootScope.TotalAssistantPlayers = 0;
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.ContestGUID = $scope.ContestGUID;
                $data.MySquadPlayer = 'Yes';
                // $data.IsAssistant = 'Yes';
                $data.IsPreTeam = 'Yes';
                $data.Params = 'PlayerID,PlayerRole,PlayerPic,BidCredit,UserTeamGUID,UserTeamName,IsAssistant';
                appDB
                        .callPostForm('auctionDrafts/getPlayers', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if ($scope.checkResponseCode(data) && data.Data.TotalRecords > 0) {
                                        $scope.Squadplayers = data.Data.Records;
                                        $scope.AssistantStatus = (data.Data.IsAssistant == 'Yes') ? true : false;
                                        $scope.UserTeamGUID = data.Data.UserTeamGUID;
                                        $scope.UserTeamName = data.Data.UserTeamName;
                                        $scope.edit = true;
                                        angular.forEach($scope.Squadplayers, function (value, key) {
                                            $rootScope.TotalAssistantPlayers++;
                                            for (i in $scope.AllPlayers) {
                                                if ($scope.AllPlayers[i].PlayerGUID == value.PlayerGUID) {
                                                    $scope.AllPlayers[i].IsAdded = true;
                                                    $scope.AllPlayers[i].BidCredit = value.BidCredit;
                                                }
                                            }
                                        });
                                    } else {
                                        $scope.UserTeamGUID = '';
                                        $scope.UserTeamName = '';
                                        $scope.AssistantStatus = false;
                                        $rootScope.TotalAssistantPlayers = 0;
                                        $rootScope.searchPlayer = '';
                                        $scope.edit = false;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.data.listLoading = false;
                                    $scope.checkResponseCode(data);
                                });
            }
            /**
             * Add/remove pre team player
             */
            $rootScope.addRemovePlayer = function (IsAdded, playerInfo) {
                if ($rootScope.TotalAssistantPlayers < $rootScope.TotalPlayers) {
                    var bidCredit = playerInfo.BidCredit;
                    if (bidCredit == '' && IsAdded == false) {
                        $scope.errorMessageShow('Please enter player amount greater than 0.');
                    } else {
                        if (!IsAdded) {
                            for (var i in $scope.AllPlayers) {
                                if ($scope.AllPlayers[i].PlayerGUID == playerInfo.PlayerGUID) {
                                    $scope.AllPlayers[i].IsAdded = true;
                                    $scope.AllPlayers[i].BidCredit = bidCredit;
                                }
                            }
                            $rootScope.TotalAssistantPlayers++;
                        } else {
                            for (var i in $scope.AllPlayers) {
                                if ($scope.AllPlayers[i].PlayerGUID == playerInfo.PlayerGUID) {
                                    $scope.AllPlayers[i].IsAdded = false;
                                    $scope.AllPlayers[i].BidCredit = '';
                                }
                            }
                            $rootScope.TotalAssistantPlayers--;
                        }

                    }
                } else {
                    if (IsAdded) {
                        for (i in $scope.AllPlayers) {
                            if ($scope.AllPlayers[i].PlayerGUID == playerInfo.PlayerGUID) {
                                $scope.AllPlayers[i].IsAdded = false;
                                $scope.AllPlayers[i].BidCredit = '';
                            }
                        }
                        $rootScope.TotalAssistantPlayers--;
                    } else {
                        $scope.errorMessageShow('Sorry,you can not select more than ' + $scope.TotalPlayers + ' players.');
                    }
                }
            }

            /**
             * save/update pre user team 
             */
            $rootScope.SaveTeam = function () {
                var selected_players = [];
                for (var i in $scope.AllPlayers) {
                    if ($scope.AllPlayers[i].IsAdded) {
                        selected_players.push({
                            'PlayerGUID': $scope.AllPlayers[i].PlayerGUID,
                            'PlayerPosition': $scope.AllPlayers[i].PlayerPosition,
                            'PlayerName': $scope.AllPlayers[i].PlayerName,
                            'PlayerID': $scope.AllPlayers[i].PlayerID,
                            'BidCredit': $scope.AllPlayers[i].BidCredit
                        });
                    }
                }
                $scope.data.listLoading = true;
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //   Series GUID
                $data.SessionKey = $localStorage.user_details.SessionKey; //   User session key
                $data.ContestGUID = $scope.ContestGUID;
                $data.UserTeamPlayers = selected_players; //   User selected players
                $data.UserTeamType = 'Auction'; //   User team name
                $data.IsPreTeam = 'Yes';
                if ($scope.edit == true && $scope.UserTeamGUID != '') {
                    var $url = 'auctionDrafts/editUserTeam';
                    $data.UserTeamGUID = $scope.UserTeamGUID;
                    $data.UserTeamName = $scope.UserTeamName; //   User team name
                } else {
                    var $url = 'auctionDrafts/addUserTeam';
                }

                $http.post($scope.env.api_url + $url, $.param($data), contentType).then(function (response) {
                    var response = response.data;
                    $scope.data.listLoading = false;
                    if ($scope.checkResponseCode(response)) {
                        $scope.successMessageShow(response.Message);
                        $scope.closePopup('select_pre_draft_player');
                        $scope.getMyPreSquad();
                    }
                });
            }
            $scope.ContestUserList = [];
            /**
             * Get auction user list
             */
            $scope.getAuctionUsers = function () {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.SeriesGUID; //Series GUID
                $data.ContestGUID = $scope.ContestGUID; //Contest GUID
                $data.Params = 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus';
                appDB
                        .callPostForm('auctionDrafts/getJoinedContestsUsers', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.ContestUserList = data.Data.Records
                                        for (var i in $scope.ContestUserList) {
                                            if ($scope.ContestUserList[i].UserGUID == $scope.user_details.UserGUID && !localStorage.hasOwnProperty('Hold_datetime_' + $scope.ContestGUID)) {
                                                $scope.UserHoldTime = $scope.ContestUserList[i].AuctionTimeBank;
                                                $scope.UserHoldTime1 = $scope.ContestUserList[i].AuctionTimeBank;
                                                $scope.TotalUserAvailableBudget = $scope.ContestUserList[i].AuctionBudget;
                                            }
                                            // $scope.ContestUserList.push(data.Data.Records[i]);
                                        }
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }
            /**
             * Change Pre assistant team status
             */
            $scope.changeAssistantStatus = function () {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.SeriesGUID; //Series GUID
                $data.ContestGUID = $scope.ContestGUID; //Contest GUID
                $data.UserTeamGUID = $scope.UserTeamGUID;
                $data.IsAssistant = ($scope.AssistantStatus) ? 'Yes' : 'No';
                appDB
                        .callPostForm('auctionDrafts/assistantTeamOnOff', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {

                                    }

                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data)
                                });
            }

            /**
             * Get contest info
             */
            $scope.ContestInfo = [];
            $scope.getContest = function () {
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.SeriesGUID; //Series GUID
                $data.ContestGUID = $scope.ContestGUID; //Contest GUID
                $data.Params = 'LeagueJoinDateTime,Status,AuctionStatus,LeagueJoinDateTimeUTC'
                appDB
                        .callPostForm('auctionDrafts/getContest', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.ContestInfo = data.Data;
                                        if ($scope.ContestInfo.AuctionStatus == 'Completed' || $scope.ContestInfo.AuctionStatus == 'Pending') {
                                            $scope.BidDisabled = true;
                                        }
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }

            /**
             * Get my squad 
             */
            $scope.MySquadPlayers = [];
            $scope.getMySquad = function () {
                $scope.isLoading = false;
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.ContestGUID = $scope.ContestGUID;
                $data.MySquadPlayer = 'Yes';
                $data.IsAssistant = 'No';
                $data.IsPreTeam = 'No';
                $data.Params = 'PlayerPosition,AuctionTopPlayerSubmitted,PlayerID,PlayerRole,PlayerPic,BidCredit,UserTeamGUID,UserTeamName,IsAssistant';
                appDB
                        .callPostForm('auctionDrafts/getPlayers', $data, )
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data) && data.Data.TotalRecords > 0) {
                                        $scope.MySquadPlayers = data.Data.Records;
                                        $scope.AuctionTopPlayerSubmitted = data.Data.AuctionTopPlayerSubmitted;
                                        $scope.PostTeamGUID = data.Data.UserTeamGUID;
                                        for (var i in $scope.MySquadPlayers) {
                                            $scope.MySquadPlayers[i].IsAdded = false;
                                        }
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }

            /**
             * Get bid history
             */
            $scope.pageNo = 1;
            $scope.pageSize = 15;
            $rootScope.bidHistory = [];
            $rootScope.getBidHistory = function (status) {
                if (status) {
                    $scope.pageNo = 1;
                    $rootScope.bidHistory = [];
                    $scope.LoadMoreFlag = true;
                    $scope.data.noRecords = false;
                }
                if ($scope.LoadMoreFlag == false || $scope.data.noRecords == true) {
                    return false
                }
                $scope.data.listLoading = true;
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //  Series Id
                $data.ContestGUID = $scope.ContestGUID;
                $data.Params = 'BidCredit,DateTime,FirstName,ProfilePic';
                $data.PageNo = $scope.pageNo;
                $data.PageSize = $scope.pageSize;
                $data.PlayerGUID = $scope.livePlayerInfo.PlayerGUID;
                appDB
                        .callPostForm('auctionDrafts/getContestBidHistory', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if ($scope.checkResponseCode(data)) {
                                        if (data.Data.hasOwnProperty('Records') && data.Data.Records != '') {
                                            $scope.LoadMoreFlag = true;
                                            for (var i in data.Data.Records) {
                                                $rootScope.bidHistory.push(data.Data.Records[i]);
                                            }
                                            $scope.pageNo++;
                                        } else {
                                            $scope.LoadMoreFlag = false;
                                        }
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.data.listLoading = false;
                                    $scope.checkResponseCode(data);
                                });
            }
            /**
             * Submit Final 16 player
             */
            $rootScope.FinalAuctionPlayers = [];
            $scope.FinalAuctionPlayersCount = 0;
            $scope.addRemoveAuctionPlayer = function (IsAdded, playerInfo) {
                if ($scope.FinalAuctionPlayersCount < 16) {
                    if (!IsAdded) {
                        for (var i in $scope.MySquadPlayers) {
                            if ($scope.MySquadPlayers[i].PlayerGUID === playerInfo.PlayerGUID) {
                                $scope.MySquadPlayers[i].IsAdded = true;
                                $rootScope.FinalAuctionPlayers.push({
                                    'PlayerGUID': $scope.MySquadPlayers[i].PlayerGUID,
                                    'PlayerPosition': 'Player',
                                    'PlayerName': $scope.MySquadPlayers[i].PlayerName,
                                    'PlayerID': $scope.MySquadPlayers[i].PlayerID,
                                    'BidCredit': $scope.MySquadPlayers[i].BidCredit
                                });
                            }
                        }
                        $scope.FinalAuctionPlayersCount++;
                    } else {
                        for (var i in $scope.MySquadPlayers) {
                            if ($scope.MySquadPlayers[i].PlayerGUID === playerInfo.PlayerGUID) {
                                $scope.MySquadPlayers[i].IsAdded = false;
                            }
                        }
                        angular.forEach($rootScope.FinalAuctionPlayers, function (val, key) {
                            if (val.PlayerGUID == playerInfo.PlayerGUID) {
                                $rootScope.FinalAuctionPlayers.splice(key, 1);
                            }
                        });
                        $scope.FinalAuctionPlayersCount--;
                    }
                } else {
                    if (IsAdded) {
                        for (i in $scope.MySquadPlayers) {
                            if ($scope.MySquadPlayers[i].PlayerGUID === playerInfo.PlayerGUID) {
                                $scope.MySquadPlayers[i].IsAdded = false;
                            }
                        }
                        $scope.FinalAuctionPlayersCount--;
                    } else {
                        $scope.errorMessageShow('Sorry,you can not select more than 16 players.');
                    }
                }
            }

            /*
             Description : To Select captain
             */

            $rootScope.selectCaptain = function (PlayerGUID) {
                for (var i = 0; i < $rootScope.FinalAuctionPlayers.length; i++) {
                    if ($rootScope.FinalAuctionPlayers[i].PlayerGUID == PlayerGUID) {

                        if ($rootScope.FinalAuctionPlayers[i].PlayerPosition != 'Captain') {
                            $rootScope.FinalAuctionPlayers[i].PlayerPosition = 'Captain';
                            $rootScope.Captain = $rootScope.FinalAuctionPlayers[i].PlayerGUID;
                        }
                    } else {
                        if ($rootScope.FinalAuctionPlayers[i].PlayerPosition != 'ViceCaptain') {
                            $rootScope.FinalAuctionPlayers[i].PlayerPosition = 'Player';
                        }
                    }
                }
                $rootScope.FinalAuctionPlayers = $rootScope.FinalAuctionPlayers;
            }

            /*
             Description : To Select vice captain
             */

            $rootScope.selectViceCaptain = function (PlayerGUID) {

                for (var i = 0; i < $rootScope.FinalAuctionPlayers.length; i++) {
                    if ($rootScope.FinalAuctionPlayers[i].PlayerGUID == PlayerGUID) {

                        if ($rootScope.FinalAuctionPlayers[i].PlayerPosition != 'ViceCaptain') {
                            $rootScope.FinalAuctionPlayers[i].PlayerPosition = 'ViceCaptain';

                            $rootScope.ViceCaptain = $rootScope.FinalAuctionPlayers[i].PlayerGUID;
                        }
                    } else {
                        if ($rootScope.FinalAuctionPlayers[i].PlayerPosition != 'Captain') {
                            $rootScope.FinalAuctionPlayers[i].PlayerPosition = 'Player';
                        }
                    }
                }
            }

            $rootScope.CreateAuctionTeam = function () {
                var $data = {};
                $data.SeriesGUID = $scope.SeriesGUID; //   Series GUID
                $data.SessionKey = $localStorage.user_details.SessionKey; //   User session key
                $data.UserTeamPlayers = $rootScope.FinalAuctionPlayers; //   User selected players
                $data.UserTeamGUID = $scope.PostTeamGUID;
                var $url = 'auctionDrafts/auctionTeamPlayersSubmit';
                $http.post($scope.env.api_url + $url, $.param($data), contentType).then(function (response) {
                    var response = response.data;
                    $scope.data.listLoading = false;
                    if ($scope.checkResponseCode(response)) {
                        $scope.successMessageShow(response.Message);
                        $scope.closePopup('selectCaptainViceCaptainModal');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                });
            }
        } else {
            window.location.href = base_url;
        }

    }]);

