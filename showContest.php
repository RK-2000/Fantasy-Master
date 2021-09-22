<?php include('header.php'); ?>
    <!--Main container sec start-->
    <div class="mainContainer" ng-controller="showContestController" ng-init="matchDetails();JoinedContest(true);" ng-cloak >
        <div class="comonBg">
            <div class="creatTeam mt-4">
                <div class="container-fluid">
                    <div class="creatTeamTop">
                        <div class="row">
                            <div class=" col-md-3 ">
                                <a class="back__btn" href="leagueCenter" ><i class="fa fa-angle-left"></i>Back </a>
                            </div>
                            <div class="col-md-6 coman_bg_overlay p-4">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 border-right">
                                    <div class="matchCenterbox ">
                                        <div class="matchCenterHeader">
                                            <ul>
                                                <li>
                                                    <div class="teamLogo">
                                                        <img ng-src="{{MatchDetails.TeamFlagLocal}}" alt="">
                                                    </div>
                                                </li>
                                                <li>{{MatchDetails.TeamNameShortLocal}}
                                                    <span>vs</span> {{MatchDetails.TeamNameShortVisitor}}</li>
                                                <li>
                                                    <div class="teamLogo">
                                                        <img ng-src="{{MatchDetails.TeamFlagVisitor}}" alt="">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="matchCenterBody">
                                            <p>{{MatchDetails.SeriesName}}
                                                <br> {{MatchDetails.MatchType}} | {{MatchDetails.MatchNo}}</p>
                                            <div class="location">
                                                <i class="fa fa-map-marker"></i> {{MatchDetails.MatchLocation}}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-6 ">
                                    <div class="matchCenterbox" ng-if="MatchDetails.Status!='Pending'">
                                        <div class=" matchFeed">
                                            <h5 class="mb-2">Match Feed</h5>
                                            <div class="matchFeedList pl-3">
                                                <ul class="row">
                                                    <li class="col-7 text-left">Match Status</li>
                                                    <li class="col-5"><span ng-class="{Pending:'text-danger', Live:'text-success',Cancelled:'text-danger',Completed:'text-success'}[MatchDetails.Status]">{{MatchDetails.Status}}</span></li>
                                                </ul>

                                                <ul class="row">
                                                    <li class="col-7 text-left">{{MatchDetails.MatchScoreDetails.TeamScoreLocal.Name}}</li>
                                                    <li class="col-5 numeric" ng-if="MatchDetails.MatchScoreDetails.TeamScoreLocal.Scores">{{MatchDetails.MatchScoreDetails.TeamScoreLocal.Scores[0].Scores}} ({{MatchDetails.MatchScoreDetails.TeamScoreLocal.Scores[0].Overs}})</li>
                                                    
                                                </ul>

                                                <ul class="row">
                                                    <li class="col-7 text-left">{{MatchDetails.MatchScoreDetails.TeamScoreVisitor.Name}}</li>
                                                    <li class="col-5 numeric " ng-if=" MatchDetails.MatchScoreDetails.TeamScoreVisitor.Scores">{{MatchDetails.MatchScoreDetails.TeamScoreVisitor.Scores[0].Scores}} ({{MatchDetails.MatchScoreDetails.TeamScoreVisitor.Scores[0].Overs}})</li>
                                                </ul>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="matchCenterbox" ng-if="MatchDetails.Status =='Pending'">
                                        <div class="coman_bg_overlay">
                                            <h4>League Closes in</h4>
                                            <div class="timer" ng-if="MatchDetails.MatchStartDateTime">
                                                <p id="demo" timer-text="{{MatchDetails.MatchStartDateTime}}" timer-data="{{MatchDetails.MatchStartDateTime}}" match-status="{{MatchDetails.Status}}" ng-bind-html="clock | trustAsHtml"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--cretteam-->
                <div class="container-fluid">       
                    <div class="tab-pane" id="joined">
                        <div class="sortHead custom_showContest">  
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
                        <div class="matchtypeBody dfs_custom_scroll" scrolly ng-if="UserJoinedContestTotalCount > 0">
                            <ul ng-repeat="joinedContests in Contests | orderBy:propertyName:reverse">
                                <li>{{joinedContests.ContestName}}</li>
                                <li>{{joinedContests.ContestType}} <br/><span ng-if="joinedContests.IsConfirm == 'Yes'" data-toggle="tooltip" title="This league is confirmed. It will go on irrespective of number of entries." data-placement="bottom" class="contest_btn_c">C</span><span ng-if="joinedContests.EntryType == 'Multiple'" data-toggle="tooltip" title="This league is multiple. So a user can join with multiple teams." data-placement="bottom" class="contest_btn_m">M</span><span ng-if="joinedContests.CashBonusContribution > '0.00'" data-toggle="tooltip" title="This league is bonus contribution contest. It will take some partial amount from your cash bonus." data-placement="bottom" class="contest_btn_b">B</span> </li>
                                <li>{{joinedContests.IsPaid=='Yes' ? '₹ '+joinedContests.EntryFee : 'FREE' }}</li>
                                <li>{{joinedContests.TotalJoined}}<small style="margin-left: 25px;"> Out Of </small><p class="pull-right">{{joinedContests.ContestSize}}</p>
                                        <div class="progress">
                                            <div class="progress-bar" style="width:{{joinedContests.joinedpercent}}%;"></div>
                                        </div>
                                </li>
                                <li>
                                    <div class="payoutParBox" ng-if="joinedContests.WinningAmount > 0">
                                        <a href="javascript:void(0)" ng-click="showWinningPayout(joinedContests.CustomizeWinning)"><cite class="fa fa-eye" aria-hidden="true"></cite></a>
                                    </div>
                                    <i>₹</i>{{joinedContests.WinningAmount}}
                                </li>
                                <li>{{joinedContests.MatchStartDateTime | myDateFormat}}</li>
                                <li class="bat showcontest_bat">
                                    <!-- <a class="btn btn-submit" href="javascript:void(0)" ng-show="joinedContests.Status != 'Pending' && joinedContests.EntryType == 'Multiple'" ng-click="SelectTeamToJoinContest(joinedContests.ContestGUID)" >Rejoin</a> -->

                                     <a class="text-danger" href="javascript:void(0)" ng-show="joinedContests.Status == 'Cancelled'">Cancelled</a> 

                                    <a class="btn btn-submit theme_bgclr" href="league?MatchGUID={{MatchGUID}}&League={{joinedContests.ContestGUID}}" ng-show=" joinedContests.Status != 'Pending' && joinedContests.Status != 'Cancelled'">Leaderboard</a>

                                </li>
                            </ul>
                        </div>
                        <div class="matchtypeBody border-0" ng-if="UserJoinedContestTotalCount == 0">
                            <div class="alertBoxParents text-center">
                                <div class="alertBox">
                                    <p>NO LEAGUES JOINED YET!</p>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <!--cretteam-->
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
    <!--Main container sec end-->

<?php include('innerFooter.php'); ?>