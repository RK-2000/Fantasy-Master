
<div class="panel-body" ng-controller="PageController"><!-- Body -->

    <header>
        <h1 class="h4">User Details</h1>
    </header>



    <div class="float-right">
        <a href="user"><button class="btn btn-success btn-sm ml-1">Back</button></a>
    </div>
    <div class="clearfix mt-2 mb-2">
        <div class="float-right">
            <a target="_blank" href="transactions?UserGUID={{UserGUID}}" class="btn btn-default btn-secondary btn-sm ng-scope">Transactions</a>
        </div>
        <span class="float-left records d-none d-sm-block">
        </span>
    </div>
    <!-- Top container/ -->

    <!-- Data table -->
    <div class="row border-top" ng-init="getUserDetails();getList('WalletAmount')" > 
        <div class="col-md-4 text-center">
            <div class="user_profile p-5">
                <div class="form-group">
                    <img width="120" class="rounded-circle" ng-src="{{userData.ProfilePic}}">
                </div>
                <div class="user_ditails">
                    <h5 class="mb-0"> <strong> {{userData.FullName}} </strong> </h5>

                    <div class="mt-4"></div>

                    <p class="mb-0"> {{userData.Email}} </p>
                    <p> {{userData.PhoneNumber}} </p>

                </div>
            </div>
        </div>	
        <div class="col-md-8 bg-light">
            <div class="shadow p-3 mb-3 bg-white">
                <h5>Personal Info </h5>

                <div class="mt-4 d-flex flex-wrap">
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Full Name : </b></label>
                            <span>{{userData.FullName}}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Email ID: </b></label>
                            <span>{{userData.Email ? userData.Email : userData.EmailForChange }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Gender : </b></label>
                            <span>{{userData.Gender}}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Birth Date : </b></label>
                            <span>{{userData.BirthDate}}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Phone Number : </b></label>
                            <span>{{userData.PhoneNumber ? userData.PhoneNumber : userData.PhoneNumberForChange }}</span>
                        </div>
                    </div>	
                    <div class="col-sm-6">
                        <div class="form-group d-flex border p-2 d-flex justify-content-between align-items-center">
                            <label><b>Status : </b></label>
                            <span ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[userData.Status]">{{userData.Status}}</span>
                        </div>
                    </div>	
                </div>
            </div>

            <div class="bg-white p-2 mb-3">
                <span class="h5"> Verifications </span>

                <div class="d-flex mt-3">
                    <div class="col-sm-4">
                        <label>PAN : </label>
                        <span ng-class="{Pending:'text-danger', Verified:'text-success',Rejected:'text-danger'}[userData.PanStatus]">{{userData.PanStatus}}</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Bank : </label>
                        <span ng-class="{Pending:'text-danger', Verified:'text-success',Rejected:'text-danger'}[userData.BankStatus]">{{userData.BankStatus}}</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Phone : </label>
                        <span ng-class="userData.PhoneNumber != '' ? 'text-success' : 'text-danger'">{{userData.PhoneNumber!='' ? 'Verified' : 'Pending' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-2 mb-3">
                <span class="h5"> Payments </span>

                <div class="d-flex mt-3">
                    <div class="col-sm-3">
                        <label> Deposit : </label>
                        <span>{{DEFAULT_CURRENCY}}{{userData.WalletAmount}}</span>
                    </div>
                    <div class="col-sm-3">
                        <label> Winning : </label>
                        <span>{{DEFAULT_CURRENCY}}{{userData.WinningAmount}}</span>
                    </div>
                    <div class="col-sm-3">
                        <label> Cash Bonus : </label>
                        <span>{{DEFAULT_CURRENCY}}{{userData.CashBonus}}</span>
                    </div>
                    <div class="col-sm-3">
                        <label> Total Amount :  </label>
                        <span>{{DEFAULT_CURRENCY}}{{userData.TotalCash}}</span>
                    </div>
                </div>
            </div>
        </div>	
    </div>
    <hr/>
    <div class="row" >
        <div class="col-md-12 pl-2 pr-2">
            <div class="verified_tabs">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" ng-click="getList('WalletAmount');">Cash Deposit</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" ng-click="getList('WinningAmount');">Winning Bonus</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" ng-click="getList('CashBonus');">Cash Bonus</a>
                        <a class="nav-item nav-link" id="nav-withdraw-tab" data-toggle="tab" href="#nav-withdraw" role="tab" aria-controls="nav-withdraw" aria-selected="false" ng-click="getWithdrawals()">Withdrawal</a>
                    </div>
                </nav>
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <button class="btn btn-success btn-sm ml-1 float-right" ng-click="exportUserAmountDetails('cash-deposit');" ng-if="transactions.length > 0">Export</button>
                        <div class="table-responsive block_pad_md" > 
                            <!-- loading -->
                            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
                            <form name="records_form" id="records_form">
                                <!-- data table -->
                                <table class="table table-striped table-hover text-center" >
                                    <!-- table heading -->
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Entry Fee</th>
                                            <th>Opening Balance</th>
                                            <th>Cr.</th>
                                            <th>Dr.</th>
                                            <th>Closing Balance</th>
                                            <th>Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <!-- table body -->
                                    <tbody>
                                        <tr ng-repeat="transactionDetails in transactions" ng-if="transactions.length">
                                            <td>{{transactionDetails.TransactionID ? transactionDetails.TransactionID : '-' }}</td>
                                            <td>{{transactionDetails.Narration}}</td>
                                            <td style="color:red;" ng-if="transactionDetails.Status == 'Failed'">{{transactionDetails.Status}}</td>
                                            <td style="color:green;" ng-if="transactionDetails.Status == 'Completed'">{{transactionDetails.Status}}</td>
                                            <td ng-if="transactionDetails.Narration != 'Deposit Money'">{{DEFAULT_CURRENCY}}{{transactionDetails.Amount}}</td>
                                            <td ng-if="transactionDetails.Narration == 'Deposit Money'">-</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.OpeningWalletAmount}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Cr' ? transactionDetails.WalletAmount : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Dr' ? transactionDetails.WalletAmount : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.ClosingWalletAmount}}</td>
                                            <td>{{transactionDetails.EntryDate | myDateFormat}}</td>
                                        </tr>
                                        <tr ng-if="!transactions.length" >
                                            <td colspan="8" class="text-center">No transactions found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <!-- no record -->
                            <p class="no-records text-center" ng-if="data.noRecords">
                                <span ng-if="data.dataList.length">No more records found.</span>
                                <span ng-if="!data.dataList.length">No records found.</span>
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <button class="btn btn-success btn-sm ml-1 float-right" ng-click="exportUserAmountDetails('winning-bonus');" ng-if="transactions.length > 0">Export</button>
                        <div class="table-responsive block_pad_md" > 
                            <!-- loading -->
                            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
                            <form name="records_form" id="records_form">
                                <!-- data table -->
                                <table class="table table-striped table-hover text-center" >
                                    <!-- table heading -->
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Entry Fee</th>
                                            <th>Opening Balance</th>
                                            <th>Cr.</th>
                                            <th>Dr.</th>
                                            <th>Closing Balance</th>
                                            <th>Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <!-- table body -->
                                    <tbody>
                                        <tr ng-repeat="transactionDetails in transactions" ng-if="transactions.length">
                                            <td>{{transactionDetails.TransactionID}}</td>
                                            <td>{{transactionDetails.Narration}}</td>
                                            <td style="color:red;" ng-if="transactionDetails.Status == 'Failed'">{{transactionDetails.Status}}</td>
                                            <td style="color:green;" ng-if="transactionDetails.Status == 'Completed'">{{transactionDetails.Status}}</td>
                                            <td ng-if="transactionDetails.Narration == 'Join Contest' || transactionDetails.Narration == 'Cancel Contest'">{{DEFAULT_CURRENCY}}{{transactionDetails.Amount}}</td>
                                            <td ng-if="transactionDetails.Narration != 'Join Contest' && transactionDetails.Narration != 'Cancel Contest'">-</td>

                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.OpeningWinningAmount}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Cr' ? transactionDetails.WinningAmount : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Dr' ? transactionDetails.WinningAmount : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.ClosingWinningAmount}}</td>
                                            <td>{{transactionDetails.EntryDate | myDateFormat}}</td>
                                        </tr>
                                        <tr ng-if="!transactions.length" >
                                            <td colspan="8" class="text-center">No transactions found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <!-- no record -->
                            <p class="no-records text-center" ng-if="data.noRecords">
                                <span ng-if="data.dataList.length">No more records found.</span>
                                <span ng-if="!data.dataList.length">No records found.</span>
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

                        <button class="btn btn-success btn-sm ml-1 float-right" ng-click="exportUserAmountDetails('cash-bonus');" ng-if="transactions.length > 0">Export</button>

                        <div class="table-responsive block_pad_md" > 

                            <!-- loading -->
                            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
                            <form name="records_form" id="records_form">
                                <!-- data table -->
                                <table class="table table-striped table-hover text-center" >
                                    <!-- table heading -->
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Entry Fee</th>
                                            <th>Opening Balance</th>
                                            <th>Cr.</th>
                                            <th>Dr.</th>
                                            <th>Closing Balance</th>
                                            <th>Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <!-- table body -->
                                    <tbody>
                                        <tr ng-repeat="transactionDetails in transactions" ng-if="transactions.length">
                                            <td>{{transactionDetails.TransactionID}}</td>
                                            <td>{{transactionDetails.Narration}}</td>
                                            <td style="color:red;" ng-if="transactionDetails.Status == 'Failed'">{{transactionDetails.Status}}</td>
                                            <td style="color:green;" ng-if="transactionDetails.Status == 'Completed'">{{transactionDetails.Status}}</td>
                                            <td ng-if="transactionDetails.Narration == 'Join Contest' || transactionDetails.Narration == 'Cancel Contest'">{{DEFAULT_CURRENCY}}{{transactionDetails.Amount}}</td>
                                            <td ng-if="transactionDetails.Narration != 'Join Contest' && transactionDetails.Narration != 'Cancel Contest'">-</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.OpeningCashBonus}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Cr' ? transactionDetails.CashBonus : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.TransactionType=='Dr' ? transactionDetails.CashBonus : '0.00'}}</td>
                                            <td>
                                                {{DEFAULT_CURRENCY}}{{ transactionDetails.ClosingCashBonus}}</td>
                                            <td>{{transactionDetails.EntryDate | myDateFormat}}</td>
                                        </tr>
                                        <tr ng-if="!transactions.length" >
                                            <td colspan="8" class="text-center">No transactions found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <!-- no record -->
                            <p class="no-records text-center" ng-if="data.noRecords">
                                <span ng-if="data.dataList.length">No more records found.</span>
                                <span ng-if="!data.dataList.length">No records found.</span>
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-withdraw" role="tabpanel" aria-labelledby="nav-withdraw-tab">
                        
                        <button class="btn btn-success btn-sm ml-1 float-right" ng-click="exportUserWithdrawalDetails();" ng-if="WithdrawalsTransactions.length > 0">Export</button>

                        <div class="table-responsive block_pad_md" > 


                            <!-- loading -->
                            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
                            <form name="records_form" id="records_form">
                                <!-- data table -->
                                <table class="table table-striped table-hover text-center" >
                                    <!-- table heading -->
                                    <thead>
                                        <tr>
                                            <th>Withdrawal ID</th>
                                            <th>Amount</th>
                                            <th>Payment Gateway</th>
                                            <th>Status</th>
                                            <th>Reject Reason</th>
                                            <th>Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <!-- table body -->
                                    <tbody>
                                        <tr ng-repeat="transactionDetails in WithdrawalsTransactions">
                                            <td style="word-break: break-all;">{{transactionDetails.WithdrawalID}}</td>
                                            <td>{{DEFAULT_CURRENCY}}{{transactionDetails.Amount}}</td>
                                            <td>{{transactionDetails.PaymentGateway}}</td>
                                            <td style="color:red;" ng-if="transactionDetails.Status == 'Rejected'">{{transactionDetails.Status}}</td>
                                            <td style="color:green;" ng-if="transactionDetails.Status == 'Verified'">Completed</td>
                                            <td style="color:orange;" ng-if="transactionDetails.Status == 'Pending'">{{transactionDetails.Status}}</td>
                                            <td ng-if="transactionDetails.Comments !=''">{{transactionDetails.Comments}}</td>
                                            <td ng-if="transactionDetails.Comments ==''">-</td>
                                            <td>{{transactionDetails.EntryDate | myDateFormat}}</td>
                                        </tr>
                                        <tr ng-if="!WithdrawalsTransactions.length" >
                                            <td colspan="8" class="text-center">No transactions found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <!-- no record -->
                            <p class="no-records text-center" ng-if="data.noRecords">
                                <span ng-if="data.dataList.length">No more records found.</span>
                                <span ng-if="!data.dataList.length">No records found.</span>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Data table/ -->

</div><!-- Body/ -->