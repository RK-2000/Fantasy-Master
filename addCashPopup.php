<!--addmoney-->
<div class="modal fade centerPopup" popup-handler id="add_money" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" >
    <div class="modal-dialog custom_popup small_popup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Add funds to Your Account</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <form ng-submit="selectPaymentMode(amount,addCashForm)" name="addCashForm" novalidate="true">
                    <div class="form-group">
                        <label>How much you would like to add to join contest</label>
                        <input placeholder="Enter amount." class="form-control numeric" name="amount" type="text" ng-model="amount" numbers-only  ng-required="true" ng-readonly="isPromoCode">
                        <div style="color:red" ng-show="cashSubmitted && addCashForm.amount.$error.required" class="form-error">
                            *Amount is Required
                        </div>
                        <div class="text-danger" ng-if="errorAmount">{{errorAmountMsg}}</div>
                    </div>
                    <div class="add_money">
                        <h4>ADD MORE CASH</h4>
                        <ul>
                            <li><button type="button" class="btn btn-submit theme_bgclr" ng-click="addExtraCash(250)" ng-disabled="isPromoCode" >₹ 250</button></li>
                            <li><button type="button" class="btn btn-submit theme_bgclr" ng-click="addExtraCash(500)" ng-disabled="isPromoCode" >₹ 500</button></li>
                            <li><button type="button" class="btn btn-submit theme_bgclr" ng-click="addExtraCash(1000)" ng-disabled="isPromoCode" >₹ 1000</button></li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <div class="customCheckbox">
                            <input type="checkbox" name="promoCode" ng-model="isPromoCode" ng-click="resetPromo(isPromoCode)">
                             <label> Have a promo code.</label>
                        </div>
                    </div>
                    <div class="form-group applyBox" ng-if="isPromoCode && !PromoCodeFlag">
                        <input type="text" class="form-control" name="promocode" ng-model="PromoCode">
                        <a href="javascript:void(0)" class="btn btn-submit theme_bgclr" ng-click="applyPromoCode(PromoCode,amount)" >Apply</a>
                    </div>
                    <div class="promocodeList" ng-if="isPromoCode" >
                    <p ng-if="PromoCodeFlag" class="h6"><span>Coupon Code </span>: {{PromoCode}} <a href="javascript:void(0)" ng-click="removeCoupon()"><i class="fa fa-trash"></i></a></p>
                    <p class="h6" ng-if="GotCashBonus>0"><span>Cash Bonus </span>: ₹. {{GotCashBonus}}</p>
                    </div>
                    <div class="button_right text-center"><!-- href="paymentMethod?amount={{amount}}" -->
                        <button class="btn btn-submit bluebg"> ADD CASH </button>
                        
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<!--addmoney