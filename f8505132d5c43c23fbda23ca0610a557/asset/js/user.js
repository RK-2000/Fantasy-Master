app.controller('PageController', function($scope, $http, $timeout) {

    var FromDate = ToDate = ''; 

    $timeout(function(){            
       $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
    }, 200);
    
    /*list*/
    $scope.applyFilter = function() {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }
    
    /* Reset form */
    $scope.resetUserForm = function(){
        $('#filterForm1').trigger('reset'); 
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

    /*list append*/
    $scope.applyOrderedList = function(OrderBy, Sequence) {
        PSequence = $scope.data.Sequence;
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/

        $scope.data.OrderBy = OrderBy;
        if (PSequence == '' || PSequence == 'ASC' || typeof PSequence == 'undefined') {
            $scope.data.Sequence = 'DESC';
        } else {
            $scope.data.Sequence = 'ASC';
        }

        $scope.getList(); 

    }

    /*list append*/ 
    $scope.getList = function() {

        if(getQueryStringValue('Type')){
            var ListType = getQueryStringValue('Type'); 
        }else{
            var ListType = '';
        }
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey +'&ListType='+ListType+'&IsAdmin=No&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&OrderBy=' + $scope.data.OrderBy + '&EntryFrom=' + FromDate + '&EntryTo=' + ToDate + '&Sequence=' + $scope.data.Sequence + '&' +'Params=RegisteredOn,EmailForChange,LastLoginDate,UserTypeName, FullName, Email, Username, ProfilePic, Gender, BirthDate, PhoneNumber,PhoneNumberForChange, Status,EmailStatus,PhoneStatus, ReferredCount,StatusID,WalletAmount,CashBonus,WinningAmount&'+$('#filterForm1').serialize()+'&'+$('#filterForm').serialize();

        $http.post(API_URL + 'admin/users', data, contentType).then(function(response) {
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

    /*Resend Mail*/
    $scope.ResendVerificationMail = function(UserGUID) {
        var data = 'SessionKey=' + SessionKey + '&Type=Email&UserGUID='+ UserGUID;
        $http.post(API_URL + 'signup/resendVerification', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
            } else {
                alertify.error(response.Message);
            }
        });
    }

    /*load edit form*/
    $scope.loadFormEdit = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/edit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=IsPrivacyNameDisplay,Status,EmailStatus,PhoneStatus,ProfilePic', contentType).then(function(response) {
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

    /*load edit form*/
    $scope.loadFormChangePassword = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.ChangePasswordformData = response.Data
                $('#changeUserPassword_form').modal({
                    show: true
                });
            }
        });

    }

     /*load edit form*/
    $scope.loadFormAddCash = function(Position, UserGUID) {
        
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/addCashBonus_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=FirstName,ProfilePic,Status', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#AddCashBonus_model').modal({
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
     /*loadFormAddCashDeposit edit form*/
    $scope.loadFormAddCashDeposit = function(Position, UserGUID) {
        
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/addCashBonusDeposit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=FirstName,ProfilePic,Status', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#AddCashBonusDeposit_model').modal({
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

    /*load edit form*/
    $scope.loadFormReferredUsersList = function(Position, UserGUID) {
        
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/referredUserlist_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'admin/users/getReferredUsers', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=FirstName,ProfilePic,Email,Status, Gender, BirthDate, PhoneNumber', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#referralUserList_model').modal({
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

    /*load verification form*/
    $scope.loadFormVerification = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/verification_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey=' + SessionKey + '&UserGUID=' + UserGUID + '&Params=Status,ProfilePic,MediaPAN,MediaBANK,PanStatus,BankStatus', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data;
                
            
                console.log($scope.formData);
                $('#Verification_model').modal({
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

    /*delete selected */
    $scope.deleteSelectedRecords = function() {
        alertify.confirm('Are you sure you want to delete?', function() {
            var data = 'SessionKey=' + SessionKey + '&' + $('#records_form').serialize();
            $http.post(API_URL + 'admin/entity/deleteSelected', data, contentType).then(function(response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if (response.ResponseCode == 200) { /* success case */
                    alertify.success(response.Message);
                    $scope.applyFilter();
                    window.location.reload();
                } else {
                    alertify.error(response.Message);
                }
                if ($scope.data.totalRecords == 0) {
                    $scope.data.noRecords = true;
                }
            });
        }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }


    /*edit data*/
    $scope.editData = function() {
        $scope.editDataLoading = true;
        var data = 'SessionKey=' + SessionKey + '&' + $('#edit_form').serialize();
        $http.post(API_URL + 'admin/users/changeStatus', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $('.modal-header .close').click();
                $timeout(function(){            
                   window.location.reload();
                }, 200);
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }

    /*change password*/
    $scope.changeUserPassword = function() {
        $scope.changeCP = true;
        var data = 'SessionKey=' + SessionKey + '&'+$('#changePassword_form').serialize();
        $http.post(API_URL + 'users/changePassword', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 ) { /* success case */
                $('.modal-header .close').click();
                alertify.success(response.Message);
            } else {
                alertify.error(response.Message);
            }
            $scope.changeCP = false;    
        });
    }

    /*add cash bonus data*/
    $scope.addCashBonus = function() {
        $scope.editDataLoading = true;
        var data = 'SessionKey=' + SessionKey + '&Status=Completed&Narration=Admin Cash Bonus&' + $('#addCash_form').serialize();
        $http.post(API_URL + 'admin/users/addCashBonus', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $('.modal-header .close').click();
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }
    /*add cash deposit data*/
    $scope.addCashDeposit = function() {
        $scope.editDataLoading = true;
        var data = 'SessionKey=' + SessionKey + '&Status=Completed&Narration=Admin Deposit Money&' + $('#addCashDeposit_form').serialize();
        $http.post(API_URL + 'admin/users/addCashDeposit', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $('.modal-header .close').click();
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }


    /*edit data*/
    $scope.verifyDetails = function(UserGUID,VetificationType,Status) {
        $scope.editDataLoading = true;
        if(VetificationType=='PAN'){
            var Params = '&PanStatus='+Status;
        }else{
            var Params = '&BankStatus='+Status;
        }
        var data = 'SessionKey=' + SessionKey + '&UserGUID=' +UserGUID+'&VetificationType='+VetificationType+Params ;
        $http.post(API_URL + 'admin/users/changeVerificationStatus', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }


    /*load delete form*/
    $scope.loadFormDelete = function(Position, UserGUID) {
        $scope.data.Position = Position;
        $scope.templateURLDelete = PATH_TEMPLATE + module + '/delete_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'users/getProfile', 'SessionKey='+SessionKey+'&UserGUID='+UserGUID+'&Params=Status,ProfilePic', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#delete_model').modal({
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

    //export user list
    $scope.exportUsers = function () { 
        if ($scope.data.dataList.length > 0) {
            var varArr = [];
            for (var i = 0; i < $scope.data.dataList.length; i++) {
                var row = {};
                row.FullName = $scope.data.dataList[i]['FullName'];
                row.Email = $scope.data.dataList[i]['Email'];
                row.Username = $scope.data.dataList[i]['Username'];
                row.Gender = $scope.data.dataList[i]['Gender'];
                row.EmailStatus = $scope.data.dataList[i]['EmailStatus'];
                row.RegisteredOn = $scope.data.dataList[i]['RegisteredOn'];
                row.LastLoginDate = $scope.data.dataList[i]['LastLoginDate'];
                varArr.push(row);
            }
            $scope.JSONToCSVConvertor(varArr, 'export-user-list', true);
        }
        else {
            alertify.error('User Not Found');
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