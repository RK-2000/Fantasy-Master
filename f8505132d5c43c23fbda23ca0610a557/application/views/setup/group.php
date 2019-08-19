<header class="panel-heading">
  <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
</header>

<div class="panel-body" ng-controller="PageController" ng-init="getList();getRolePrivileges();getGroups();">
   <!-- Left menu -->
   <?php //include("menu.php"); ?>

   <!-- Top container -->
   <div class="clearfix mt-2 mb-2">
      <span class="float-left records hidden-sm-down">
         <span ng-if="data.dataList.length" class="h5">Total records: {{TotalRecords}}</span>
      </span>

      <div class="float-right mr-2">      
         <button class="btn btn-success btn-sm ml-1 float-right" onclick='$("#add_group_modal").modal("show")'>Add Group</button>
      </div>
     
   </div>
   <!-- Top container/ -->

   <div class="appContent panel manage-grp">
     
      <div class="panel-body">

         <!-- data table -->
         <table class="table table-striped table-hover" ng-if="data.dataList.length">
            <!-- table heading -->
            <thead>
               <tr>
                  <th style="width: 50px;">Group Name</th>
                  <th style="width: 400px;">Permitted Modules</th>
                  <th style="width: 50px;">Action</th>
               </tr>
            </thead>
            <!-- table body -->
            <tbody>
               <tr ng-repeat="(key, lists) in data.dataList">
                  <td>{{lists.UserTypeName}}</td>
                  <td>
                     <span ng-repeat="m in lists.PermittedModules">
                        {{m.ModuleTitle}}
                        {{$last ? '' : ($index==lists.PermittedModules.length-2) ? ' and ' : ',&nbsp;'}}
                     </span>
                  </td>
                  <td class="text-center">
                     <div class="dropdown" ng-if="lists.IsAdmin!='Yes'">
                        <button class="btn btn-secondary  btn-sm action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-if="data.UserGUID!=row.UserGUID">&#8230;</button>
                        <div class="dropdown-menu dropdown-menu-left">
                           <a class="dropdown-item" href="" ng-click="loadFormEdit(key,lists.UserTypeGUID);">Edit</a>
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
  
   </div>

   <!-- Edit permission-modal -->
   <div class="modal fade" id="edit_permission_modal">
      <div class="modal-dialog modal-md" role="document">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h3 class="modal-title h5">{{formData.UserTypeName}}</h3>        
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body clearfix">
               <form id="editForm" name="editForm" novalidate>
                  <div class="modal-body">
                     <div class="form-area">
                        <div class="form-group">
                           <h4 class="text-center">Set Permission</h4>
                        </div>
                        <hr>
                        <div class="row">
                           <div class="col-md-6" ng-repeat="List in formData.PermittedModules">
                              <div class="form-group">
                                 <div class="customCheckbox checkbox">
                                    <input name="ModuleName[]" value="{{List.ModuleName}}" class="coupon_question" ng-checked="List.Permission=='Yes'"  type="checkbox" id="{{List.ModuleTitle}}">
                                    <label for="{{List.ModuleTitle}}">{{List.ModuleTitle}}</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="modal-footer">
                     <input type="hidden" name="UserTypeGUID" value="{{formData.UserTypeGUID}}" >   
                     <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                     <button type="submit" class="btn btn-success btn-sm" ng-disabled="editDataLoading" ng-click="editData();">Save</button>
                  </div>

               </form>
            </div>
            <!-- category footer -->
         </div>
      </div>
   </div>

   <!-- Add-Group-Modal -->
   <div class="modal fade" id="add_group_modal">
      <div class="modal-dialog modal-md" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h3 class="modal-title h5">Add Group</h3>      
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
               <div class="form-area">
                  <form id="add_form" name="add_form" autocomplete="off" >
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="control-label">Group Name</label>
                              <input name="GroupName" type="text" class="form-control" value="" placeholder="Group Name" maxlength="20">
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
               <button type="submit" class="btn btn-success btn-sm" ng-disabled="addDataLoading" ng-click="addGroupData()">Save</button>
            </div>
         </div>
      </div>
   </div>

</div><!-- Body/ -->

