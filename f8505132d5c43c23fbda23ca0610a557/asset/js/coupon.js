app.controller('PageController', function ($scope, $http,$timeout){

    var FromDate = ToDate = ''; 
    $scope.DEFAULT_CURRENCY = DEFAULT_CURRENCY;

    $timeout(function(){            
       $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
    }, 200);

    /*list*/
    $scope.applyFilter = function ()
    {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /* Reset form */
    $scope.resetForm = function(){
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
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&PageNo='+$scope.data.pageNo+'&PageSize='+$scope.data.pageSize + '&ValidFrom=' + FromDate + '&ValidTo=' + ToDate +'&'+$('#filterForm1').serialize();
        $http.post(API_URL+'store/getCoupons', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data.Records){ /* success case */
                $scope.data.totalRecords = response.Data.TotalRecords;
                for (var i in response.Data.Records) {
                   $scope.data.dataList.push(response.Data.Records[i]);
               }
               $scope.data.pageNo++;               
           }else{
            $scope.data.noRecords = true;
        }
        $scope.data.listLoading = false;
    });
    }



    /*load edit form*/
    $scope.loadFormAdd = function (Position, StoreGUID)
    {
        $scope.templateURLAdd = PATH_TEMPLATE+module+'/add_form.htm?'+Math.random();
        $('#add_model').modal({show:true});
        $timeout(function(){        
           $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
           $('input[name=CouponValidTillDate]').datetimepicker({format: "yyyy-mm-dd",minView: 2, startDate: new Date()});
       }, 500);
    }


    /*load delete form*/
    $scope.loadFormEdit = function (Position, CouponGUID)
    {
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE+module+'/edit_form.htm?'+Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL+'store/getCoupon', 'SessionKey='+SessionKey+'&CouponGUID='+CouponGUID, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#Edit_model').modal({show:true});
                $timeout(function(){            
                 $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
                 $('input[name=CouponValidTillDate]').datetimepicker({format: "yyyy-mm-dd",minView: 2, startDate: new Date()});
             }, 300);
            }
        });

    }




    /*add data*/
    $scope.addData = function ()
    {
        $scope.addDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+$("form[name='add_form']").serialize();
        $http.post(API_URL+'admin/store/addCoupon', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */               
                alertify.success(response.Message);
                $scope.applyFilter();
                $('.modal-header .close').click();
            }else{
                alertify.error(response.Message);
            }
            $scope.addDataLoading = false;          
        });
    }




    /*edit data*/
    $scope.editData = function ()
    {
        $scope.editDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+$('#edit_form').serialize();
        $http.post(API_URL+'admin/store/editCoupon', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */               
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $scope.data.dataList[$scope.data.Position].CouponValidTillDate = response.Data.CouponValidTillDate;
                $('.modal-header .close').click();
            }else{
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;          
        });
    }



}); 





