<?php include('header.php'); ?>
    <!--Main container sec start-->
    <div class="mainContainer" ng-controller="LeagueCenterController" ng-cloak ng-init="LeagueCenter('Pending');">
        <div class="comonBg">
            <div class="matchcenterDetail leagueCenter">
                <div class="container-fluid">
                    <div class="creatTeamTop">
                        <div class="row">
                            <div class=" col-md-3 ">
                                <a class="back__btn" href="javascript:void(0)" ng-click="Back()"><i class="fa fa-angle-left"></i>Back</a>
                            </div>
                            <div class="col-md-6 ">
                                <div class="coman_bg_overlay text-center primarHead mb-3">
                                    <h1>League Center</h1>
                                    <p class="text-light">List of all upcoming, live and past matches joined by you. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--cretteam-->
                <div class="container-fluid">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='upcoming' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="activeMenuTab('upcoming');">Upcoming <span>{{Statics.UpcomingJoinedContest}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='running' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="activeMenuTab('running');">Live <span>{{Statics.LiveJoinedContest}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='completed' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="activeMenuTab('completed');">Completed <span>{{Statics.CompletedJoinedContest}}</span></a>
                            </li>
                        
                        </ul>

                            <div class="tab-content">
                                <div class="tab-pane {{activeTab=='upcoming' ? 'active' : '' }}" id="upcoming" ng-show="activeTab=='upcoming'">
                                    <div class="matchtypeHead res_leagcenter" ng-if="ContestsTotalCount > 0" >
                                        <ul class="flex-child-3par">
                                            <li>Match</li>
                                            <li class="text-center">League Close in</li>
                                            <li class="text-center">League Joined</li>
                                            <li class="text-center">Action </li>
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody dfs_custom_scroll" scrolly   ng-if="ContestsTotalCount > 0" >
                                        <ul class="flex-child-3par" ng-repeat="Contests in ContestDetails">
                                            <li>
                                                <div class="content float-left"><img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagLocal}}"> <strong>{{Contests.TeamNameShortLocal}} VS {{Contests.TeamNameShortVisitor}} <img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagVisitor}}"> </strong>
                                                <div class="res_font"><a target="_top">{{Contests.SeriesName}}</a></div>
                                                </div>
                                            </li>
                                            <li class="text-center live_counter"><p id="demo" timer-text="{{Contests.MatchStartDateTime}}" timer-data="{{Contests.MatchStartDateTime}}" match-status="{{Contests.Status}}" ng-bind-html="clock | trustAsHtml" class="ng-binding"></p></li>
                                            <li class="text-center"> {{Contests.MyTotalJoinedContest}} </li>
                                            <li class="bat"><a href="javascript:void(0)" ng-click="goLobby(Contests.MatchGUID,'upcoming')"  class="btn btn-submit theme_bgclr">View League</a> </li>
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody border-0" ng-if="ContestsTotalCount == 0">
                                        <div class="alertBoxParents text-center">
                                            <div class="alertBox">
                                                <p>NO UPCOMMING MATCHES!</p>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane {{activeTab=='running' ? 'active' : '' }}" id="running" ng-show="activeTab=='running'">
                                    <div class="matchtypeHead res_leagcenter" ng-if="ContestsTotalCount > 0" >
                                        <ul class="flex-child-3par">
                                            <li>Match</li>
                                            <li> Match Status </li>
                                            <li class="text-center">League Joined</li>
                                            <li class="text-center">Action</li>
                                            
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody dfs_custom_scroll" scrolly  ng-if="ContestsTotalCount > 0" >
                                        <ul  class="flex-child-3par" ng-repeat="Contests in ContestDetails">
                                            <li>
                                                <div class="content float-left"><img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagLocal}}"> <strong>{{Contests.TeamNameShortLocal}} VS {{Contests.TeamNameShortVisitor}} <img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagVisitor}}"> </strong>
                                                   <div class="res_font"><a target="_top">{{Contests.SeriesName}}</a><p>{{Contests.MatchStartDateTime | date : 'yyyy-MM-dd' }}</p></div>
                                                </div>
                                            </li>

                                            <li class="green-clr"> Live </li>
                                            
                                            <li class="text-center">{{Contests.MyTotalJoinedContest}}</li>
                                            <li class="bat"><a href="javascript:void(0)" ng-click="goLobby(Contests.MatchGUID,'Running')"  class="btn btn-submit theme_bgclr">Leaderboard</a> </li>
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody border-0" ng-if="ContestsTotalCount == 0 ">
                                        <div class="alertBoxParents text-center">
                                            <div class="alertBox">
                                                <p>NO LIVE MATCHES!</p>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane {{activeTab=='completed' ? 'active' : '' }}" id="completed" ng-show="activeTab=='completed'">
                                    <div class="matchtypeHead res_leaguecomp" ng-if="ContestsTotalCount > 0" >
                                        <ul class="flex-child-3par">
                                            <li>Match</li>
                                            <li>League Date</li>
                                            <li class="text-center">League Joined</li>
                                            <li class="text-center">Won</li>
                                            <li class="text-center">Action</li>
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody dfs_custom_scroll" scrolly ng-if="ContestsTotalCount > 0">
                                        <ul ng-repeat="Contests in ContestDetails " class="flex-child-3par res_leag_list" ng-if="Contests.Status=='Completed'" >
                                            <li>
                                                <div class="content float-left"><img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagLocal}}"> <strong>{{Contests.TeamNameShortLocal}} VS {{Contests.TeamNameShortVisitor}} <img class="leagueCenterMatchImg" ng-src="{{Contests.TeamFlagVisitor}}"> </strong>
                                                <div >
                                                    <a target="_top">{{Contests.SeriesName}}</a></div>
                                                </div>
                                            </li>
                                            <li>{{Contests.MatchStartDateTime | myDateFormat }}</li>
                                            <li class="green-clr text-center"> {{Contests.MyTotalJoinedContest}} </li>
                                            <li class="text-center green-clr">{{moneyFormat(Contests.TotalUserWinning)}} </li>
                                            <li><a href="javascript:void(0)" ng-click="goLobby(Contests.MatchGUID,'Completed')"  class="btn btn-submit theme_bgclr">Leaderboard</a> </li>
                                        </ul>
                                    </div>
                                    <div class="matchtypeBody border-0" ng-if="ContestsTotalCount == 0">
                                        <div class="alertBoxParents text-center">
                                            <div class="alertBox">
                                                <p>NO COMPLETED MATCHES!</p>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>

                </div>
                <!--cretteam-->
            </div>
        </div>
    </div>
    <style>
        p#demo span strong {
            color: #ccc;
        }
    </style>
    <!--Main container sec end-->
<?php include('innerFooter.php'); ?>