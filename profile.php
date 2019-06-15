<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="profileController" ng-cloak >
    <div class="pt-5">
            <div class="top-header-title">
                <h3> Profile </h3>
            </div>
        <div class="profilePage" ng-init="getCountryList();getProfileInfo();">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 offset-sm-2">
                        <div class="accountContent">
                            <div class="accountHead">
                                <div class="accountHolder">
                                    <!-- <a href="javascript:void(0)" ng-click="removeProfilePic()"><i class="fa fa-times-circle"></i></a> -->
                                    <div class="accountImg">
                                        <img ng-src="{{profileDetails.ProfilePic}}" ng-if="profileDetails.ProfilePic" alt="" width="100%">
                                        <div class="profile_upload_btn">
                                            <form name="fileUpload" enctype="multipart/form-data" id="fileUpload">
                                                <input ngf-select ngf-accept="'image/*'" ng-change="pictureFile(picFile, '')" ng-model="picFile" name="file" id="file" type="file">
                                            </form>
                                        </div>
                                        <div id="myProgress" style="display: none;">
                                            <div id="myBar"></div>
                                        </div>
                                    </div>
                                    {{profileDetails.FullName}}
                                    <span>{{profileDetails.Email}}</span>
                                </div>

                            </div>

                            <form name="userform" class="form-commen" ng-submit="updateProfile(userform)" novalidate="">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input class="form-control customReadOnlyField" type="text" name="FirstName" ng-model="profileDetails.FirstName" ng-required="true" placeholder="Full Name" >
                                            <div style="color:red" ng-show="submitted && userform.FirstName.$error.required" class="form-error">
                                                *Full name is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Team Name</label>
                                            <input class="form-control customReadOnlyField" type="text" placeholder="Team Name" name="Username" ng-model="profileDetails.Username" ng-required="true" ng-minlength="6" ng-maxlength="8" >
                                            <div style="color:red" ng-show="submitted && userform.Username.$error.required" class="form-error">
                                                *Team Name is required.
                                            </div>
                                            <div style="color:red" ng-show="submitted && userform.Username.$error.minlength" class="form-error">*Team Name should be minimum 6 character long.
                                            </div>
                                            <div style="color:red" ng-show="submitted && userform.Username.$error.maxlength" class="form-error">*Team Name would be maximum 8 character long.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <div datepicker datepicker-class="test-custom-class" date-typer="true" date-format="yyyy-MM-dd" date-min-limit="{{minDateValidate.toString()}}" date-max-limit="{{dobValidate.toString()}}" >
                                                <input type="text"  placeholder="Birth Date" name="BirthDate" class="customReadOnlyField angular-datepicker-input form-control " ng-model="profileDetails.BirthDate" ng-required="true" />
                                            </div>
                                            <div style="color:red" ng-show="submitted && userform.BirthDate.$error.required" class="form-error">
                                                *Birth date is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="form-control customReadOnlyField selectpickerState" ng-model="profileDetails.Gender" name="Gender" ng-required="true">
                                                <option value="">Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <div style="color:red" ng-show="submitted && userform.Gender.$error.required" class="form-error">
                                                *Gender is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Country </label>
                                            <select class="form-control customReadOnlyField selectpickerState" ng-model="profileDetails.CountryCode" name="Country" ng-required="true" >
                                                <option value="">Select Country</option>
                                                <option ng-if="countries.CountryCode == 'IN'" ng-repeat="countries in countryList.Records" value="{{countries.CountryCode}}" >{{countries.CountryName}}</option>
                                            </select>
                                            <div style="color:red" ng-show="submitted && userform.Country.$error.required" class="form-error">
                                                *Country is required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>State</label>

                                            <select class="form-control customReadOnlyField selectpickerState" name="State" ng-model="profileDetails.StateName" ng-required="true">
                                                <option value="">Select State</option>
                                                <option value="{{States.StateName}}" ng-repeat="States in stateList['Records']">{{States.StateName}}</option>
                                            </select>
                                            <div style="color:red" ng-show="submitted && userform.State.$error.required" class="form-error">
                                                *State is required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control customReadOnlyField" placeholder="City" ng-model="profileDetails.CityName" name="City" ng-required="true">
                                            <div style="color:red" ng-show="submitted && userform.City.$error.required" class="form-error">
                                                *City is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control customReadOnlyField" placeholder="Address" type="text" ng-model="profileDetails.Address" name="Address" ng-required="true">
                                            <div style="color:red" ng-show="submitted && userform.Address.$error.required" class="form-error">
                                                *Address is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-submit theme_bgclr w-100">SAVE</button>
                                    </div>
                                    <div class="col-sm-6 col-sm-pull-6">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
<!--Main container sec end-->
<?php include('innerFooter.php'); ?>