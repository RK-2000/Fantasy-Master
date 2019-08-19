<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="settingsController" ng-init="getCountryList();getStatesList()" ng-cloak >
    <div class="pt-5">
        <div class="top-header-title">
            <h3> Verify Accounts </h3>
        </div>
        <div class="settingPage accountPage">
            <div class="container">
                <div class="settingContent accountContent col-md-10 offset-md-1">
                    <div class="settingHead comnnFour">
                        <h4>Verify Your Account</h4>
                    </div>

                    <div class="veryfy_condition">
                        <h4>After this, enjoy 1- click cash withdrawals forever! </h4>

                        <ul>
                            <li> Max size 4MB. Formats - .jpg .jpeg .png .pdf only.</li>
                            <li>We don’t accept password-protected docs.</li>
                        </ul>
                        <ul class="veryfy_icn">
                            <li class="red"><i class="fa fa-info-circle" aria-hidden="true"></i> Pending</li>
                            <li class="green"><i class="fa fa-check-circle" aria-hidden="true"></i> Verified
                            </li>
                        </ul>
                    </div>

                    <div class="settingPoints">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{activeMenu =='verification' ? 'active' : ''  }}" data-toggle="tab" ng-click="activateTab('verification')" href="javascript:void(0)">Mobile/Email Verification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeMenu =='panCard' ? 'active' : ''  }} " data-toggle="tab" ng-click="activateTab('panCard')" href="javascript:void(0)">Pan Card</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeMenu =='account' ? 'active' : ''  }} " ng-if="profileDetails.PanStatus == 'Verified'" data-toggle="tab" ng-click="activateTab('account')" href="javascript:void(0)">Bank Account Details</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane {{activeMenu =='verification' ? 'active' : ''  }}" id="verification">
                                <div class="row flex-column">
                                    <div class="dis_status">
                                        <strong>Phone Status :</strong> <span ng-class="profileDetails.PhoneNumber != '' ? 'text-success' : 'text-danger'">{{profileDetails.PhoneNumber ? 'Verified' : 'Pending' }}</span>
                                    </div>
                                    <div class="col-sm-4" ng-show="!isOtpSend">
                                        <h4>Verify your Mobile Number</h4>
                                        <form name="verifyMobileForm" class="form-commen" ng-submit="updateMobileNumber(verifyMobileForm)" novalidate="">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input placeholder="Enter Your Mobile number" class="form-control" type="text" ng-model="PhoneNumber" name="mobile" numbers-only ng-change="removeMassage()" maxlength="10" ng-required="true" ng-disabled="profileDetails.PhoneNumber != ''" >
                                                <div style="color:red" ng-show="submitted && verifyMobileForm.mobile.$error.required" class="form-error">
                                                    *Mobile number is required.
                                                </div>
                                                <div style="color:red" ng-show="verifyMobileForm.mobile.$error.pattern || verifyMobileForm.mobile.$error.minlength" class="form-error">*Mobile number must be of 10 digit.</div>
                                                <div style="color:red" ng-show="verifyMobileForm.mobile.$error.oldNumber" class="form-error">*Phone number should not be same.</div>

                                            </div>
                                            <p>We’ll send you an OTP Verification</p>
                                            <div class="form-group">
                                                <button class="btn btnSetting theme_bgclr" ng-disabled="profileDetails.PhoneNumber != ''" >Send OTP</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-4" ng-show="isOtpSend">
                                        <h4>Verify your Mobile Number</h4>
                                        <form name="verifyMobileOptForm" class="form-commen" ng-submit="verifyMobileNumber(verifyMobileOptForm)" novalidate="">
                                            <div class="form-group">
                                                <label>One Time Password</label>
                                                <input placeholder="Enter Your OTP" class="form-control" type="text" ng-model="OTP" name="OTP" numbers-only ng-change="removeMassage()" ng-maxlength="6" ng-required="true">
                                                <div style="color:red" ng-show="otpSubmitted && verifyMobileOptForm.OTP.$error.required" class="form-error">
                                                    *One time password is required.
                                                </div>
                                                <div style="color:red" ng-show="verifyMobileForm.OTP.$error.pattern || verifyMobileOptForm.OTP.$error.minlength" class="form-error">*OTP must be of 6 digit.</div>
                                            </div>
                                            <p>We’ll send you an OTP Verification</p>
                                            <div class="form-group">
                                                <button class="btn btnSetting theme_bgclr">Verify</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row flex-column">
                                    <div class="dis_status">
                                        <strong>Email Status :</strong> <span ng-class="profileDetails.EmailStatus == 'Verified' ? 'text-success' : 'text-danger'">{{profileDetails.EmailStatus}}</span>
                                    </div>
                                    <div class="col-sm-4" ng-show="!isEmailSend">
                                        <h4>Verify your Email Address</h4>
                                        <form name="verifyEmailForm" class="form-commen" ng-submit="updateEmail(verifyEmailForm)" novalidate="">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input placeholder="Enter Your Email" class="form-control" type="text" ng-model="Email" name="email" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" ng-change="removeMassage()" ng-required="true" ng-disabled="profileDetails.EmailStatus == 'Verified'" >
                                                <div style="color:red" ng-show="submitted && verifyEmailForm.email.$error.required" class="form-error">
                                                    *Email is required.
                                                </div>
                                                <div style="color:red" ng-show="verifyEmailForm.email.$error.pattern" class="form-error">*Please enter valid email.</div>

                                            </div>
                                            <div class="form-group">
                                                <button class="btn btnSetting theme_bgclr" ng-disabled="profileDetails.EmailStatus == 'Verified'" >Send Verify Email</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane {{activeMenu =='panCard' ? 'active' : ''  }}" id="panCard">
                                <form ng-submit="uploadPanCardDetails(panDetailsForm, panImage)"  name="panDetailsForm" enctype="multipart/form-data" novalidate="">

                                    <div class="dis_status p-0">
                                        <strong>PAN Status :</strong> <span ng-class="{Pending:'text-danger', Verified:'text-success',Rejected:'text-danger'}[profileDetails.PanStatus]">{{profileDetails.PanStatus}}</span>  
                                    </div>
                                    <div class="row" >
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input type="text" placeholder="John" class="form-control" ng-model="panDetails.FullName" name="FullName" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'" ng-required="true">
                                                <p style="color:red" ng-if="panSubmitted && panDetailsForm.FullName.$error.required">*Name is required. </p>
                                            </div>
                                            <p><i class="fa fa-info-circle" data-toggle="tooltip" title=" Your name must match the name on your PAN card"></i>  <span> Your name must match the name on your PAN card </span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>PAN Card Number</label>
                                                <input type="text" placeholder="AAAPL1234C" class="form-control" ng-model="panDetails.PanCardNumber" ng-pattern="/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/" name="PanCardNumber" ng-required="true" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'" > 
                                                <p style="color:red" ng-if="panSubmitted && panDetailsForm.PanCardNumber.$error.required">*PAN number is required field. </p>   
                                                <p style="color:red" ng-show="panDetailsForm.PanCardNumber.$error.pattern" class="form-error">*Please enter valid PAN card number.</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>D.O.B.</label>
                                                <div datepicker datepicker-class="test-custom-class" date-typer="true" date-format="yyyy-MM-dd">
                                                    <input type="text" placeholder="Birth Date" name="BirthDate" class="angular-datepicker-input form-control" ng-model="panDetails.BirthDate" ng-required="true" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'"  />
                                                </div>
                                                <div style="color:red" ng-show="panSubmitted && panDetailsForm.BirthDate.$error.required" class="form-error">
                                                    *Birth date is required.
                                                </div>
                                                <p><i class="fa fa-info-circle" data-toggle="tooltip" title="For Withdrawals,this Should Match the date of birth on PAN CARD"></i> <span> For Withdrawals,this Should Match the date of birth on PAN CARD </span></p>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select State</label>
                                                <select class="form-control selectpicker" ng-model="panDetails.StateName" name="StateName" ng-required="true" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'"  >
                                                    <option value="">State</option>
                                                    <option ng-repeat="states in StateList.Records" value="{{states.StateName}}">{{states.StateName}}</option>
                                                </select>
                                                <div style="color:red" ng-show="panSubmitted && panDetailsForm.StateName.$error.required" class="form-error">
                                                    *State is required.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group custom_filetype">
                                                <label ng-if="profileDetails.PanStatus == 'Rejected' || profileDetails.PanStatus == 'Not Submitted'" >Upload Pan card image</label>
                                                <div class="input-file-container">
                                                    <input class="input-file" id="my-file" type="file" ngf-select ngf-accept="'image/*'" ng-model="panImage" name="file" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'" ng-required="true">
                                                    <label ng-if="profileDetails.PanStatus == 'Rejected' || profileDetails.PanStatus == 'Not Submitted'"  tabindex="0" for="my-file" class="input-file-trigger">Choose File</label>
                                                </div>
                                                <div style="color:red" ng-show="panSubmitted && panDetailsForm.file.$error.required" class="form-error">
                                                    *Pan card image is required.
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <ul class="form-group">
                                                <li><i class="fa fa-info-circle info_icon" data-toggle="tooltip" title="Your withdrawals involve transfer of money from your FSL11 account to your bank account. Hence, PAN Card is mandatory to get your account verified, due to regulatory requirements." data-placement="bottom"  ></i>Why should I submit my PAN</li>
                                                <li>&nbsp;&nbsp; - Max size 4MB. Formats - .jpg .jpeg .png .pdf only.</li>
                                                <li>&nbsp;&nbsp; - We don’t accept password-protected docs.</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <button type="submit" class="btn btnSetting theme_bgclr" ng-disabled="profileDetails.PanStatus == 'Verified' || profileDetails.PanStatus == 'Pending'" ng-if="profileDetails.PanStatus != 'Verified'" >Submit For Verification</button>
                                                <label ng-if="profileDetails.PanStatus == 'Verified'"  tabindex="0" for="my-file" class="input-file-trigger">PAN account details verified</label>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane {{activeMenu =='account'?'active': ''}}" id="account">
                                <form name="bankDetailsForm" novalidate="" enctype="multipart/form-data" ng-submit="uploadBankDetail(bankDetailsForm, bankImage)"  >
                                    <div class="dis_status p-0">
                                        <strong>BANK Status :</strong> <span ng-class="{Pending:'text-danger', Verified:'text-success',Rejected:'text-danger'}[profileDetails.BankStatus]">{{profileDetails.BankStatus}}</span> 
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input type="text" placeholder="Name as same in bank Eg. Jhon Smith" name="FullName" ng-model="bankDetails.FullName" class="form-control" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'" >
                                                <p style="color:red" ng-if="bankSubmitted && bankDetailsForm.FullName.$error.required">*Name is required. </p>
                                            </div>
                                            <p><i class="fa fa-info-circle"></i> Your name must match the name on your Bank Account</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Bank</label>
                                                <input type="text" placeholder="State bank of India" name="Bank" ng-model="bankDetails.Bank" class="form-control" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'" >
                                                <p style="color:red" ng-if="bankSubmitted && bankDetailsForm.Bank.$error.required">*Bank is required. </p>
                                            </div>
                                            <p><i class="fa fa-info-circle"></i> Your Bank Name.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Account Number</label>
                                                <input type="text" placeholder="12345678901234" name="AccountNumber" ng-model="bankDetails.AccountNumber" class="form-control" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'" >
                                                <p style="color:red" ng-if="bankSubmitted && bankDetailsForm.AccountNumber.$error.required">*Account number is required. </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>D.O.B.</label>
                                                <div datepicker datepicker-class="test-custom-class" date-typer="true" date-format="yyyy-MM-dd">
                                                    <input type="text" placeholder="Birth Date" name="BirthDate" class="angular-datepicker-input form-control" ng-model="bankDetails.BirthDate" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'"  />
                                                </div>
                                                <div style="color:red" ng-show="bankSubmitted && bankDetailsForm.BirthDate.$error.required" class="form-error">
                                                    *Birth date is required.
                                                </div>
                                            </div>
                                            <p><i class="fa fa-info-circle"></i> For Withdrawals,this Should Match the date of birth on PAN CARD</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>IFSC Code</label>
                                                <input type="text" class="form-control" placeholder="xxxxx" ng-model="bankDetails.IFSCCode" name="IfscCode" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'">
                                                <p style="color:red" ng-if="bankSubmitted && bankDetailsForm.IfscCode.$error.required">*IFSC Code is required. </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group custom_filetype">
                                                <label ng-if="profileDetails.BankStatus == 'Rejected' || profileDetails.BankStatus == 'Not Submitted'" >Upload Passbook Image / Online bank statement</label>
                                                <div class="input-file-container">
                                                    <input class="input-file" ngf-select ngf-accept="'image/*'" name="bankImage" id="my-file" type="file" ng-model="bankImage" ng-required="true" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'" >
                                                    <label ng-if="profileDetails.BankStatus == 'Rejected' || profileDetails.BankStatus == 'Not Submitted'" tabindex="0" for="my-file" class="input-file-trigger">Choose File</label>
                                                </div>
                                                <div style="color:red" ng-show="bankSubmitted && bankDetailsForm.bankImage.$error.required" class="form-error">
                                                    *Passbook / Online bank statement image is required.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <ul class="form-group">
                                                <li><i class="fa fa-info-circle" data-toggle="tooltip" title="At FSL11, we transfer your winnings directly to your bank account. Hence, once your bank account details are submitted and verified, you can make withdrawls from your FSL11 account to your bank account." data-placement="bottom"  ></i> Why should I submit my bank account details?</li>

                                                <li>&nbsp;&nbsp; - Max size 4MB. Formats - .jpg .jpeg .png .pdf only.</li>
                                                <li>&nbsp;&nbsp; - We don’t accept password-protected docs.</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <button button="submit" class="btn btnSetting theme_bgclr" ng-disabled="profileDetails.BankStatus == 'Verified' || profileDetails.BankStatus == 'Pending'" ng-if="profileDetails.BankStatus != 'Verified'">Submit For Verification</button>
                                                <label ng-if="profileDetails.BankStatus == 'Verified'" tabindex="0" for="my-file" class="input-file-trigger">Bank account details verified</label>
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
    </div>
</div>
<!--Main container sec end-->
<?php include('innerFooter.php'); ?>