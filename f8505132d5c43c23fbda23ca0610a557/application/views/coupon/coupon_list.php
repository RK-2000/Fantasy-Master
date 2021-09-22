<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle']; ?></h1>
</header>

<div class="panel-body" ng-controller="PageController" ><!-- Body --> 



	<!-- Top container -->
	<div class="clearfix mt-2 mb-2">
		<span class="float-left records hidden-sm-down">
			<span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
		</span>
		<div class="float-right">
			<button class="btn btn-success btn-sm" ng-click="loadFormAdd();">Add Coupon</button>
		</div>
		<div class="float-right">
			<button class="btn btn-default btn-secondary btn-sm ng-scope" data-toggle="modal" data-target="#filter_model"><img src="asset/img/filter.svg"></button>&nbsp;
		</div>
	</div>
	<!-- Top container/ -->


	<!-- Data table -->
	<div class="table-responsive block_pad_md" infinite-scroll="getList()" infinite-scroll-disabled='data.listLoading' infinite-scroll-distance="0"> 

		<!-- loading -->
		<p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>

		<!-- data table -->
		<table class="table table-striped table-hover " ng-if="data.dataList.length">
			<!-- table heading -->
			<thead>
				<tr>
					<th style="width:80px;" class="text-center">Banner</th>
					<th style="width: 120px;">Coupon Code</th>
					<th style="width: 150px;">Title</th>
					<th>Description</th>
					<th style="width: 80px;">Value</th>
					<th style="width: 160px;">Created on</th>
					<th style="width: 160px;">Valid Till</th>
					<th style="width: 100px;">Minium Amount</th>
					<th style="width: 100px;">Maximum Amount</th>
					<th style="width: 100px;">No. Of Uses</th>
					<th style="width: 100px;">Status</th>
					<th style="width: 100px;">Action</th>
				</tr>
			</thead>
			<!-- table body -->
			<tbody>
				<tr scope="row" ng-repeat="(key, row) in data.dataList">

					<td class="listed sm text-center">
						<img ng-src="{{row.CouponBanner}}" alt="coupon-img">
					</td>
					<td><div class="content float-left"><strong><a class="dropdown-item" href="couponhistory?CouponGUID={{row.CouponGUID}}&CouponCode={{row.CouponCode}}">{{row.CouponCode}}</a><strong></div></td>
					<td>{{row.CouponTitle}}</td>
					<td>{{row.CouponDescription}}</td>
					<td>{{DEFAULT_CURRENCY}}{{row.CouponValue}}<span ng-if="row.CouponType=='Percentage'">%</span></td>
					<td>{{row.EntryDate}}</td>
					<td>{{row.CouponValidTillDate}}</td>
					<td>{{DEFAULT_CURRENCY}}{{row.MiniumAmount}}</td>
					<td>{{DEFAULT_CURRENCY}}{{row.MaximumAmount}}</td>
					<td>{{row.NumberOfUses}}</td>
					<td><span ng-class="{Inactive:'text-danger', Active:'text-success'}[row.Status]">{{row.Status}}</span></td> 
					<td class="text-center">
						<div class="dropdown">
							<button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
							<div class="dropdown-menu dropdown-menu-left">
								<a class="dropdown-item" href="" ng-click="loadFormEdit(key, row.CouponGUID)">Edit</a>
								<a class="dropdown-item" href="couponhistory?CouponGUID={{row.CouponGUID}}&CouponCode={{row.CouponCode}}">History</a>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- no record -->
		<p class="no-records text-center" ng-if="data.noRecords">
			<span ng-if="data.dataList.length">No more records found.</span>
			<span ng-if="!data.dataList.length">No records found.</span>
		</p>
	</div>
	<!-- Data table/ -->


	<!-- add Modal -->
	<div class="modal fade" id="add_model">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Add <?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
					<div ng-include="templateURLAdd"></div>
			</div>
		</div>
	</div>


	<!-- edit Modal -->
	<div class="modal fade" id="Edit_model">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Edit <?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLEdit"></div>
			</div>
		</div>
	</div>

		<div class="modal fade" id="filter_model" ng-init="initDateRangePicker()">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Filters</h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>

				<!-- Filter form -->
			
				<!-- Filter form -->
				<form id="filterForm1" role="form" autocomplete="off" class="ng-pristine ng-valid">
					<div class="modal-body">
						<div class="form-area">

							<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="filter-col" for="CouponType">Coupon Type</label>
									<select id="CouponType" name="CouponType" class="form-control chosen-select">
										<option value="">Please Select</option>
										<option value="Flat">Flat</option>
										<option value="Percentage">Percentage</option>
									</select>   
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="filter-col" for="Status">Status</label>
									<select id="Status" name="Status" class="form-control chosen-select">
										<option value="">Please Select</option>
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</select>   
								</div>
							</div>
							<div class="col-md-6" ng-init="initDateRangePicker()">
								<div class="form-group">
									<label class="filter-col" for="ParentCategory">Valid Between</label>
									<div id="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
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
						<button type="button" class="btn btn-secondary btn-sm" ng-click="resetForm()">Reset</button>
						<button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
					</div>

				</form>
				<!-- Filter form/ -->
			</div>
		</div>
	</div>


</div><!-- Body/ -->



