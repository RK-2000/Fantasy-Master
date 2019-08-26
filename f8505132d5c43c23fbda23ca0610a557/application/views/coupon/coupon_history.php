<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>
<div class="panel-body" ng-controller="PageController">
    <!-- Body -->

    <!-- Top container -->
    <div class="clearfix mt-2 mb-2">
        <div class="clearfix mt-2 mb-2">
            <span class="float-left records d-none d-sm-block">
                <span class="h5"><b>Coupon Details</b></span><br>
            </span>
        </div>
        <div class="clearfix mt-2 mb-2">
            <span class="float-left records hidden-sm-down">
                <span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
            </span>
        </div>
        <!-- Top container/ -->
        <table class="table table-striped table-hover">
            <!-- table heading -->
            <thead>
                <tr>
                    <th style="width: 100px;" class="text-center">Coupon Code</th>
                    <th style="width: 100px;" class="text-center">Coupon Value</th>
                    <th style="width: 100px;" class="text-center">Coupon Type</th>
                    <th style="width: 100px;" class="text-center">Coupon Validity</th>
                    <th style="width: 100px;" class="text-center">Total Successful Uses</th>
                    <th style="width: 100px;" class="text-center">Total Discount (Till now)</th>
                    <th style="width: 100px;" class="text-center">Status</th>

                </tr>
            </thead>
            <!-- table body -->
            <tbody>
                <tr scope="row">
                    <td class="text-center">
                        <span>{{couponData.CouponCode}}</span>
                    </td>
                    <td class="text-center">
                        <span>{{couponData.CouponValue}}</span>
                    </td>
                    <td class="text-center">
                        <span>{{couponData.CouponType}}</span>
                    </td>
                    <td class="text-center">
                        <span>{{couponData.CouponValidTillDate}}</span>
                    </td>
                    <td class="text-center">
                        <span>{{couponData.TotalSuccessfulUses}}</span>
                    </td>
                    <td class="text-center">
                        <span>{{DEFAULT_CURRENCY}}{{couponData.TotalDiscount}}</span>
                    </td>
                    <td class="text-center">
                        <span ng-if="couponData.Status"
                            ng-class="{Inactive:'text-danger', Active:'text-success'}[couponData.Status]">{{couponData.Status}}</span><span
                            ng-if="!couponData.Status">-</span>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- Data table -->
        <div class="table-responsive block_pad_md" infinite-scroll="getHistoryList()"
            infinite-scroll-disabled='data.listLoading' infinite-scroll-distance="0">
            <!-- loading -->
            <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
            <hr>
            <div class="clearfix mt-2 mb-2">
                <span class="float-left records d-none d-sm-block">
                    <span class="h5"><b>History</b></span><br>
                </span>
                <div class="float-right">
                    <button class="btn btn-success btn-sm ml-1 float-right"
                        onclick="window.location.href= BASE_URL + 'coupon'">Back</button>
                </div>
                <div class="float-right">
                    <button class="btn btn-default btn-secondary btn-sm ng-scope" data-toggle="modal"
                        data-target="#filter_model"><img src="asset/img/filter.svg"></button>
                </div>
            </div>
            <form name="records_form" id="records_form">
                <!-- data table -->
                <table class="table table-striped table-hover" ng-if="couponHistory.length">
                    <!-- table heading -->
                    <thead>
                        <tr>
                            <th style="width: 200px;">User</th>
                            <th style="width: 100px;">Phone No.</th>
                            <th style="width: 100px;">Transaction ID</th>
                            <th style="width: 100px;">Amount</th>
                            <th style="width: 100px;">Discount Amount</th>
                            <th style="width: 100px;">Pay By</th>
                            <th style="width: 100px;">Date & Time</th>
                            <th style="width: 120px;">Status</th>

                        </tr>
                    </thead>
                    <!-- table body -->
                    <tbody>
                        <tr scope="row" ng-repeat="(key, row) in couponHistory">

                            <td class="listed sm clearfix">
                            <a href="userdetails?UserGUID={{row.UserGUID}}"><img class="rounded-circle float-left" ng-src="{{row.ProfilePic}}"></a>
                            <div class="content float-left"><strong><a target="_blank" href="userdetails?UserGUID={{row.UserGUID}}">{{row.FullName}}</a></strong>
                            <div ng-if="row.Email || row.EmailForChange"><a href="mailto:{{row.Email == '' ? row.EmailForChange : row.Email}}" target="_top">{{row.Email == "" ? row.EmailForChange : row.Email}}</a></div><div ng-if="!row.Email && !row.EmailForChange">-</div>
                            <span ng-if="row.Email || row.EmailForChange" ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[row.EmailStatus]">({{row.EmailStatus}})</span><br>
                            </div>

                        </td> 
                        <td>
                            <div ng-if="row.PhoneNumber || row.PhoneNumberForChange"><a href="javascript:void(0);">{{row.PhoneNumber == "" ? row.PhoneNumberForChange : row.PhoneNumber}}</a>
                            </div>
                            <div ng-if="!row.PhoneNumber && !row.PhoneNumberForChange">-</div>
                            <span ng-if="row.PhoneNumber || row.PhoneNumberForChange" ng-class="{Pending:'text-danger', Verified:'text-success',Deleted:'text-danger',Blocked:'text-danger'}[row.PhoneStatus]">({{row.PhoneStatus}})</span><br>
                        </td> 
                        <td>
                            {{row.TransactionID}}
                        </td>
                        <td>
                            {{DEFAULT_CURRENCY}}{{row.Amount}}
                        </td>
                        <td>
                            {{DEFAULT_CURRENCY}}{{row.CouponDetails.DiscountedAmount}}
                        </td>
                        <td>
                            {{row.PaymentGateway}}
                        </td>
                        <td>
                            {{row.EntryDate}}
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
                                            <label class="filter-col" for="PhoneStatus">Phone Status</label>
                                            <select id="PhoneStatus" name="PhoneStatus" class="form-control chosen-select">
                                                <option value="">Please Select</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Verified">Verified</option>
                                            </select>   
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="Status">Transaction Status</label>
                                            <select id="Status" name="Status" class="form-control chosen-select">
                                                <option value="">Please Select</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Failed">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="filter-col" for="ParentCategory">Search</label>
                                            <input type="text" class="form-control" name="Keyword" placeholder="Search">
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
                                    
                                </div>

                            </div> <!-- form-area /-->
                        </div> <!-- modal-body /-->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                ng-click="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal"
                                ng-disabled="editDataLoading" ng-click="applyHistoryFilter()">Apply</button>
                        </div>

                    </form>
                    <!-- Filter form/ -->
                </div>
            </div>
        </div>

    </div><!-- Body/ -->