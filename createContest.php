    <!--create Contest-->
    <div class="modal fade centerPopup" popup-handler id="create_contest" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
        <div class="modal-dialog custom_popup small_popup">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Make Your Own League</h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup">
                    <!-- <h3 class="text-center">Coming Soon</h3> -->
                    <form name="createContestForm" ng-submit="CreateContest(createContestForm)" novalidate="">
                        <div class="row">

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Contest Name</label>
                                    <input type="text" name="ContestName" ng-model="ContestName" placeholder="Contest Name" class="form-control" ng-required="true" >
                                    <div style="color:red" ng-show="submitted && createContestForm.ContestName.$error.required" class="form-error">
                                        *Contest name is required.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Total Winning Amount</label>
                                    <input type="text" name="WinningAmount" ng-model="ContestWinningAmount" placeholder="Winning Amount" class="form-control" ng-required="true" numbers-only>
                                    <div style="color:red" ng-show="submitted && createContestForm.ContestWinningAmount.$error.required" class="form-error">
                                        *Winning amount is required.
                                    </div>
                                </div>
                            </div>
                        
                            <!-- <div class="col-sm-4">  
                                <div class="form-group">
                                    <label class="filter-col" for="ParentCategory">Game Type</label>
                                    <select id="ContestFormat" ng-model="GameType" name="GameType" class="form-control selectpicker" ng-change="getTime(GameType)">
                                        <option value="">Please Select</option>
                                        <option value="Advance">Advance Play</option>
                                        <option value="Safe">Safe Play</option>
                                    </select>
                                    <small style="color: #fff;">League is locked {{GameTimeLive}} minutes before the actual Match time.</small>
                                    <div style="color:red" ng-show="submitted && createContestForm.GameType.$error.required" class="form-error">
                                        *Game type is required.
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-6 col-lg-4">  
                                <div class="form-group">
                                    <label class="filter-col" for="ParentCategory">Contest Format</label>
                                    <select id="ContestFormat" ng-model="ContestFormat" name="ContestFormat" class="form-control selectpicker" ng-required="true">
                                        <option value="">Please Select</option>
                                        <option value="Head to Head">Head to Head</option>
                                        <option value="League">League</option>
                                    </select>
                                    <div style="color:red" ng-show="submitted && createContestForm.ContestFormat.$error.required" class="form-error">
                                        *Contest format is required.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-lg-4" ng-show="ContestFormat!='Head to Head'">
                                <div class="form-group">
                                    <label>Contest size</label>
                                    <input type="text" name="ContestSize" ng-model="ContestSize" placeholder="Contest Size" class="form-control" ng-required="true" numbers-only>
                                    <div style="color:red" ng-show="submitted && createContestForm.ContestSize.$error.required" class="form-error">
                                        *Contest size is required.
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="ContestSize" ng-model="ContestSize" value="2" ng-if="ContestFormat=='Head to Head'" ng-focus="ContestSize=2">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Join contest with multiple teams</label>
                                    <select class="form-control selectpicker" name="EntryType" ng-model="EntryType" ng-required="true">
                                        <option value="">Please Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Multiple">Multiple</option>
                                    </select>
                                    <div style="color:red" ng-show="submitted && createContestForm.EntryType.$error.required" class="form-error">
                                        *Entry type is required.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="EntryFee">Entry Fee</label>
                                    <input placeholder="Entry Fee" class="form-control" type="text" ng-model="EntryFee" name="EntryFee" ng-required="true" readonly>
                                    <div style="color:red" ng-show="submitted && createContestForm.EntryFee.$error.required" class="form-error">
                                        *Entry fee is required.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ">
                                    <div class="customCheckbox">
                                        <input type="checkbox" ng-model="winnings" ng-click="customizeWin()" >
                                        <label>Customize winnings
                                        <small style="font-style: italic;"> (If not set, All winnings will go to participant at 1st Place!)</small></label> 
                                        </label>
                                    </div>   
                                </div>
                                
                            </div>

                            <div class="col-sm-12" ng-show="winnings">
                                <div class="form-group">
                                    <label for="EntryFee">No. of winners</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" ng-model="NoOfWinners" class="form-control" ng-change="changeWinners()" numbers-only >
                                        <label class="d-none visibility_hidden">Amount</label>
                                        <a href="javascript:void(0)" class="ml-2 btn btn-submit createContestSetBtn theme_bgclr"  ng-click="Showform()" >Set</a>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div ng-show="showField && winnings"  class="creatcontast_list mb-4">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Winning %</th>
                                                <th>Winning Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="r in choices ">
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <label>From</label><select class="form-control" ng-model="r.select_1" ng-options="number for number in r.numbers" disabled="true"></select>
                                                            </td>
                                                            <td>
                                                                <label>To</label><select class="form-control"  ng-init="DataForm.select_2 = r.numbers[0]" ng-change="changePercent($index)" ng-model="r.select_2" ng-options="number for number in r.numbers"></select>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <label>Percent</label> <input type="text" ng-model="r.percent" name="percent" class="form-control" ng-change="changePercent($index)" valid-number>
                                                </td>
                                                <td><label class="visibility_hidden">amount</label> ₹{{r.amount|number:2}} </td>
                                                <td class="d-flex"><label class="visibility_hidden">amount</label>
                                                <button class="btn btn-submit bggreen" type="button" ng-click="addField()" ng-show="$index == (choices.length-1)" > Add More Winners </button> 
                                                <button type="button" class="btn btn-submit" ng-show="$index == (choices.length-1)" ng-click="removeField($index)"  style="background-color: orange;"> Remove Winners </button></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <div style="color:red" ng-show="percent_error">*Percent field is required</div>
                                <div style="color:red" ng-show="calculation_error">*{{calculation_error_msg}}</div>
                            </div>
                        </div>

                        <div class="button_right text-center">
                            <div class="form-group">
                                <button class="btn btn-submit theme_bgclr">Create League</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--create contest-->