<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="auctionController" ng-init="Series();" ng-cloak >
    <div class="pt-5 comonBg bglayer">
        <div class="matchcenterDetail pos_rel">
            <div class="container-fluid">
                <div class="row">
                    <div class="creatTeamTop">
                        <div class="mt-4 silder_show">
                            <div class="wrapper">
                                <div class="slider" slick-custom-carousel ng-if="silder_visible" >
                                    <div class="" ng-repeat="series in seriesList.Records" ng-if="seriesList.Records.length > 0"  >
                                        <a href="javascript:void(0);" ng-click="selectSeries(series)">
                                            <div class="slider_item {{selected_series.SeriesGUID == series.SeriesGUID ? 'active' : '' }}">
                                                <h4> {{series.SeriesName}} </h4>
                                                <div class="d_flex">
                                                    <figure class="mb-0"><img ng-src="{{base_url}}/assets/img/champions.png" alt="{{series.TeamNameShortLocal}}"class="img-fluid" width="60" /></figure>
                                                    <div class="timer">
                                                        <p id="demo" timer-text="{{series.SeriesStartDate}}" timer-data="{{series.SeriesStartDate}}" match-status="{{series.Status}}" ng-bind-html="clock | trustAsHtml" class="ng-binding"></p>
                                                    </div>
                                                    <!-- <figure class="mb-0"> <img ng-src="{{series.TeamFlagVisitor}}" alt="{{series.TeamNameShortVisitor}}" class="img-fluid" width="60"  /> </figure> -->
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section class="contest_sec d-flex align-items-center auction_contest">
                            <div class="col-md-3 pl-0">
                                <div class="timer">
                                    <p> Next Start  </p> 
                                    <p id="demo" timer-text="{{selected_series.SeriesStartDate}}" timer-data="{{selected_series.SeriesStartDate}}" match-status="{{selected_series.Status}}" ng-bind-html="clock | trustAsHtml" class="ng-binding"><span>213 <strong>DAY</strong></span><span>22 <strong>HRS</strong></span><span>32 <strong>MIN</strong></span><span>04 <strong>SEC</strong></span></p>
                                </div>
                            </div>

                            <div class="col-md-6 text-center">
                                <!-- <a  class="btn btnClone theme_bgclr" href="javascript:void(0)" ng-click="openPopup('create_contest')" > Create a contest </a> -->
                                <a class="btn btnClone bgclr bdr_wht" href="referAndEarn">  Invite Friends </a>
                                <a class="btn btnClone theme_bgclr bdr_wht"  href="javascript:void(0)" ng-click="openPopup('joinPrivateContestPopup')">Got A League Code? </a>
                            </div>
                            <div class=" col-md-3 search-form">
                                <input class="form-control" type="search" placeholder="Search" ng-model="search_contest" aria-label="Search">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="button" ng-click="searchContest(search_contest)"><i class="fa fa-search"></i></button>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="container-fluid  lobby_res mt-4 auction_table">
                <ul class="nav nav-tabs">
                    <li class="nav-item"  >
                        <a class="nav-link {{activeTab=='normal' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('normal');" role="tab" aria-controls="nav-home" aria-selected="true">ALL </a>
                    </li>
                    <li class="nav-item"  >
                        <a class="nav-link {{activeTab=='joined' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('joined');" role="tab" aria-controls="nav-profile" aria-selected="false"> My Contests </a>
                    </li>
                    <!-- <li class="nav-item"  >
                        <a class="nav-link {{activeTab=='team' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="data.pageNo = 1; gotoTab('team'); UsersTeamList();" role="tab" aria-controls="nav-profile" aria-selected="false"> My Team</a>
                    </li> -->
                </ul>

                <div class="tab-content">
                    <div class="tab-pane {{activeTab=='normal' ? 'active' : '' }}" id="normal" >
                        <div class="sortHead">
                            <div class="matchtypeHead ng-scope" ng-show="ContestsTotalCount > 0">
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

                        <div class="matchtypeBody dfs_custom_scroll ng-scope" scrolly ng-show="ContestsTotalCount > 0">
                            <ul ng-repeat="Contest in Contests | orderBy: filter "  ng-if="Contest.Status == 'Pending'" >
                                <li>
                                    {{Contest.ContestName}}
                                </li>
                                <li>
                                    {{Contest.ContestType}} <br/> 
                                        <span ng-if="Contest.IsConfirm == 'Yes'" data-toggle="tooltip" title="This league is confirmed. It will go on irrespective of number of entries." data-placement="bottom" class="contest_btn_c" >C</span><span ng-if="Contest.EntryType == 'Multiple'" data-toggle="tooltip" title="This league is multiple. So a user can join with multiple teams." data-placement="bottom" class="contest_btn_m">M</span><span ng-if="Contest.CashBonusContribution > '0.00'" data-toggle="tooltip" title="This league is bonus contribution contest. It will take some partial amount from your cash bonus." data-placement="bottom" class="contest_btn_b">B</span> 
                                </li>
                                <li>{{Contest.IsPaid=='Yes' ? '₹ '+Contest.EntryFee : 'FREE' }}</li>
                                <li>{{Contest.TotalJoined}} <p class="pull-right">{{Contest.ContestSize}}</p>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:{{Contest.joinedpercent}}%;"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="payoutParBox">
                                        <a href="javascript:void(0)" ng-click="showWinningPayout(Contest.CustomizeWinning)"><cite class="fa fa-eye" aria-hidden="true"></cite></a>
                                    </div>
                                    <i>₹</i>{{Contest.WinningAmount}}
                                </li>
                                <li>{{Contest.LeagueJoinDateTime | myDateFormat:Contest.GameTimeLive}}</li>
                                <li class="bat">
                                    <button class="btn btn-submit bggreen" ng-click="check_balance_amount(Contest)" ng-show="Contest.IsJoined == 'No' && Contest.Status == 'Pending'">Join</button> 

                                    <a class="btn btn-submit theme_bgclr" href="createAuctionTeam?SeriesGUID={{selected_series.SeriesGUID}}&League={{Contest.ContestGUID}}" ng-show="Contest.IsJoined == 'Yes' && Contest.Status == 'Pending'">Enter Auction</a>
                                    <a class="btn btn-submit theme_bgclr" href="javascript:void(0)" ng-click="openinvitationModal(Contest.UserInvitationCode)" > Invite Friends </a>
                                </li>
                            </ul>
                        </div>
                        <div class="matchtypeBody border-0" ng-show="ContestsTotalCount == 0" style="">
                            <div class="alertBoxParents text-center">
                                <div class="alertBox">
                                    <p>No Contests Found!</p>
                                </div>  
                            </div>
                        </div>

                    </div>


                    <div class="tab-pane {{activeTab=='joined' ? 'active' : '' }}" id="joined"  >
                        <div class="sortHead">  
                            <div class="matchtypeHead" ng-if="UserJoinedContestTotalCount > 0" >
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
                        <div class="matchtypeBody" scrolly ng-if="UserJoinedContestTotalCount > 0">
                            <ul ng-repeat="joinedContests in data.dataList">
                                <li>{{joinedContests.ContestName}}</li>
                                <li>
                                    {{joinedContests.ContestType}} <br/> 
                                        <span ng-if="joinedContests.IsConfirm == 'Yes'" data-toggle="tooltip" title="This league is confirmed. It will go on irrespective of number of entries." data-placement="bottom" class="contest_btn_c" >C</span><span ng-if="joinedContests.EntryType == 'Multiple'" data-toggle="tooltip" title="This league is multiple. So a user can join with multiple teams." data-placement="bottom" class="contest_btn_m">M</span><span ng-if="joinedContests.CashBonusContribution > '0.00'" data-toggle="tooltip" title="This league is bonus contribution contest. It will take some partial amount from your cash bonus." data-placement="bottom" class="contest_btn_b">B</span> 
                                </li>
                                <li>{{joinedContests.IsPaid=='Yes' ? '₹ '+joinedContests.EntryFee : 'FREE' }}</li>
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
                                <li>{{joinedContests.LeagueJoinDateTime | myDateFormat}}</li>
                                <li class="bat">
                                    <a class="btn btn-submit theme_bgclr" href="createAuctionTeam?SeriesGUID={{selected_series.SeriesGUID}}&League={{joinedContests.ContestGUID}}" ng-show="joinedContests.IsAuctionFinalTeamSubmitted == 'No' && joinedContests.Status == 'Pending' || joinedContests.Status == 'Running'">Enter Auction</a>
                                    <a class="btn btn-submit theme_bgclr" href="auctionLeague?SeriesGUID={{selected_series.SeriesGUID}}&League={{joinedContests.ContestGUID}}" ng-show="joinedContests.IsAuctionFinalTeamSubmitted == 'Yes'">Leaderboard</a>
                                    <a class="btn btn-submit theme_bgclr" href="javascript:void(0)" ng-click="openinvitationModal(joinedContests.UserInvitationCode)" > Invite Friends </a>
                                </li>
                            </ul>
                        </div>
                        <div class="matchtypeBody border-0" ng-if="UserJoinedContestTotalCount == 0">
                            <div class="alertBoxParents text-center">
                                <div class="alertBox">
                                    <p>No Leagues Joined Yet!</p>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane {{activeTab=='team' ? 'active' : '' }}" id="team">
                        <div class="matchtypeHead">
                            <ul ng-if="userTeamList.Records">
                                <li>Team Name</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                        <div class="matchtypeBody" ng-if="userTeamList.Records" >
                            <ul ng-repeat="userTeams in userTeamList.Records" >
                                <li>{{userTeams.UserTeamName}}</li>
                                <li>{{userTeams.UserTeamType=='InPlay' ? 'In Play' : 'Normal' }}</li>
                                <li><button class="btn btn-submit theme_bgclr" ng-click="ViewTeamOnGround(userTeams.UserTeamGUID)">View</button> </li>
                                <li><a class="btn btn-submit theme_bgclr" href="javascript:void(0)" ng-click="edit_team(userTeams.UserTeamGUID)">Edit</a> </li>
                                <!-- <li><a class="btn btn-submit" href="createTeam?MatchGUID={{MatchGUID}}&UserTeamGUID={{userTeams.UserTeamGUID}}">Copy</a> </li> -->
                            </ul>
                        </div>
                        <div class="matchtypeBody border-0" ng-if="!userTeamList.Records" >
                            <div class="alertBoxParents text-center">
                                <div class="alertBox">
                                    <p>No Teams Created Yet! </p>
                                </div>  
                            </div>
                        </div>
                    </div>

                </div>
                <!--cretteam-->
            </div>
        </div>
        <!-- Show Payouts break ups -->
        
        <div class="modal fade centerPopup" popup-handler id="PayoutBreakUp" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
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
    </div>
</div>
<div class="modal fade centerPopup" popup-handler id="joinLeaguePopup" tabindex="-1" role="dialog" >
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
                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <button class="btn btn-submit bggreen pull-right" ng-click="JoinContest()" > Join </button>
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
                                    <li><a class="fb" href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                    <li><a class="tw" href="https://twitter.com/login" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a class="inst" href="https://www.instagram.com/accounts/login" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                    <li><a class="wt" href="https://web.whatsapp.com/" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<!--Main container sec end-->
<?php include('innerFooter.php'); ?>