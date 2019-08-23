'use strict';

app.controller('PageController', function($scope, $http, $timeout) {
    $timeout(function () {
        $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8 }).trigger("chosen:updated");
    }, 300);

    function arrayColumn(array, columnName) {
        return array.map(function(value, index) {
            return value[columnName];
        })
    }
    $scope.UserTypeID = UserTypeID;

    /*edit Data */
    $scope.editData = function() {
        $scope.editDataLoading = true;
        var data = $('#editForm').serialize() + '&SessionKey=' + SessionKey;
        $http.post(API_URL + 'setup/editGroup', data, contentType).then(function(response) {
            var response = response.data;
            if (response.ResponseCode == 200 && response.Data) {
                $scope.data.dataList[$scope.data.Position] = response.Data;
                alertify.success(response.Message);
                $('.modal-header .close').click();
            } else {
                alertify.error(response.Message);
            }
            $scope.editDataLoading = false;
        });
    }

    /*load edit form*/
    $scope.ModuleName = ''
    $scope.loadFormEdit = function(Position, UserTypeGUID) {
        $scope.data.loadFormEdit = true;
        $scope.data.Position = Position;
        var data = 'SessionKey=' + SessionKey +
            '&UserTypeGUID=' + UserTypeGUID +
            '&Params=UserTypeID,Modules';
        $http.post(API_URL + 'setup/getGroup', data, contentType).then(function(response) {
            var response = response.data;
            if (response.ResponseCode == 200 && response.Data) {
                $scope.formData = response.Data;
                
                $scope.formData.PermittedModules.map(function(value) {
                    if(value.IsDefault == "Yes"){
                        $scope.ModuleName = value.ModuleName
                    }
                })
                
            } else {
                $scope.data.noRecords = true;
            }
            $scope.data.loadFormEdit = false;
        });
        $scope.loadFormAdd();
    }

    /*list*/
    $scope.applyFilter = function ()
    {
        $scope.data = angular.copy($scope.orig); /*copy and reset from original scope*/
        $scope.getList();
    }

    /*show listing*/
    $scope.getList = function() {
       $scope.data.listLoading = true;
        var data = 'SessionKey=' + SessionKey + '&Params=UserTypeID,Modules';
        $http.post(API_URL + 'setup/getGroups', data, contentType).then(function(response) {
            var response = response.data;
            if (response.ResponseCode == 200 && response.Data.Records) {
                /* success case */
                $scope.TotalRecords = response.Data.TotalRecords;
                for (var i in response.Data.Records) {
                    $scope.data.dataList.push(response.Data.Records[i]);
                }
            } else {
                $scope.data.noRecords = true;
            }
            $scope.data.listLoading = false;
        });
    }

    /*load add form*/
    $scope.loadFormAdd = function() {
        $('#edit_permission_modal').modal({
            show: true
        });
    }

    /*add Group data*/
    $scope.addGroupData = function ()
    {
        $scope.addDataLoading = true;
        var data = 'SessionKey='+SessionKey+'&'+$("form[name='add_form']").serialize();
        $http.post(API_URL+'setup/addGroup', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            if(response.ResponseCode==200){ /* success case */               
                alertify.success(response.Message);
                $('.modal-header .close').click();
                $scope.applyFilter();
            }else{
                alertify.error(response.Message);
            }
            $scope.addDataLoading = false;          
        });
        
    }

    $scope.OldValue = '';
    $scope.SetDefaultModule = function(ModuleName) {
        //console.log($scope.IsDefault)
        for(let i in $scope.formData.PermittedModules){            
            if($scope.formData.PermittedModules[i].ModuleName == $scope.OldValue){
                $scope.formData.PermittedModules[i].Permission = '';
            }
            if($scope.formData.PermittedModules[i].ModuleName == ModuleName){
                $scope.formData.PermittedModules[i].Permission = 'Yes';
                $scope.OldValue = ModuleName;
            }
        }  
    }

});