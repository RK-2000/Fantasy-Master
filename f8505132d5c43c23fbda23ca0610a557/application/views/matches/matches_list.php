<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1> 
</header>

<div class="panel-body" ng-controller="PageController"><!-- Body -->

	<!-- Top container -->
	<div class="clearfix mt-2 mb-2">
		<span class="float-left records d-none d-sm-block">
			<span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
		</span>
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
		<table class="table table-striped table-condensed table-hover table-sortable" ng-if="data.dataList.length">
			<!-- table heading -->
			<thead>
				<tr>
					<th>Series Name</th>
					<th style="width: 100px;"></th>
					<th style="width: 200px;">Team Local</th>
					<th style="width: 100px;"></th>
					<th style="width: 200px;">Team Visitor</th>
					<th>Match Type & Location</th>
					<th style="width: 170px;">Match Start On</th>
					<th style="width: 100px;" class="text-center">Status</th>
					<th style="width: 100px;" class="text-center">Action</th>
				</tr>
			</thead>
			<!-- table body -->
			<tbody id="tabledivbody">
				<tr scope="row" ng-repeat="(key, row) in data.dataList" id="sectionsid_{{row.MenuOrder}}.{{row.CategoryID}}">
				
					<td>
						<strong>{{row.SeriesName}}</strong>
					</td>
					<td class="text-center">
						<img class="float-left" ng-src="{{row.TeamFlagLocal}}" width="80px" height="80px;">
					</td>
					<td>
						<p>{{row.TeamNameLocal}} <br><small>( {{row.TeamNameShortLocal}} )</small></p>
					</td>
					<td class="text-center">
						<img class="float-left" ng-src="{{row.TeamFlagVisitor}}" width="80px" height="80px;">
					</td>
					<td>
						<p>{{row.TeamNameVisitor}} <br><small>( {{row.TeamNameShortVisitor}} )</small></p>
					</td>
					<td>
						<p>{{row.MatchType}} at {{row.MatchLocation}} </p>
					</td>
					
					<td align="center">
						<p>{{row.MatchStartDateTime}} <br> (<span am-time-ago="row.MatchStartDateTime" ></span>)</p>
					</td>
					
					<td class="text-center"><span ng-class="{Pending:'text-secondary', Completed:'text-success',Cancelled:'text-danger',Running:'text-primary'}[row.Status]">{{row.Status}}</span></td>

					<td class="text-center">
						<div class="dropdown">
							<button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
							<div class="dropdown-menu dropdown-menu-left">
								<a class="dropdown-item" href=""  ng-if="row.Status=='Pending'" ng-click="loadFormEdit(key, row.MatchGUID)">Edit</a>
								<a class="dropdown-item" target="_blank" href="players?MatchGUID={{row.MatchGUID}}" >Players</a>
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




	<!-- Filter Modal -->
	<div class="modal fade" id="filter_model" ng-init="getFilterData()">
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
										<label class="filter-col" for="CategoryTypeName">Series</label>
										<select id="SeriesGUID" name="SeriesGUID" class="form-control chosen-select" ng-model="SeriesGUID" ng-change="getTeamData(SeriesGUID)">
											<option value="">Please Select</option>
											<option ng-repeat="row in filterData.SeiresData" value="{{row.SeriesGUID}}">{{row.SeriesName}}</option>
										</select>   
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="filter-col">Teams</label>
										<select name="TeamGUID" class="form-control chosen-select" ng-model="TeamGUID" ng-change="getTeamData(SeriesGUID,TeamGUID)">
											<option value="">Please Select</option>
											<option ng-repeat="row in LocalTeamData.TeamData" value="{{row.TeamGUID}}">{{row.TeamName}}</option>
										</select>   
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="filter-col" for="Status">Status</label>
										<select id="StatusID" name="StatusID" class="form-control chosen-select">
											<option value="">Please Select</option>
											<option value="1">Pending</option>
											<option value="2">Running</option>
											<option value="5">Completed</option>
											<option value="3">Cancelled</option>
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
							<div class="row" ng-init="initDateRangePicker()">
								<div class="col-md-8">
									<div class="form-group">
										<label class="filter-col" for="ParentCategory">Matches Between</label>
										<div id="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
											<i class="fa fa-calendar"></i>&nbsp;
											<span>Select Date Range</span> <i class="fa fa-caret-down"></i> 
										</div>
									</div>
								</div>
							</div>
                    </div> <!-- form-area /-->
					</div> <!-- modal-body /-->

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" ng-click="resetUserForm();">Reset</button>
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
					<h3 class="modal-title h5">Edit <?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLEdit"></div>
			</div>
		</div>
	</div>
</div><!-- Body/ -->