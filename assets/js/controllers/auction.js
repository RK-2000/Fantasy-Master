app.controller('auctionController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', '$timeout', '$filter','$http', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, $timeout, $filter,$http) {
        $scope.env = environment;
        $scope.data.pageSize = 15;
        $scope.data.pageNo = 1;
        $scope.coreLogic = Mobiweb.helpers;
        $scope.ContestsTotalCount = 0;
        $scope.UserTeamsTotalCount = 0;
        $scope.UserJoinedContestTotalCount = 0;

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.user_details = $localStorage.user_details;
            $scope.selected_series = {};
            $rootScope.ContestGUID = '';
            
            /*To manage Tabs*/
            $scope.activeTab = 'normal';
            $scope.gotoTab = function (tab) {
                $scope.activeTab = tab;
                if(tab == 'normal'){
                    $scope.getContests(true);
                }else if(tab == 'joined'){
                    $scope.JoinedContest(true);
                }
            }

            /*Function to get all series*/
            $scope.seriesList = [];
            $scope.Series = function () {
                $scope.silder_visible = false;
                $scope.data.listLoading = false;
                var $data = {};
                $data.Params = 'SeriesName,SeriesGUID,StatusID,SeriesStartDate,Status,SeriesID';
                $data.OrderBy = 'SeriesStartDate';
                $data.Sequence = 'ASC';
                $data.StatusID = 2;
                $data.DraftAuctionPlay = 'Yes';
                // $data.SeriesStartDate = $filter('date')(new Date(), 'yyyy-MM-dd');
                appDB.callPostForm('sports/getSeries', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.seriesList = data.Data;
                                        $scope.selected_series = data.Data.Records[0];
                                        $scope.silder_visible = true;
                                        $scope.getContests(true);
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }


            $scope.selectSeries = function (series) {
                $scope.selected_series = series;
                $scope.activeTab = 'normal';
                $scope.getContests(true);
            }

            /* function to get contests according to matches */
            $scope.Contests = [];
            $scope.getContests = function (status) {
                if($scope.activeTab != 'normal'){ 
                    return false;
                }
                if(status){
                    $scope.data.pageNo = 1;
                    $scope.Contests = [];  
                    $scope.LoadMoreFlag = true; 
                    $scope.data.noRecords = false;
                }
                if($scope.LoadMoreFlag == false ||  $scope.data.noRecords == true){ return false }
                $scope.data.listLoading = true;
                $data = {};
                $data.SeriesGUID = $scope.selected_series.SeriesGUID; // Selected SeriesGUID
                $data.SessionKey = $localStorage.user_details.SessionKey; // User SessionKey
                $data.PageNo = $scope.data.pageNo; // Page Number
                $data.PageSize = $scope.data.pageSize; // Page Size
                $data.Params = 'LeagueJoinDateTime,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,IsJoined,Status,ContestFormat,ContestType,CustomizeWinning,TotalJoined,UserInvitationCode,TeamNameLocal,TeamNameVisitor,IsConfirm,CashBonusContribution,GameTimeLive,ContestType';
                $data.Keyword = $scope.Keyword;
                $data.ContestFull = 'No';
                $data.Status = ['1', '2', '5'];
                $data.StatusID = 1;
                $data.Privacy = 'No';
                $data.UserGUID = '';
                $data.AuctionStatus = 'Pending';
                appDB
                        .callPostForm('auctionDrafts/getContests', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.ContestsTotalCount = data.Data.TotalRecords;
                                        if (data.Data.hasOwnProperty('Records') && data.Data.Records !='' ) {
                                            $scope.LoadMoreFlag = true;
                                            for (var i in data.Data.Records) {
                                                data.Data.Records[i].EntryFee = Number(data.Data.Records[i].EntryFee);
                                                data.Data.Records[i].ContestSize = Number(data.Data.Records[i].ContestSize);
                                                data.Data.Records[i].WinningAmount = Number(data.Data.Records[i].WinningAmount);
                                                data.Data.Records[i].joinedpercent = parseInt(data.Data.Records[i].TotalJoined) * 100 / parseInt(data.Data.Records[i].ContestSize);
                                                $scope.Contests.push(data.Data.Records[i]);
                                            }
                                            $scope.data.pageNo++;
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
                $scope.data.listLoading = false;
            }

            /*To get User joined contest */

            var $data = {};
            $scope.JoinedContest = function (status) {
                if($scope.activeTab != 'joined'){
                    return false;
                }
                if(status){
                    $scope.data.pageNo = 1;
                    $scope.data.dataList = [];
                    $scope.LoadMoreFlag = true; 
                    $scope.data.noRecords = false;
                }
                if($scope.LoadMoreFlag == false ||  $scope.data.noRecords == true){ return false }
                var $data = {};
                $scope.data.listLoading = true;
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.selected_series.SeriesGUID; //Series GUID
                $data.Params = 'UserInvitationCode,ContestType,ContestID,LeagueJoinDateTime,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,Status,TotalJoined,CustomizeWinning,CashBonusContribution';
                $data.PageNo = $scope.data.pageNo;
                $data.PageSize = $scope.data.pageSize;
                $data.Keyword = $scope.Keyword;
                $data.JoinedContestStatusID = 'Yes';
                $data.Status = 'Pending';
                $data.MyJoinedContest = 'Yes';
                $data.Privacy = 'All';
                appDB
                        .callPostForm('auctionDrafts/getContests', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.UserJoinedContestTotalCount = data.Data.TotalRecords;
                                        if (data.Data.hasOwnProperty('Records') && data.Data.Records !='' ) {
                                            $scope.LoadMoreFlag = true;
                                            for (var i in data.Data.Records) {
                                                data.Data.Records[i].EntryFee = Number(data.Data.Records[i].EntryFee);
                                                data.Data.Records[i].ContestSize = Number(data.Data.Records[i].ContestSize);
                                                data.Data.Records[i].WinningAmount = Number(data.Data.Records[i].WinningAmount);
                                                data.Data.Records[i].joinedpercent = parseInt(data.Data.Records[i].TotalJoined) * 100 / parseInt(data.Data.Records[i].ContestSize);
                                                $scope.data.dataList.push(data.Data.Records[i]);
                                            }
                                            $scope.data.pageNo++;
                                        } else {
                                            $scope.LoadMoreFlag = false;
                                        }
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });

            }

            /*To join Contest*/
            $scope.check_balance_amount = function (ContestInfo) {
                $rootScope.ContestInfo = ContestInfo;
                if(parseInt($scope.profileDetails.TotalCash) < parseInt(ContestInfo.EntryFee)){
                    $scope.openPopup('add_more_money');
                }else{
                    $scope.openPopup('joinLeaguePopup');
                }
            }

            $rootScope.JoinContest = function(){
                var $data = {};
                $data.ContestGUID = $rootScope.ContestInfo.ContestGUID;
                $data.SeriesGUID = $scope.selected_series.SeriesGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                appDB
                        .callPostForm('auctionDrafts/join', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.closePopup('joinLeaguePopup');

                                        $scope.successMessageShow(data.Message);
                                        setTimeout(function () {
                                            $scope.data.pageNo = 1;
                                            $scope.getWalletDetails();
                                            $scope.getContests(true);
                                        }, 1000);
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data);
                                });
            }
            /* Function for search contest */
            $scope.searchContest = function (search) {
                $scope.Keyword = search;
                if ($scope.activeTab === 'normal') {
                    $scope.getContests(true);
                } else if ($scope.activeTab === 'joined') {
                    $scope.JoinedContest(true);
                } else {
                    $scope.UsersTeamList();
                }

            }
            /*
             Description : To get user created team list
             */
            $scope.userTeamList = [];
            $scope.UsersTeamList = function () {
                $scope.data.listLoading = false;
                var $data = {};
                $scope.userTeamList = []; //user team list array
                $data.SessionKey = $localStorage.user_details.SessionKey; //user session key
                $data.SeriesGUID = $scope.selected_series.SeriesGUID; //Match GUID
                $data.Params = 'UserTeamName';
                // $data.PageNo = 0;
                // $data.PageSize = $scope.data.pageSize;
                $data.Keyword = $scope.Keyword;
                $data.UserTeamType = 'Auction';
                $data.UserGUID = $localStorage.user_details.UserGUID;
                appDB
                        .callPostForm('auctionDrafts/getUserTeams', $data)
                        .then(
                                function successCallback(data) {
                                    if ($scope.checkResponseCode(data)) {
                                        $scope.data.listLoading = false;
                                        if (data.Data.hasOwnProperty('Records')) {
                                            $scope.userTeamList = data.Data;
                                        } else {
                                            $scope.userTeamList = [];
                                            $scope.data.noRecords = true;

                                        }
                                        $scope.UserTeamsTotalCount = data.Data.TotalRecords;
                                    } else {
                                        $scope.data.noRecords = true;
                                    }
                                },
                                function errorCallback(data) {
                                    $scope.checkResponseCode(data)
                                });

            }

            $scope.showWinningPayout = function(Winnings){
                $scope.CustomizeWinning = Winnings;
                $scope.openPopup('PayoutBreakUp');
            }
            
            /**
             * Sort by Entry fee and payouts
             */
            $scope.propertyName = 'EntryFee';
            $scope.reverse = true;
            $scope.sortBy = function (propertyName) {
                $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
                $scope.propertyName = propertyName;
            };
            
            $rootScope.InviteCode = '';
            $scope.openinvitationModal = function(invitationCode){
                $rootScope.InviteCode = invitationCode;
                $scope.openPopup('invitationModal');
            }
            
            $rootScope.activeInviteTab = 'viaSms';

            $rootScope.inviteInviteTab = function (tab) {
                $rootScope.activeInviteTab = tab;
            }
            /*function to invite friend*/
            $scope.inviteField = {};

            $scope.inviteSubmitted = false;
            $scope.InviteFriend = function (form, ReferType, InviteCode) {
                $scope.inviteSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey;
                if (ReferType == 'Phone') {
                    $data.PhoneNumber  = $scope.inviteField.PhoneNumber;
                } else {
                    $data.Email = $scope.inviteField.Email;
                }
                $data.ReferType = ReferType;
                $data.InviteCode = InviteCode;
                appDB
                        .callPostForm('users/InviteContest', $data)
                        .then(
                                function successCallback(data) {

                                    if (data.ResponseCode == 200) {
                                        var toast = toastr.success(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.closePopup('invitationModal');
                                        $scope.inviteField = {};
                                        $scope.inviteSubmitted = false;
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
                                            window.location.href = base_url;
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
             Description : To check private contest and join
             */
            $scope.codeSubmitted = false;
            $scope.checkContestCode = function (form, UserInvitationCode) {
                $scope.codeSubmitted = true;
                if (!form.$valid) {
                    return false;
                }
                $scope.data.listLoading = true;
                var $data = {};
                $data.SessionKey = $localStorage.user_details.SessionKey; // User SessionKey
                $data.Params = 'IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,TotalJoined,CustomizeWinning';
                $data.UserInvitationCode = UserInvitationCode;
                appDB
                        .callPostForm('auctionDrafts/getPrivateContest', $data)
                        .then(
                                function successCallback(data) {
                                    $scope.data.listLoading = false;
                                    if (data.ResponseCode == 200) {
                                        $scope.closePopup('joinPrivateContestPopup');

                                        $scope.successMessageShow(data.Message);
                                        setTimeout(function () {
                                            $scope.data.pageNo = 1;
                                            $scope.getWalletDetails();
                                            $scope.getContests(true);
                                        }, 1000);
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
                                    $scope.data.listLoading = false;
                                    if (typeof data == 'object') {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                        $scope.data.noRecords = true;
                                    }
                                });
            }
        } else {
            window.location.href = base_url;
        }

    }]);

