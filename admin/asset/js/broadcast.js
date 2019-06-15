app.controller('PageController', function ($scope, $http,$timeout){

  /*add data*/
  $scope.addData = function ()
  {
    $scope.addDataLoading = true;
    var data = 'SessionKey='+SessionKey+'&'+$("form[name='add_form']").serialize();
    $http.post(API_URL+'admin/users/broadcast', data, contentType).then(function(response) {
        var response = response.data;
        if(response.ResponseCode==200){ /* success case */               
            alertify.success(response.Message);
            setTimeout(function(){
              location.reload();
            },800);
        }else{
            alertify.error(response.Message);
        }
        $scope.addDataLoading = false;          
    });
}


}); 





