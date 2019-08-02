<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="auctionLeagueController" ng-init="getContest();" ng-cloak >
    <div class="comonBg">
        <div class="creatTeam">
            <div class="container">
                <div class="row">
                    <div class="creatTeamTop">
                        <div class="col-sm-12 createTeamButton">
                            <a href="javascript:void(0)" ng-click="Back()"><i class="fa fa-arrow-left"></i> BACK </a>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                 <div class="col-sm-12 ">
                                    <div class="matchCenterbox">
                                        <img src="{{base_url}}/assets/img/champions.png" alt="{{Contest.SeriesName}}" style="width: 76px;">
                                        <div class="matchCenterBody">
                                            <p class="autionleague_head">{{Contest.SeriesName}}</p>
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
                <div class="row">
                    <div class="col-sm-9">
                        <div class="gradList mb-5">
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
                                        <cite><a href="https://api.whatsapp.com/send?text=Join {{Contest.TeamNameLocal}} V/s {{Contest.TeamNameVisitor}} {{Contest.IsPaid=='No' ? 'Free Roll' : 'Paid' }} league on FantasyCult and win ₹{{Contest.WinningAmount}}. Entry fee ₹{{Contest.EntryFee}}. Use league code {{Contest.UserInvitationCode}}. " target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></cite>
                                        <cite>
                                            <a href="mailto:?subject=Get paid to be a fan&body=Join {{Contest.TeamNameLocal}} V/s {{Contest.TeamNameVisitor}} {{Contest.IsPaid=='No' ? 'Free Roll' : 'Paid' }} league on FantasyCult and win ₹{{Contest.WinningAmount}}. Entry fee ₹{{Contest.EntryFee}}. Use league code {{Contest.UserInvitationCode}}"><i class="fa fa-envelope"></i></a>
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
                                            <a  href="javascript:void(0)" ng-click="showWinningPayout(Contest.CustomizeWinning)"><p><i class="fa fa-star" aria-hidden="true"></i> View payout breakup</p></a>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="text-right mb-5 leagueStatusParent">
                            <h2>Series Status</h2>
                            <span ng-class="{Pending:'text - danger', Live:'text - success',Cancelled:'text - danger',Completed:'text - success'}[Contest.Status]" >{{Contest.Status}}</span>
                        </div>
                    </div>
                </div>

                <!-- <div class="row" ng-if="Contest.Status == 'Pending'">
                    <div class="col-md-4 offset-sm-9">
                        <a class="btn btn-submit" ng-if="UserContestTeams.length" href="javascript:void(0)" ng-click="switchTeamPopup();">Switch Team</a>
                        <a class="btn btn-submit" href="createTeam?MatchGUID={{MatchGUID}}&League={{ContestGUID}}">Create Team</a>
                    </div>
                </div> -->

                <div class="row" ng-if="userTeams.length">
                    <div class="col-sm-12">
                        <div class="creatTeamTable custom_league">
                            <div class="creatTeamHead">
                                <p class="auctionleague_leader">LEADER BOARD</p>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" >
                                    <div class="player_tab_table">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>RANK</th>
                                                    <th>USER</th>
                                                    <th>POINTS</th>
                                                    <th ng-if="Contest.Status == 'Completed'">WON</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table_scroll" scrolly >
                                            <table class="table-fixed">
                                                <tbody >
                                                    <tr ng-repeat="team in userTeams" ng-click="showPlaying11(team.UserTeamPlayers,team.UserGUID)" class="{{SelectedUserGUID == team.UserGUID ? 'active' : '' }}">
                                                        <td class="text-center " ng-if="Contest.Status == 'Completed' && team.UserRank == '1'"><img  src="assets/img/winner-free.svg" width='20'><p>Champion</p></td>
                                                        <td class="text-center " ng-if="team.UserRank != '1' || Contest.Status != 'Completed'">#{{team.UserRank}}</td>
                                                        <td class="text-center ">{{team.Username}}(T1)</td>
                                                        <td class="text-center numeric" >{{team.TotalPoints}}</td>
                                                        <td ng-if="Contest.Status == 'Completed'" class="numeric">Rs.{{team.UserWinningAmount}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
<!--                    <div class="col-sm-8">
                        <div class="creatTeamTable lineupTeams">
                            <div class="creatTeamHead">
                                <h4>LINEUP</h4>
                                <br>
                                <button class="btn btn-submit" ng-click="openPopup('scoringRulePopup');pointSystem();">SCORING CRITERIA</button>
                                <button class="btn btn-submit" ng-click="openPopup('fantasyScorePopup');pointSystem();">FANTASY SCORECARD</button>
                                <a class="btn btn-submit" ng-if="user_details.UserGUID === SelectedUserGUID && Contest.Status == 'Pending' && SelectedUserTeamGUID" href="createTeam?Operation=edit&MatchGUID={{MatchGUID}}&UserTeamGUID={{SelectedUserTeamGUID}}" >Edit Team</a>
                                <a class="btn btn-submit" ng-if="Contest.Status != 'Pending'" href="javascript:void(0)" ng-click="bestTeamPlayers();">Best Team</a>
                                <br>

                            </div>
                        </div>
                        <div class="groundSec">
                            <div class="previewBg" style="width: 70% !important;">
                                <div class="teamPar">
                                    <h6 style="color: #fff;" ng-if="BestTeam"> Best Team </h6>
                                    <div class="teamPreviewRow wicketkeeperPosition">
                                        <span>Wicket-Keeper</span>
                                        <ul>
                                            <li ng-if="teamStructure['WicketKeeper'].player.length > 0" ng-repeat=" WicketKeeper in teamStructure['WicketKeeper'].player" >
                                                <div class="captaine" ng-if="WicketKeeper.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine" ng-if="WicketKeeper.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{WicketKeeper.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName blckbg">{{ WicketKeeper.PlayerName | limitTo: 10 }}{{WicketKeeper.PlayerName.length > 10 ? '...' : ''}} </div>
                                                <span >{{WicketKeeper.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow batsmanPosition">
                                        <span>Batsmen</span>
                                        <ul>
                                            <li ng-if="teamStructure['Batsman'].player.length > 0" ng-repeat=" Batsman in teamStructure['Batsman'].player" >
                                                <div class="captaine" ng-if="Batsman.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine" ng-if="Batsman.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{Batsman.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName blckbg">{{ Batsman.PlayerName | limitTo: 10 }}{{Batsman.PlayerName.length > 10 ? '...' : ''}} </div>
                                                <span >{{Batsman.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow bowlerPosition">
                                        <span>Bowlers</span>
                                        <ul>
                                            <li ng-if="teamStructure['Bowler'].player.length > 0" ng-repeat=" Bowler in teamStructure['Bowler'].player" >
                                                <div class="captaine" ng-if="Bowler.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine" ng-if="Bowler.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{Bowler.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName blckbg">{{ Bowler.PlayerName | limitTo: 10 }}{{Bowler.PlayerName.length > 10 ? '...' : ''}} </div>
                                                <span  >{{Bowler.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                    <div class="teamPreviewRow allrounderPosition">
                                        <span>All-Rounders</span>
                                        <ul>
                                            <li ng-if="teamStructure['AllRounder'].player.length > 0" ng-repeat=" AllRounder in teamStructure['AllRounder'].player" >
                                                <div class="captaine" ng-if="AllRounder.PlayerPosition == 'Captain'">C</div>
                                                <div class="captaine" ng-if="AllRounder.PlayerPosition == 'ViceCaptain'">VC</div>
                                                <div class="playerImg point_bg">
                                                    <img ng-src="{{AllRounder.PlayerPic}}" alt="player" class="mCS_img_loaded">
                                                </div>
                                                <div class="playerName blckbg">{{ AllRounder.PlayerName | limitTo: 10 }}{{AllRounder.PlayerName.length > 10 ? '...' : ''}} </div>
                                                <span  >{{AllRounder.Points}} </span>
                                            </li>
                                        </ul>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>-->
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
        
        <!-- Player list popup -->
         <div class="modal fade centerPopup" popup-handler id="showPlayerList" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
            <div class="modal-dialog custom_popup"> 

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><b>Top Playing 11</b></h4>
                    </div>
                    <div class="modal-body clearfix comon_body ammount_popup">
                        <div class="row">
                            <div class="payoutPar text-center dfs_custom_scroll popup__table" style="max-height: 400px !important;width: 100%">
                                <table style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Player Name</th>
                                            <th>Player Position</th>
                                            <th>Bid Amount</th>
                                            <th>Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="player in Playing11" ng-if="Playing11.length > 0">
                                            <td>
                                                <img ng-src="{{player.PlayerPic}}" width="35" alt="">{{player.PlayerName}}
                                            </td>
                                            <td>
                                                {{player.PlayerPosition}}
                                            </td>
                                            <td>
                                                ₹ {{numDifferentiation(player.BidCredit)}}
                                            </td>
                                            <td>
                                                {{player.Points}}
                                            </td>
                                        </tr>
                                        <tr ng-if="Playing11.length == 0">
                                            <td colspan="4" class="text-center">No Record Found.</td>
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
                                    <td ng-repeat = "pointdata in scorecard.PointsData track by $index" >
                                        <p ng-if="pointdata.ScoreValue != 0">{{pointdata.CalculatedPoints}}</p>
                                        <p ng-if="pointdata.ScoreValue == 0">-</p>
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
                    <div class="col-sm-12">
                        <ul ng-if="userTeamList" style="color: #fff;">
                            <li ng-repeat="teams in userTeamList" >
                                <input type="checkbox" name="team" ng-model="teams.checked" value="{{teams.UserTeamGUID}}" ng-change="selectSwitchTeam(teams.UserTeamGUID)" />
                                {{teams.UserTeamName}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" style="float:right">
                    <button type="button" ng-disabled="switchTeamsButton == true" ng-click="switchTeam();" class="btn btn-submit" style="margin-top: 5px;float: right;margin-right: 5px;">Switch Team</button>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
 .popup__table th {
    background-color: #222;
    font-weight: 700;
    text-align: left;
  }
  .popup__table td{
      text-align:left;
  }
   tr{
    cursor: pointer !important;
  }
</style>
<!-- switch team end here -->
<?php include('innerFooter.php'); ?>