'use strict';

app.controller('myAccountController', ['$scope', '$rootScope', '$location', 'environment', '$localStorage', '$sessionStorage', 'appDB', 'toastr', '$http', function ($scope, $rootScope, $location, environment, $localStorage, $sessionStorage, appDB, toastr, $http) {
        $scope.env = environment;
        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            //do something
            $scope.pageSize = 15;
            $scope.pageNo = 1;
            $scope.activeTab = 'transaction';
            $scope.ChangeTab = function (tab) {
                $scope.activeTab = tab;
                if(tab == 'transaction'){
                    $scope.getAccountInfo(true);
                }else if(tab === 'withdrawal'){
                    $scope.getWithdrawals(true);
                }
            }

            $rootScope.TransactionMessage = '';
            if (getQueryStringValue('status')) {
                if (getQueryStringValue('status') == 'failed') {
                    $('#TransactionModal').modal('show');
                    $rootScope.TransactionMessage = 'Transaction Failed!';
                }

                if (getQueryStringValue('status') == 'success') {
                    $('#TransactionModal').modal('show');
                    $rootScope.TransactionMessage = 'Transaction Success!';
                }

                if (getQueryStringValue('status') == 'Cancelled') {
                    $('#TransactionModal').modal('show');
                    $rootScope.TransactionMessage = 'Transaction Cancelled!';
                }
            }

           
            $scope.getAccountInfo = function (status) { 
                if ($scope.activeTab != 'transaction') {
                    return false;
                }
                if (status) {
                    $scope.pageNo = 1;
                    $scope.transactions = [];
                    $scope.LoadMoreFlag = true;
                    $scope.data.noRecords = false;
                }
                if ($scope.LoadMoreFlag == false || $scope.data.noRecords == true) {
                    return false
                }
                var $data = {};
                
                $data.UserGUID = $localStorage.user_details.UserGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Params = "Amount,CurrencyPaymentGateway,TransactionType,TransactionID,Status,Narration,OpeningBalance,ClosingBalance,EntryDate,WalletAmount,WinningAmount,CashBonus,TotalCash";
                $data.TransactionMode = 'All';
                $data.PageNo = $scope.pageNo;
                $data.PageSize = $scope.pageSize;
                $data.Filter = 'FailedCompleted';
                $http.post($scope.env.api_url + 'wallet/getWallet', $.param($data), contentType).then(function (response) {
                    var response = response.data;
                    if (response.ResponseCode == 200) { 
                        $scope.TotalTransactionCount = response.Data.TotalRecords;
                         if (response.Data.hasOwnProperty('Records') && response.Data.Records != '') {
                            $scope.LoadMoreFlag = true;
                            for (var i in response.Data.Records) {
                                $scope.transactions.push(response.Data.Records[i]);
                            }
                            $scope.pageNo++;
                        } else {
                            $scope.LoadMoreFlag = false;
                        }
                    } else {
                        $scope.data.noRecords = true;
                    }
                    if (response.ResponseCode == 500) {
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
                        localStorage.clear();
                        window.location.reload();
                    }

                });

            }



            $scope.getWithdrawals = function (status) { 
                if ($scope.activeTab != 'withdrawal') {
                    return false;
                }
                if (status) {
                    $scope.TotalWithdrawTransactionCount = 0;
                    $scope.pageNo = 1;
                    $scope.WithdrawTransactions = [];
                    $scope.LoadMoreFlag = true;
                    $scope.data.noRecords = false;
                }
                if ($scope.LoadMoreFlag == false || $scope.data.noRecords == true) {
                    return false
                }
                var $data = {};
                $data.UserGUID = $localStorage.user_details.UserGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Params = "Amount,PaymentGateway,EntryDate,Status";
                $data.PageNo = $scope.pageNo;
                $data.PageSize = $scope.pageSize;

                $http.post($scope.env.api_url + 'wallet/getWithdrawals', $.param($data), contentType).then(function (response) {
                    var response = response.data;
                    if (response.ResponseCode == 200) { 
                        if (response.Data.hasOwnProperty('Records') && response.Data.Records != '') {
                            $scope.TotalWithdrawTransactionCount = response.Data.TotalRecords;
                            $scope.LoadMoreFlag = true;
                            for (var i in response.Data.Records) {
                                $scope.WithdrawTransactions.push(response.Data.Records[i]);
                            }
                            $scope.pageNo++;
                        } else {
                            $scope.LoadMoreFlag = false;
                        }
                    } else {
                        $scope.data.noRecords = true;
                    }
                    if (response.ResponseCode == 500) {
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
                        localStorage.clear();
                        window.location.reload();
                    }

                });

            }

            $scope.submitted = false;
            
            

        } else {
            window.location.href = base_url;
        }
    }]);