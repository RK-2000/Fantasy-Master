app.controller('PageController', function ($scope, $http,$timeout){

    var FromDate = ToDate = ''; 

    $scope.data.pageSize = 15;
    /*----------------*/
    $scope.getFilterData = function ()
    {
        var data = 'SessionKey='+SessionKey+'&StatusID=2&Params=SeriesName,SeriesGUID&'+$('#filterPanel form').serialize();
        $http.post(API_URL+'admin/matches/getFilterData', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data){ 
                /* success case */
             $scope.filterData =  response.Data;
             $timeout(function(){
                $("select.chosen-select").chosen({ width: '100%',"disable_search_threshold": 8}).trigger("chosen:updated");
            }, 300);          
         }
     });
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


    $scope.getTeamData = function (SeriesGUID,LocalTeamGUID='')
    {
        if (!SeriesGUID) {
            $scope.LocalTeamData = [];
            $timeout(function () {
                $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8 }).trigger("chosen:updated");
            }, 300);
            return;
        }
        var data = 'SessionKey=' + SessionKey + '&LocalTeamGUID='+LocalTeamGUID+'&SeriesGUID='+ SeriesGUID+'&Params=TeamNameLocal,TeamNameVisitor,TeamGUID&' + $('#filterPanel form').serialize();
        $http.post(API_URL + 'admin/matches/getTeamData', data, contentType).then(function (response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if (response.ResponseCode == 200 && response.Data) {
                /* success case */
                if (LocalTeamGUID != '') {
                    $scope.VisitorTeamData = response.Data;
                }else{
                    $scope.LocalTeamData = response.Data;
                }
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
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+ '&MatchStartFrom=' + FromDate + '&MatchEndTo=' + ToDate +'&OrderByToday=Yes&Params='+'SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status&PageNo='+$scope.data.pageNo+'&PageSize='+$scope.data.pageSize+'&'+$('#filterForm').serialize()+'&'+$('#filterForm1').serialize();
        $http.post(API_URL+'admin/matches/getMatches', data, contentType).then(function(response) {
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
        // setTimeout(function(){ tblsort(); }, 1000);
    });
    }
    
    /*load add form*/
    $scope.loadFormAdd = function (Position, CategoryGUID)
    {
        $scope.templateURLAdd = PATH_TEMPLATE+module+'/add_form.htm?'+Math.random();
        $('#add_model').modal({show:true});
        $timeout(function(){            
           $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
       }, 200);
    }



    /*load edit form*/
    $scope.loadFormEdit = function (Position, MatchGUID)
    { 
      
        $scope.data.Position = Position;
        $scope.templateURLEdit = PATH_TEMPLATE+module+'/edit_form.htm?'+Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL+'admin/matches/getMatches', 'MatchGUID='+MatchGUID+'&Params=Status,MatchClosedInMinutes,SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation&SessionKey='+SessionKey, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data.Records[0]
                $('#edit_model').modal({show:true});
                $timeout(function(){            
                   $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
               }, 200);
            }
        });
    }

    /*load delete form*/
    $scope.loadFormDelete = function (Position, CategoryGUID)
    {
        $scope.data.Position = Position;
        $scope.templateURLDelete = PATH_TEMPLATE+module+'/delete_form.htm?'+Math.random();
        $scope.data.pageLoading = true;
        $http.post(API_URL+'category/getCategory', 'SessionKey='+SessionKey+'&CategoryGUID='+CategoryGUID, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */
                $scope.data.pageLoading = false;
                $scope.formData = response.Data
                $('#delete_model').modal({show:true});
                $timeout(function(){            
                   $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
               }, 200);
            }
        });
    }

    /*edit data*/
    $scope.editData = function ()
    {
        $scope.editDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+$("form[name='edit_form']").serialize();
        $http.post(API_URL+'admin/matches/changeStatus', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */               
                alertify.success(response.Message);
                $scope.data.dataList[$scope.data.Position].MatchClosedInMinutes = response.Data.MatchClosedInMinutes;
                $scope.data.dataList[$scope.data.Position].Status = response.Data.Status;
                $('.modal-header .close').click();
            }else{
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;          
        });
    }
}); 
