app.controller('PageController', function ($scope, $http,$timeout){

    var FromDate = ToDate = ''; 
    $scope.data.pageSize = 15;
    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;

    $timeout(function(){            
        $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
     }, 200);

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

    /*----------------*/
     /*list*/
    $scope.applyFilter = function() {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    $scope.TransactionMode = 'All';
    /*list append*/
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&Params=Amount,UserGUID,ProfilePic,Email,EmailForChange,PhoneNumber,PhoneNumberForChange,PaymentGateway,Status,EntryDate,FullName,MediaBANK,Comments&PageNo=' + $scope.data.pageNo + '&PageSize=' + $scope.data.pageSize + '&FromDate=' + FromDate + '&ToDate=' + ToDate + '&OrderBy=EntryDate&Sequence=DESC&' + $('#filterForm1').serialize()+'&'+$('#filterForm').serialize();
        $http.post(API_URL+'admin/users/getWithdrawals', data, contentType).then(function(response) {
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

    /*load edit form*/
    $scope.loadFormEdit = function(Position, WithdrawalID) {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE + module + '/edit_form.htm?' + Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL + 'admin/users/getWithdrawals', 'SessionKey=' + SessionKey + '&WithdrawalID=' + WithdrawalID + '&Params=Params=Amount,PaymentGateway,Status,EntryDate,FullName,Email,PhoneNumber,ProfilePic,MediaBANK,UserID', contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data.Records[0];
                
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

    /*edit data*/
    $scope.editData = function(Status) {
        $scope.editDataLoading = true;
        var person = '';
        if (Status == 'Rejected') {
            var person = prompt("Reason for Rejection-", '');
            if (person == null || person == '') {
                alertify.error("Reject Reason is Required");
                $scope.editDataLoading = false;
                return false;   
            }
        }
        var data = 'SessionKey=' + SessionKey +'&Comments='+person+ '&Params=Status&' + $('#edit_form').serialize();
        $http.post(API_URL + 'admin/users/changeWithdrawalStatus', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200) { /* success case */
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $scope.data.dataList[$scope.data.Position].Comments = person;
                $('.modal-header .close').click();
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }

    /*Export List*/
    $scope.ExportList = function() {
        var data = 'SessionKey='+SessionKey+'&Params=Amount,Email,PhoneNumber,PaymentGateway,Status,EntryDate,FirstName,MediaBANK&' + $('#filterForm1').serialize();
        $http.post(API_URL + 'admin/users/exportWithdrawals', data, contentType).then(function(response) {
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
