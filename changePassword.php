<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="profileController" ng-cloak >
    <div class="pt-5">
           <div class="top-header-title">
                <h3> Change Password </h3>
            </div>
        <div class="profilePage py-5" ng-init="getProfileInfo()">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 offset-sm-4">
                        <div class="accountContent">
                            <form name="createform1" class="form-horizontal form-commen ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength" novalidate="" ng-submit="changePassword(createform1)" style="">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" ng-model="CurrentPassword" name="currentPass" placeholder="Current Password" class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" ng-required="true" ng-change="hideShowMsg()" required="required" aria-invalid="true" style="">
                                            <div style="color: red;" ng-show="isSubmitted && createform1.currentPass.$error.required" class="form-error ng-hide" aria-hidden="true">
                                                *Current password is required.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" ng-model="Password" name="newPass" placeholder="New Password" class="form-control " ng-required="true" ng-change="confirmPass='';hideShowMsg()" ng-pattern="/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9@_%]+)$/" ng-minlength="6" required="required" aria-invalid="true" style="">
                                            <div style="color: red;" ng-show="isSubmitted && createform1.newPass.$error.required" class="form-error ng-hide" aria-hidden="true">
                                                *New password is required.
                                            </div>
                                            <div style="color:red" ng-show="createform1.newPass.$error.pattern || createform1.newPass.$error.minlength" class="form-error ng-hide" aria-hidden="true">*Password must have one capital, one number and 6 character long.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" ng-model="confirmPass" name="confirmPass" placeholder="Confirm Password" compare-to="Password" class="form-control" ng-required="true" ng-change="hideShowMsg()">
                                            <div style="color:red" ng-show="isSubmitted && createform1.confirmPass.$error.required" class="form-error">
                                                *Confirm password is required.
                                            </div>
                                            <div style="color:red" ng-show="!createform1.confirmPass.$error.required && createform1.confirmPass.$error.compareTo">Your passwords must match.</div>
                                        </div>
                                    </div>

                                    <!-- ngIf: showMsg==true -->
                                    <div class="col-sm-6 offset-sm-3">
                                        <div class="button_right">
                                            <button type="submit" class="btn btn-submit theme_bgclr w-100" ng-disabled="Password != confirmPass">Change</button>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <!--Main container sec end-->
    <?php include('innerFooter.php'); ?>