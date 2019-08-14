<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle']; ?></h1>
</header>
<div class="panel-body" ng-controller="PageController">
    <!-- Body -->
    <!-- Top container/ -->
    <div class="float-right">
        <a href="user"><button class="btn btn-success btn-sm ml-1">Back</button></a>
    </div>
    <div class="clearfix mt-2 mb-2">
        <span class="float-left records d-none d-sm-block">
            <span class="h5"><b>{{userData.FullName}} ({{userData.Email}})</b></span><br>
        </span>
    </div>
  
    <!-- Data table -->
    <div class="table-responsive block_pad_md" infinite-scroll="getList()" infinite-scroll-disabled='data.listLoading'
        infinite-scroll-distance="0">

        <!-- loading -->
        <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
        <form name="records_form" id="records_form">
            <!-- data table -->
            <table class="table table-striped table-hover user__table" ng-if="data.dataList.length">
                <!-- table heading -->
                <thead>
                    <tr>
                        <th style="width: 300px;min-width:200px;">User</th>
                        <th>Phone No.</th>
                        <th style="width: 160px;">Deposit Amount</th>
                        <th style="width: 160px;">Winning Amount</th>
                        <th style="width: 160px;">Cash Bonus</th>
                        <th style="width: 160px;" class="text-center sort" ng-click="applyOrderedList('E.EntryDate', 'ASC')">Registered On <span class="sort_deactive">&nbsp;</span></th>
                        <th style="width: 160px;" class="text-center">Last Login</th>
                        <th style="width: 100px;" class="text-center">Status</th>

                    </tr>
                </thead>
                <!-- table body -->
                <tbody>
                    <tr scope="row" ng-repeat="(key, row) in data.dataList" style="height:100px;">

                        <td class="listed sm clearfix">
                        <a href="userdetails?UserGUID={{row.UserGUID}}"><img class="rounded-circle float-left" ng-src="{{row.ProfilePic}}"></a>
                            <div class="content float-left"><strong><a target="_blank" href="userdetails?UserGUID={{row.UserGUID}}">{{row.FullName}}</a></strong>
                            <div ng-if="row.Email || row.EmailForChange"><a href="mailto:{{row.Email == '' ? row.EmailForChange : row.Email}}" target="_top">{{row.Email == "" ? row.EmailForChange : row.Email}}</a></div><div ng-if="!row.Email && !row.EmailForChange">-</div>
                            <span ng-if="row.Email || row.EmailForChange" ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[row.EmailStatus]">({{row.EmailStatus}})</span><br>
                            </div>

                        </td> 
                        <td>
                            <div ng-if="row.PhoneNumber || row.PhoneNumberForChange"><a href="javascript:void(0);">{{row.PhoneNumber == "" ? row.PhoneNumberForChange : row.PhoneNumber}}</a></div><div ng-if="!row.PhoneNumber && !row.PhoneNumberForChange">-</div>
                            <span ng-if="row.PhoneNumber || row.PhoneNumberForChange" ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[row.PhoneStatus]">({{row.PhoneStatus}})</span><br>
                            </div>
                        </td> 
                        <td>{{DEFAULT_CURRENCY}}{{row.WalletAmount}}</td> 
                        <td>{{DEFAULT_CURRENCY}}{{row.WinningAmount}}</td> 
                        <td>{{DEFAULT_CURRENCY}}{{row.CashBonus}}</td> 
                        <td><span ng-if="row.RegisteredOn">{{row.RegisteredOn}}</span><span ng-if="!row.RegisteredOn">-</span></td> 
                        <td><span ng-if="row.LastLoginDate">{{row.LastLoginDate}}</span><span ng-if="!row.LastLoginDate">-</span></td> 
                        <td class="text-center"><span ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[row.Status]">{{row.Status}}</span>
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
                <form id="filterForm1" role="form" autocomplete="off" class="ng-pristine ng-valid">
                    <div class="modal-body">
                        <div class="form-area">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="filter-col" for="EmailStatus">Email Status</label>
                                        <select id="EmailStatus" name="EmailStatus" class="form-control chosen-select">
                                            <option value="">Please Select</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Verified">Verified</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="filter-col" for="ParentCategory">Registered Between</label>
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
                        <button type="button" class="btn btn-secondary btn-sm" ng-click="resetUserForm()">Reset</button>
                        <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal"
                            ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
                    </div>

                </form>
                <!-- Filter form/ -->
            </div>
        </div>
    </div>




    <!-- edit Modal -->
    <div class="modal fade" id="edit_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Edit <?php echo $this->ModuleData['ModuleName']; ?></h3>
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
    <!-- Verification Modal -->
    <div class="modal fade" id="Verification_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Verirification</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="Verification_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>
    <!-- Add deposit money Modal -->
    <div class="modal fade" id="AddDepositMoney_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Add Token</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="addDeposit_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>
    <!-- Add deposit money Modal -->
    <div class="modal fade" id="AddDepositPoint_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Add Points</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="addDepositPoint_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>
    <!-- Add cash bonus Modal -->
    <div class="modal fade" id="AddCashBonus_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Add Cash Bonus</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="addCash_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>

    <!-- Add referral users list Modal -->
    <div class="modal fade" id="referralUserList_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Referral Users List</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="referralUserList_form" name="referralUserList_form" autocomplete="off"
                    ng-include="templateURLEdit">
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
                    <h3 class="modal-title h5">Delete <?php echo $this->ModuleData['ModuleName']; ?></h3>
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


</div><!-- Body/ -->