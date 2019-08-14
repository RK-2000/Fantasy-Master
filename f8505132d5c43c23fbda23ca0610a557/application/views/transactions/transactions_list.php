<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>
<div class="panel-body" ng-controller="PageController">
    <!-- Body -->

    <!-- Top container -->
    <div class="clearfix mt-2 mb-2">
        <div class="clearfix mt-2 mb-2">
            <span class="float-left records d-none d-sm-block">
                <span class="h5"><b>{{userData.FullName}} ({{userData.Email}})</b></span><br>
            </span>

        </div>



        <div class="clearfix mt-2 mb-2">
            <span class="float-left records hidden-sm-down">
                <span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
            </span>
            <div class="float-right">
                <button class="btn btn-success btn-sm ml-1 float-right"
                    onclick="window.location.href= BASE_URL + 'user'">Back</button>
            </div>
            <div class="float-right ">
                <button class="btn btn-success btn-sm ml-1"
                    ng-click="ExportList('TransactionList','TransactionList.csv')">Export</button>&nbsp;
            </div>
            <div class="float-right">
                <button class="btn btn-default btn-secondary btn-sm ng-scope" data-toggle="modal"
                    data-target="#filter_model"><img src="asset/img/filter.svg"></button>
            </div>

        </div>
        <!-- Top container/ -->



        <!-- Data table -->
        <div class="table-responsive block_pad_md" infinite-scroll="getList()"
            infinite-scroll-disabled='data.listLoading' infinite-scroll-distance="0">

            <!-- loading -->
            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
            <form name="records_form" id="records_form">
                <!-- data table -->
                <table class="table table-striped table-hover" ng-if="data.dataList.length">
                    <!-- table heading -->
                    <thead>
                        <tr>
                            <!-- <th style="width: 50px;" class="text-center" ng-if="data.dataList.length>1"><input type="checkbox" name="select-all" id="select-all" class="mt-1" ></th> -->
                            <th style="width: 200px;min-width:200px;">TransactionID</th>
                            <th>Narration</th>

                            <th style="width: 120px;">Opening Balance</th>
                            <th style="width: 160px;" class="text-center">Cr.</th>
                            <th style="width: 100px;" class="text-center">Dr.</th>
                            <th style="width: 100px;" class="text-center">Available Balance</th>
                            <th style="width: 100px;" class="text-center">Date & Time</th>
                            <th style="width: 120px;">Status</th>

                        </tr>
                    </thead>
                    <!-- table body -->
                    <tbody>
                        <tr scope="row" ng-repeat="(key, row) in data.dataList">

                            <td class="listed sm clearfix">
                                <span ng-if="row.TransactionID">{{row.TransactionID}}</span><span
                                    ng-if="!row.TransactionID">-</span>
                            </td>
                            <td>
                                <span ng-if="row.Narration">{{row.Narration}}</span><span
                                    ng-if="!row.Narration">-</span>
                            </td>

                            <td>
                                <span ng-if="row.OpeningBalance">{{DEFAULT_CURRENCY}}{{row.OpeningBalance}}</span><span
                                    ng-if="!row.OpeningBalance">-</span>
                            </td>
                            <td>
                                <span ng-if="row.TransactionType=='Cr'">{{DEFAULT_CURRENCY}}{{row.Amount}}</span>
                                <span ng-if="row.TransactionType!='Cr'">{{DEFAULT_CURRENCY}}0</span>
                            </td>
                            <td>
                                <span ng-if="row.TransactionType=='Dr'">{{DEFAULT_CURRENCY}}{{row.Amount}}</span>
                                <span ng-if="row.TransactionType!='Dr'">{{DEFAULT_CURRENCY}}0</span>
                            </td>
                            <td>
                                <span ng-if="row.ClosingBalance">{{DEFAULT_CURRENCY}}{{row.ClosingBalance}}</span><span
                                    ng-if="!row.ClosingBalance">-</span>
                            </td>
                            <td>
                                <span ng-if="row.EntryDate">{{row.EntryDate}}</span><span
                                    ng-if="!row.EntryDate">-</span>
                            </td>
                            <td>
                                <span ng-if="row.Status"
                                    ng-class="{Completed:'text-success',Failed:'text-danger',Pending:'text-danger'}[row.Status]">{{row.Status}}</span><span
                                    ng-if="!row.Status">-</span>
                            </td>
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
        <!-- Data table/ -->


        <!-- edit Modal -->
        <div class="modal fade" id="edit_model">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5">Edit <?php echo $this->ModuleData['ModuleName'];?></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- form -->
                    <form id="edit_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
                    </form>
                    <!-- /form -->
                </div>
            </div>
        </div>


        <!-- delete Modal -->
        <div class="modal fade" id="delete_model">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5">Delete <?php echo $this->ModuleData['ModuleName'];?></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <!-- form -->
                    <form id="edit_form" name="edit_form" autocomplete="off" ng-include="templateURLDelete">
                    </form>
                    <!-- /form -->
                </div>
            </div>
        </div>
        <!-- Filter Modal -->
        <div class="modal fade" id="filter_model" ng-init="initDateRangePicker()">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5">Filters</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>

                    <!-- Filter form -->
                    <form id="filterForm" role="form" name="form" autocomplete="off" class="ng-pristine ng-valid">
                        <div class="modal-body">
                            <div class="form-area">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="Narration">Narration</label>
                                            <select id="Narration" name="Narration" class="form-control chosen-select">
                                                <option value="">Please Select</option>
                                                <option value="Deposit Money">Deposit Money</option>
                                                <option value="Join Contest">Join Contest</option>
                                                <option value="Cancel Contest">Cancel Contest</option>
                                                <option value="Signup Bonus">Signup Bonus</option>
                                                <option value="Admin Cash Bonus">Admin Cash Bonus</option>
                                                <option value="Admin Deposit Money">Admin Deposit Money</option>
                                                <option value="Join Contest Winning">Join Contest Winning</option>
                                                <option value="First Deposit Bonus">First Deposit Bonus</option>
                                                <option value="Verification Bonus">Verification Bonus</option>
                                                <option value="Referral Bonus">Referral Bonus</option>
                                                <option value="Coupon Discount">Coupon Discount</option>
                                                <option value="Withdrawal Request">Withdrawal Request</option>
                                                <option value="Withdrawal Reject">Withdrawal Reject</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="Status">Status</label>
                                            <select id="Status" name="Status" class="form-control chosen-select">
                                                <option value="">Please Select</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Failed">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="ParentCategory">Date Between</label>
                                            <div id="dateRange"
                                                style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span>Select Date Range</span> <i class="fa fa-caret-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="ParentCategory">Search</label>
                                            <input type="text" class="form-control" name="Keyword" placeholder="Search">
                                        </div>
                                    </div>
                                </div>





                            </div> <!-- form-area /-->
                        </div> <!-- modal-body /-->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                ng-click="resetUserForm()">Reset</button>
                            <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal"
                                ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
                        </div>

                    </form>
                    <!-- Filter form/ -->
                </div>
            </div>
        </div>

    </div><!-- Body/ -->