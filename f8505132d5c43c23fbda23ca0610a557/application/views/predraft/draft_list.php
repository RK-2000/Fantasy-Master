<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>

<div class="panel-body" ng-controller="PageController"><!-- Body -->

	<!-- Top container -->
	<div class="clearfix mt-2 mb-2" >
		<span class="float-left records d-none d-sm-block">
			<span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
		</span>
		<div class="float-right">
			<!-- <button class="btn btn-default btn-secondary btn-sm ng-scope" data-toggle="modal" data-target="#filter_model"><img src="asset/img/search.svg"></button> -->
			<button class="btn btn-success btn-sm ml-1" ng-click="loadFormAdd();">Add Pre Draft</button>
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
					<th>Draft Name</th>
					<th style="width: 100px;">Type</th>
					<th style="width: 70px;">Is Paid?</th>
					<th style="width: 70px;">Size</th>
					<th style="width: 70px;">Privacy</th>
					<th style="width: 70px;" class="text-center">Fee</th>
					<th style="width: 70px;" class="text-center">Entry Type</th>
					<th style="width: 70px;" class="text-center"># Winners</th>
					<th style="width: 100px;" class="text-center">Winning Amount</th>
					<th style="width: 100px;" class="text-center">Action</th>
					
					
				</tr>
			</thead>
			<!-- table body -->
			<tbody id="tabledivbody">


				<tr scope="row" ng-repeat="(key, row) in data.dataList" id="sectionsid_{{row.MenuOrder}}.{{row.CategoryID}}">
				
					<td>
						<div class="content float-left"><strong><a href="javascript:void(0)" >{{row.DraftName}}</a></strong>
						
						</div>
					</td>
					<td>
						<p>{{!row.DraftType ? '-' : row.DraftType }}</p>
					</td>
					<td>
						<p>{{row.IsPaid}}</p>
					</td>
					<td>
						<p>{{row.DraftSize}}</p>
					</td>
					<td>
						<p>{{row.Privacy}}</p>
					</td>
					<td>
						<p>{{data.DEFAULT_CURRENCY}} {{row.EntryFee}}</p>
					</td>
					<td>
						<p>{{row.EntryType}}</p>
					</td>
					<td>
						<p>{{row.NoOfWinners}}</p>
					</td>
					<td>
						<p>{{data.DEFAULT_CURRENCY}} {{row.WinningAmount}}</p>
					</td>
					<td class="text-center">
						<div class="dropdown">
							<button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
							<div class="dropdown-menu dropdown-menu-left">
								<a class="dropdown-item" href="" ng-click="loadFormEdit(key, row.PredraftContestID)">Edit</a>
								<a class="dropdown-item" href="" ng-click="deleteDraft(key, row.PredraftContestID)">Delete</a>
								
							</div>
						</div>
						<div class="dropdown" ng-if="row.Status!='Pending'">
							<span>-</span>
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
									<label class="filter-col" for="ParentCategory">Series</label>
									<select id="Series" name="SeriesGUID" ng-model="SeriesGUID" class="form-control chosen-select" ng-change="getMatches(SeriesGUID)">
										<option value="">Please Select</option>
										<option ng-repeat="Series in filterData.SeiresData" value="{{Series.SeriesGUID}}">{{Series.SeriesName}}</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="filter-col" for="ParentCategory">Match</label>
									<select id="MatchGUID" name="MatchGUID" class="form-control chosen-select">
										<option value="">Please Select</option>
										<option class="filter-matches" ng-repeat="match in MatchData" value="{{match.MatchGUID}}">{{match.TeamNameLocal}} Vs {{match.TeamNameVisitor}} ON {{match.MatchStartDateTime}}</option>
									</select>
								</div>
							</div>
							</div>

						</div> <!-- form-area /-->
					</div> <!-- modal-body /-->

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" onclick="$('.filter-matches').remove();$('#filterForm1').trigger('reset'); $('.chosen-select').trigger('chosen:updated');">Reset</button>
						<button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
					</div>

				</form>
				<!-- Filter form/ -->
			</div>
		</div>
	</div>



	<!-- add Modal -->
	<div class="modal fade" id="add_model">
		<div class="modal-dialog modal-md customContestModal  modal-lg" style="max-width: 900px;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Add Pre Draft</h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLAdd"></div>
			</div>
		</div>
	</div>



	<!-- edit Modal -->
	<div class="modal fade" id="edit_model">
		<div class="modal-dialog modal-md customContestModal  modal-lg" style="max-width: 900px;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Edit <?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLEdit"></div>
			</div>
		</div>
	</div>

	<!-- status Modal -->
	<div class="modal fade" id="status_model">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5"><?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLEdit"></div>
			</div>
		</div>
	</div>

	<!-- contest joined user Modal -->
	<div class="modal fade" id="contestJoinedUsers_model">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5"><?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div ng-include="templateURLEdit"></div>
			</div>
		</div>
	</div>


	<!-- delete Modal -->
	<div class="modal fade" id="delete_model">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title h5">Delete <?php echo $this->ModuleData['ModuleName'];?></h3>     	
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<!-- form -->
				<form id="edit_form" name="edit_form" autocomplete="off" ng-include="templateURLDelete">
				</form>
				<!-- /form -->
			</div>
		</div>
	</div>
</div><!-- Body/ -->