<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
  
</header>

<div class="panel-body" ng-controller="PageController">
    <!-- Body -->
    <h4 ng-class="{Pending:'text-secondary', Completed:'text-success',Cancelled:'text-danger',Running:'text-primary'}[contestDetail.Status]">Status : {{contestDetail.Status}} </h4>
    <!-- Top container -->
    <div class="row">
        <div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
            <div class="form-group">
                <center>
                    <h3>{{contestDetail.SeriesName}}</h3>
                </center>
            </div>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-md-4">
            <div class="form-group">
                <img ng-src="{{matchDetail.TeamFlagLocal}}" width="100px" height="100px">
            </div>
            <div class="form-group">
                <p><strong>{{contestDetail.TeamNameLocal}} ({{contestDetail.TeamNameShortLocal}})</strong></p>
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
                <p><strong>{{contestDetail.TeamNameVisitor}} ({{contestDetail.TeamNameShortVisitor}})</strong></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
            <div class="form-group">
                <center>
                    <h3>Contest Name : <b>{{contestDetail.ContestName}}</b></h3><br>
                </center>
            </div>
            <div class="form-group">
                <center>
                    <h5>Match Datetime : <b>{{contestDetail.MatchStartDateTime}}</b></h5>
                </center>
            </div>
            <div class="form-group">
                <center>
                <h4 ng-class="{Pending:'text-secondary', Completed:'text-success',Cancelled:'text-danger',Running:'text-primary'}[contestDetail.Status]">Status : {{contestDetail.Status}} </h4>
                </center>
            </div>
        </div>
    </div>
    <hr>
    <div class="float-right">
        <a href="contests"><button class="btn btn-success btn-sm ml-1">Back</button></a>
    </div>
    <div class="clearfix mt-2 mb-2">
        <!-- <span class="float-left records d-none d-sm-block">
            <span class="h5"><b>{{userData.FullName}} ({{userData.Email}})</b></span><br>
        </span>-->

    </div>
    <div class="clearfix mt-2 mb-2">
        <span class="float-left records d-none d-sm-block">
            <span ng-if="data.dataList.length" class="h5">Total records: {{data.totalRecords}}</span>
        </span>

    </div>
    <!-- Top container/ -->


    <!-- Data table -->
    <div class="table-responsive block_pad_md" infinite-scroll="getList()" infinite-scroll-disabled='data.listLoading'
        infinite-scroll-distance="0">
        <!-- loading -->
        <p ng-if="data.listLoading" class="text-center data-loader"><img src="asset/img/loader.svg"></p>

        <!-- data table -->
        <table class="table table-striped table-condensed table-hover table-sortable" ng-if="data.dataList.length">
            <!-- table heading -->
            <thead>
                <tr>
                    <th style="width: 100px;min-width:100px;">User</th>
                    <th>Full Name</th>
                    <th style="width: 100px;">User Team Name</th>
                    <th style="width: 100px;">Total Point</th>
                    <th style="width: 100px;">User Rank</th>
                    <th style="width: 100px;">User Winning Amount</th>
                    <th style="width: 100px;">Action</th>
                </tr>
            </thead>
            <!-- table body -->
            <tbody id="tabledivbody">

                <tr scope="row" ng-repeat="(key, row) in data.dataList">

                    <td class="listed sm clearfix">
                        <a href="userdetails?UserGUID={{row.UserGUID}}"><img class="rounded-circle float-left"
                                ng-src="{{row.ProfilePic}}"></a>
                    </td>
                    <td style="width: 100px;">{{row.FullName}}</td>
                    <td style="width: 100px;">{{row.UserTeamName}}</td>
                    <td style="width: 100px;">{{row.TotalPoints}}</td>
                    <td style="width: 100px;">{{row.UserRank}}</td>
                    <td style="width: 100px;">{{data.DEFAULT_CURRENCY}} {{row.UserWinningAmount}}</td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-secondary  btn-sm action" type="button" id="dropdownMenu2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>
                            <div class="dropdown-menu dropdown-menu-left">
                                <a class="dropdown-item" href="" ng-click="loadTeams(row)">View Team Players</a>
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
    <!-- contest joined user Modal -->
    <div class="modal fade" id="viewTeams_model">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5">Team Players</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div ng-include="templateURLEdit"></div>
            </div>
        </div>
    </div>
</div><!-- Body/ -->