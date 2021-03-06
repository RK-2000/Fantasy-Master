app.controller('PageController', function ($scope, $http,$timeout){
    $scope.data.pageSize = 100;
    $scope.data.ParentCategoryGUID = ParentCategoryGUID;
    /*----------------*/

    /*list append*/
    $scope.getList = function ()
    {
        if ($scope.data.listLoading || $scope.data.noRecords) return;
        $scope.data.listLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+$('#filterForm').serialize();
        $http.post(API_URL+'admin/config/bannerList', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200 && response.Data.Records){ /* success case */
                $scope.data.totalRecords = response.Data.TotalRecords;
                 $scope.data.dataList = response.Data.Records;
                /*for (var i in response.Data.Records) {
                    $scope.data.dataList.push(response.Data.Records[i]);
                }
                $scope.data.pageNo++;               */
            }else{
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
        });
    }


    /*add data*/
    $scope.updateConfigData = function (ConfigTypeGUID,ConfigValue,ConfigStatus)
    {
        // console.log(ConfigTypeGUID+' '+ConfigValue+' '+ConfigStatus); return false;
        $scope.addDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+'ConfigTypeGUID='+ConfigTypeGUID+'&ConfigTypeValue='+ConfigValue+'&Status='+ConfigStatus;
        $http.post(API_URL+'admin/config/update', data, contentType).then(function(response) {
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


    /*load add form*/
    $scope.loadFormAdd = function (Position, CategoryGUID)
    {
        $scope.templateURLAdd = PATH_TEMPLATE+module+'/add_form.htm?'+Math.random();
        $('#add_model').modal({show:true});
        $timeout(function(){            
           $(".chosen-select").chosen({ width: '100%',"disable_search_threshold": 8 ,"placeholder_text_multiple": "Please Select",}).trigger("chosen:updated");
       }, 200);
    }

     /*add data*/
    $scope.addData = function ()
    {
        $scope.addDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&Section=Banner&'+$("form[name='add_form']").serialize();
        $http.post(API_URL+'admin/config/addBanner', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */               
                alertify.success(response.Message);
                window.location.reload();
                $('.modal-header .close').click();
                $scope.applyFilter();
            }else{
                alertify.error(response.Message);
            }
            $scope.addDataLoading = false;          
        });
    }

    /*load delete form*/
    $scope.loadFormDelete = function (Position, MediaGUID)
    {
        if(confirm('Are you sure, want to delete this banner ?')){
            $scope.addDataLoading = true;
            $http.post(API_URL+'upload/delete', 'SessionKey='+SessionKey+'&MediaGUID='+MediaGUID, contentType).then(function(response) {
                var response = response.data;
                manageSession(response.ResponseCode);
                if(response.ResponseCode==200){ /* success case */
                    $scope.getList();        
                    alertify.success(response.Message);
                }else{
                    alertify.error(response.Message);
                }
                $scope.addDataLoading = false; 
            });
        }
    }

}); 

