<div class="mainContainer" ng-controller="PageController" ng-init="getList();getRolePrivileges();getGroups();">


   <!-- Left menu -->
   <?php //include("menu.php"); ?>


   <div class="appContent panel manage-grp">
      <div class="panel-heading">
         <h1 class="h4"><?php echo $this->ModuleData['ModuleTitle'];?></h1>
      </div>
      <div class="panel-body">
         <div class="row">
            <div class="col-sm-9">
               <div class="total-record">
                  <span>Total Records :{{TotalRecords}}</span>
               </div>
            </div>
            <div class="col-sm-2 offset-sm-1">
               <div class="form-group ">
                  <!-- <a class="btn btn-primary  rounded mb-2" ng-click="loadFormAdd()" >Add Group</a> -->
                  <!-- <a class="btn btn-primary  rounded mb-2" href="javascript:void(0);" >Add Group</a> -->
               </div>
            </div>
         </div>

         <table class="table">
            <thead>
               <tr>
                  <th>Group Name</th>
                  <th>Permitted Modules</th>
                  <th>Employee </th>
                  <th colspan="2"></th>
               </tr>
            </thead>
            <tbody>
               <tr ng-repeat="(key, lists) in data.dataList">
                  <td>{{lists.UserTypeName}}</td>
                  <td>
                     <span ng-repeat="m in lists.PermittedModules">
                        {{m.ModuleTitle}}
                        {{$last ? '' : ($index==lists.PermittedModules.length-2) ? ' and ' : ',&nbsp;'}}
                     </span>
                  </td>
                  <td>{{lists.UserCount}}</td>
                  <td>
                     <!-- <a href="javascript:void(0)" ng-click="loadFormEdit(key,lists.UserTypeID)" class="editBtn"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a> -->
                     <a href="javascript:void(0)" ng-click="loadFormEdit(key,lists.UserTypeGUID);" data-toggle="modal"  class="editBtn"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
                     <!-- <a href="javascript:void(0)" ng-click="deleteData(lists.UserTypeID)" class="deleteBtn"><i class="fa fa-trash" aria-hidden="true"></i> </a> -->
                  </td>
               </tr>
            </tbody>
         </table>

      </div>

      <div class="appFooter">
         <b>Powered by</b> <img src="asset/images/logo.png" alt="">
      </div>

   <!-- <div class="appContent">
      <div class="contentWrapper">
         <div class="contentBody setup_new engage">
            <div class="reviewIdea">
                  
               <div class="employeeSelect">
                 
               </div>
            </div>
         </div>
      </div>
      <div class="appFooter">
      <b>Powered by</b> <img src="asset/admin/images/logo.png" alt="">
      </div>
   </div> -->
   <!--appContent-->
</div>
<!--plus-category-modal-->
<div class="modal" id="addgroup">
   <div class="custompopup modal-md  modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">{{formData.UserTypeName}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body clearfix">
            <form id="editForm" name="editForm" novalidate>
               <div class="card">
                  <!-- <div class="card-header">
                     <input type="text" placeholder="Add user type name." name="UserTypeName" ng-model="addGroupFields.UserTypeName" class="form-control" ng-required="true">
                  </div> -->
                  <div class="card-title">
                     <h4>Set Permission</h4>
                  </div>
                  <div class="card-body">
                     <ul>
                        <li ng-repeat="List in formData.PermittedModules">
                           <div class="customCheckbox">
                              <!-- <input class="coupon_question" ng-model="List.status" ng-change="change(List, List.status)"  type="checkbox"> -->
                              <input name="ModuleName[]" value="{{List.ModuleName}}" class="coupon_question" ng-checked="List.Permission=='Yes'"  type="checkbox">
                              <label>{{List.ModuleTitle}}</label>
                              
                           </div>
                        </li>
                     </ul>
                  </div>
                  <div class="modal-footer pull-right">
                     <div class="form-group">
                        <input type="hidden" name="UserTypeGUID" value="{{formData.UserTypeGUID}}" >
                        <button class="btn btn-default"  data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" ng-disabled="editDataLoading" ng-click="editData();">Save</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <!-- category footer -->
      </div>
   </div>
</div>
<script>
   function valueChanged(){
      if($('.coupon_question').is(":checked"))   
         $("#addreview").modal();
      else
         $("#addreview").hide();
   }
</script>
