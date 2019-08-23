
var app = angular.module('FANTASY', ['ngStorage', 'ngAnimate', 'toastr', '720kb.datepicker', 'ngFileUpload', 'socialLogin', 'infinite-scroll', 'ngCookies']);
var contentType = {
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    }
};
app.factory('socket',['$location', function ($location) { 
    //Creating connection with server
    var path = 'https://159.65.135.44:3000';
    if($location.$$host === 'localhost'){
        path = 'http://192.168.1.14:3000';
    }
    var socket = io.connect(path);
    //This part is only for login users for authenticated socket connection between client and server.
    //If you are not using login page in you website then you should remove rest piece of code..

    return socket;

}]);
/*main controller*/
app.controller('MainController', ["$scope", "$http", "$timeout", "$localStorage", "$sessionStorage", "appDB", "toastr",'$rootScope', function ($scope, $http, $timeout, $localStorage, $sessionStorage, appDB, toastr,$rootScope) {
        $scope.data = {dataList: [], totalRecords: '0', pageNo: 1, pageSize: 25, noRecords: false, UserGUID: UserGUID, notificationCount: 0};
        $scope.orig = angular.copy($scope.data);
        $scope.UserTypeID = UserTypeID;
        $scope.base_url = base_url;
        /*delete Entity*/

        $scope.deleteData = function (EntityGUID)
        {
            $scope.deleteDataLoading = true;
            alertify.confirm('Are you sure you want to delete?', function () {
                var data = 'SessionKey=' + SessionKey + '&EntityGUID=' + EntityGUID;
                $http.post(API_URL + 'api_admin/entity/delete', data, contentType).then(function (response) {
                    var response = response.data;
                    if (response.ResponseCode == 200) { /* success case */
                        alertify.success(response.Message);
                        $scope.data.dataList.splice($scope.data.Position, 1); /*remove row*/
                        $scope.data.totalRecords--;
                        $('.modal-header .close').click();
                    } else {
                        alertify.error(response.Message);
                    }
                    if ($scope.data.totalRecords == 0) {
                        $scope.data.noRecords = true;
                    }
                });
            }).set('labels', {ok: 'Yes', cancel: 'No'});
            $scope.deleteDataLoading = false;

        }

        
        $scope.amount = 100;
        $rootScope.profileDetails = {};
        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {

            $scope.getWalletDetails = function () {
                var $data = {};
                $data.UserGUID = $localStorage.user_details.UserGUID;
                $data.SessionKey = $localStorage.user_details.SessionKey;
                $data.Params = 'IsPrivacyNameDisplay,FirstName, Email,ProfilePic,WalletAmount,WinningAmount,CashBonus,TotalCash';
                appDB
                        .callPostForm('users/getProfile', $data)
                        .then(
                                function successCallback(data)
                                {

                                    if (data.ResponseCode == 200)
                                    {
                                        
                                        $rootScope.profileDetails = data.Data;
                                        $scope.WinningAmount = $rootScope.profileDetails.WinningAmount;
                                    }
                                    if (data.ResponseCode == 500) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);

                                    }
                                    if (data.ResponseCode == 501) {
                                        var toast = toastr.warning(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                },
                                function errorCallback(data)
                                {

                                    if (typeof data == 'object')
                                    {
                                        var toast = toastr.error(data.Message, {
                                            closeButton: true
                                        });
                                        toastr.refreshTimer(toast, 5000);
                                    }
                                });


            }
            $scope.getWalletDetails();
        }

        $scope.checkResponseCode = function (data) {
            if (data.ResponseCode == 200) {
                return true;
            } else if (data.ResponseCode == 500) {
                var toast = toastr.warning(data.Message, {
                    closeButton: true
                });
                toastr.refreshTimer(toast, 5000);
                return false;
            } else if (data.ResponseCode == 501) {
                var toast = toastr.warning(data.Message, {
                    closeButton: true
                });
                toastr.refreshTimer(toast, 5000);
                return false;
            } else if (data.ResponseCode == 502) {
                var toast = toastr.warning(data.Message, {
                    closeButton: true
                });
                toastr.refreshTimer(toast, 5000);
                setTimeout(function () {
                    localStorage.clear();
                    window.location.reload();
                }, 1000);
                return false;
            }
        }
        
        $scope.errorMessageShow = function(Message){
            var toast = toastr.error(Message, {
                    closeButton: true
                });
            toastr.refreshTimer(toast, 5000);
        }
        $scope.successMessageShow = function(Message){
            var toast = toastr.success(Message, {
                    closeButton: true
                });
            toastr.refreshTimer(toast, 5000);
        }
        $scope.warningMessageShow = function(Message){
            var toast = toastr.warning(Message, {
                    closeButton: true
                });
            toastr.refreshTimer(toast, 5000);
        }
// Listen for click on toggle checkbox
        $(document).on('click', "#select-all", function (event) {
            $('.select-all-checkbox').not(this).prop('checked', this.checked);
        });

        $(document).on('click', ".select-all-checkbox", function (event) {
            var anyBoxesChecked = false;
            $('.select-all-checkbox').each(function () {
                if ($(this).is(":checked")) {
                    anyBoxesChecked = true;
                }
            });

            if (anyBoxesChecked) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }

        });
        

        $scope.getPlayerShortName = function (PlayerName) {
            var FirstLetter = PlayerName.substr(0, 1);
            var SecondLetter = PlayerName.substr(PlayerName.indexOf(' ') + 1);
            return FirstLetter + ' ' + SecondLetter;
        }

        if ($localStorage.hasOwnProperty('user_details') && $localStorage.isLoggedIn == true) {
            $scope.isLoggedIn = $localStorage.isLoggedIn;
            $scope.user_details = $localStorage.user_details;
        }

        $scope.moneyFormat = function (money) {
            money = Number(money);
            var a = money.toLocaleString('en-IN', {
                maximumFractionDigits: 2,
                style: 'currency',
                currency: 'INR'
            });
            return a;
        }
    }]);

