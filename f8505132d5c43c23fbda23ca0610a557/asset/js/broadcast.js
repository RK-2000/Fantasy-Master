app.controller('PageController', function ($scope, $http, $timeout) {

  var FromDate = ToDate = ''; 

  $timeout(function () {
      $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8, }).trigger("chosen:updated");
  }, 300);

  /*add data*/
  $scope.addData = function () {
      $scope.addDataLoading = true;
      var data = 'SessionKey=' + SessionKey + '&' + $("form[name='add_form']").serialize();
      if ($('input[name="AllUsers"]').is(":checked")) {
        data += '&AllUsers=Yes';
      }
      $http.post(API_URL + 'admin/users/broadcast', data, contentType).then(function (response) {
          var response = response.data;
          manageSession(response.ResponseCode);
          if (response.ResponseCode == 200) {
              alertify.success(response.Message);
              window.location.reload();
          } else {
              alertify.error(response.Message);
          }
          $scope.addDataLoading = false;
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

  /* Get Users */
  $scope.getUsers = function () {
      $scope.data.listLoading = true;
      $scope.IsUsers = false;
        var data = 'SessionKey=' + SessionKey +'&IsAdmin=No&Status=Verified&OrderBy=FirstName&EntryFrom=' + FromDate + '&EntryTo=' + ToDate + '&Sequence=ASC&' +'Params=Status,Email,EmailStatus,PhoneNumber,PhoneStatus&'+$('#filterForm1').serialize();
        $http.post(API_URL + 'admin/users', data, contentType).then(function(response) {
            var response = response.data;
            manageSession(response.ResponseCode);
            $scope.userData = response.Data.Records
            $scope.data.listLoading = false;
            $scope.IsUsers = true;
            $timeout(function () {
                $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 8 }).trigger("chosen:updated");
            }, 300);
        });
  };

});





