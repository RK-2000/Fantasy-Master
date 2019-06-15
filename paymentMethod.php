<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="headerController" ng-cloak >
    <div class="mt-5">
        <div class="top-header-title">
            <h3> Select Payment Mode </h3>
            <p> Pay for {{moneyFormat(amount)}} </>
        </div>
        <div class="paymentSec mt-5">
            <div class="container">
                <div class="paymentBox">
                    <div class="paymentHead">
                        Pay Via
                    </div>
                    <div class="paymentBody">
                        <form action="https://securegw.paytm.in/theia/processTransaction" ng-submit="submitPayTmData()" name="payTmData" method="post" id="submitPayTm">
                            <input type="hidden" name="MID" ng-model="MID" value="{{MID}}">
                            <input type="hidden" name="ORDER_ID" ng-model="ORDER_ID" value="{{ORDER_ID}}">
                            <input type="hidden" name="CUST_ID" ng-model="CUST_ID" value="{{CUST_ID}}">
                            <input type="hidden" name="INDUSTRY_TYPE_ID" ng-model="INDUSTRY_TYPE_ID" value="{{INDUSTRY_TYPE_ID}}">
                            <input type="hidden" name="CHANNEL_ID"  ng-model="CHANNEL_ID" value="{{CHANNEL_ID}}">
                            <input type="hidden" name="TXN_AMOUNT"  ng-model="TXN_AMOUNT" value="{{TXN_AMOUNT}}">
                            <input type="hidden" name="WEBSITE" ng-model="WEBSITE" value="{{WEBSITE}}">
                            <input type="hidden" name="CHECKSUMHASH"  ng-model="CHECKSUMHASH" value="{{CHECKSUMHASH}}">
                            <input type="hidden" name="CALLBACK_URL"  ng-model="CALLBACK_URL" value="{{CALLBACK_URL}}">
                        </form> 
                        <p>After selecting a payment method, you will be directed to a secure gateway for payment.</p>
                        <div class="row btn__group">
                            <!-- <div class="col-sm-4 p-0">
                                <button class="btn btn-submit theme_bgclr" ng-click="payUReq(amount)"> <i class="fa fa-credit-card"></i> Credit/Debit Card</button>
                            </div> -->
                            <div class="col-sm-6">
                                <button class="btn btn-submit theme_bgclr" ng-click="payTmReq(amount)" > <i class="fa fa-money"></i> Paytm</button>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-submit theme_bgclr" ng-click="razorPayReq(amount)"> <i class="fa fa-cc-mastercard"></i> Razorpay</button>
                            </div>
                        </div>
                        <p>By proceeding, you have read and agreed to FSL11 <a target="_blank" href="TermConditions">Terms and Conditions</a> and <a target="_blank" href="privacyPolicy">Privacy Policy</a></p> 

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main container sec end-->
<?php include('innerFooter.php'); ?>