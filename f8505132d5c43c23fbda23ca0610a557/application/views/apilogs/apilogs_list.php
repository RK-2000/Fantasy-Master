<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle']; ?></h1>
</header>
<div class="panel-body" ng-controller="PageController">
    <!-- Body -->

    <!-- Top container -->
    <div class="clearfix mt-2 mb-2">
        <span class="float-left records d-none d-sm-block">
            <p ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</p>
            <p><strong class="api_status" ng-class="{false:'text-danger', true:'text-success'}[data.IsAPILogs]">API Save Log Current Status : {{data.IsAPILogs == true ? 'On' : 'Off'}}</strong></p>
        </span>
        <div class="float-right mr-2">
            <button class="btn btn-success btn-sm ml-1 float-right" ng-click="deleteAll();" title="Click Here To Delete All API Logs">Delete All</button>
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
       <p><strong>API Save Log Current Status : {{data.IsAPILogs == true ? 'On' : 'Off'}}</strong></p>
        <!-- data table -->
        <table class="table table-striped table-condensed table-hover table-sortable" ng-if="data.dataList.length">
            <!-- table heading -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>API</th>
                    <th>URL</th>
                    <th>Entry Date</th>
                    <th style="width: 100px;" class="text-center">Action</th>
                </tr>
            </thead>
            <!-- table body -->
            <tbody id="tabledivbody">
                <tr scope="row" ng-repeat="(key, row) in data.dataList" id="sectionsid_{{row.MenuOrder}}.{{row.CategoryID}}">
                    <td>
                        {{row._id.$oid}}
                    </td>
                    <td>
                        {{row.DataJ.API}}
                    </td>
                    <td>
                        {{row.URL}}
                    </td>
                    <td>
                        {{row.EntryDate}}
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
                            <div class="dropdown-menu dropdown-menu-left">
                                <a class="dropdown-item" href="" ng-click="deleteAPILog(key, row._id.$oid)">Delete</a>
                                <a class="dropdown-item" href="" ng-click="viewAPILog(row)">View Details</a>
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
                <form id="filterForm" role="form" autocomplete="off" class="ng-pristine ng-valid">
                    <div class="modal-body">
                        <div class="form-area">

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
                        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#filterForm').trigger('reset'); $('.chosen-select').trigger('chosen:updated');">Reset</button>
                        <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" ng-disabled="editDataLoading" ng-click="applyFilter()">Apply</button>
                    </div>

                </form>
                <!-- Filter form/ -->
            </div>
        </div>
    </div>

    <!-- delete Modal -->
    <div class="modal fade" id="delete_model">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Delete <?php echo $this->ModuleData['ModuleName']; ?></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- form -->
                <form id="edit_form" name="edit_form" autocomplete="off" ng-include="templateURLDelete">
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>

    <!-- view Modal -->
    <div class="modal fade" id="view_model">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">View API Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div ng-include="templateURLEdit"></div>
            </div>
        </div>
    </div>
</div><!-- Body/ -->