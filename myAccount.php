<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="myAccountController" ng-init="getAccountInfo(true)" ng-cloak >
    <div class="pt-5">
        <div class="top-header-title">
            <h3> My Account </h3>
        </div>
        <div class="accountPage">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10 offset-md-1 res_account">
                        <div class="accountContent">
                            <div class="accountHead">
                                <div class="accountHolder">
                                    <div class="accountImg">
                                        <img ng-src="{{profileDetails.ProfilePic}}" ng-if="profileDetails.ProfilePic" alt="" width="100%">
                                    </div>
                                    {{profileDetails.FirstName}}
                                    <span>{{profileDetails.Email}}</span>
                                </div>
                                <ul>
                                    <li>Total Cash
                                        <span>{{moneyFormat(profileDetails.TotalCash)}}</span>
                                    </li>
                                    <li> Unutilized Deposit Cash
                                        <span> {{moneyFormat(profileDetails.WalletAmount)}}</span>
                                    </li>
                                    <li>Winning Cash
                                        <span> {{moneyFormat(profileDetails.WinningAmount)}}</span>
                                        <a ng-if="profileDetails.WinningAmount > 0" href="javascript:void(0)" ng-click="openPopup('withdrawPopup')">Withdraw</a>
                                    </li>
                                    <li> Cash Bonus
                                        <span>{{moneyFormat(profileDetails.CashBonus)}}</span>
                                    </li>
                                </ul>

                                <div class="d-flex border-top py-3 border-bottom">
                                    <div class="col-md-4 offset-md-2">
                                        <a href="javascript:void(0)" ng-click="openPopup('add_money')" class="btn bggreen text-white w-100"> <i class="fa fa-money fa-1x mr-2" aria-hidden="true"></i> Add Cash </a>
                                    </div>

                                    <div class="col-md-4">
                                        <a href="javascript:void(0)" ng-click="openPopup('withdrawPopup')" class="btn light-bluebg text-white w-100"> <i class="fa fa-credit-card fa-1x mr-2" aria-hidden="true"></i> Withdraw Cash </a>
                                    </div>
                                </div>
                            </div>
                            <div class="transictionOption">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link {{activeTab=='transaction' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="ChangeTab('transaction');">Cash Transaction</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{activeTab=='withdrawal' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="ChangeTab('withdrawal');">Cash Withdrawal</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="withdrawal" class="tab-pane {{activeTab=='withdrawal' ? 'active' : '' }}">
                                        <h4 class="pull-left p-2">Withdrawal</h4>
                                        <div class="pull-right">
                                            <label>Withdraw Status</label>
                                            <select class="selectpicker" ng-model="withdraw_status">
                                                <option value="">Select Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Verified">Verified</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="table-responsive table-striped">
                                            <table class="" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Payment Source</th>
                                                        <th>Amount</th>
                                                        <th>Date &amp; Time</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark border border-top-0 dfs_custom_scroll" scrolly>
                                                    <tr ng-repeat="transactionDetails in WithdrawTransactions | filter:{Status: withdraw_status}" ng-if="TotalWithdrawTransactionCount > 0">
                                                        <td>{{transactionDetails.PaymentGateway}}</td>
                                                        <td>{{moneyFormat(transactionDetails.Amount)}}</td>
                                                        <td>{{transactionDetails.EntryDate| myDateFormat}}</td>
                                                        <td>{{transactionDetails.Status}}</td>
                                                    </tr>
                                                    <tr ng-if="TotalWithdrawTransactionCount == 0" >
                                                        <td colspan="4" class="text-center">No transactions found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="transaction"  class="tab-pane  {{activeTab=='transaction' ? 'active' : '' }}">
                                        <h4 class="pull-left p-2">Transaction Details</h4>
                                        <div class="pull-right">
                                            <label>Transaction Status</label>
                                            <select class="selectpicker" ng-model="trans_status">
                                                <option value="">Select Status</option>
                                                <option value="Failed">Failed</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                        </div>
                                        <div class="table-responsive table-striped text-dark">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Transaction ID</th>
                                                        <th>Details</th>
                                                        <th>Status</th>
                                                        <th>Opening Balance</th>
                                                        <th>Cr.</th>
                                                        <th>Dr.</th>
                                                        <th>Available Balance</th>
                                                        <th>Date &amp; Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark border border-top-0 dfs_custom_scroll"  scrolly>
                                                    <tr ng-repeat="transactionDetails in transactions | filter:{Status: trans_status}" ng-if="TotalTransactionCount > 0" >

                                                        <td style="word-break: break-all;">{{transactionDetails.TransactionID}}</td>
                                                        <td>{{(transactionDetails.Narration == 'Join Contest Winning')?'Contest Winnings':transactionDetails.Narration}}</td>
                                                        <td>{{transactionDetails.Status}}</td>
                                                        <td>
                                                            {{ moneyFormat(transactionDetails.OpeningBalance)}}
                                                        </td>
                                                        <td>
                                                            {{ transactionDetails.TransactionType == 'Cr' ? moneyFormat(transactionDetails.Amount) : moneyFormat(0.00)}}</td>
                                                        <td>
                                                            {{ transactionDetails.TransactionType == 'Dr' ? moneyFormat(transactionDetails.Amount) : moneyFormat(0.00)}}</td>
                                                        <td>
                                                            {{ moneyFormat(transactionDetails.ClosingBalance)}}
                                                        </td>
                                                        <td>{{transactionDetails.EntryDate| myDateFormat}}</td>
                                                    </tr>
                                                    <tr ng-if="TotalTransactionCount == 0" >
                                                        <td colspan="8" class="text-center">No transactions found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--withdraw money modal-->
<!--    <div class="modal fade centerPopup" popup-handler id="withdrawPopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" >
        <div class="modal-dialog custom_popup small_popup">

             Modal content
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Withdraw</h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup" ng-init = "mode='Bank'">
                    <form name="withdrawForm" novalidate="true">
                        <div class="form-group text-center">
                            <div class="customCheckbox">
                                <input type="radio" name="mode" ng-model="mode" value="Bank"> <label>Bank</label>
                            </div>
                            <div class="customCheckbox">
                            <input type="radio" name="mode" ng-model="mode" value="Paytm"><label> PayTm</label>
                            </div>
                        </div>
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
                            <div class="form-group mt-2">
                                <div class="button_right text-center" ng-if="mode=='Paytm' && showOtp">
                                    <button class="btn btn-submit theme_bgclr" ng-click="withdrawlConfirm(WithdrawalID,OTP,mode)">withdrawal</button>
                                </div>
                                <div class="button_right text-center" ng-if="mode!='Paytm' && showOtp ">
                                    <button class="btn btn-submit theme_bgclr" ng-click="withdrawlConfirm(WithdrawalID,OTP,mode)">Withdraw</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <p> Click Here to request OTP for  </p>
                            <div class="button_right text-center" ng-if="!showOtp">
                                <button class="btn btn-submit theme_bgclr text-center" ng-click="withdrawRequest(withdrawForm,WinningAmount,mode)">withdrawal Cash</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>-->

<!--transaction message modal -->
<div class="modal fade centerPopup" popup-handler id="TransactionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" >
    <div class="modal-dialog custom_popup small_popup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Message</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <div class="form-group">
                    <div class="form-group">
                        <p class="text-center">{{TransactionMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('innerFooter.php'); ?>