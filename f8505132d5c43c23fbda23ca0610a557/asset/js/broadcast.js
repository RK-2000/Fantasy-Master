app.controller('PageController', function ($scope, $http, $timeout) {

  $timeout(function () {
      $("select.chosen-select").chosen({ width: '100%', "disable_search_threshold": 4, }).trigger("chosen:updated");
  }, 300);

  /*add data*/
  $scope.addData = function () {
      $scope.addDataLoading = true;
      var data = 'SessionKey=' + SessionKey + '&' + $("form[name='add_form']").serialize() + '&EmailMessage=' + tinyMCE.get('editor').getContent();
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

  $scope.NotificationType = 'Email';
  $scope.changeNotificationType = function (NotificationType) {
      $scope.NotificationType = NotificationType;
      if (NotificationType == 'Email') {
          window.location.reload();
      }
  }
  $scope.getUsers = function () {
      var data = 'SessionKey=' + SessionKey + '&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus';
      $http.post(API_URL + 'admin/users', data, contentType).then(function (response) {
          var response = response.data;
          manageSession(response.ResponseCode);
          $scope.userData = response.Data.Records    
      });
  };

});


$(document).ready(function () {
  tinymce.init({
      selector: '#editor',
      font_size_classes: "fontSize1, fontSize2, fontSize3, fontSize4, fontSize5, fontSize6",
      plugins: [
          "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
          "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
          "save table contextmenu directionality template paste textcolor code"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | sizeselect | fontselect | fontsize | fontsizeselect",
      style_formats: [{
          title: 'Bold text',
          inline: 'b'
      }, {
          title: 'Red text',
          inline: 'span',
          styles: {
              color: '#ff0000'
          }
      }, {
          title: 'Red header',
          block: 'h1',
          styles: {
              color: '#ff0000'
          }
      }, {
          title: 'Example 1',
          inline: 'span',
          classes: 'example1'
      }, {
          title: 'Example 2',
          inline: 'span',
          classes: 'example2'
      }, {
          title: 'Table styles'
      }, {
          title: 'Table row 1',
          selector: 'tr',
          classes: 'tablerow1'
      }],
      image_title: true,
      automatic_uploads: true
  });

})





