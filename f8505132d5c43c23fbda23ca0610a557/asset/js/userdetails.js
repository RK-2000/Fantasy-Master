app.controller('PageController', function($scope, $http, $timeout) {

    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;

    /*list append*/
    $scope.getUserDetails = function() {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey + '&IsAdmin=No&UserGUID=' + getQueryStringValue('UserGUID') +'&' +'Params=RegisteredOn,LastLoginDate,UserTypeName, FullName, Email, Username, ProfilePic, Gender, BirthDate, PhoneNumber, Status, ReferredCount,StatusID,PanStatus,BankStatus,WalletAmount,WinningAmount,CashBonus,TotalCash&'+$('#filterForm').serialize();

        $http.post(API_URL + 'users/getProfile', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) { /* success case */
                $scope.userData = response.Data;
            } else {
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
        });
    }
    $scope.UserGUID = getQueryStringValue('UserGUID');
    /*load edit form*/
    $scope.loadFormEdit = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/edit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#edit_model').modal({
                    show: true
                });
                $timeout(function() {
                    $(".chosen-select").chosen({
                        width: '100%',
                        "disable_search_threshold": 8,
                        "placeholder_text_multiple": "Please Select",
                    }).trigger("chosen:updated");
                }, 200);
            }
        });

    }

    /*list append*/
    $scope.transactions = [];
    $scope.getList = function(TransactionMode) {
        var data = 'SessionKey=' + SessionKey + '&UserGUID='+getQueryStringValue('UserGUID')+'&IsAdmin=No&OrderBy=' + $scope.data.OrderBy + '&Sequence=' + $scope.data.Sequence + '&' +'Params=Amount,CurrencyPaymentGateway,TransactionType,TransactionID,Status,Narration,EntryDate,OpeningWalletAmount,OpeningWinningAmount,OpeningCashBonus,WalletAmount,WinningAmount,CashBonus,ClosingWalletAmount,ClosingWinningAmount,ClosingCashBonus,TotalCash&Filter=FailedCompleted&TransactionMode='+TransactionMode+'&'+$('#filterForm').serialize();
        $http.post(API_URL + 'admin/wallet/getWallet', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data.Records) { /* success case */
                $scope.transactions = response.Data.Records;
            } else {
                $scope.data.noRecords = true;
            }
        });
    }

    /*Withdrawal list append*/
    $scope.WithdrawalsTransactions = [];
    $scope.getWithdrawals = function() {
        var data = 'SessionKey=' + SessionKey + '&UserGUID='+getQueryStringValue('UserGUID')+'&Params=Amount,Comments,PaymentGateway,EntryDate,Status&OrderBy=EntryDate&Sequence=DESC&'+$('#filterForm').serialize();
        $http.post(API_URL + 'admin/wallet/getWithdrawals', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data.Records) { /* success case */
                $scope.WithdrawalsTransactions = response.Data.Records;
            } else {
                $scope.data.noRecords = true;
            }
        });
    }

    /*Export Amount Details
        Cash Deposit
        Winning Bonus
        Cash Bonus 
    */
    $scope.exportUserAmountDetails = function (ExportMode) { 
        if ($scope.transactions.length > 0) {
            var varArr = [];
            for (var i = 0; i < $scope.transactions.length; i++) {
                var row = {};
                row.TransactionID   = $scope.transactions[i]['TransactionID'];
                row.Details         = $scope.transactions[i]['Narration'];
                row.Status          = $scope.transactions[i]['Status'];
                if (ExportMode == 'cash-deposit') {
                    if ($scope.transactions[i]['Narration'] != 'Deposit Money') {
                        row.EntryFee        = $scope.transactions[i]['Amount'];
                    }else{                        
                        row.EntryFee        = '-';
                    }
                    row.OpeningBalance  = $scope.transactions[i]['OpeningWalletAmount'];
                    if ($scope.transactions[i]['TransactionType'] == 'Cr') {
                        row.Cr        = $scope.transactions[i]['WalletAmount'];
                    }else{                        
                        row.Cr        = '0.00';
                    }
                    if ($scope.transactions[i]['TransactionType'] == 'Dr') {
                        row.Dr        = $scope.transactions[i]['WalletAmount'];
                    }else{                        
                        row.Dr        = '0.00';
                    }
                    row.ClosingBalance  = $scope.transactions[i]['ClosingWalletAmount'];
                }else if (ExportMode == 'winning-bonus') {
                    if ($scope.transactions[i]['Narration'] == 'Join Contest' || $scope.transactions[i]['Narration'] == 'Cancel Contest') {
                        row.EntryFee        = $scope.transactions[i]['Amount'];
                    }else{                        
                        row.EntryFee        = '-';
                    }
                    row.OpeningBalance  = $scope.transactions[i]['OpeningWinningAmount'];
                    if ($scope.transactions[i]['TransactionType'] == 'Cr') {
                        row.Cr        = $scope.transactions[i]['WinningAmount'];
                    }else{                        
                        row.Cr        = '0.00';
                    }
                    if ($scope.transactions[i]['TransactionType'] == 'Dr') {
                        row.Dr        = $scope.transactions[i]['WinningAmount'];
                    }else{                        
                        row.Dr        = '0.00';
                    }
                    row.ClosingBalance  = $scope.transactions[i]['ClosingWinningAmount'];
                }else if (ExportMode == 'cash-bonus') {
                    if ($scope.transactions[i]['Narration'] == 'Join Contest' || $scope.transactions[i]['Narration'] == 'Cancel Contest') {
                        row.EntryFee        = $scope.transactions[i]['Amount'];
                    }else{                        
                        row.EntryFee        = '-';
                    }
                    row.OpeningBalance  = $scope.transactions[i]['OpeningCashBonus'];
                    if ($scope.transactions[i]['TransactionType'] == 'Cr') {
                        row.Cr        = $scope.transactions[i]['CashBonus'];
                    }else{                        
                        row.Cr        = '0.00';
                    }
                    if ($scope.transactions[i]['TransactionType'] == 'Dr') {
                        row.Dr        = $scope.transactions[i]['CashBonus'];
                    }else{                        
                        row.Dr        = '0.00';
                    }
                    row.ClosingBalance  = $scope.transactions[i]['ClosingCashBonus'];
                }
                row.DateTime       = $scope.transactions[i]['EntryDate'];
                varArr.push(row);
            }
            $scope.JSONToCSVConvertor(varArr, 'export-'+ExportMode+'-list', true);
        }
    }

    /* Export user withdrawal Details */
    $scope.exportUserWithdrawalDetails = function () { 
        if ($scope.WithdrawalsTransactions.length > 0) {
            var varArr = [];
            for (var i = 0; i < $scope.WithdrawalsTransactions.length; i++) {
                var row = {};
                row.TransactionID   = $scope.WithdrawalsTransactions[i]['WithdrawalID'];
                row.Amount          = $scope.WithdrawalsTransactions[i]['Amount'];
                row.PaymentGateway  = $scope.WithdrawalsTransactions[i]['PaymentGateway'];
                if ($scope.WithdrawalsTransactions[i]['Status'] == 'Verified') {
                    row.Status          = 'Completed';
                }else{
                    row.Status          = $scope.WithdrawalsTransactions[i]['Status'];
                }
                if ($scope.WithdrawalsTransactions[i]['Comments'] == '') {
                    row.DateTime          = $scope.WithdrawalsTransactions[i]['EntryDate'];
                }else{
                    row.RejectReason          = $scope.WithdrawalsTransactions[i]['Comments'];
                }                
                varArr.push(row);
            }
            $scope.JSONToCSVConvertor(varArr, 'export-withdrawal-list', true);
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