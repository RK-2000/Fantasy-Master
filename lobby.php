<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="lobbyController" ng-init="Series();" ng-cloak >
    <div class="comonBg bglayer">
        <div class="wrraper">
            <div class="container-fluid">
                <div class="mt-3 select_item">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 pr-lg-0">
                                    <p> Select Series </p>
                                    <div class="select_option_item">
                                        <select  name="series" id="series" ng-model="selected_series" ng-change="changeSeries(selected_series)">
                                            <option value="">  Please Select </option>
                                            <option ng-repeat="series in seriesList.Records" value="{{series.SeriesGUID}}"> {{series.SeriesName}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 pr-lg-0">
                                    <p> Contest Type </p>
                                    <div class="select_option_item">
                                        <select name="contest_type" ng-change="filter()" ng-model="contest_type" class="selectpicker">
                                            <option value=""> Please Select </option>
                                            <option value="Hot">Hot</option>
                                            <option value="Champion">Champion</option>
                                            <option value="Practice">Practice</option>
                                            <option value="More">More</option>
                                            <option value="Mega">Mega</option>
                                            <option value="Winner Takes All">Winner Takes All</option>
                                            <option value="Only For Beginners">Only For Beginners</option>
                                            <option value="Normal">Private Contest</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 pr-lg-0">
                                    <p> No. Of Participants Range </p>
                                    <div class="select_option_item">
                                        <select class="selectpicker" name="contestSizeRange" ng-change="filter()" ng-model="contestSizeRange">
                                            <option value="">Please Select </option>
                                            <option value="0-50">0 - 50</option>
                                            <option value="50-200">50 - 200</option>
                                            <option value="200-500">200 - 500</option>
                                            <option value="500"> Greater than 500</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 pr-lg-0">
                                    <p> Entry Fee Range</p>
                                    <div class="select_option_item">
                                        <select class="selectpicker" name="entry_fee_range" ng-change="filter()" ng-model="entry_fee_range">
                                            <option value="">Please Select </option>
                                            <option value="0-50">0 - 50</option>
                                            <option value="50-200">50 - 200</option>
                                            <option value="200-500">200 - 500</option>
                                            <option value="500"> Greater than 500</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 text-center">
                            <a class="btn btnClone theme_bgclr mt-4" href="javascript:void(0)" ng-click="clear_filter()"> Clear All  </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="matchcenterDetail">
                <div class="container-fluid">
                    <div class="">
                        <div class="creatTeamTop">
                            <div class="mt-4 silder_show">
                                <div class="wrapper">
                                    <div class="slider lobby_page_slider" slick-custom-carousel ng-if="silder_visible" >
                                        <div class="" ng-repeat="matches in MatchesList.Records" ng-if="MatchesList.Records.length > 0"  >
                                            <a href="javascript:void(0);" ng-click="selectMatch(matches.MatchGUID)">
                                                <div class="slider_item {{MatchGUID == matches.MatchGUID ? 'active' : '' }}">
                                                    <h4> {{matches.SeriesName}} </h4>
                                                    <div class="d_flex">
                                                        <figure class="mb-0"><img ng-src="{{matches.TeamFlagLocal}}" alt="{{matches.TeamNameShortLocal}}"class="img-fluid" width="60" /></figure>
                                                        <div class="timer">
                                                                    <h4 class="theme_txtclr"><small>{{matches.TeamNameShortLocal}}</small>  VS <small>{{matches.TeamNameShortVisitor}}</small></h4>
                                                            <p id="demo" timer-text="{{matches.MatchStartDateTime}}" timer-data="{{matches.MatchStartDateTime}}" match-status="{{matches.Status}}" ng-bind-html="clock | trustAsHtml" class="ng-binding"></p>
                                                        </div>
                                                        <figure class="mb-0"> <img ng-src="{{matches.TeamFlagVisitor}}" alt="{{matches.TeamNameShortVisitor}}" class="img-fluid" width="60"  /> </figure>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <section class="row contest_sec  align-items-center">
                                <div class="col-md-4">
                                    <div class="timer">
                                        <p> Next Contest Starts In  </p> 
                                        <p id="demo" timer-text="{{MatchesDetail.MatchStartDateTime}}" timer-data="{{MatchesDetail.MatchStartDateTime}}" match-status="{{MatchesDetail.Status}}" ng-bind-html="clock | trustAsHtml" class="ng-binding"><span>213 <strong>DAY</strong></span><span>22 <strong>HRS</strong></span><span>32 <strong>MIN</strong></span><span>04 <strong>SEC</strong></span></p>
                                    </div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <a  class="btn btnClone theme_bgclr bdr_wht mr-3" href="javascript:void(0)" ng-click="openPopup('create_contest');clearPopup();" > Create a contest </a>
                                    <a class="btn btnClone theme_bgclr bdr_wht" href="javascript:void(0)" ng-click="createTeam(MatchGUID, '')" >Create Team</a>
                                    <!-- <a class="btn btnClone theme_bgclr bdr_wht mr-2" href="referAndEarn">  Invite Your Friends </a> -->
                                </div>
                                <div class="col-md-3 text-right">
                                    <a class="btn btnClone theme_bgclr bdr_wht" ng-if="MatchesDetail.Status == 'Pending'"  href="javascript:void(0)" ng-click="openPopup('joinPrivateContestPopup')">Got A League Code? </a>
                                </div>
                            </section>
                            <hr/>
                        </div>
                    </div>
                </div>
                <!--cretteam-->
                <div class="container-fluid lobby_res">
                    <div class="row">
                        <div class="col-md-9"> 
                            <ul class="nav nav-tabs">
                                <li class="nav-item"  >
                                    <a class="nav-link {{activeTab=='normal' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="data.pageNo = 1; gotoTab('normal');" role="tab" aria-controls="nav-home" aria-selected="true">ALL </a>
                                </li>
                                <li class="nav-item"  >
                                    <a class="nav-link {{activeTab=='joined' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="data.pageNo = 1;gotoTab('joined');" role="tab" aria-controls="nav-profile" aria-selected="false"> My Contests </a>
                                </li>
                                <li class="nav-item"  >
                                    <a class="nav-link {{activeTab=='team' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="data.pageNo = 1; gotoTab('team');" role="tab" aria-controls="nav-profile" aria-selected="false"> My Team</a>
                                </li>
                            </ul>
                        </div>  

                         <div class="text-right search-form col-md-3 mt-2">
                            <input class="form-control" type="search" placeholder="Search By Name" ng-model="search_contest" aria-label="Search" ng-keypress="searchContest(search_contest)">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" ng-click="searchContest(search_contest)"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane {{activeTab=='normal' ? 'active' : '' }}" id="normal" >
                            <div class="sortHead">
                                <ul class="dFlex">
                                    <li>
                                        <span class="contest_btn_b ">B</span> Bonus Contribution
                                    </li>
                                    <li>
                                        <span class="contest_btn_c">C</span> Confirm League
                                    </li>
                                    <li>
                                        <span>M</span> Multi Entry
                                    </li>
                                </ul>
                                <div class="matchtypeHead ng-scope">
                                    <ul>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestName')">Contest</a><span class="sortorder" ng-show="propertyName === 'ContestName'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestType')">Contest Type</a><span class="sortorder" ng-show="propertyName === 'ContestType'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('EntryFee')">Entry Fee</a><span class="sortorder" ng-show="propertyName === 'EntryFee'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestSize')">Entries</a><span class="sortorder" ng-show="propertyName === 'ContestSize'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('WinningAmount')">Payout</a><span class="sortorder" ng-show="propertyName === 'WinningAmount'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('MatchStartDateTime')">Start</a><span class="sortorder" ng-show="propertyName === 'MatchStartDateTime'" ng-class="{reverse: reverse}"></span></li>
                                        <li>Action</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="matchtypeBody ng-scope dfs_custom_scroll" scrolly ng-show="ContestsTotalCount > 0"> 
                                <ul ng-repeat="Contest in Contests | orderBy:propertyName:reverse"  ng-if="Contest.Status == 'Pending'">
                                    <li>
                                        <a  href="javascript:void(0)" ng-if="Contest.IsJoined == 'No'" >{{Contest.ContestName}}</a>
                                        <a  href="league?MatchGUID={{MatchGUID}}&League={{Contest.ContestGUID}}&Source=ViewLeague" ng-if="Contest.IsJoined == 'Yes'">{{Contest.ContestName}}</a>
                                    </li>
                                    <li> {{Contest.ContestType}} <br/>
                                        <span ng-if="Contest.IsConfirm == 'Yes'" data-toggle="tooltip" title="This league is confirmed. It will go on irrespective of number of entries." data-placement="bottom" class="contest_btn_c" >C</span>
                                        <span ng-if="Contest.EntryType == 'Multiple'" data-toggle="tooltip" title="This league is multiple. So a user can join with multiple teams." data-placement="bottom" class="contest_btn_m" >M</span>
                                        <span ng-if="Contest.CashBonusContribution > '0.00'" data-toggle="tooltip" title="This league is bonus contribution contest. It will take some partial amount from your cash bonus." data-placement="bottom" class="contest_btn_b">B</span> 
                                    </li>

                                    <li>{{Contest.IsPaid=='Yes' ? '₹ '+Contest.EntryFee : 'FREE' }}</br>
                                        <p style="font-size: 10px;" ng-if="Contest.CashBonusContribution > '0.00' && Contest.IsPaid=='Yes'">Usable Cash Bonus: {{Contest.CashBonusContribution}}% of the Entry fee</p>
                                    </li>
                                    <li>{{Contest.TotalJoined}} <small style="margin-left: 25px;"> Out Of </small><p class="pull-right">{{Contest.ContestSize}}</p>
                                        <div class="progress">
                                            <div class="progress-bar" style="width:{{Contest.joinedpercent}}%;"></div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="payoutParBox">
                                            <a href="javascript:void(0)" ng-click="showWinningPayout(Contest.CustomizeWinning)"><cite class="fa fa-eye" aria-hidden="true"></cite></a>
                                        </div>
                                        <i>₹</i> {{Contest.WinningAmount}}
                                    </li>
                                    <li>{{Contest.MatchStartDateTime | myDateFormat}}</li>
                                    <li class="bat text-right">
                                        <button class="btn btn-submit bggreen mr-2" ng-click="SelectTeamToJoinContest(Contest, 'Join')" ng-show="Contest.IsJoined == 'No' && Contest.Status == 'Pending'">Join</button> 

                                        <a class="btn btn-submit light_bg mr-2" href="javascript:void(0)" ng-show="Contest.IsJoined == 'Yes' && Contest.Status == 'Pending' && Contest.EntryType == 'Multiple'" ng-click="SelectTeamToJoinContest(Contest, 'Rejoin')" >Rejoin</a>
                                        
                                        <a class="btn btn-submit mr-2 light-bluebg" href="league?MatchGUID={{MatchGUID}}&League={{Contest.ContestGUID}}" ng-show="Contest.IsJoined == 'Yes' && Contest.Status == 'Pending' && Contest.EntryType == 'Single'">Joined</a>

                                        <a class="btn btn-submit mr-2 light-bluebg" href="league?MatchGUID={{MatchGUID}}&League={{Contest.ContestGUID}}" ng-show="Contest.IsJoined == 'Yes' && Contest.Status != 'Pending'">Joined</a>

                                        <a class="btn btn-submit mr-2" href="javascript:void(0)" ng-show="Contest.IsJoined == 'No' && Contest.Status != 'Pending'">Not Joined</a>
                                        
                                        <a class="btn btn-submit theme_bgclr mr-2" href="javascript:void(0)" ng-click="openinvitationModal(Contest.UserInvitationCode)" > Invite Your Friends </a>
                                        
                                    </li>
                                </ul>
                            </div>
                            <div class="matchtypeBody border-0" ng-show="ContestsTotalCount == 0" style="">
                                <div class="alertBoxParents text-center">
                                    <div class="alertBox">
                                        <p>No contests found!</p>
                                    </div>  
                                </div>
                            </div>

                        </div>


                        <div class="tab-pane {{activeTab=='joined' ? 'active' : '' }}" id="joined"  >
                            <div class="sortHead">  
                                <div class="matchtypeHead" >
                                    <ul>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestName')">Contest</a><span class="sortorder" ng-show="propertyName === 'ContestName'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestType')">Contest Type</a><span class="sortorder" ng-show="propertyName === 'ContestType'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('EntryFee')">Entry Fee</a><span class="sortorder" ng-show="propertyName === 'EntryFee'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('ContestSize')">Entries</a><span class="sortorder" ng-show="propertyName === 'ContestSize'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('WinningAmount')">Payout</a><span class="sortorder" ng-show="propertyName === 'WinningAmount'" ng-class="{reverse: reverse}"></span></li>
                                        <li><a href="javascript:void(0)" class="text-white" ng-click="sortBy('MatchStartDateTime')">Start</a><span class="sortorder" ng-show="propertyName === 'MatchStartDateTime'" ng-class="{reverse: reverse}"></span></li>
                                        <li class="text-center">Action</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="matchtypeBody dfs_custom_scroll" scrolly ng-if="UserJoinedContestTotalCount > 0">
                                <ul ng-repeat="joinedContests in data.dataList | orderBy:propertyName:reverse">
                                    <li><a  href="league?MatchGUID={{MatchGUID}}&League={{joinedContests.ContestGUID}}&Source=ViewLeague">{{joinedContests.ContestName}}</a></li>
                                    <li>{{(joinedContests.ContestType !='Normal')?joinedContests.ContestType:'Private Contest'}} <br/> 
                                        <span ng-if="joinedContests.IsConfirm == 'Yes'" data-toggle="tooltip" title="This league is confirmed. It will go on irrespective of number of entries." data-placement="bottom" class="contest_btn_c" >C</span><span ng-if="joinedContests.EntryType == 'Multiple'" data-toggle="tooltip" title="This league is multiple. So a user can join with multiple teams." data-placement="bottom" class="contest_btn_m">M</span><span ng-if="joinedContests.CashBonusContribution > '0.00'" data-toggle="tooltip" title="This league is bonus contribution contest. It will take some partial amount from your cash bonus." data-placement="bottom" class="contest_btn_b">B</span> 
                                    </li>
                                    <li>{{joinedContests.IsPaid=='Yes' ? '₹ '+joinedContests.EntryFee : 'FREE' }}<br>
                                        <p style="font-size: 10px;" ng-if="joinedContests.CashBonusContribution > '0.00' && joinedContests.IsPaid=='Yes'">Usable Cash Bonus: {{joinedContests.CashBonusContribution}}% of the Entry fee</p>
                                    </li>
                                    <li>{{joinedContests.TotalJoined}} <p class="pull-right">{{joinedContests.ContestSize}}</p>
                                        <div class="progress">
                                            <div class="progress-bar" style="width:{{joinedContests.joinedpercent}}%;"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="payoutParBox">
                                            <a href="javascript:void(0)" ng-click="showWinningPayout(joinedContests.CustomizeWinning)"><cite class="fa fa-eye" aria-hidden="true"></cite></a>
                                        </div>
                                        <i>₹</i>{{joinedContests.WinningAmount}}
                                    </li>
                                    <li>{{joinedContests.MatchStartDateTime | myDateFormat}}</li>
                                    <li class="bat text-right">
                                        <a class="btn btn-submit light_bg" href="javascript:void(0)" ng-show="joinedContests.Status == 'Pending' && joinedContests.EntryType == 'Multiple'" ng-click="SelectTeamToJoinContest(joinedContests, 'Rejoin')" >Rejoin</a>

                                        <a class="btn btn-submit light-bluebg" href="league?MatchGUID={{MatchGUID}}&League={{joinedContests.ContestGUID}}" ng-show="joinedContests.Status == 'Pending' && joinedContests.EntryType == 'Single'">Joined</a>

                                        <a class="btn btn-submit light-bluebg" href="league?MatchGUID={{MatchGUID}}&League={{joinedContests.ContestGUID}}" ng-show=" joinedContests.Status != 'Pending'"> Joined </a>

                                        <a class="btn btn-submit theme_bgclr mr-2" href="javascript:void(0)" ng-click="openinvitationModal(joinedContests.UserInvitationCode)" > Invite Your Friends </a>

                                    </li>
                                </ul>
                            </div>
                            <div class="matchtypeBody border-0" ng-if="UserJoinedContestTotalCount == 0">
                                <div class="alertBoxParents text-center">
                                    <div class="alertBox">
                                        <p>No Leagues joined yet!</p>
                                    </div>  
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane {{activeTab=='team' ? 'active' : '' }}" id="team">
                            <div class="matchtypeHead">
                                <ul >
                                    <li><a href="javascript:void(0)" class="text-white" ng-click="sortByTeam('UserTeamName')">Team Name</a><span class="sortorder" ng-show="propertyNameByTeam === 'UserTeamName'" ng-class="{reverse: reverse1}"></span> </li>
                   <!--                  <li><a href="javascript:void(0)" class="text-white" ng-click="sortByTeam('UserTeamType')">Type</a><span class="sortorder" ng-show="propertyNameByTeam === 'UserTeamType'" ng-class="{reverse: reverse1}"></span>  </li> -->
                                    <li><a href="javascript:void(0)" class="text-white" ng-click="sortByTeam('Captain')">Captain</a><span class="sortorder" ng-show="propertyNameByTeam === 'Captain'" ng-class="{reverse: reverse1}"></span>  </li>
                                    <li><a href="javascript:void(0)" class="text-white" ng-click="sortByTeam('ViceCaptain')">Vice Captain</a><span class="sortorder" ng-show="propertyNameByTeam === 'ViceCaptain'" ng-class="{reverse: reverse1}"></span>  </li> 
                                    <li class="text-center"> <a href="javascript:void(0)" class="text-white" ng-click="sortByTeam('TotalJoinedContests')">Join Contest</a><span class="sortorder" ng-show="propertyNameByTeam === 'TotalJoinedContests'" ng-class="{reverse: reverse1}"></span> </li>
                                    <li class="text-center">Actions</li>
                                </ul>
                            </div>
                            <div class="matchtypeBody custom_myteam" ng-if="UserTeamsTotalCount > 0" >
                                <ul ng-repeat="userTeams in userTeamList | orderBy:propertyNameByTeam:reverse1" >
                                    <li>{{userTeams.UserTeamName}}</li>
                                    <!-- <li>{{userTeams.UserTeamType=='InPlay' ? 'In Play' : 'Normal' }}</li> -->
                                    <li>{{userTeams.Captain}}</li>
                                    <li>{{userTeams.ViceCaptain}}</li>
                                    <li class="text-center">{{userTeams.TotalJoinedContests}}</li>
                                    <li><button class="btn btn-submit theme_bgclr" ng-click="ViewTeamOnGround(userTeams.UserTeamGUID)">View</button>
                                        <a class="btn btn-submit theme_bgclr" href="createTeam?Operation=edit&MatchGUID={{MatchGUID}}&UserTeamGUID={{userTeams.UserTeamGUID}}">Edit</a> 
                                        <a class="btn btn-submit theme_bgclr" href="createTeam?MatchGUID={{MatchGUID}}&UserTeamGUID={{userTeams.UserTeamGUID}}">Copy</a> </li>
                                </ul>
                            </div>
                            <div class="matchtypeBody border-0" ng-if="UserTeamsTotalCount == 0" >
                                <div class="alertBoxParents text-center">
                                    <div class="alertBox">
                                        <p>No teams created yet! </p>
                                    </div>  
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--cretteam-->
                </div>
            </div>
            <!-- create contest directive starts-->
            <create-contest></create-contest>
            <!-- create contest directive ends-->

            <!--join private contest -->
            <div class="modal fade centerPopup" popup-handler id="joinPrivateContestPopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
                <div class="modal-dialog custom_popup small_popup"> 
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Join League</h4>
                        </div>
                        <div class="modal-body clearfix comon_body ammount_popup">
                            <form name="privateContestForm" ng-submit="checkContestCode(privateContestForm, UserInvitationCode)" novalidate="">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <input type="text" name="UserInvitationCode" placeholder="Contest Invite Code" class="form-control" ng-model="UserInvitationCode" ng-required="true">
                                            <div style="color:red" ng-show="codeSubmitted && privateContestForm.UserInvitationCode.$error.required" class="form-error">
                                                *Contest code is required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <button class="btn btn-submit theme_bgclr"> Join </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Ask friends to share league code with you to directly enter into private league.</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
            <!--create contest-->

            <!--User team list to join contest -->
            <div class="modal fade centerPopup" popup-handler id="joinLeaguePopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
                <div class="modal-dialog custom_popup small_popup"> 
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Join League</h4>
                        </div>
                        <div class="modal-body clearfix comon_body ammount_popup">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th> Current Balance </th>
                                    <th> Joining Amount </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><p class="ng-binding"> {{moneyFormat(profileDetails.TotalCash)}}</p></td>
                                    <td><p class="ng-binding"> {{moneyFormat(ContestInfo.EntryFee)}}</p></td>
                                </tr>
                                </tbody>
                            </table>

                            <form novalidate="" name="joinContestForm" ng-submit="JoinContest(joinContestForm)">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <strong> Select Team </strong>
                                            <select class="form-control selectpickerTeam" ng-model="join.UserTeamGUID" name="UserTeamGUID" ng-required="true">
                                                <option value="">Please Select</option>
                                                <option value="{{teams.UserTeamGUID}}" ng-repeat="teams in userTeamList">{{teams.UserTeamName}}</option>
                                            </select>
                                            <div style="color:red" ng-show="joinSubmitted && joinContestForm.UserTeamGUID.$error.required" class="form-error">
                                                *Please select team to join contest.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10 offset-md-1 text-center py-2 flex-spacearround">
                                        <button class="btn btn-submit theme_bgclr"> Join </button>
                                        <h6 class="text-black"> OR </h6>
                                        <a class="btn btn-submit theme_bgclr" href="javascript:void(0)" ng-click="createTeam(MatchGUID, ContestGUID)">Create Team</a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 

            <!--User team list to join contest-->
            <!-- Show Team popup -->

            <div id="mySidenav" class="sidenav ">
                <div class="previewBg">
                    <a href="javascript:void(0)" class="closebtn" ng-click="closeNav()">×</a>
                    <div class="teamPar">
                        <div class="teamPreviewRow wicketkeeperPosition">
                            <span>Wicket-Keeper</span>
                            <ul>
                                <li ng-if="teamStructure['WicketKeeper'].player.length > 0" ng-repeat=" WicketKeeper in teamStructure['WicketKeeper'].player" >
                                    <div class="captaine captain_css" ng-if="WicketKeeper.PlayerPosition == 'Captain'">C</div>
                                    <div class="captaine vicecaptain_css" ng-if="WicketKeeper.PlayerPosition == 'ViceCaptain'">VC</div>
                                    <div class="playerImg point_bg">
                                        <img ng-src="{{WicketKeeper.PlayerPic}}" alt="player">
                                    </div>
                                    <div class="playerName {{(WicketKeeper.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(WicketKeeper.PlayerName) | limitTo: 10 }}{{getPlayerShortName(WicketKeeper.PlayerName).length > 10 ? '...' : ''}} </div>
                                    <span >{{WicketKeeper.Points}} </span>
                                </li>
                            </ul>
                        </div> 
                        <div class="teamPreviewRow batsmanPosition">
                            <span> Batsmen </span>
                            <ul>
                                <li ng-if="teamStructure['Batsman'].player.length > 0" ng-repeat=" Batsman in teamStructure['Batsman'].player" >
                                    <div class="captaine captain_css" ng-if="Batsman.PlayerPosition == 'Captain'">C</div>
                                    <div class="captaine vicecaptain_css" ng-if="Batsman.PlayerPosition == 'ViceCaptain'">VC</div>
                                    <div class="playerImg point_bg">
                                        <img ng-src="{{Batsman.PlayerPic}}" alt="player">
                                    </div>
                                    <div class="playerName {{(Batsman.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(Batsman.PlayerName) | limitTo: 10 }}{{getPlayerShortName(Batsman.PlayerName).length > 10 ? '...' : ''}} </div>
                                    <span >{{Batsman.Points}} </span>
                                </li>
                            </ul>
                        </div> 
                        <div class="teamPreviewRow bowlerPosition">
                            <span> Bowlers  </span>
                            <ul>
                                <li ng-if="teamStructure['Bowler'].player.length > 0" ng-repeat=" Bowler in teamStructure['Bowler'].player" >
                                    <div class="captaine captain_css" ng-if="Bowler.PlayerPosition == 'Captain'">C</div>
                                    <div class="captaine vicecaptain_css" ng-if="Bowler.PlayerPosition == 'ViceCaptain'">VC</div>
                                    <div class="playerImg point_bg">
                                        <img ng-src="{{Bowler.PlayerPic}}" alt="player">
                                    </div>
                                    <div class="playerName {{(Bowler.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(Bowler.PlayerName) | limitTo: 10 }}{{getPlayerShortName(Bowler.PlayerName).length > 10 ? '...' : ''}} </div>
                                    <span >{{Bowler.Points}} </span>
                                </li>
                            </ul>
                        </div> 
                        <div class="teamPreviewRow allrounderPosition">
                            <span> All-Rounders </span>
                            <ul>
                                <li ng-if="teamStructure['AllRounder'].player.length > 0" ng-repeat=" AllRounder in teamStructure['AllRounder'].player" >
                                    <div class="captaine captain_css" ng-if="AllRounder.PlayerPosition == 'Captain'">C</div>
                                    <div class="captaine vicecaptain_css" ng-if="AllRounder.PlayerPosition == 'ViceCaptain'">VC</div>
                                    <div class="playerImg point_bg">
                                        <img ng-src="{{AllRounder.PlayerPic}}" alt="player">
                                    </div>
                                    <div class="playerName {{(AllRounder.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(AllRounder.PlayerName) | limitTo: 10 }}{{getPlayerShortName(AllRounder.PlayerName).length > 10 ? '...' : ''}} </div>
                                    <span >{{AllRounder.Points}} </span>
                                </li>
                            </ul>
                        </div> 
                    </div>
                </div>
            </div> 
            
            <!-- Show Payouts break ups -->
        
            <div class="modal fade centerPopup " popup-handler id="PayoutBreakUp" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
                <div class="modal-dialog custom_popup small_popup"> 

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title"><b>Payout Breakup</b></h4>
                        </div>
                        <div class="modal-body clearfix comon_body ammount_popup">
                            <div class="row">
                                <div class="payoutPar text-center mCustomScrollbar" style="width: 100%">
                                    <ul>
                                        <li>
                                        <dd><b>Rank</b></dd>
                                        <dd><b>Winning Amount</b></dd>
                                        </li>

                                        <li ng-repeat="winnings in CustomizeWinning" >
                                        <dd>{{winnings.From}} - {{winnings.To}}</dd>
                                        <dd>₹ {{winnings.WinningAmount}}</dd>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Show private contest invitation modal-->
    <div class="modal fade centerPopup" popup-handler id="invitationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
        <div class="modal-dialog custom_popup small_popup"> 

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"> Your Invite Code </h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup">
                    <div class="row">
                        <input type="text" class="invite_code" id="invite_code" value="{{InviteCode}}">
                        <i class="fa fa-clipboard invite_code_copy" ng-click="copyText()" aria-hidden="true"></i>
                    </div>

                    <div class="referRightBox">
                        <div class="viaTabPar">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link {{activeInviteTab=='viaSms' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="inviteInviteTab('viaSms')">
                                        Share Via SMS  </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{activeInviteTab=='viaMail' ? 'active' : '' }}"  data-toggle="tab" href="javascript:void(0)" ng-click="inviteInviteTab('viaMail')">Share Via Mail</a>
                                </li>
                            </ul>

                            <div class="tab-content ">
                                <div id="viaSms" class="tab-pane fade {{activeInviteTab=='viaSms' ? 'active show' : '' }} ">
                                    <form name="mobileForm" ng-submit="InviteFriend(mobileForm, 'Phone',InviteCode)" novalidate="">
                                        <div class="form-group">
                                            <input type="text" ng-model="inviteField.PhoneNumber" name="PhoneNumber" class="form-control" placeholder="Enter Mobile Number" ng-required="true" numbers-only ng-pattern="/^\+?\d{10}$/">
                                            <div style="color:red" ng-show="inviteSubmitted && mobileForm.PhoneNumber.$error.required" class="form-error">
                                                * Mobile Number is required.
                                            </div>
                                            <div style="color:red" ng-show="mobileForm.PhoneNumber.$error.pattern" class="form-error">
                                                *Please enter valid Mobile Number.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-submit btn-block text-uppercase theme_bgclr">Invite</button>
                                        </div>   
                                    </form>
                                </div>

                                <div id="viaMail" class="tab-pane fade {{activeInviteTab=='viaMail' ? 'active show' : '' }}">
                                    <form name="emailForm" ng-submit="InviteFriend(emailForm, 'Email',InviteCode)" novalidate="">
                                        <div class="form-group">
                                            <input type="email" class="form-control" ng-model="inviteField.Email" name="Email" placeholder="Enter Email Address" ng-required="true" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/">
                                            <div style="color:red" ng-show="inviteSubmitted && emailForm.Email.$error.required" class="form-error">
                                                * Email is required.
                                            </div>
                                            <div style="color:red" ng-show="emailForm.Email.$error.pattern" class="form-error">
                                                *Please enter valid email.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-submit btn-block text-uppercase theme_bgclr">Invite</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="listPar">
                                <p>Share with Friends via Social</p>
                                <ul>
                                    <li>
                                        <a class="fb" href="javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a class="tw" href="javascript:void(0)" data-js="twitter-share"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a class="inst" href="https://www.instagram.com/accounts/login" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a class="wt" href="https://api.whatsapp.com/send?text=Put your cricket knowledge to test and play with me on FSL11. Click https://fsl11.com/download-app to download the FSL11 app or login on portal and Use contest code: {{InviteCode}} to join my contest." target="_blank"><i class="fa fa-whatsapp"></i></a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>   

<!--Main container sec end-->
<?php include('innerFooter.php'); ?>    
<script type="text/javascript">

    $('.fb').click( function() 
    {
        var shareurl = $(this).data('shareurl');
        window.open('https://www.facebook.com/dialog/feed?app_id=2261225134126697&picture=http://www.fbrell.com/f8.jpg&name=FSL11&description=homeschool%20.versus%20.awayschool%20@%20.venue%20@%20.datetime&caption=Put your cricket knowledge to test and play with me on FSL11. Click https://fsl11.com/download-app to download the FSL11 app or login on portal and Use contest code: '+$('#invite_code').text()+' to join my contest.', 'Fantasy', 
        'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
        return false;
    });

var twitterShare = document.querySelector('[data-js="twitter-share"]');

twitterShare.onclick = function(e) {
  e.preventDefault();    
      var twitterWindow = window.open("https://twitter.com/intent/tweet?text=Put your cricket knowledge to test and play with me on FSL11. Click https://fsl11.com/download-app to download the FSL11 app or login on portal and Use contest code: "+$('#invite_code').text()+" to join my contest.", 'twitter-popup', 'height=350,width=600');
      if(twitterWindow.focus) { twitterWindow.focus(); }
        return false;
    }
       
</script>
