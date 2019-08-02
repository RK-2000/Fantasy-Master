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

        <form id="add_form" name="add_form" autocomplete="off">

            <div class="row col-md-8">
                <div class="checkbox">
                    <input name="AllUsers" type="checkbox" class="form-check-input" checked ng-model="chkselct"
                            ng-init="chkselct=true" id="users">
                    <label for="users">
                        All Users
                    </label>
                </div>
            </div>

            <div class="row" ng-hide='chkselct'>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Select Users</label>
                        <select data-placeholder="Select Users" ng-init="getUsers();" id="breeds" name="Users[]" multiple=""
                            class="form-control chosen-select" ng-model="Users">
                            <option>Select Users</option>
                            <option ng-repeat="Users in userData" value="{{Users.UserGUID}}">{{Users.FullName}}
                                ({{Users.Email}})</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Notification Type</label>
                        <select name="NotificationType" class="form-control" ng-model="NotificationType"
                            ng-change="changeNotificationType(NotificationType);">
                            <option value="Email" ng-selected="NotificationType=='Email'">Email</option>
                            <option value="WebsiteNotification" ng-selected="NotificationType=='WebsiteNotification'">
                                Website Notification</option>
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
                    <div class="form-group" ng-if="NotificationType=='Email'">
                        <label class="control-label">Message</label>
                        <textarea name="EmailMessage" id="editor" class="form-control" rows="10"
                            placeholder="MESSAGE"></textarea>
                    </div>
                    <div class="form-group" ng-if="NotificationType=='WebsiteNotification'">
                        <label class="control-label">Message</label> 
                        <textarea name="WebsiteMessage" class="form-control" rows="10" placeholder="MESSAGE"></textarea>
                    </div>
                </div>
            </div>

            <!-- hidden parameters -->
            <input type="hidden" class="MediaGUIDs" id="MediaGUIDs" name="MediaGUIDs" value=""> <!-- for banner -->
            <!-- hidden parameters /-->
        </form>



        <button type="submit" class="btn btn-success btn-sm" ng-disabled="addDataLoading"
            ng-click="addData()">Send</button>


    </div>

</div><!-- Body/ -->