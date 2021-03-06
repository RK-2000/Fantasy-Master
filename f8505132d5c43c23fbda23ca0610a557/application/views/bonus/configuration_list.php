<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>
<div class="panel-body" ng-controller="PageController" ng-init="getList()"><!-- Body -->

	<!-- Top container -->
	<div class="clearfix mt-2 mb-2">
		
	</div>
	<!-- Top container/ -->


	<!-- Data table -->
	<div class="table-responsive block_pad_md" > 
		<!-- loading -->
		<p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>
		<form method="post" id="generalPoint_form" name="generalPoint_form"  autocomplete='off'>
		<!-- data table for General Points -->
		
		<table class="table table-striped table-condensed table-hover table-sortable" ng-show="data.dataList.length > 0">
			<!-- table heading -->
			<thead>
				<tr>
					<th style="width: 250px;">Config Type</th>
					<th style="width: 200px;">Config Type Value</th>
					<th style="width: 150px;">Status</th>
					<th style="width: 100px;">Action</th>
				</tr>
			</thead>
			<!-- table body -->
			<tbody id="tabledivbody">
				
					<tr scope="row" ng-repeat="(key, row) in data.dataList" >
					
						<td>
							<strong>{{row.ConfigTypeDescprition}}</strong>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control numeric " ng-model="row.ConfigTypeValue" >
							</div>
						</td>
						<td>
							<div class="form-group">
								<select name="Status" id="Status" class="form-control chosen-select" ng-model="row.Status">
									<option value="Active" ng-selected="row.Status=='Active'" >Active</option>
									<option value="Inactive" ng-selected="row.Status=='Inactive'" >Inactive</option>
								</select>
							</div>
						</td>
						<td>
							<button class="btn btn-success btn-sm" ng-click="updateConfigData(row.ConfigTypeGUID,row.ConfigTypeValue,row.Status)">Update</button>
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


</div><!-- Body/ -->