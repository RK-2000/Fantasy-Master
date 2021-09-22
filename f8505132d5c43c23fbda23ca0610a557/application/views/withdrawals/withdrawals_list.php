<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>
<div class="panel-body" ng-controller="PageController" ><!-- Body -->

	<!-- Top container -->
	<div class="clearfix mt-2">
		<span class="float-left records hidden-sm-down">
			<span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
		</span>

		<div class="float-right">
			<button class="btn btn-default btn-secondary btn-sm ng-scope" data-toggle="modal" data-target="#filter_model"><img src="asset/img/filter.svg"></button>&nbsp;
		</div>
		<div class="float-right d-none">
			<button class="btn btn-default btn-secondary btn-sm ng-scope" ng-click="ExportList('WithdrawalList','WithdrawalList.csv')">Export</button>&nbsp;
		</div>
	</div>
	<!-- Top container/ -->



	<!-- Data table -->
	<div class="table-responsive block_pad_md" infinite-scroll="getList()" infinite-scroll-disabled='data.listLoading' infinite-scroll-distance="0"> 

		<!-- loading -->
		<p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
		<form name="records_form" id="records_form">
			<!-- data table -->
			<table id="WithdrawalList" class="table table-striped table-hover" ng-if="data.dataList.length">
				<!-- table heading -->
				<thead>
					<tr>
						<th style="width: 300px;min-width:200px;">User</th>
						<th>Phone No.</th>
						<th>Amount</th>
						<th>Payment Gateway</th>
						<th>Bank Details</th>
						<th style="width:120px;">Status</th>
						<th style="width:120px;">Entry Date</th>
						<th style="width:100px;" class="text-center">Action</th>

					</tr>
				</thead>
				<!-- table body -->
				<tbody>
					<tr scope="row" ng-repeat="(key, row) in data.dataList">
						<td class="listed sm clearfix">
							<a href="userdetails?UserGUID={{row.UserGUID}}" target="_blank"><img class="rounded-circle float-left" ng-src="{{row.ProfilePic}}"></a>
							<div class="content float-left"><strong><a target="_blank" href="userdetails?UserGUID={{row.UserGUID}}">{{row.FullName}}</a></strong>
							<div ng-if="row.Email || row.EmailForChange"><a href="mailto:{{row.Email == '' ? row.EmailForChange : row.Email}}" target="_top">{{row.Email == "" ? row.EmailForChange : row.Email}}</a></div><div ng-if="!row.Email && !row.EmailForChange">-</div>
							</div>

						</td> 
						<td>
							<div ng-if="row.PhoneNumber || row.PhoneNumberForChange"><a href="javascript:void(0);">{{row.PhoneNumber == "" ? row.PhoneNumberForChange : row.PhoneNumber}}</a>
							</div>
							<div ng-if="!row.PhoneNumber && !row.PhoneNumberForChange">-</div>
						</td> 
						<td>
							<span ng-if="row.Amount">{{DEFAULT_CURRENCY}}{{row.Amount}}</span><span ng-if="!row.Amount">-</span>
						</td>
						<td>
							<span ng-if="row.PaymentGateway">{{row.PaymentGateway}}</span><span ng-if="!row.Amount">-</span>
						</td> 
						<td>
							<span ng-if="!row.MediaBANK.MediaCaption.FullName">-</span><br>
							<span ng-if="row.MediaBANK.MediaCaption.FullName">Name : {{row.MediaBANK.MediaCaption.FullName}}</span><br>
							<span ng-if="row.MediaBANK.MediaCaption.Bank"> Bank : {{row.MediaBANK.MediaCaption.Bank}}</span>
							<span ng-if="!row.MediaBANK.MediaCaption.FullName">-</span><br>
							<span ng-if="row.MediaBANK.MediaCaption.AccountNumber"> A/C : {{row.MediaBANK.MediaCaption.AccountNumber}}</span>
							<span ng-if="!row.MediaBANK.MediaCaption.AccountNumber">-</span><br>
							<span ng-if="row.MediaBANK.MediaCaption.IFSCCode"> IFSC : {{row.MediaBANK.MediaCaption.IFSCCode}}</span>
							<span ng-if="!row.MediaBANK.MediaCaption.IFSCCode">-</span>
						</td>
						<td class="text-center">
							<span ng-if="row.Status" ng-class="{Pending:'text-danger', Verified:'text-success',Rejected:'text-danger'}[row.Status]">{{row.Status}}</span><span ng-if="!row.Status">-</span><p ng-if="row.Status == 'Pending'">(<span am-time-ago="row.EntryDate" ></span>)</p>
						</td>
						<td>
							<span ng-if="row.EntryDate">{{row.EntryDate}}</span><span ng-if="!row.EntryDate">-</span>
						</td> 
						<td class="text-center">
							<div class="dropdown" ng-if="row.Status == 'Pending'">
								<button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-if="data.UserGUID!=row.UserGUID">&#8230;</button>
								<div class="dropdown-menu dropdown-menu-left">
									<a class="dropdown-item" href="" ng-click="loadFormEdit(key, row.WithdrawalID)">Action</a>
								</div>
							</div>
							<div ng-if="row.Status == 'Rejected'"><strong>Reject Reason</strong> {{row.Comments}}</div>
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
	
	<div class="modal fade" id="filter_model" ng-init="initDateRangePicker()">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Filters</h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>

				<!-- Filter form -->
				<form id="filterForm1" role="form" autocomplete="off" class="ng-pristine ng-valid">
					<div class="modal-body">
					<div class="form-area">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="filter-col" for="ParentCategory">From & End Date Between</label>
									<div id="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
										<i class="fa fa-calendar"></i>&nbsp;
										<span>Select Date Range</span> <i class="fa fa-caret-down"></i> 
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="filter-col" for="Status">Withdrawal Status</label>
									<select id="Status" name="Status" class="form-control chosen-select">
										<option value="">Please Select</option>
										<option value="Pending">Pending</option>
										<option value="Verified">Verified</option>
										<option value="Rejected">Rejected</option>
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
						</div> <!-- form-area /-->
					</div> <!-- modal-body /-->

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" ng-click="resetUserForm()">Reset</button>
						<button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
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
					<h3 class="modal-title h5">Withdrawal Request</h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<!-- form -->
				<form id="edit_form" name="edit_form" autocomplete="off" ng-include="templateURLEdit">
				</form>
				<!-- /form -->
			</div>
		</div>
	</div>

</div><!-- Body/ -->