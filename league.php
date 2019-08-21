<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="leagueController" ng-init="getContests();" ng-cloak >
    <div class="comonBg">
        <div class="creatTeam mt-3">
            <div class="container-fluid">
                <div class="creatTeamTop">
                    <div class="row">
                        <div class="col-md-3">
                            <a class="back__btn" href="javascript:void(0)" ng-click="Back()"><i class="fa fa-angle-left"></i>Back </a>
                        </div>
                        <div class="col-md-6 coman_bg_overlay">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-sm-6 border-right">
                                    <div class="matchCenterbox">
                                        <div class="matchCenterHeader">
                                            <ul>
                                                <li>
                                                    <div class="teamLogo">
                                                        <img ng-src="{{Contest.TeamFlagLocal}}" alt="">
                                                    </div>
                                                </li>
                                                <li>{{Contest.TeamNameShortLocal}}
                                                    <span>vs</span> {{Contest.TeamNameShortVisitor}}</li>
                                                <li>
                                                    <div class="teamLogo">
                                                        <img ng-src="{{Contest.TeamFlagVisitor}}" alt="">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="matchCenterBody">
                                            <p>{{Contest.SeriesName}}
                                                <br> {{Contest.MatchType}} | {{Contest.MatchNo}}</p>
                                            <div class="location">
                                                <i class="fa fa-map-marker"></i> {{Contest.MatchLocation}}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class=" matchCenterbox" ng-if="Contest.Status != 'Pending'">
                                        <div class="matchCenterFooter matchFeed h-100 bg-transparent">
                                            <h5 class="mb-2"> Match Feed </h5>
                                            <div class="matchFeedList text-left">
                                                <ul class="row">
                                                    <li class="col-md-7">Match status</li>
                                                    <li class="col-md-5"><span ng-class="{Pending:'text - danger', Live:'text - success',Cancelled:'text - danger',Completed:'text - success'}[Contest.Status]">{{Contest.Status}}</span></li>
                                                </ul>
                                                <ul class="row">
                                                    <li class="col-md-7">{{Contest.MatchScoreDetails.TeamScoreLocal.Name}}</li>
                                                    <li class="col-md-5 numeric " ng-if="Contest.MatchScoreDetails.TeamScoreLocal.Scores">{{Contest.MatchScoreDetails.TeamScoreLocal.Scores}} ({{Contest.MatchScoreDetails.TeamScoreLocal.Overs}})</li>

                                                </ul>

                                                <ul class="row">
                                                    <li class="col-md-7">{{Contest.MatchScoreDetails.TeamScoreVisitor.Name}}</li>
                                                    <li class="col-md-5 numeric" ng-if=" Contest.MatchScoreDetails.TeamScoreVisitor.Scores">{{Contest.MatchScoreDetails.TeamScoreVisitor.Scores}} ({{Contest.MatchScoreDetails.TeamScoreVisitor.Overs}})</li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-100" ng-if="Contest.Status == 'Pending'">
                                        <h5 class="mb-2">League Closes In</h5>
                                        <div class="timer league_demo" ng-if="Contest.MatchStartDateTimeUTC">
                                            <p id="demo" timer-text="{{Contest.MatchStartDateTimeUTC}}" timer-data="{{Contest.MatchStartDateTimeUTC}}" match-status="{{Contest.Status}}" ng-bind-html="clock | trustAsHtml"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!--cretteam-->
                <div class="coman_bg_overlay mb-2">
                    <div class="row  align-items-center">
                        <div class="col-sm-8">
                            <div class="gradList">
                                <ul>
                                    <li>
                                        <img ng-src="assets/img/cricket_bat.png" alt="">
                                    </li>
                                    <li>
                                        <span>Opponent</span>
                                        <cite>{{Contest.Privacy=='Yes' ? 'Private' : 'Public' }}</cite>
                                    </li>
                                    <li>
                                        <span>Type </span>
                                        <cite>{{Contest.ContestType}}</cite>
                                    </li>

                                    <li>
                                        <span>Invite</span>
                                        <div class="d-flex justify-space-between">
                                            <cite><i class="fa fa-facebook" aria-hidden="true"></i> </cite>
                                            <cite><i class="fa fa-twitter" aria-hidden="true"></i> </cite>
                                            <cite><a href="https://api.whatsapp.com/send?text=Join {{Contest.TeamNameLocal}} V/s {{Contest.TeamNameVisitor}} {{Contest.IsPaid=='No' ? 'Free Roll' : 'Paid' }} league on FSL11 and win ₹{{Contest.WinningAmount}}. Entry fee ₹{{Contest.EntryFee}}. Use league code {{Contest.UserInvitationCode}}. " target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></cite>
                                            <cite>
                                                <a href="mailto:?subject=Get paid to be a fan&body=Join {{Contest.TeamNameLocal}} V/s {{Contest.TeamNameVisitor}} {{Contest.IsPaid=='No' ? 'Free Roll' : 'Paid' }} league on FSL11 and win ₹{{Contest.WinningAmount}}. Entry fee ₹{{Contest.EntryFee}}. Use league code {{Contest.UserInvitationCode}}"><i class="fa fa-envelope"></i></a>
                                            </cite>
                                        </div>
                                    </li>
                                    <li>
                                        <span>Buy in </span>
                                        <cite><u>₹ <lable>{{Contest.EntryFee}}</lable></u> </cite>
                                    </li>

                                    <li>
                                        <span>Entries</span>
                                        <cite>{{Contest.TotalJoined}}/{{Contest.ContestSize}}</cite>
                                    </li>
                                    <li>
                                        <span>Payouts</span>
                                        <div class="paoutRow">
                                            <u>₹ <lable>{{Contest.WinningAmount}}</lable></u>  
                                        </div>
                                        <div class="payoutMsg">

                                            <div class="payoutParBox" ng-if="Contest.IsPaid == 'Yes'">
                                                <a href="javascript:void(0)" ng-click="showWinningPayout(Contest.CustomizeWinning)"><p><i class="fa fa-star" aria-hidden="true"></i> View Payout Breakup</p></a>
                                            </div>
                                        </div>

                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="text-right leagueStatusParent mb-3">
                                <h2> League Status  :  <span ng-class="{Pending:'text - danger', Live:'text - success',Cancelled:'text - danger',Completed:'text - success'}[Contest.Status]" >{{Contest.Status}}</span></h2> 
                            </div>

                            <div class="pb-3 text-right" ng-if="Contest.Status == 'Pending'">
                                <a class="btn btn-submit theme_bgclr text-dark" ng-if="UserContestTeams.length" href="javascript:void(0)" ng-click="switchTeamPopup();">Switch Team</a>
                                <a class="btn btn-submit theme_bgclr text-dark" href="createTeam?MatchGUID={{MatchGUID}}&League={{ContestGUID}}">Create Team</a>
                                <a class="btn btn-submit theme_bgclr text-dark" ng-if="user_details.UserGUID === SelectedUserGUID && Contest.Status == 'Pending' && SelectedUserTeamGUID" href="createTeam?Operation=edit&MatchGUID={{MatchGUID}}&UserTeamGUID={{SelectedUserTeamGUID}}" >Edit Team</a>

                                <!--        <div class="text-right" ng-if="Contest.Status =='Pending'">
                                           <a class="btn btn-submit light_bg text-dark" ng-if="UserContestTeams.length" href="javascript:void(0)" ng-click="switchTeamPopup();">Switch Team</a>
                                           <a class="btn btn-submit light_bg text-dark" href="createTeam?MatchGUID={{MatchGUID}}&League={{ContestGUID}}">Create Team</a>
                                           <a class="btn btn-submit light_bg text-dark" ng-if="user_details.UserGUID === SelectedUserGUID && Contest.Status =='Pending' && SelectedUserTeamGUID" href="createTeam?Operation=edit&MatchGUID={{MatchGUID}}&UserTeamGUID={{SelectedUserTeamGUID}}" >Edit Team</a>
                                       </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" ng-if="userTeams.length">
                    <div class="col-md-6 pr-0 ">
                        <div class="bg-white h-100 bdr-rad">
                            <div class="creatTeamHead py-2">
                                <h4 class="text-white"> LEADER BOARD</h4>
                            </div>
                            <div class="creatTeamTable">
                                <div class="tab-content">
                                    <div class="tab-pane active" >
                                        <div class="player_tab_table  team_info">
                                            <table>
                                                <thead>
                                                    <tr class="text-center">
                                                        <th><a href="javascript:void(0)" class="text-muted" ng-click="sortBy('Username')">USER</a><span class="sortorder ml-1 " ng-show="propertyName === 'Username'" ng-class="{reverse: reverse}"></span></th>
                                                        <th><a href="javascript:void(0)" class="text-muted" ng-click="sortBy('UserRank')">RANK</a><span class="sortorder  ml-1" ng-show="propertyName === 'UserRank'" ng-class="{reverse: reverse}"></span></th>
                                                        <th><a href="javascript:void(0)" class="text-muted" ng-click="sortBy('TotalPoints')">POINTS</a><span class="sortorder ml-1 " ng-show="propertyName === 'TotalPoints'" ng-class="{reverse: reverse}"></span></th>
                                                        <th ng-if="Contest.Status == 'Completed'"><a href="javascript:void(0)" class="text-muted ml-1 " ng-click="sortBy('UserWinningAmount')">WON</a><span class="sortorder" ng-show="propertyName === 'UserWinningAmount'" ng-class="{reverse: reverse}"></span></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="table_scroll" scrolly style="max-height: 800px;overflow-y: scroll;">
                                                <table class="table-fixed" >
                                                    <tbody>
                                                        <tr ng-repeat="team in userTeams| orderBy:propertyName:reverse" ng-click="ViewTeamOnGround(team.UserTeamPlayers)" class="">
                                                            <td class="text-center " style="text-transform: uppercase;">{{team.Username}}(T{{getTeamName(team.UserTeamName)}})</td>
                                                            <td class="text-center " ng-if="Contest.Status == 'Completed' && team.UserRank == '1'"><img  src="assets/img/winner-free.svg" width='20'><p>Champion</p></td>
                                                            <td class="text-center " ng-if="Contest.Status == 'Completed' && team.UserRank != '1'">#{{team.UserRank}}</td>
                                                            <td class="text-center " ng-if="Contest.Status != 'Completed'">#{{team.UserRank}}</td>
                                                            <td class="text-center numeric" >{{team.TotalPoints}}</td>
                                                            <td ng-if="Contest.Status == 'Completed'" class="numeric">{{moneyFormat(team.UserWinningAmount)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="creatTeamTable lineupTeams">
                            <div class="creatTeamHead  py-3">
                                <h4 class="text-white mb-1">LINEUP</h4>
                                <button class="btn btn-submit" ng-click="openPopup('scoringRulePopup'); pointSystem();">SCORING CRITERIA</button>
                                <button class="btn btn-submit" ng-click="openPopup('fantasyScorePopup');fantasyScoreCard(''); pointSystem();">FANTASY SCORECARD</button>
                            </div>
                        </div>
                        <div class="groundSec">
                            <div class="previewBg" >
                                <div class="teamPar">

                                    <div class="teamPreviewRow wicketkeeperPosition">
                                        <span class="d-inline-block mb-4">Wicket-Keeper</span>
                                        <ul>
                                            <li ng-if="teamStructure['WicketKeeper'].player.length > 0" ng-repeat=" WicketKeeper in teamStructure['WicketKeeper'].player" ng-click="(Contest.Status != 'Pending')?showPlayerFanstayPoint(WicketKeeper.PlayerGUID):''" style="cursor: pointer">
                                                <div class="captaine captain_css" ng-if="WicketKeeper.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine vicecaptain_css" ng-if="WicketKeeper.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{WicketKeeper.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName {{(WicketKeeper.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(WicketKeeper.PlayerName) | limitTo: 10}}{{getPlayerShortName(WicketKeeper.PlayerName).length > 10 ? '...' : ''}} </div>
                                                <span >{{WicketKeeper.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow batsmanPosition">
                                        <span>Batsmen</span>
                                        <ul>
                                            <li ng-if="teamStructure['Batsman'].player.length > 0" ng-repeat=" Batsman in teamStructure['Batsman'].player" ng-click="(Contest.Status != 'Pending')?showPlayerFanstayPoint(Batsman.PlayerGUID):''" style="cursor: pointer">
                                                <div class="captaine captain_css" ng-if="Batsman.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine vicecaptain_css" ng-if="Batsman.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{Batsman.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName {{(Batsman.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(Batsman.PlayerName) | limitTo: 10}}{{getPlayerShortName(Batsman.PlayerName).length > 10 ? '...' : ''}} </div>
                                                <span >{{Batsman.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow bowlerPosition">
                                        <span>Bowlers</span>
                                        <ul>
                                            <li ng-if="teamStructure['Bowler'].player.length > 0" ng-repeat=" Bowler in teamStructure['Bowler'].player" ng-click="(Contest.Status != 'Pending')?showPlayerFanstayPoint(Bowler.PlayerGUID):''" style="cursor: pointer">
                                                <div class="captaine captain_css" ng-if="Bowler.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine vicecaptain_css" ng-if="Bowler.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{Bowler.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName {{(Bowler.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(Bowler.PlayerName) | limitTo: 10}}{{getPlayerShortName(Bowler.PlayerName).length > 10 ? '...' : ''}} </div>
                                                <span  >{{Bowler.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow allrounderPosition">
                                        <span class="d-inline-block mb-4">All-Rounders</span>
                                        <ul>
                                            <li ng-if="teamStructure['AllRounder'].player.length > 0" ng-repeat=" AllRounder in teamStructure['AllRounder'].player" ng-click="(Contest.Status != 'Pending')?showPlayerFanstayPoint(AllRounder.PlayerGUID):''" style="cursor: pointer">
                                                <div class="captaine captain_css" ng-if="AllRounder.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine vicecaptain_css" ng-if="AllRounder.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{AllRounder.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName {{(AllRounder.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ getPlayerShortName(AllRounder.PlayerName) | limitTo: 10}}{{getPlayerShortName(AllRounder.PlayerName).length > 10 ? '...' : ''}} </div>
                                                <span  >{{AllRounder.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--cretteam-->
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
                        <p style="color: #ccc !important;">"Note - If there is a tie between 2 or more gamers, then the prize money will be divided equally among."</p>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<!--Main container sec end-->

<!-- scoring rule modal start -->
<!-- scoringRulePopup -->
<div class="modal fade centerPopup" popup-handler id="scoringRulePopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
    <div class="modal-dialog custom_popup small_popup res_scoring">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Scoring Rules</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <div class="row">

                    <div class="col-sm-4 player_tab_table">
                        <span><b>BATTING POINTS</b></span>
                        <table>
                            <tbody>
                                <tr ng-repeat="pointDetails in pointsArray| orderBy:'Sort'" ng-if="pointDetails.PointsInningType == 'Batting' && pointDetails.Points != '0.0'">
                                    <td>{{pointDetails.PointsTypeDescprition}}</td>
                                    <td class="numeric" >{{pointDetails.Points}}</td>
                                </tr>    
                            </tbody>
                        </table>        
                    </div>
                    <div class="col-sm-4 player_tab_table">
                        <span><b>BOWLING POINTS</b></span>
                        <table>
                            <tbody>
                                <tr ng-repeat="pointDetails in pointsArray| orderBy:'Sort'" ng-if="pointDetails.PointsInningType == 'Bowling' && pointDetails.Points != '0.0'">
                                    <td>{{pointDetails.PointsTypeDescprition}}</td>
                                    <td class="numeric" >{{pointDetails.Points}}</td>
                                </tr>    
                            </tbody>
                        </table>        
                    </div>
                    <div class="col-sm-4 player_tab_table">
                        <span><b>GENERAL POINTS</b></span>
                        <table>
                            <tbody>
                                <tr ng-repeat="pointDetails in pointsArray| orderBy:'Sort'" ng-if="pointDetails.PointsInningType == 'Fielding' && pointDetails.Points != '0.0'">
                                    <td>{{pointDetails.PointsTypeDescprition}}</td>
                                    <td class="numeric" >{{pointDetails.Points}}</td>
                                </tr>    
                            </tbody>
                        </table>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- scoring rule modal ends -->

<!-- fantasy score card -->
<div class="modal fade centerPopup" popup-handler id="fantasyScorePopup" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true" >
    <div class="modal-dialog custom_popup small_popup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Fantasy Score Card</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <div class="row">
                    <div class="col-sm-12">                            
                        <table class="white_bg table no-margin text-left ng-scope" ng-if="fields.matchDataObject.m_t != 'Test'">
                            <thead>
                                <tr>
                                    <th data-toggle="tooltip" title="Name of player" data-placement="top" >Name</th>
                                    <th data-toggle="tooltip" title="Starting Bonus" data-placement="top">SB</th>
                                    <th data-toggle="tooltip" title="Total runs scored by player in match. Fantasy points also available." data-placement="top">RUNS</th>
                                    <th data-toggle="tooltip" title="Total fours scored by player in match. Fantasy points also available." data-placement="top" >4s</th>
                                    <th data-toggle="tooltip" title="Total sixes scored by player in match. Fantasy points also available." data-placement="top" >6s</th>
                                    <th data-toggle="tooltip" title="Strike rate bonus awarded to player. Check scoring criteria for more." data-placement="top" >STB</th>
                                    <th data-toggle="tooltip" title="Batting bonus awarded to player. Check scoring criteria for more." data-placement="top" >BTB</th>
                                    <th data-toggle="tooltip" title="Duck points awarded to player. Check scoring criteria for more." data-placement="top" >DUCK</th>
                                    <th data-toggle="tooltip" title="Total wickets taken by player in match. Fantasy points also available." data-placement="top" >WK</th>
                                    <th data-toggle="tooltip" title="Total maidens bowled by player in match. Fantasy points also available." data-placement="top" >MD</th>
                                    <th data-toggle="tooltip" title="Economy bonus awarded to player. Check scoring criteria for more." data-placement="top" >EB</th>
                                    <th data-toggle="tooltip" title="Bowling bonus awarded to player. Check scoring criteria for more." data-placement="top" >BWB</th>
                                    <th data-toggle="tooltip" title="Total run outs by player in match. Fantasy points also available." data-placement="top" >RO</th>
                                    <th data-toggle="tooltip" title="Total stumpings by player in match. Fantasy points also available." data-placement="top" >ST</th>
                                    <th data-toggle="tooltip" title="Total catches by player in match. Fantasy points also available." data-placement="top" >CT</th>
                                    <th style="min-width: 80px;" data-toggle="tooltip" title="Total Fantasy Points scored by player in match." data-placement="top" >FP</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr ng-repeat="scorecard in fantasyScores.Records">
                                    <td>
                                        {{scorecard.PlayerName}}
                                    </td>
                                    <td ng-repeat="pointdata in scorecard.PointsData track by $index" >
                                        <p>{{pointdata.CalculatedPoints}}</p>
                                    </td>
                                    <td>
                                        {{scorecard.TotalPoints}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- scoring rule modal ends -->

<!-- switch team modal -->
<div class="modal fade centerPopup" popup-handler id="switchTeamModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
    <div class="modal-dialog custom_popup small_popup res_scoring">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Switch Team</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <div class="row">

                    <div class="col-sm-12 custom_checkbox" ng-if="userTeamList">

                        <ul ng-if="userTeamList">
                            <li ng-repeat="teams in userTeamList" >
                                <input type="checkbox" name="team" ng-model="teams.checked" value="{{teams.UserTeamGUID}}" ng-change="selectSwitchTeam(teams.UserTeamGUID)" />
                                {{teams.UserTeamName}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" style="float:right">
                    <button type="button" ng-disabled="switchTeamsButton == true" ng-click="switchTeam();" class="btn btn-submit theme_bgclr" style="margin-top: 5px;float: right;margin-right: 5px;">Switch Team</button>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    .tooltip {
        pointer-events: none;
    }
</style>
<!-- switch team end here -->
<?php include('innerFooter.php'); ?>