/*jquery*/
$(document).ready(function () {

    /*Submit Form*/
    $(".form-control").keypress(function (e) {
        if (e.which == 13) {
            $(this.form).find(':submit').focus().click();
        }
    });
    $('[data-toggle="tooltip"]').tooltip();

    /*disable right click*/
    $('html').on("contextmenu", function (e) {
//        return false;
    });


    $(document).on('keypress', ".numeric", function (event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8 || event.keyCode === 46) {
            return true;
        } else if (key < 48 || key > 57) {
            return false;
        } else {
            return true;
        }
    });


    $(document).on('keypress', ".integer", function (event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8/* || event.keyCode === 46*/) {
            return true;
        } else if (key < 48 || key > 57) {
            return false;
        } else {
            return true;
        }
    });




    /*upload profile picture*/
    $(document).on('click', "#picture-uploadBtn", function () {
        $(this).parent().find('#fileInput').focus().val("").trigger('click');
    });


    $(document).on('change', '#fileInput', function () {
        var target = $(this).data('target');
        var mediaGUID = $(this).data('targetinput');
        var progressBar = $('.progressBar'), bar = $('.progressBar .bar'), percent = $('.progressBar .percent');
        $(this).parent().ajaxForm({
            data: {SessionKey: SessionKey},
            dataType: 'json',
            beforeSend: function () {
                progressBar.fadeIn();
                var percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            uploadProgress: function (event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            success: function (obj, statusText, xhr, $form) {
                if (obj.ResponseCode == 200) {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                    $(target).prop("src", obj.Data.MediaURL);
                    //$("input[name='MediaGUIDs']").val(obj.Data.MediaGUID);
                    $(mediaGUID).val(obj.Data.MediaGUID);
                } else {
                    alertify.error(obj.Message);
                }
            },
            complete: function (xhr) {
                progressBar.fadeOut();
                $('#fileInput').val("");
            }
        }).submit();
    });

    $(document).on('keypress', ".numeric", function (event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8 || event.keyCode === 46) {
            return true;
        } else if (key < 48 || key > 57) {
            return false;
        } else {
            return true;
        }
    });


});/* document ready end */
function getQueryStringValue(key)
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return (!vars[key]) ? '' : vars[key];
}


