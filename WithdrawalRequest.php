<!--withdraw money modal-->
<div class="modal fade centerPopup" popup-handler id="withdrawPopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" >
    <div class="modal-dialog custom_popup small_popup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"> Withdraw Funds </h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup" ng-init="mode = 'Bank'">
                <form name="withdrawForm" novalidate="true">
                    <!--                        <div class="form-group text-center">
                                                <div class="customCheckbox">
                                                    <input type="radio" name="mode" ng-model="mode" value="Bank"> <label>Bank</label>
                                                </div>
                                                <div class="customCheckbox">
                                                <input type="radio" name="mode" ng-model="mode" value="Paytm"><label> PayTm</label>
                                                </div>
                                            </div>-->
                    <div class="form-group">
                        <label>How much you would like to withdraw.</label>
                        <input placeholder="Rs. 50" class="form-control numeric" name="amount" type="text" ng-model="WinningAmount"  ng-required="true"  >
                        <div style="color:red" ng-show="withdrawSubmitted && withdrawForm.amount.$error.required" class="form-error">
                            *Amount is Required.
                        </div>
                    </div>

                    <div class="form-group" ng-if="showOtp">
                        <input placeholder="One time password" class="form-control numeric" name="OTP" type="text" ng-model="OTP"  ng-required="true"  >
                        <div style="color:red" ng-show="withdrawSubmitted && withdrawForm.OTP.$error.required" class="form-error">
                            *OTP is Required.
                        </div>
                        <!--                            <div class="form-group mt-2">
                                                        <div class="button_right text-center" ng-if="mode=='Paytm' && showOtp">
                                                            <button class="btn btn-submit theme_bgclr" ng-click="withdrawlConfirm(WithdrawalID,OTP,mode)">withdrawal</button>
                                                        </div>
                                                        <div class="button_right text-center" ng-if="mode!='Paytm' && showOtp ">
                                                            <button class="btn btn-submit theme_bgclr" ng-click="withdrawlConfirm(WithdrawalID,OTP,mode)">Withdraw</button>
                                                        </div>
                                                    </div>-->
                    </div>
                    <div class="form-group" >
                        <p ng-if="showOtp"> Click Here to request OTP for  </p>
                        <div class="button_right text-center" >
                            <button class="btn btn-submit theme_bgclr text-center" ng-click="withdrawRequest(withdrawForm, WinningAmount, mode)">Withdraw</button>
                        </div>
                        <ul class="mt-2">
                            <li>* Minimum withdrawal amount is ₹200.</li>
                            <li>* Only amount from winnings can be withdrawn.</li>
                        </ul>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>