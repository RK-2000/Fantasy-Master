app.controller('PageController', function ($scope, $http, $timeout, $rootScope) {
    $scope.data.pageSize = 100;
    $scope.data.ParentCategoryGUID = ParentCategoryGUID;
    $scope.data.DEFAULT_CURRENCY = DEFAULT_CURRENCY;
    
    /*----------------*/
    $scope.getFilterData = function ()
    {
        var data = 'SessionKey=' + SessionKey + '&Params=SeriesName,SeriesGUID&StatusID=2&' + $('#filterPanel form').serialize();

        $http.post(API_URL + 'admin/matches/getFilterData', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) {
                /* success case */
                $scope.filterData = response.Data;
                $timeout(function () {
                    $("select.chosen-select").chosen({width: '100%', "disable_search_threshold": 8}).trigger("chosen:updated");
                }, 300);
            }
        });
    }

    /*list*/
    $scope.applyFilter = function ()
    {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /*list append*/
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords)
            return;
        $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey  + '&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&Params=Privacy,AdminPercent,IsPaid,WinningAmount,DraftSize,EntryFee,NoOfWinners,EntryType,CustomizeWinning,DraftType,TotalJoined,CashBonusContribution,Status,UserJoinLimit&Privacy=All&OrderBy=PredraftContestID&Sequence=DESC&' + $('#filterForm1').serialize();
        $http.post(API_URL + 'admin/predraftContest/getPredraft', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && parseInt(response.Data.TotalRecords) > 0) {
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

    /*load add form*/
    $scope.loadFormAdd = function (Position, CategoryGUID)
    {
        $scope.clearForm();
        $scope.custom.EntryFee = 0;
        $scope.custom.CashBonusContribution = 0;
        $scope.IsAutoCreate = "";
        $scope.unfilledWinningPercent = "";
        $scope.custom.WinningAmount = 0;
        $scope.templateURLAdd = PATH_TEMPLATE + module + '/add_form.htm?' + Math.random();
        $('#add_model').modal({show: true});
        $timeout(function () {
            $('#MatchGUID').select2();
            $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
        }, 200);


    }

    /*To get matches according to Series*/
    $scope.getMatches = function (SeriesGUID,Status = '') {
        $scope.MatchData = {};
        var data = 'SeriesGUID=' + SeriesGUID + '&Params=MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor&OrderBy=MatchStartDateTime&Sequence=ASC&Status='+Status;
        $http.post(API_URL + 'sports/getMatches', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) { /* success case */
                $scope.MatchData = response.Data.Records;
                $timeout(function () {
                    $("select.chosen-select").chosen({width: '100%', "disable_search_threshold": 8}).trigger("chosen:updated");
                }, 300);
            }
        });
    }

    $(document).on('click', '#all_matches', function () {
        $('#MatchGUID option').prop('selected', true);
        $('#MatchGUID option[value=""]').prop('selected', false);
        $('#MatchGUID').select2();
    });

    $(document).on('click', '#clear_all', function () {
        $('#MatchGUID option').prop('selected', false);
        $('#MatchGUID').select2();
    });
    
    $("#filter_model").on('show.bs.modal', function(){
        $('form#filterForm1 span.select2-container').remove();
    });

    /*load edit form*/

    $scope.loadFormEdit = function (Position, PredraftContestID)
    {
        
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/edit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'admin/predraftContest/getPredraft', 'SessionKey=' + SessionKey + '&PredraftContestID=' + PredraftContestID + '&Params=unfilledWinningPercent,IsVirtualUserJoined,VirtualUserJoinedPercentage,AdminPercent,Privacy,IsPaid,WinningAmount,DraftSize,EntryFee,NoOfWinners,EntryType,CustomizeWinning,DraftType,CashBonusContribution,UserJoinLimit,DraftFormat,IsConfirm,ShowJoinedDraft,IsAutoCreate',contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data.Records[0]


                $scope.custom.WinningAmount = parseInt(response.Data.Records[0].WinningAmount);
                $scope.custom.EntryFee = parseInt(response.Data.Records[0].EntryFee);


                $scope.remainingAmount = $scope.custom.WinningAmount;
                $scope.custom.AdminPercent = parseInt(response.Data.Records[0].AdminPercent);
                //$scope.EntryFee = response.Data.EntryFee;
                $scope.IsAutoCreate = response.Data.Records[0].IsAutoCreate;
                $scope.unfilledWinningPercent = response.Data.Records[0].unfilledWinningPercent;

                $scope.custom.NoOfWinners = response.Data.Records[0].NoOfWinners;
                $scope.custom.DraftSize = response.Data.Records[0].DraftSize;
                if($scope.formData.CashBonusContribution){
                    $scope.custom.CashBonusContribution = parseInt($scope.formData.CashBonusContribution);
                }else{
                    $scope.custom.CashBonusContribution = 0;
                }
                
                
                $scope.custom.choices = response.Data.Records[0].CustomizeWinning;
               if (response.Data.Records[0].CustomizeWinning.length > 0) {
                    $scope.showField = true;
                }
                if (response.Data.Records[0].CustomizeWinning) {

                    if ($scope.numbers == '') {
                        for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                            $scope.numbers.push(i);
                        }
                    } else {
                        for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                            $scope.numbers.push(i)
                            $scope.numbers.splice(i);
                        }
                    }

                    angular.forEach($scope.custom.choices, function (value, key) {
                        value.numbers = $scope.numbers;
                        value.percent = value.Percent;
                        value.amount = value.WinningAmount;
                        value.From = value.From;
                        value.To = value.To;
                        $scope.remainingAmount = $scope.remainingAmount - value.amount;
                    });
                }
                $('#edit_model').modal({show: true});
                $scope.editForm = true;

                $timeout(function () {

                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*load delete form*/
    $scope.loadFormDelete = function (Position, CategoryGUID)
    {
        $scope.data.Position = Position;
        $scope.templateURLDelete = PATH_TEMPLATE + module + '/delete_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'category/getCategory', 'SessionKey=' + SessionKey + '&CategoryGUID=' + CategoryGUID, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#delete_model').modal({show: true});
                $timeout(function () {
                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    /*Delete contest*/
    $scope.deleteDraft = function (Position, PredraftContestID)
    {
        if(confirm('Are you sure, want to delete this draft ?')){
            $scope.addDataLoading = true;
            $http.post(API_URL+'admin/predraftContest/delete', 'SessionKey='+SessionKey+'&PredraftContestID='+PredraftContestID, contentType).then(function(response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if(response.ResponseCode==200){ /* success case */
                    window.location.reload();        
                    alertify.success(response.Message);
                }else{
                    alertify.error(response.Message);
                }
                $scope.addDataLoading = false; 
            });
        }
    }

    /*add data*/
    $scope.DraftFormat = 'Head to Head';
    $scope.IsPaid = 'Yes';
    $scope.addData = function ()
    {
        $scope.addDataLoading = true;

        // if(!$scope.contestPrizeParser($scope.custom.choices)){
        if ($scope.contestPrizeParser($scope.custom.choices)[0].WinningAmount == 0) {
            var customWinings = JSON.stringify([{'From': 1, 'To': $scope.custom.NoOfWinners, 'WinningAmount': $scope.custom.WinningAmount, 'percent': 100}]);
        } else {
            var customWinings = JSON.stringify($scope.contestPrizeParser($scope.custom.choices));
        }
        console.log(customWinings + "customWinings");
        var $win = {};
        $win.CustomizeWinning = JSON.parse(customWinings);

        //customWinings = JSON.parse(customWinings);
      console.log(customWinings + "second");
        /*}
         else{
         var customWinings   = '';
         }*/

        if ($scope.DraftFormat == 'Head to Head') {
            var DraftSize = 2;
        }
        var data = 'SessionKey=' + SessionKey + '&Privacy=No&' + $("form[name='add_form']").serialize() + '&CustomizeWinning=' + $win.CustomizeWinning;
        $http.post(API_URL + 'admin/predraftContest/add', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.applyFilter();
                $scope.addDataLoading = false;
                $('.modal-header .close').click();
            } else {
                $scope.addDataLoading = false;
                alertify.error(response.Message);
            }
        });
        
        $scope.addDataLoading = false;
    }

    $scope.addDataPredraft = function ()
    {
        $scope.addDataLoading = true;
        if ($scope.contestPrizeParser($scope.custom.choices)[0].WinningAmount == 0) {
            var customWinings = JSON.stringify([{'From': 1, 'To': $scope.custom.NoOfWinners, 'WinningAmount': $scope.custom.WinningAmount, 'Percent': 100}]);
        } else {
            var customWinings = JSON.stringify($scope.contestPrizeParser($scope.custom.choices));
        }
        if ($scope.DraftFormat == 'Head to Head') {
            var DraftSize = 2;
        }
        
        var $data = {};
            $data.SessionKey = SessionKey;
            $data.DraftName = $('form#add_form input[name="DraftName"]').val();
            $data.DraftFormat = $('form#add_form select[name="DraftFormat"]').val();
            $data.AdminPercent = $('form#add_form input[name="AdminPercent"]').val();
            $data.DraftType = $('form#add_form select[name="DraftType"]').val();
            $data.Privacy = 'No';
            $data.IsPaid = $('form#add_form select[name="IsPaid"]').val();
            $data.IsConfirm = $('form#add_form select[name="IsConfirm"]').val();
            $data.IsAutoCreate = $('form#add_form select[name="IsAutoCreate"]').val();
            $data.ShowJoinedDraft = $('form#add_form select[name="ShowJoinedDraft"]').val();
            $data.WinningAmount = $('form#add_form input[name="WinningAmount"]').val();
            $data.DraftSize = $('form#add_form input[name="DraftSize"]').val();
            $data.EntryFee = $('form#add_form input[name="EntryFee"]').val();
            $data.NoOfWinners = $('form#add_form input[name="NoOfWinners"]').val();
            $data.EntryType = $('form#add_form select[name="EntryType"]').val();
            $data.UserJoinLimit = $('form#add_form input[name="UserJoinLimit"]').val();
            $data.CashBonusContribution = $('form#add_form input[name="CashBonusContribution"]').val();
            $data.IsWinnerSocialFeed = $('form#add_form select[name="IsWinnerSocialFeed"]').val();
            $data.CustomizeWinning = JSON.parse(customWinings);
            $http.post(API_URL + 'admin/predraftContest/add', $.param($data), contentType).then(function (response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if (response.ResponseCode == 200) { /* success case */
                    alertify.success(response.Message);
                    $scope.applyFilter();
                    $scope.addDataLoading = false;
                    $('.modal-header .close').click();
                    window.location.reload();
                } else {
                    $scope.addDataLoading = false;
                    alertify.error(response.Message);
                }
            });
            $scope.addDataLoading = false;
    }


    /*edit data*/
    $scope.editData = function ()
    {

        $scope.editDataLoading = true;

        var inputData = {};

        inputData.DraftName = $scope.formData.DraftName;
        inputData.IsPaid = $scope.formData.IsPaid;
        inputData.WinningAmount = $scope.custom.WinningAmount;
        inputData.CashBonusContribution = $scope.custom.CashBonusContribution;
        inputData.DraftFormat = $scope.formData.DraftFormat;
        inputData.DraftType = $scope.formData.DraftType;
        inputData.EntryFee = $scope.custom.EntryFee;
        inputData.EntryType = $scope.formData.EntryType;

        if (inputData.EntryType == 'Multiple') {

            inputData.UserJoinLimit = $scope.formData.UserJoinLimit;

        }
        
        if(inputData.DraftFormat == 'Head to Head')
        {
            if($scope.custom.NoOfWinners > 1)
            {
                $scope.calculation_error = true;
                $scope.calculation_error_msg = 'No. of winners should be only one in case of head to head contest';
               return false;
            }
        }
         

        inputData.DraftSize = $scope.custom.DraftSize;
        if (inputData.DraftFormat != 'Head to Head') {

            inputData.DraftType = $scope.formData.DraftType;

        }
        
        inputData.IsConfirm = $scope.formData.IsConfirm;
        inputData.ShowJoinedDraft = $scope.formData.ShowJoinedDraft;
        inputData.IsAutoCreate = $scope.formData.IsAutoCreate;
        inputData.unfilledWinningPercent = $scope.formData.unfilledWinningPercent;
        inputData.PredraftContestID = $scope.formData.PredraftContestID;
       
        inputData.NoOfWinners = $scope.custom.NoOfWinners;
        inputData.CustomizeWinning = JSON.stringify($scope.custom.choices);
        inputData.SessionKey = SessionKey;
        inputData.Privacy = $scope.formData.Privacy;
        inputData.AdminPercent = $scope.custom.AdminPercent;
        inputData.UserJoinLimit = $scope.formData.UserJoinLimit;

        
        var customWinings = [];
        $.each($scope.custom.choices, function (key, value) {
           
            customWinings.push({'From': value.From, 'To': value.To, 'Percent': value.percent, 'WinningAmount': value.amount});
        });
        inputData.CustomizeWinning = customWinings;
       
        
        //var data = 'SessionKey=' + SessionKey + '&' + 'UserJoinLimit=' + inputData.UserJoinLimit + '&AdminPercent=' + inputData.AdminPercent + '&DraftName=' + inputData.DraftName + '&IsPaid=' + inputData.IsPaid + '&WinningAmount=' + inputData.WinningAmount + '&CashBonusContribution=' + inputData.CashBonusContribution + '&DraftFormat=' + inputData.DraftFormat + '&EntryFee=' + inputData.EntryFee + '&EntryType=' + inputData.EntryType + '&DraftSize=' + inputData.DraftSize + '&DraftType=' + inputData.DraftType + '&IsConfirm=' + inputData.IsConfirm + '&ShowJoinedDraft=' + inputData.ShowJoinedDraft + '&IsAutoCreate=' + inputData.IsAutoCreate + '&NoOfWinners=' + inputData.NoOfWinners + '&PredraftContestID=' + inputData.PredraftContestID + '&Privacy=' + inputData.Privacy + '&CustomizeWinning=' + inputData.CustomizeWinning;
       $http.post(API_URL + 'admin/predraftContest/edit', $.param(inputData), contentType).then(function (response) {

            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position] = response.Data;
                $scope.editDataLoading = false;
                $('.modal-header .close').click();
                 
                    window.location.reload();
                
            } else {
                $scope.editDataLoading = false;
                alertify.error(response.Message);
            }
        });
        $scope.editDataLoading = false;
    }


    /*--------------------------------------------------------------------------------------*/

    /*create contest calculations starts*/
    $scope.custom = {};
    $scope.clearForm = function () {
        $scope.showField = false;
        $scope.custom.choices = [];
        $scope.custom.choices.push({
            row: 0,
            From: 1,
            To: 1,
            amount: 0.00,
            percent: 0
        });

        if ($scope.custom.NoOfWinners && $scope.contest_sizes) {
            if ($scope.numbers == '') {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i);
                }
            } else {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i)
                    $scope.numbers.splice(i);
                }
            }
        }
    }
    $scope.totalPercentage = 0; // For Contest Creation Belives total Percentage is 0
    $scope.totalPersonCount = 0; // For Contest Creation Belives total Person count is 0
    $scope.currentSelectedMatch = 0; //To maintain current Selected Match Id
    /*------------calculate entryFee-------------------*/
    $scope.adminPercent = 10;
    $scope.custom.DraftSize = 2;
    $scope.showSeries = true;
    $scope.contestError = false;
    $scope.contestErrorMsg = '';


    /*Function to Fetch Matches*/
  

    $scope.$watch('custom.WinningAmount', function (newValue, oldValue) {

        if (newValue != oldValue) {
            if (typeof newValue == 'undefined') {
                $scope.custom.EntryFee = 0.00;
                return false;
            }
            /*if (newValue > 10000) {
             $scope.custom.WinningAmount = 10000;
             }*/
           
            if (angular.isNumber($scope.custom.WinningAmount)) {
                $scope.custom.WinningAmount = $scope.custom.WinningAmount.toString();
            }
            if ($scope.custom.WinningAmount.match(/^0[0-9].*$/)) {
                $scope.custom.WinningAmount = $scope.custom.WinningAmount.replace(/^0+/, '');
            }


            if (parseInt($scope.custom.DraftSize) > 0) {
                $scope.totalEntry = $scope.custom.WinningAmount / $scope.custom.DraftSize;
                $scope.custom.EntryFee = ($scope.totalEntry * $scope.adminPercent / 100 + $scope.totalEntry).toFixed(2);
            } else {
                $scope.custom.EntryFee = 0;
            }
             console.log($scope.custom.EntryFee + "entry fee")
            if (!$scope.editForm) {
                $scope.clearForm();
            }

        }
    }, true);

    $scope.$watch('custom.EntryFee', function (newValue, oldValue) {
        if (newValue != oldValue) {
            if (typeof newValue == 'undefined') {
                $scope.WinningAmount = 0.00;
                return false;
            }
            /*if (newValue > 10000) {
             $scope.custom.WinningAmount = 10000;
             }*/
            
            if (angular.isNumber($scope.custom.EntryFee)) {
                $scope.custom.EntryFee = $scope.custom.EntryFee.toString();

            }
            if ($scope.custom.EntryFee.match(/^0[0-9].*$/)) {
                $scope.custom.EntryFee = $scope.custom.EntryFee.replace(/^0+/, '');
            }

            if (parseInt($scope.custom.DraftSize) > 0) {

                $scope.totalEntry = $scope.custom.EntryFee * $scope.custom.DraftSize;
                $scope.adminAmount = Math.floor(($scope.totalEntry * $scope.adminPercent) / 100);
                $scope.WinningAmount = $scope.totalEntry - $scope.adminAmount;
               
            } else {
                $scope.WinningAmount = 0;
            }
            if (!$scope.editForm) {
                $scope.clearForm();
            }

        }
    }, true);

     $scope.$watch('custom.DraftSize', function (newValue, oldValue) {

        // $scope.custom.NoOfWinners = '';
        if (newValue != oldValue) {
            if (typeof newValue == 'undefined') {
                $scope.custom.EntryFee = 0.00;
                return false;
            }

            if (typeof $scope.custom.WinningAmount == 'undefined') {
                $scope.custom.winningamount_error = true;
                return false;
            } else {
                $scope.custom.winningamount_error = false;
            }
            /*if (newValue > 100) {
             $scope.custom.ContestSize = 100;
             }*/
            if ($scope.custom.DraftSize.match(/^0[0-9].*$/)) {
                $scope.custom.DraftSize = $scope.custom.DraftSize.replace(/^0+/, '');
            }


            if (parseInt($scope.custom.DraftSize) > 0) {
                $scope.totalEntry = $scope.custom.WinningAmount / $scope.custom.DraftSize;
                $scope.custom.EntryFee = ($scope.totalEntry * $scope.adminPercent / 100 + $scope.totalEntry).toFixed(2);
            } else {
                $scope.custom.EntryFee = 0;
            }
             if (!$scope.editForm) {
                $scope.clearForm();
            }
            // if(isNaN($scope.EntryFee)){
            //     $scope.EntryFee = 0;
            // }
        }

    });

     $scope.$watch('custom.AdminPercent', function (newValue, oldValue) {
        $scope.AdminPercent = newValue;
        if (newValue != oldValue) {
            if (typeof newValue == 'undefined') {
                $scope.custom.EntryFee = 0.00;
                return false;
            }
            /*if (newValue > 10000) {
             $scope.custom.WinningAmount = 10000;
             }*/
            if (angular.isNumber($scope.custom.WinningAmount)) {
                $scope.custom.WinningAmount = $scope.custom.WinningAmount.toString();
            }
            if ($scope.custom.WinningAmount.match(/^0[0-9].*$/)) {
                $scope.custom.WinningAmount = $scope.custom.WinningAmount.replace(/^0+/, '');
            }
            if (parseInt($scope.custom.DraftSize) > 0) {
                $scope.totalEntry = $scope.custom.WinningAmount / $scope.custom.DraftSize;
                $scope.custom.EntryFee = ($scope.totalEntry * $scope.AdminPercent / 100 + $scope.totalEntry).toFixed(2);
            } else {
                $scope.custom.EntryFee = 0;
            }
            if (!$scope.editForm) {
                $scope.clearForm();
            }

        }
    }, true);
    /*------------calculate Percent and Amount-------------------*/
    $scope.custom.choices = [];
    $scope.amount = 0.00;
    $scope.remainingAmount = 0;

    $scope.changePercent = function (x) {
        /*Remove Error First*/
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
       
        /*Remove Error First*/
        if (x != 0 && x > 0) {
            let tempPersnCount1 = ($scope.custom.choices[x].To - $scope.custom.choices[x].From) + 1;
            let tempPersnCount0 = ($scope.custom.choices[x - 1].To - $scope.custom.choices[x - 1].From) + 1;
          
        }
        
            
      
        
        $scope.totalCalculatePercentage = 100;
        $scope.remainingPercentage = 0;
        let total = 0;
        for (var i = 0; i < $scope.custom.choices.length; i++) {
              //total = total;
           
            if($scope.custom.choices[i].percent === "")
            {
                
                $scope.custom.choices[i].percent = parseFloat(0);
            }

            total = total + parseFloat($scope.custom.choices[i].percent);
          
           
        }
        if (total > 100) {
            $scope.custom.choices[x].percent = '';
            $scope.calculation_error = true;
            $scope.calculation_error_msg = 'Sum of percentage can not be more then 100%';
            $scope.custom.choices[x].amount = parseFloat(0);
            return false;
        }

        for (var i = 0; i < $scope.custom.choices.length; i++) {
            if (i === x) {
                let persenCount = 0;
                if (parseInt($scope.custom.choices[i].To) == parseInt($scope.custom.choices[i].From)) {
                    persenCount = 1;
                } else {
                    persenCount = ($scope.custom.choices[i].To - $scope.custom.choices[i].From) + 1;
                }
                $scope.winnersAmount = $scope.custom.WinningAmount * $scope.custom.choices[i].percent / 100;
                let amount = ($scope.winnersAmount / persenCount).toFixed(2);
                let fractionNumber = amount.split('.');
                amount = fractionNumber[0] + '.' + fractionNumber[1].slice(0, 1);
                $scope.custom.choices[i].amount = amount;
                // $scope.choices[i].percent = $scope.choices[i].percent.toString();
                $scope.custom.choices[i].percent = $scope.custom.choices[i].percent.toString();

                if ($scope.custom.choices[i].percent.match(/^0[0-9].*$/)) {
                    $scope.custom.WinningAmount = $scope.custom.WinningAmount.replace(/^0+/, '');
                }
                $scope.custom.choices[i].percent = $scope.custom.choices[i].percent.replace(/^0+/, '');
            }
        }
       
        $scope.remainingPercentage = $scope.totalCalculatePercentage - total;
    }


    $scope.changeCustomizeAmount = function (x) {
        /*Remove Error First*/
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        $scope.totalCalculateAmount = 0;
       
        /*Remove Error First*/
        if (x != 0 && x > 0) {
            let tempPersnCount1 = ($scope.custom.choices[x].To - $scope.custom.choices[x].From) + 1;
            let tempPersnCount0 = ($scope.custom.choices[x - 1].To - $scope.custom.choices[x - 1].From) + 1;
       
        }
        
        $scope.totalCalculateAmount = $scope.custom.WinningAmount;
        $scope.remainingAmount = 0;
        let total = 0;
        let amounttotal = 0;
      
       
        for (var i = 0; i < $scope.custom.choices.length; i++) {
           
                let persenCount = 0;
                if (parseInt($scope.custom.choices[i].To) == parseInt($scope.custom.choices[i].From)) {
                    persenCount = 1;
                } else {
                    persenCount = ($scope.custom.choices[i].To - $scope.custom.choices[i].From) + 1;
                }
                console.log(persenCount + "persenCount");
                $scope.custom.choices[i].percent = ($scope.custom.choices[i].amount * persenCount * 100) / $scope.custom.WinningAmount;
              
               // $scope.custom.choices[i].percent = Math.floor($scope.custom.choices[i].percent * 100) / 100;
               
               amounttotal += $scope.custom.choices[i].amount * persenCount;
         

        }
        if (amounttotal > $scope.totalCalculateAmount) {
            $scope.custom.choices[x].percent = '';
            $scope.calculation_error = true;
            $scope.calculation_error_msg = 'Sum of amount can not be more then winning amount';
            $scope.custom.choices[x].amount = parseFloat(0);
            return false;
        }
       
            $scope.remainingAmount = $scope.totalCalculateAmount - amounttotal;
        
    }


    $scope.changeAmount = function (x) {
        /*Remove Error First*/
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        /*Remove Error First*/

    }
   
    $scope.removeCustomWinning = function () {
         $scope.showField = false;
        $scope.custom.choices = [];
        $scope.custom.choices.push({
            row: 0,
            From: 1,
            To: 1,
            amount: 0.00,
            percent: 0
        });

        if ($scope.custom.NoOfWinners && $scope.contest_sizes) {
            if ($scope.numbers == '') {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i);
                }
            } else {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i)
                    $scope.numbers.splice(i);
                }
            }
        }

    }

    $scope.customizeMultieams = function () {
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        if ($scope.custom.DraftSize == null || $scope.custom.DraftSize < 3) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Contest size must be greater then 2!";
            $scope.EntryType = 'Single';
            return false;
        }
    }
    $scope.customizeWin = function () {
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        if ($scope.winnings == "") {
            $scope.showField = false;
            $scope.custom.NoOfWinners = '';
            return false;
        }
        if ($scope.custom.WinningAmount == null || $scope.custom.WinningAmount < 1) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Please enter total winning amount!";
            $scope.winnings = false;
            return false;
        }
        if ($scope.custom.DraftSize == null || $scope.custom.DraftSize < 2) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Contest size must be greater or equals to 2";
            $scope.winnings = false;
            return false;
        }
    }
    $scope.changeWinAmount = function () {
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        if ($scope.custom.WinningAmount == null || $scope.custom.WinningAmount < 1) {
            $scope.winnings = false;
        }
    }
    $scope.changeWinners = function () {
        $scope.EntryType = 'Single';
        $scope.calculation_error = false;
        $scope.calculation_error_msg = '';
        if ($scope.custom.DraftSize == null || $scope.custom.DraftSize < 2) {
            $scope.winnings = false;
        }
        $scope.showField = false;
        $scope.contestError = false;
        $scope.clearForm();
    }
    /*---------------add and remove Field-------------------*/
    $scope.From = 1;
    var x = 0;
    $scope.custom.choices.push({
        row: x,
        From: 1,
        To: 1,
        amount: 0.00,
        percent: 0
    });
    $scope.addField = function () {
        x = x + 1;
        $scope.numbers1 = [];

        var select2_value = "";
        $scope.percent_error = false;
        var lastIndex = $scope.custom.choices.length - 1;
        if ($scope.custom.choices[lastIndex].percent == 0) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Last percentage is blank!";
            return false;
        }
        if ($scope.totalPercentage == 100) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Amount has been distributed already!";
            return false;
        }
        console.log('here ', $scope.custom.choices);
        for (var k = 0; $scope.custom.choices.length > k; k++) {

            if (k == $scope.custom.choices.length - 1) {
                if ($scope.custom.choices[k].percent) {
                    select2_value = ($scope.custom.choices[k].To + 1);
                    for (var j = ($scope.custom.choices[k].To + 1); j <= parseInt($scope.custom.NoOfWinners); j++) {
                        $scope.numbers1.push(j)
                    }
                } else {
                    $scope.percent_error = true;
                    return false;
                }
            }
        }
        if (select2_value <= parseInt($scope.custom.NoOfWinners)) {
            $scope.custom.choices.push({
                row: x,
                From: select2_value,
                To: select2_value,
                numbers: $scope.numbers1,
                percent: 0,
                amount: 0.00
            });
        } else {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "All Winners has been selected already!";
        }

    }
    $scope.$watch('$scope.custom.choices', function (n, o, scope) {
        var totalPercentagetemp = 0;
        var isRemoval = false;
        var removalIndex = 0;
        /*Code to track Changes in top rows and if any remove below rows*/
        if ($scope.custom.choices.length > 1) {
            for (var counter = 0; counter < $scope.custom.choices.length; counter++) {
                if (counter < o.length - 1 && (o[counter].amount != n[counter].amount || o[counter].To != o[counter].To)) {
                    isRemoval = true;
                    removalIndex = counter + 1;
                }
            }
        }
        if (isRemoval == true) {
            var numberOfRows = $scope.custom.choices.length;
            if (removalIndex <= numberOfRows - 1) {
                var removeElementCount = numberOfRows - removalIndex;
                $scope.custom.choices.splice(removalIndex, removeElementCount);
            }

        }
        /*Code to track Changes in top rows and if any remove below rows*/

        /*Total Percentage Count and Handler*/
        for (var counter = 0; counter < $scope.custom.choices.length; counter++) {
            totalPercentagetemp += parseFloat($scope.custom.choices[counter].percent);
        }
        if (totalPercentagetemp > 100) {
            $scope.custom.choices = 0;
            return false;
        }
        $scope.totalPercentage = totalPercentagetemp;
        /*Total Percentage count and handler*/

        /*Total Person count and Handler*/
        let personCount = 0;
        for (var i = 0; i < $scope.custom.choices.length; i++) {
            if ($scope.custom.choices[i].From == $scope.custom.choices[i].To) {
                personCount++;
            } else {
                personCount += parseInt(($scope.custom.choices[i].To - $scope.custom.choices[i].From) + 1);
            }
        }
        $scope.totalPersonCount = personCount;
        /*Total Person Count and Handler*/
    }, true);

    /*Handle Contest Size*/
    $scope.$watch('custom.NoOfWinners', function (newValue, oldValue) {
        if (parseInt(newValue) > parseInt($scope.custom.DraftSize)) {
            alertify.error("No. of Winners can not be greater than Draft Size.");
            $scope.custom.NoOfWinners = oldValue;
        }
    });

    $scope.$watch('custom.AdminPercent', function (newValue, oldValue) {
        if (parseInt(newValue) > 100) {
            alertify.error("Admin percentage can not be greater than 100.");
            $scope.custom.AdminPercent = oldValue;
        }
    });


    $scope.removeField = function (index) {
        if (index == 0) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "You can not remove first row.";
            return false;
        }
        if (index < $scope.custom.choices.length - 1) {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "While having row beneath you can not delete current row.";
            return false;
        }
        if (index >= 0) {
            $scope.custom.choices.splice(index, 1);
            $scope.calculation_error = false;
            $scope.calculation_error_msg = '';
        }
                $scope.totalCalculateAmount = $scope.custom.WinningAmount;
                $scope.remainingAmount = 0;
                let total = 0;
        for (var i = 0; i < $scope.custom.choices.length; i++) {
              
            if($scope.custom.choices[i].amount === "")
            {
               
                $scope.custom.choices[i].amount = parseFloat(0);
            }

            total = total + parseFloat($scope.custom.choices[i].amount);
         }
         $scope.remainingAmount = $scope.totalCalculateAmount - total;

    }
  
  $scope.removeChildFields = function (index) {
       
        if (index >= 0) {
                //$scope.remainingPercentage = 100;
                $scope.custom.choices.splice(index+1);
                $scope.calculation_error = false;
                $scope.calculation_error_msg = '';

                $scope.totalCalculateAmount = $scope.custom.WinningAmount;
                $scope.remainingAmount = 0;
                let total = 0;
        for (var i = 0; i < $scope.custom.choices.length; i++) {
              //total = total;
           
            if($scope.custom.choices[i].amount === "")
            {
              
                $scope.custom.choices[i].amount = 0;
            }

            total = total + parseFloat($scope.custom.choices[i].amount);
         }
         $scope.remainingAmount = $scope.totalCalculateAmount - total;

            
        }
    }



    /*------------ show  and hide form-------------------*/
    $scope.showField = false;
    $scope.numbers = [];
    $scope.Showform = function () {
        console.log($scope.custom.NoOfWinners)

        if ($scope.custom.NoOfWinners == '' || $scope.custom.NoOfWinners == '0') {
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Please enter proper winner count!";
            return false;
        }
        $scope.remainingAmount = 0;
        if ($scope.custom.NoOfWinners && $scope.custom.DraftSize) {
            if ($scope.numbers == '') {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i);
                }
            } else {
                for (var i = 1; i <= parseInt($scope.custom.NoOfWinners); i++) {
                    $scope.numbers.push(i)
                    $scope.numbers.splice(i);
                }
            }
            $scope.custom.choices[0].numbers = $scope.numbers;
            if (parseInt($scope.custom.DraftSize) >= parseInt($scope.custom.NoOfWinners)) {
                $scope.error = false;
                $scope.showField = true;
            } else {
                $scope.error = true;
                $scope.showField = false;
                return false;
            }
        } else {
            $scope.error = true;
            $scope.showField = false;
            $scope.calculation_error = true;
            $scope.calculation_error_msg = "Please enter proper winner count!";
            return false;
        }
    }
    $scope.contestPrizeParser = function ($choices)
    {
        let response = [];
        let valueArray = [];
        for (var $i = 0; $i < $scope.custom.choices.length; $i++)
        {
            valueArray.push({'From': $scope.custom.choices[$i].From, 'To': $scope.custom.choices[$i].To, 'Percent': $scope.custom.choices[$i].percent, 'WinningAmount': $scope.custom.choices[$i].amount});
        }
        response = valueArray;
        return response;
    }


    /*create contest calculations ends*/

    /*--------------------------------------------------------------------------------------*/

    /*load edit form*/

    $scope.loadFormStatus = function (Position, PredraftContestID)
    {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/updateStatus_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'admin/PredraftContest/getPredraft', 'SessionKey=' + SessionKey + '&PredraftContestID=' + PredraftContestID + '&Params=DraftName,Status', contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data.Records[0]
                $('#status_model').modal({show: true});
                $timeout(function () {

                    $(".chosen-select").chosen({width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                }, 200);
            }
        });
    }

    $scope.loadContestJoinedUser = function (Position, ContestGUID)
    {

        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/joinedContest_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'contest/getJoinedContestsUsers', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=UserTeamName,TotalPoints,UserWinningAmount,FirstName,Username,UserGUID,UserTeamPlayers,UserTeamID,UserRank', contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
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

        $http.post(API_URL + 'contest/getContests', 'SessionKey=' + SessionKey + '&ContestGUID=' + ContestGUID + '&Params=Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,SeriesID,MatchID,SeriesGUID,TeamNameLocal,TeamNameVisitor,SeriesName,CustomizeWinning,ContestType,CashBonusContribution,UserJoinLimit,ContestFormat,IsConfirm,ShowJoinedContest,TotalJoined', contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
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

    /*edit status*/
    $scope.editStatus = function (Status, PredraftContestID)
    {
        $scope.editDataLoading = true;
        var data = 'SessionKey=' + SessionKey + '&PredraftContestID=' + PredraftContestID + '&Status=' + Status;
        $http.post(API_URL + 'admin/PredraftContest/changeStatus', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = Status;
                $('.modal-header .close').click();
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }

        // load predraft contest detail
        $scope.loadPredraftContest = function (Position, PredraftContestID) {

            $scope.data.Position = Position;
            $scope.templateURLEdit = PATH_TEMPLATE + module + '/deatil_form.htm?' + Math.random();
            $scope.data.pageLoading = true;
            $http.post(API_URL + 'admin/PredraftContest/getPredraft', 'SessionKey=' + SessionKey + '&PredraftContestID=' + PredraftContestID + '&Params=Privacy,AdminPercent,IsPaid,WinningAmount,DraftSize,EntryFee,NoOfWinners,EntryType,Status,CustomizeWinning,DraftType,TotalJoined,CashBonusContribution,UserJoinLimit,DraftFormat,DraftName,ShowJoinedDraft,UnfilledWinningPercent,IsAutoCreate,IsConfirm,MatchStartDateTime', contentType).then(function (response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if (response.ResponseCode == 200) { /* success case */
                    $scope.data.pageLoading = false;
                    $scope.predraftData = response.Data.Records[0];
                    $('#predraftcontest_model').modal({ show: true });
                    $timeout(function () {
                        $(".chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, "placeholder_text_multiple": "Please Select", }).trigger("chosen:updated");
                    }, 200);
                }
            });
            $('.table').removeProperty('min-height');
        }
        // End
}); 

/* sortable - ends */