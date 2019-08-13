app.controller('PageController', function ($scope, $http,$timeout){

    var FromDate = ToDate = '';
    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    $timeout(function(){            
        $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
     }, 200);
    $scope.data.pageSize = 15;
    /*----------------*/
     /*list*/
    $scope.applyFilter = function() {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /* Reset form */
    $scope.resetUserForm = function(){
        $('#filterForm').trigger('reset'); 
        $('.chosen-select').trigger('chosen:updated');
        $('#dateRange span').html('Select Date Range');
        FromDate = ToDate = '';
    }

      /* Add Date Range Picker */
      $scope.initDateRangePicker = function (){
        $('#dateRange').daterangepicker({
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            locale: {
                cancelLabel: 'Clear'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
        $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
            FromDate = picker.startDate.format('YYYY-MM-DD');
            ToDate   = picker.endDate.format('YYYY-MM-DD');
            $('#dateRange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
            $('#dateRange span').html('Select Date Range');
            FromDate = ToDate = '';
        });
    }



    $scope.getUserInfo = function(){
        $scope.userData = {};
        var UserGUID = getQueryStringValue('UserGUID');
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK,Email', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.userData = response.Data;
            }
        });
    }

    $scope.TransactionMode = 'All';
    /*list append*/
    $scope.getList = function () 
    {
        $scope.getUserInfo();
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&UserGUID='+getQueryStringValue('UserGUID')+'&TransactionMode='+$scope.TransactionMode+'&Params=Amount,CurrencyPaymentGateway,TransactionType,TransactionID,Status,Narration,OpeningBalance,ClosingBalance,EntryDate,WalletAmount,WinningAmount,CashBonus&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=' + $scope.data.OrderBy + '&EntryFrom=' + FromDate + '&EntryTo=' + ToDate + '&Sequence=' + $scope.data.Sequence + '&' + $('#filterForm').serialize();
        $http.post(API_URL+'admin/users/getWallet', data, contentType).then(function(response) {
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
    });
    }

    /*Export List*/
    $scope.ExportList = function() {
        var data = 'SessionKey='+SessionKey+'&UserGUID='+getQueryStringValue('UserGUID')+'&TransactionMode='+$scope.TransactionMode+'&Params=Amount,CurrencyPaymentGateway,TransactionType,TransactionID,Status,Narration,OpeningBalance,ClosingBalance,EntryDate,WalletAmount,WinningAmount,CashBonus&' + $('#filterForm').serialize();
        $http.post(API_URL + 'admin/users/exportTransactions', data, contentType).then(function(response) {
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
                alertify.success(response.Message);
                $timeout(function () {
                    $http.post(API_URL + 'admin/matches/deleteFile', 'SessionKey=' + SessionKey + '&File='+response.Data, contentType).then(function (response) {
                        console.log('response',response);
                    });
                }, 5000); // After 5 seconds
            } else {
                alertify.error(response.Message);
            }
        });
    }



}); 
