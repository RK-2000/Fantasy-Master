<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>

<div class="panel-body" ng-controller="PageController"><!-- Body -->

	<!-- Top container -->
	<div class="row">
		<div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
			<div class="form-group">
				<center><h3>{{matchDetail.SeriesName}}</h3></center>
			</div>
		</div>
	</div>
	<div class="row" style="text-align: center;">
		<div class="col-md-4">
			<div class="form-group">
				<img ng-src="{{matchDetail.TeamFlagLocal}}" width="100px" height="100px">
			</div>
			<div class="form-group">
				<p><strong>{{matchDetail.TeamNameLocal}} ({{matchDetail.TeamNameShortLocal}})</strong></p>
			</div>
		</div>
		<div class="col-md-4">
			<!-- <div class="form-group"> -->
				<h6 class="display-4 text-muted">v/s</h6>
			<!-- </div> -->
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<img ng-src="{{matchDetail.TeamFlagVisitor}}" width="100px" height="100px">
			</div>
			<div class="form-group">
				<p><strong>{{matchDetail.TeamNameVisitor}} ({{matchDetail.TeamNameShortVisitor}})</strong></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
			<div class="form-group">
				<center><h3>Match Datetime : <b>{{matchDetail.MatchStartDateTime}}</b></h3></center>
			</div>
		</div>
	</div>
	<hr>
	<div class="clearfix mt-2 mb-2">
		<span class="float-left records d-none d-sm-block">
			<span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
		</span>
		<div class="float-right">
            <button class="btn btn-success btn-sm ml-1" data-toggle="modal" data-target="#import_salary_model"> Import
                Salary </button>
        </div>
		<div class="float-right mr-2"> <button class="btn btn-success btn-sm ml-1 float-right" onclick="window.location.href= BASE_URL + 'matches'">Back</button> </div>
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
					<th>Player's Name</th>
					<th>Team Name</th>
					<th style="width: 100px;" ng-if="!AllMatches" >Player's Role</th>
                    <th style="width: 100px;" ng-if="!AllMatches" >Credit Points</th>
			        <th style="width: 100px;" class="text-center">Action</th>
				</tr>
			</thead>
			<!-- table body -->
			<tbody id="tabledivbody">
				<tr scope="row" ng-repeat="(key, row) in data.dataList" id="sectionsid_{{row.MenuOrder}}.{{row.CategoryID}}">
					<td class="listed sm clearfix">
						<img class="rounded-circle float-left" ng-src="{{row.PlayerPic}}">
						<div class="content float-left">
							<strong>{{row.PlayerName}}</strong>
						</div>
					</td>
					<td>
						<p>{{row.TeamName}}</p>
					</td>
					<td ng-if="!AllMatches" >
						<p>{{row.PlayerRole}}</p>
					</td>
					<td ng-if="!AllMatches" >
						<p>{{row.PlayerSalary}}</p>
					</td>
               <td ng-if="!AllMatches" class="text-center">
						<div class="dropdown">
							<button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
							<div class="dropdown-menu dropdown-menu-left">
								<a class="dropdown-item" href="" ng-if="matchDetail.Status='Pending'" ng-click="loadFormEdit(key, row.PlayerGUID)">Edit</a>
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
	<div class="modal fade" id="filter_model"  ng-init="getFilterData()">
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
										<label class="filter-col" for="CategoryTypeName">Team Name</label>
										<select id="TeamGUID" name="TeamGUID" class="form-control chosen-select">
											<option value="">Please Select</option>
											<option ng-repeat="row in filterData.TeamData" value="{{row.TeamGUID}}">{{row.TeamName}}</option>
										</select>   
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="filter-col">Player Role</label>
										<select id="Player Role" name="PlayerRole" class="form-control chosen-select">
											<option value="">Please Select</option>
											<option value="WicketKeeper">Wicket Keeper</option>
											<option value="Batsman">Batsman</option>
											<option value="AllRounder">All Rounder</option>
											<option value="Bowler">Bowler</option>
										</select>   
									</div>
								</div>
							</div>
							<div class="row">
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
						<button type="button" class="btn btn-secondary btn-sm" onclick="$('#filterForm1').trigger('reset'); $('.chosen-select').trigger('chosen:updated');">Reset</button>
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
	    <!-- Import Salary Modal -->
		<div class="modal fade" id="import_salary_model" ng-init="importPlayerSalary()">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Import Salary</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>

                <!-- Import form -->
                <form id="ImportSalaryForm" name="ImportSalaryForm" role="form" autocomplete="off"
                    class="ng-pristine ng-valid" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-area">
                            <div class="float-right">
                                <a href="javascript:;" class="btn btn-success btn-sm"
                                    ng-click="downloadSalarySample()">Download Sample </a>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="filter-col" for="Status">Select File (CSV)</label>
                                        <input name="csv_file" id="csv_file" type="file" class="form-control"
                                            accept=".csv" onchange="return validateFile(this,'csv')"
                                            style="width:400px">

                                        <!-- <input type="file" accept=".csv" name="File" id="fileInput" class="form-control" onchange="return validateFile(this,'csv');"> -->
                                        <input type="hidden" name="SeriesGUID" id="SeriesGUID"
                                            value="<?php if (!empty($_GET['SeriesGUID'])) { echo $_GET['SeriesGUID']; ;}?>">
                                        <input type="hidden" name="RoundNo" id="RoundNo"
                                            value="<?php if (!empty($_GET['RoundNo'])) { echo $_GET['RoundNo']; ;}?>">
                                    </div>
                                </div>
                            </div>

                        </div> <!-- form-area /-->
                    </div> <!-- modal-body /-->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm"
                            ng-click="importPlayerSalary()">Import</button>
                        <!-- ng-click="importPlayerSalary()" -->
                    </div>

                </form>
                <!-- Filter form/ -->
            </div>
        </div>
    </div>

</div><!-- Body/ -->