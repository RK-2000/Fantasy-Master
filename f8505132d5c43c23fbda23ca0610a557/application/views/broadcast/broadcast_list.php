<header class="panel-heading">
    <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle']; ?></h1>
</header>
<style>
input.chosen-search-input.default {
    width: 100% !important;
}
</style>
<div class="panel-body" ng-controller="PageController">
    <!-- Body -->
    <div class="form-area" style="margin: auto; border:1px solid #f7f7f7; padding:10px;">

        <div class="row col-md-8">
            <div class="checkbox">
                <input name="AllUsers" type="checkbox" class="form-check-input" checked ng-model="chkselct"
                        ng-init="chkselct=true" id="users">
                <label for="users">
                    All Users
                </label>
            </div>
        </div>

        <!-- Filter form -->
        <form id="filterForm1" role="form" autocomplete="off" class="ng-pristine ng-valid" ng-hide='chkselct'>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <hr/>
                        <h2 class="h5" style="margin-top: 10px;">Filter Users</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="EmailStatus">Email Status</label>
                        <select id="EmailStatus" name="EmailStatus" class="form-control chosen-select">
                            <option value="">Please Select</option>
                            <option value="Pending">Pending</option>
                            <option value="Verified">Verified</option>
                        </select>   
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="PhoneStatus">Phone Status</label>
                        <select id="PhoneStatus" name="PhoneStatus" class="form-control chosen-select">
                            <option value="">Please Select</option>
                            <option value="Pending">Pending</option>
                            <option value="Verified">Verified</option>
                        </select>   
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="PanStatus">Pan Status</label>
                        <select id="PanStatus" name="PanStatus" class="form-control chosen-select">
                            <option value="">Please Select</option>
                            <option value="1">Pending</option>
                            <option value="2">Verified</option>
                            <option value="3">Rejected</option>
                            <option value="9">Not Submitted</option>
                        </select>   
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="BankStatus">Bank Status</label>
                        <select id="BankStatus" name="BankStatus" class="form-control chosen-select">
                            <option value="">Please Select</option>
                            <option value="1">Pending</option>
                            <option value="2">Verified</option>
                            <option value="3">Rejected</option>
                            <option value="9">Not Submitted</option>
                        </select>   
                    </div>
                </div>
            </div>
            <div class="row" ng-init="initDateRangePicker()">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="ParentCategory">Registered Between</label>
                        <div id="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span>Select Date Range</span> <i class="fa fa-caret-down"></i> 
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="filter-col" for="ParentCategory">Search</label>
                        <input type="text" class="form-control" name="Keyword" placeholder="Search">
                    </div>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-secondary btn-sm" ng-click="resetUserForm()">Reset</button>
                <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" ng-disabled="listLoading" ng-click="getUsers()">Filter</button>
            </div>
        </form>
        <!-- Filter form/ -->

        <form id="add_form" name="add_form" autocomplete="off">
            <br/>
            <div class="row" ng-if='IsUsers && !chkselct'>
                <div class="col-md-8">
                    <hr/>
                    <div class="form-group">
                        <label class="control-label">Select Users</label>
                        <select data-placeholder="Select Users" id="users" name="Users[]" multiple=""
                            class="form-control chosen-select" ng-model="Users">
                            <option ng-repeat="Users in userData" value="{{Users.UserGUID}}">{{Users.FullName}}
                                ({{Users.Email ? Users.Email : Users.PhoneNumber}})</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" ng-if='IsUsers && !chkselct'>
                <div class="col-md-8">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="$('select#users option').removeAttr('selected').trigger('chosen:updated');">Clear All</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="$('select#users option').attr('selected','selected').prop('selected',true).trigger('chosen:updated');">Select All</button>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <hr/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Notification Type</label>
                        <select data-placeholder="Select Notification Types" name="NotificationType[]" class="form-control chosen-select" multiple ng-model="NotificationType"
                            ng-change="changeNotificationType(NotificationType);">
                            <option value="Email">Email</option>
                            <option value="SMS">SMS</option>
                            <option value="Website">Website/App</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Title</label>
                        <input name="Title" type="text" class="form-control" value="" maxlength="40"
                            placeholder="TITLE">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Message</label> 
                        <textarea name="Message" class="form-control" rows="10" placeholder="MESSAGE"></textarea>
                    </div>
                </div>
            </div>
        </form>

        <button type="submit" class="btn btn-success btn-sm" ng-disabled="addDataLoading"
            ng-click="addData()">Send</button>


    </div>

</div><!-- Body/ -->