<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="createTeamController" ng-init="matchCenterDetails();MatchPlayers();getContest();" ng-cloak >
    <div class="comonBg pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="creatTeamTop">
                    <div class="">
                        <div class="wrapper">
                            <div class="row">
                                <div class=" col-md-4 ">
                                    <a class="back__btn" href="javascript:void(0)" ng-click="Back()"><i class="fa fa-angle-left"></i> Back</a>
                                </div>
                                <div class="col-md-4">
                                    <div style="text-align:center" class="coman_bg_overlay">
                                        <div class="d_flex">
                                            <figure class="mb-0"><img ng-src="{{MatchesDetail.TeamFlagLocal}}" alt="{{MatchesDetail.TeamNameShortLocal}}"class="img-fluid" width="60" /></figure>

                                            <h5> {{MatchesDetail.SeriesName}} </h5>

                                            <figure class="mb-0"> <img ng-src="{{MatchesDetail.TeamFlagVisitor}}" alt="{{MatchesDetail.TeamNameShortVisitor}}" class="img-fluid" width="60"  /> </figure>
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
            <div class="row align-item-stretch">
                <div class="col-lg-6 p-lg-0">
                    <div class="creatTeamTable bg-white h-100">
                        <div class="credit">
                            <div class="text-center" timer-text="{{MatchesDetail.MatchStartDateTime}}" timer-data="{{MatchesDetail.MatchStartDateTime}}" match-status="{{MatchesDetail.Status}}" match-type="createTeam" ng-bind-html="clock | trustAsHtml" class="ng-binding" > </div>

                            <div class="text-center"> Max 7 Players From A Team </div>

                            <div class=" credit_wrapr">
                                <div class="creatTeamHead mb-0">
                                    <!-- <h4> <strong> Select Players </strong></h4> -->
                                    <!-- <button class="btn btnClone">Copy Team</button> -->
                                    <span class="palyer-select">Players </span>
                                    <p> {{teamCount}} / <small> {{teamSize}} </small> </p>
                                </div>
                                <div class="creatteam_info">
                                    <figure>
                                        <img width="50" ng-src="{{MatchesDetail.TeamFlagLocal}}" alt="{{MatchesDetail.TeamNameShortLocal}}" />   
                                    </figure>
                                    <div> <h5> {{MatchesDetail.TeamNameShortLocal}}  </h5> <p> {{teamA.count}} </p></div>
                                    <div> <h5> {{MatchesDetail.TeamNameShortVisitor}} </h5> <p> {{teamB.count}}</p></div>

                                    <figure>
                                        <img width="50" ng-src="{{MatchesDetail.TeamFlagVisitor}}" alt="{{MatchesDetail.TeamNameShortVisitor}}" />   
                                    </figure>
                                </div>

                                <div class="creatTeamHead mb-0 text-right">
                                    <span> Credit Left </span>
                                    <p class="text-align"> {{leftCredits| number : 1}}  </p>  <!-- / <small> {{totalCredits}} </small> -->
                                </div>
                            </div>    
                        </div>

                        <div class="progressContainer">
                            <div class="inactiveStepper {{(activeStepperCheck(1)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 1">1</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(2)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 2">2</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(3)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 3">3</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(4)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 4">4</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(5)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 5">5</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(6)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 6">6</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(7)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 7">7</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(8)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 8">8</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(9)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 9">9</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(10)) ? 'activeStepper' : ''}}"><span class="activeNumber" ng-show="selectedPlayers.length == 10">10</span></div>
                            <div class="inactiveStepper {{(activeStepperCheck(11)) ? 'activeStepper' : ''}}"><span class="activeNumber" style="color:{{(selectedPlayers.length < 11)?'#000 !important;':''}}" >11</span></div>
                        </div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item ">
                                <a class="nav-link {{activeTab == 'wk' ? 'active' : '' }} " data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('wk')">WK<span class="{{(teamStructure['WicketKeeper'].min <= teamStructure['WicketKeeper'].player.length)?'btn-green':''}}">{{(teamStructure['WicketKeeper'].player.length > 0)?teamStructure['WicketKeeper'].player.length:'0'}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab == 'batsmen' ? 'active' : '' }} " data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('batsmen')">BAT<span class="{{(teamStructure['Batsman'].min <= teamStructure['Batsman'].player.length)?'btn-green':''}}">{{(teamStructure['Batsman'].player.length > 0)?teamStructure['Batsman'].player.length:'0'}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab == 'rounders' ? 'active' : '' }} " data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('rounders')">AR<span class="{{(teamStructure['AllRounder'].min <= teamStructure['AllRounder'].player.length)?'btn-green':''}}">{{(teamStructure['AllRounder'].player.length > 0)?teamStructure['AllRounder'].player.length:'0'}} </span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab == 'bowlers' ? 'active' : '' }} " data-toggle="tab" href="javascript:void(0)" ng-click="gotoTab('bowlers')">BOWL<span class="{{(teamStructure['Bowler'].min <= teamStructure['Bowler'].player.length)?'btn-green':''}}">{{(teamStructure['Bowler'].player.length > 0)?teamStructure['Bowler'].player.length:'0'}}</span></a>
                            </li>

                        </ul>
                        <div class="table_scroll mCustomScrollbar pitch_view_height" id="change_onpitch">
                            <div class="tab-content">
                                <div class="tab-pane {{activeTab == 'wk' ? 'active' : '' }} " id="wk">
                                    <div class="player_tab_table">
                                        <div class="team_min_requires_span"><span>Pick 1 Wicket-Keeper</span></div>
                                        <table>
                                            <thead>
                                                <tr class="d-flex">
                                                    <th class="col-md-2"></th>
                                                    <th class="col-md-4"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerName')">Player</a><span class="sortorder" ng-show="propertyName === 'PlayerName'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PointCredits')"> Points</a><span class="sortorder" ng-show="propertyName === 'PointCredits'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerSalary')">Credits</a><span class="sortorder" ng-show="propertyName === 'PlayerSalary'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"> </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table-fixed">
                                            <tbody>
                                                <tr ng-repeat="playerDetails in players| orderBy:propertyName:reverse" ng-if="playerDetails.PlayerRole == 'WicketKeeper'" class="{{playerDetails.IsAdded==true?'active':''}} {{playerDetails.Disabled?'disabled':''}} {{playerDetails.HighCreditDiabled?'highcreditdisabled':''}}" >
                                                    <td class="flex_center col-md-2">
                                                        <figure ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''"> <img class="leagueCenterMatchImg" ng-src="{{playerDetails.PlayerPic}}"></figure>
                                                    </td>

                                                    <td class="col-md-4"> 
                                                        <p style="cursor: pointer" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''">
                                                            {{playerDetails.PlayerName}}  <br/> <small> {{playerDetails.TeamNameShort}} </small> - <small> WK </small>
                                                        </p>
                                                    </td>

                                                    <td class="numeric col-md-2"> {{playerDetails.PointCredits}} </td>
                                                    <td class="numeric col-md-2"> {{playerDetails.PlayerSalaryCredit}} </td>
                                                    <td class="col-md-2">
                                                        <a href="javascript:void(0)" class="{{!playerDetails.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? addRemovePlayer(playerDetails.PlayerGUID, playerDetails.IsAdded, playerDetails) : '' : ''">
                                                            <i class="fa fa-{{!playerDetails.IsAdded ? 'plus' : 'minus' }}" ></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class=" tab-pane {{activeTab == 'batsmen' ? 'active' : '' }}" id="batsmen">
                                    <div class="player_tab_table">
                                        <div class="team_min_requires_span"><span>Pick 3-5 Batsmen</span></div>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="col-md-2"></th>
                                                    <th class="col-md-4"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerName')">Player</a><span class="sortorder" ng-show="propertyName === 'PlayerName'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PointCredits')"> Points</a><span class="sortorder" ng-show="propertyName === 'PointCredits'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerSalary')">Credits</a><span class="sortorder" ng-show="propertyName === 'PlayerSalary'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"> </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table-fixed">
                                            <tbody>
                                                <tr ng-repeat="playerDetails in players| orderBy:propertyName:reverse" ng-if="playerDetails.PlayerRole == 'Batsman'" class="{{playerDetails.IsAdded==true?'active':''}} {{playerDetails.Disabled?'disabled':''}} {{playerDetails.HighCreditDiabled?'highcreditdisabled':''}}" >
                                                    <td class="flex_center col-md-2">
                                                        <figure ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''"> <img class="leagueCenterMatchImg" ng-src="{{playerDetails.PlayerPic}}"> </figure>
                                                    </td>
                                                    <td class="col-md-4">
                                                        <p style="cursor: pointer" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''">
                                                            {{playerDetails.PlayerName}}  <br/> <small> {{playerDetails.TeamNameShort}} </small> - <small> BAT </small>
                                                        </p>
                                                    </td>

                                                    <td class="numeric col-md-2">{{playerDetails.PointCredits}}</td>
                                                    <td class="numeric col-md-2">{{playerDetails.PlayerSalaryCredit}}</td>
                                                    <td class="col-md-2">
                                                        <a href="javascript:void(0)" class="{{!playerDetails.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? addRemovePlayer(playerDetails.PlayerGUID, playerDetails.IsAdded, playerDetails) : '' : ''">
                                                            <i class="fa fa-{{!playerDetails.IsAdded ? 'plus' : 'minus' }}" ></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane {{activeTab == 'rounders' ? 'active' : '' }} " id="rounders">
                                    <div class="player_tab_table">
                                        <div class="team_min_requires_span"><span>Pick 1-3 All-Rounders</span></div>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="col-md-2"></th>
                                                    <th class="col-md-4"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerName')">Player</a><span class="sortorder" ng-show="propertyName === 'PlayerName'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PointCredits')">Points</a><span class="sortorder" ng-show="propertyName === 'PointCredits'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerSalary')">Credits</a><span class="sortorder" ng-show="propertyName === 'PlayerSalary'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"> </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table-fixed">

                                            <tbody>
                                                <tr ng-repeat="playerDetails in players| orderBy:propertyName:reverse" ng-if="playerDetails.PlayerRole == 'AllRounder'" class="{{playerDetails.IsAdded==true?'active':''}} {{playerDetails.Disabled?'disabled':''}} {{playerDetails.HighCreditDiabled?'highcreditdisabled':''}}" >
                                                    <td class="flex_center col-md-2">
                                                        <figure ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''"> <img class="leagueCenterMatchImg" ng-src="{{playerDetails.PlayerPic}}"> </figure>
                                                    </td>

                                                    <td class="col-md-4">
                                                        <p style="cursor: pointer" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''">
                                                            {{playerDetails.PlayerName}}  <br/> <small> {{playerDetails.TeamNameShort}} </small> - <small> ALL </small>
                                                        </p></td>

                                                    <td class="numeric col-md-2">{{playerDetails.PointCredits}}</td>
                                                    <td class="numeric col-md-2">{{playerDetails.PlayerSalaryCredit}}</td>
                                                    <td class="col-md-2">
                                                        <a href="javascript:void(0)" class="{{!playerDetails.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? addRemovePlayer(playerDetails.PlayerGUID, playerDetails.IsAdded, playerDetails) : '' : ''" >
                                                            <i class="fa fa-{{!playerDetails.IsAdded ? 'plus' : 'minus' }}"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="tab-pane {{activeTab == 'bowlers' ? 'active' : '' }} " id="bowlers">
                                    <div class="player_tab_table">
                                        <div class="team_min_requires_span"><span>Pick 3-5 Bowlers</span></div>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="col-md-2"></th>
                                                    <th class="col-md-4"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerName')">Player</a><span class="sortorder" ng-show="propertyName === 'PlayerName'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PointCredits')"> Points</a><span class="sortorder" ng-show="propertyName === 'PointCredits'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2"><a href="javascript:void(0)" class="white_color" ng-click="sortBy('PlayerSalary')">Credits</a><span class="sortorder" ng-show="propertyName === 'PlayerSalary'" ng-class="{reverse: reverse}"></span></th>
                                                    <th class="col-md-2">  </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table-fixed">

                                            <tbody>
                                                <tr ng-repeat="playerDetails in players| orderBy:propertyName:reverse" ng-if="playerDetails.PlayerRole == 'Bowler'" class="{{playerDetails.IsAdded==true?'active':''}} {{playerDetails.Disabled?'disabled':''}} {{playerDetails.HighCreditDiabled?'highcreditdisabled':''}}" >
                                                    <td class="flex_center col-md-2">
                                                        <figure ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''"> <img class="leagueCenterMatchImg" ng-src="{{playerDetails.PlayerPic}}"> </figure>
                                                    </td>
                                                    <td class="col-md-4">
                                                        <p style="cursor: pointer" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? playersInfo(playerDetails) : '' : ''">
                                                            {{playerDetails.PlayerName}}  <br/> <small> {{playerDetails.TeamNameShort}} </small> - <small> BOWL </small>
                                                        </p>
                                                    </td>
                                                    <td class="numeric col-md-2">{{playerDetails.PointCredits}}</td>
                                                    <td class="numeric col-md-2">{{playerDetails.PlayerSalaryCredit}}</td>
                                                    <td class="col-md-2">
                                                        <a href="javascript:void(0)" class="{{!playerDetails.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="(!playerDetails.Disabled) ? (!playerDetails.HighCreditDiabled) ? addRemovePlayer(playerDetails.PlayerGUID, playerDetails.IsAdded, playerDetails) : '' : ''" >
                                                            <i class="fa fa-{{!playerDetails.IsAdded ? 'plus' : 'minus' }}"></i>
                                                        </a>
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
                <div class="col-lg-6  tabs_wrapr pr-0">
                    <div class="bg-white h-100 bdr-rad">
                        <div class="credit groundBtn text-left">
                            <div class="row align-items-center">
                                <div class="col-sm-3"><button class="btn btnCmn " ng-click="resetTeamStructure()" ng-disabled="teamCount == 0" >Clear</button></div>
                                <ul class="col-sm-6 nav nav-pills btn_tabs" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{activeView =='PitchView' ? 'active' : '' }}" id="PitchView-tab" data-toggle="pill" href="javscript:void(0)" ng-click="changeView('PitchView')" role="tab" aria-controls="pills-PitchView" aria-selected="true">Pitch View</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{activeView =='TeamView' ? 'active' : '' }} " id="TeamView-tab" data-toggle="pill" href="javascript:void(0)" ng-click="changeView('TeamView')" role="tab" aria-controls="pills-TeamView" aria-selected="false">Team View</a>
                                    </li>

                                </ul>
                                <div class="col-sm-3"><button class="btn btnCmn theme_bgclr   pull-right" ng-click="openSaveteam()" ng-disabled="teamCount != 11" >Submit </button></div>
                            </div>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade {{activeView =='TeamView' ? 'active show' : '' }} "  id="TeamView" role="tabpanel" aria-labelledby="TeamView-tab">
                                <div class="player_tab_table team_view_ht">
                                    <table class="table-fixed w-100">
                                        <thead>
                                            <tr>
                                                <th class="col-md-2"></th>
                                                <th class="col-md-4 "><a href="javascript:void(0)" class="white_color" ng-click="sortWithBy('PlayerName')">Player</a><span class="sortorder" ng-show="SelectedPlayerPropertyName === 'PlayerName'" ng-class="{reverse: SelectedPlayerReverse}"></span></th>
                                                <th class="col-md-2 "><a href="javascript:void(0)" class="white_color" ng-click="sortWithBy('PointCredits')"> Points</a><span class="sortorder" ng-show="SelectedPlayerPropertyName === 'PointCredits'" ng-class="{reverse: SelectedPlayerReverse}"></span></th>
                                                <th class="col-md-2 "><a href="javascript:void(0)" class="white_color" ng-click="sortWithBy('PlayerSalary')">Credits</a><span class="sortorder" ng-show="SelectedPlayerPropertyName === 'PlayerSalary'" ng-class="{reverse: SelectedPlayerReverse}"></span></th>
                                                <th class="col-md-2"> </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="table_scroll mCustomScrollbar">
                                        <table class="table-fixed w-100">                                      
                                            <tbody>
                                                <tr ng-repeat="playerDetails in selectedPlayers| orderBy:SelectedPlayerPropertyName:SelectedPlayerReverse" ng-if="selectedPlayers.length > 0" >
                                                    <td class="flex_center col-md-2">
                                                        <figure> <img class="leagueCenterMatchImg" ng-src="{{playerDetails.PlayerPic}}"> </figure>
                                                    </td>
                                                    <td class="col-md-4">
                                                        <p ng-click="playersInfo(playerDetails)" style="cursor: pointer">{{playerDetails.PlayerName}} <br/> 
                                                            <small> {{playerDetails.TeamNameShort}} </small> - <small> {{playerDetails.PositionType}} </small>
                                                        </p>
                                                    </td>
                                                    <td class="numeric col-md-2">{{playerDetails.PointCredits}}</td>
                                                    <td class="numeric col-md-2">{{playerDetails.PlayerSalaryCredit}}</td>
                                                    <td class="col-md-2">
                                                        <a href="javascript:void(0)" class="{{!playerDetails.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="addRemovePlayer(playerDetails.PlayerGUID, playerDetails.IsAdded, playerDetails)" >
                                                            <i class="fa fa-{{!playerDetails.IsAdded ? 'plus' : 'minus' }}"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                    <div ng-if="selectedPlayers.length == 0" class="no_record_found">
                                        No player selected.
                                    </div>
                                </div>  
                            </div>
                            <div class="tab-pane fade {{activeView =='PitchView' ? 'active show' : '' }} " id="PitchView" role="tabpanel" aria-labelledby="PitchView-tab">
                                <div class="groundSec">
                                    <div class="previewBg" >
                                        <div class="teamPar" ng-if="selectedPlayers.length > 0">
                                            <div class="teamPreviewRow wicketkeeperPosition">
                                                <span>Wicket-Keeper</span>
                                                <ul>
                                                    <li ng-if="teamStructure['WicketKeeper'].player.length > 0" ng-repeat=" WicketKeeper in teamStructure['WicketKeeper'].player" >
                                                        <div class="captaine captain_css" ng-if="WicketKeeper.PlayerPosition == 'Captain'">C</div>
                                                        <div class="captaine vicecaptain_css" ng-if="WicketKeeper.PlayerPosition == 'ViceCaptain'">VC</div>
                                                        <div class="playerImg point_bg">
                                                            <img ng-src="{{WicketKeeper.PlayerPic}}" alt="player">
                                                        </div>
                                                        <div class="playerName {{(WicketKeeper.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ WicketKeeper.PlayerShortName | limitTo: 10 }}{{WicketKeeper.PlayerShortName.length > 10 ? '...' : ''}} </div>
                                                        <span >{{WicketKeeper.PlayerSalary}} </span>
                                                    </li>
                                                </ul>
                                            </div> 
                                            <div class="teamPreviewRow batsmanPosition">
                                                <span>Batsmen</span>
                                                <ul>
                                                    <li ng-if="teamStructure['Batsman'].player.length > 0" ng-repeat=" Batsman in teamStructure['Batsman'].player" >
                                                        <div class="captaine captain_css" ng-if="Batsman.PlayerPosition == 'Captain'">C</div>
                                                        <div class="captaine vicecaptain_css" ng-if="Batsman.PlayerPosition == 'ViceCaptain'">VC</div>
                                                        <div class="playerImg point_bg">
                                                            <img ng-src="{{Batsman.PlayerPic}}" alt="player">
                                                        </div>
                                                        <div class="playerName {{(Batsman.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ Batsman.PlayerShortName | limitTo: 10 }}{{Batsman.PlayerShortName.length > 10 ? '...' : ''}} </div>
                                                        <span >{{Batsman.PlayerSalary}} </span>
                                                    </li>
                                                </ul>
                                            </div> 
                                            <div class="teamPreviewRow bowlerPosition">
                                                <span>Bowlers</span>
                                                <ul>
                                                    <li ng-if="teamStructure['Bowler'].player.length > 0" ng-repeat=" Bowler in teamStructure['Bowler'].player" >
                                                        <div class="captaine captain_css" ng-if="Bowler.PlayerPosition == 'Captain'">C</div>
                                                        <div class="captaine vicecaptain_css" ng-if="Bowler.PlayerPosition == 'ViceCaptain'">VC</div>
                                                        <div class="playerImg point_bg">
                                                            <img ng-src="{{Bowler.PlayerPic}}" alt="player">
                                                        </div>
                                                        <div class="playerName {{(Bowler.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ Bowler.PlayerShortName | limitTo: 10 }}{{Bowler.PlayerShortName.length > 10 ? '...' : ''}} </div>
                                                        <span >{{Bowler.PlayerSalary}} </span>
                                                    </li>
                                                </ul>
                                            </div> 
                                            <div class="teamPreviewRow allrounderPosition">
                                                <span>All-Rounders</span>
                                                <ul>
                                                    <li ng-if="teamStructure['AllRounder'].player.length > 0" ng-repeat=" AllRounder in teamStructure['AllRounder'].player" >
                                                        <div class="captaine captain_css" ng-if="AllRounder.PlayerPosition == 'Captain'">C</div>
                                                        <div class="captaine vicecaptain_css" ng-if="AllRounder.PlayerPosition == 'ViceCaptain'">VC</div>
                                                        <div class="playerImg point_bg">
                                                            <img ng-src="{{AllRounder.PlayerPic}}" alt="player">
                                                        </div>
                                                        <div class="playerName {{(AllRounder.SelectedPlayerTeam == 'A'?'blckbg':'whitebg')}}">{{ AllRounder.PlayerShortName | limitTo: 10 }}{{AllRounder.PlayerShortName.length > 10 ? '...' : ''}} </div>
                                                        <span >{{AllRounder.PlayerSalary}} </span>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                        <div ng-if="selectedPlayers.length == 0" class="emptyGround">
                                            <div class="emptyGround_msg" >No Players Selected yet</div>
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


    </div>

<!--Main container sec end-->
<!--addmoney-->
<div class="modal fade centerPopup" id="selectCaptainViceCaptainModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
    <div class="modal-dialog custom_popup small_popup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Select Captain</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <form name="SaveTeamForm" novalidate="true" >
                    <div class="form-group">
                        <div class="select_cap"><label>Captain</label> <figure><img width="30px" src="assets/img/captain-label.png" alt="" /></figure></div>
                        <select class="form-control selectpickerCaptain" ng-model="Captain" ng-change="selectCaptain(Captain)" >
                            <option value="">Please Select</option>
                            <option ng-repeat="player in selectedPlayers" value="{{player.PlayerGUID}}" ng-if="player.PlayerPosition != 'ViceCaptain' && player.PlayerRole == 'WicketKeeper'" data-content="<img src='assets/img/keeper2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" value="{{player.PlayerGUID}}" ng-if="player.PlayerPosition != 'ViceCaptain' && player.PlayerRole == 'Batsman'" data-content="<img src='assets/img/batsmen2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" value="{{player.PlayerGUID}}" ng-if="player.PlayerPosition != 'ViceCaptain' && player.PlayerRole == 'Bowler'" data-content="<img src='assets/img/bowler2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" value="{{player.PlayerGUID}}" ng-if="player.PlayerPosition != 'ViceCaptain' && player.PlayerRole == 'AllRounder'" data-content="<img src='assets/img/all_rounder2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="select_cap"><label>Vice Captain</label> <figure><img width="30px" src="assets/img/vice-captain-label.png" alt="" /></figure></div>
                        <select class="form-control selectpickerViceCapatin" ng-model="ViceCaptain" ng-change="selectViceCaptain(ViceCaptain)" >
                            <option value="">Please Select</option>
                            <option ng-repeat="player in selectedPlayers" ng-if="player.PlayerPosition != 'Captain' && player.PlayerRole == 'WicketKeeper'" value="{{player.PlayerGUID}}" data-content="<img src='assets/img/keeper2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" ng-if="player.PlayerPosition != 'Captain' && player.PlayerRole == 'Batsman'" value="{{player.PlayerGUID}}" data-content="<img src='assets/img/batsmen2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" ng-if="player.PlayerPosition != 'Captain' && player.PlayerRole == 'Bowler'" value="{{player.PlayerGUID}}" data-content="<img src='assets/img/bowler2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                            <option ng-repeat="player in selectedPlayers" ng-if="player.PlayerPosition != 'Captain' && player.PlayerRole == 'AllRounder'" value="{{player.PlayerGUID}}" data-content="<img src='assets/img/all_rounder2.png'  width='30' >{{player.PlayerName}}" >{{player.PlayerName}}</option>
                        </select>
                    </div>
                    <div class="button_right text-center">
                        <input type="hidden" name="UserTeamPlayers" value="{{selectedPlayers}}">
                        <a class="btn btn-submit theme_bgclr" ng-click="SaveTeam()">Save Team</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--addmoney-->
<div id="playersInfoModal" class="sidenav text-white" popup-handler>
    <a href="javascript:void(0)" class="closebtn" ng-click="closeNav()">×</a>

    <div class="container-fluid">
        <div class="profileHeader fantasyHist row">
            <div class="profileSocial col-md-3">
                <div class="profileImg">
                    <img alt="" class="img-circle" ng-src="{{playerDetails.PlayerPic}}">
                </div>
                <h3 class="main-block-header ng-binding">{{playerDetails.PlayerName}}</h3>
            </div>

            <div class="listOfUser col-md-9">
                <ul>
                    <li><span>Full Name : </span> {{playerDetails.PlayerName}}</li>
                    <li><span>Playing Role : </span> {{!playerDetails.PlayerRole ? '-' : playerDetails.PlayerRole}}</li>
                    <li><span>Batting Style : </span>{{!playerDetails.PlayerBattingStyle ? '-' : playerDetails.PlayerBattingStyle}}</li> 
                </ul>

                <ul>
                    <li><span>Team : </span>{{playerDetails.PlayerCountry}}</li>
                    <li><span>Bowling Style : </span>{{!playerDetails.PlayerBowlingStyle ? '-' : playerDetails.PlayerBowlingStyle}}</li>
                    <li><span>Selected % In Match : </span>{{playerDetails.PlayerSelectedPercent}}%</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="fantasyHist mb-5">
        <!-- <ul class="nav nav-tabs matchTabs justify-content-between">
            <li class="nav-item">
                <a class="nav-link {{activePlayerTab=='play' ? 'active' : '' }} " data-toggle="tab" href="javascript:void(0)" ng-click="playerDetailsTab('play')">PLAYER STAT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{activePlayerTab=='Bio' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="playerDetailsTab('Bio')">BIO</a>
            </li>
        </ul> -->
        <div class="tab-content">
            <div class="tab-pane {{activePlayerTab=='play' ? 'active' : '' }}" id="play">
                <h4>Batting</h4>
                <div class="matchtypeHead">
                    <ul>
                        <li>TYPE</li>
                        <li>MAT</li>
                        <li>INNS</li>
                        <li>RUNS</li>
                        <li>BF</li>
                        <li>AVG</li>
                        <li>STR</li>
                        <li>50s</li>
                        <li>100s</li>
                        <li>HS</li>
                        <li>4s</li>
                        <li>6s</li>
                        <li>NOs</li>
                        <li>CT</li>
                        <li>ST</li>


                    </ul>
                </div>

                <div class="matchtypeBody">
                    <ul ng-repeat="(key,val) in PlayerBattingStats">
                        <li>{{key}}</li>
                        <li>{{!val.Matches ? '-' : val.Matches }}</li>
                        <li>{{!val.Innings ? '-' : val.Innings}}</li>
                        <li>{{!val.Runs ? '-' : val.Runs}}</li>
                        <li>{{!val.Balls ? '-' : val.Balls }}</li>
                        <li>{{!val.Average ? '-' : val.Average}}</li>
                        <li>{{!val.StrikeRate ? '-' : val.StrikeRate}}</li>
                        <li>{{!val.Fifties ? '-' : val.Fifties}}</li>
                        <li>{{!val.Hundreds ? '-' : val.Hundreds}}</li>
                        <li>{{!val.HighestScore ? '-' : val.HighestScore}}</li>
                        <li>{{!val.Fours ? '-' : val.Fours }}</li>
                        <li>{{!val.Sixes ? '-' : val.Sixes}}</li>
                        <li>{{!val.NotOut ? '-' : val.NotOut}}</li>
                        <li>{{!val.Catches ? '-' : val.Catches}}</li>
                        <li>{{!val.Stumpings ? '-' : val.Stumpings}}</li>
                    </ul>


                </div>

                <h4 class="mt-4">Bowling</h4>
                <div class="matchtypeHead">
                    <ul>
                        <li>TYPE</li>
                        <li>MAT</li>
                        <li>INNS</li>
                        <li>WK</li>
                        <li>RG</li>
                        <li>BALLS</li>
                        <li>AVG</li>
                        <li>ER</li>
                        <li>STR</li>
                        <li>4W</li>
                        <li>5W</li>
                        <li>10W</li>
                    </ul>
                </div>

                <div class="matchtypeBody">
                    <ul ng-repeat="(k,v) in PlayerBowlingStats">
                        <li>{{k}}</li>
                        <li>{{!v.Matches ? '-' : v.Matches }}</li>
                        <li>{{!v.Innings ? '-' : v.Innings}}</li>
                        <li>{{!v.Wickets ? '-' : v.Wickets}}</li>
                        <li>{{!v.Runs ? '-' : v.Runs }}</li>
                        <li>{{!v.Balls ? '-' : v.Balls}}</li>
                        <li>{{!v.Average ? '-' : v.Average}}</li>
                        <li>{{!v.Economy ? '-' : v.Economy}}</li>
                        <li>{{!v.StrikeRate ? '-' : v.StrikeRate}}</li>
                        <li>{{!v.FourPlusWicketsInSingleInning ? '-' : v.FourPlusWicketsInSingleInning }}</li>
                        <li>{{!v.FivePlusWicketsInSingleInning ? '-' : v.FivePlusWicketsInSingleInning}}</li>
                        <li>{{!v.TenPlusWicketsInSingleInning ? '-' : v.TenPlusWicketsInSingleInning}}</li>
                    </ul>


                </div>
            </div>
            <div class="tab-pane {{activePlayerTab=='Bio' ? 'active' : '' }}" id="Bio">
                <div class="listOfUser">
                    <ul>
                        <li><span>Full Name</span> {{playerDetails.PlayerName}}</li>
                    </ul>

                    <ul>
                        <li><span>Team</span>{{playerDetails.PlayerCountry}}</li>
                        <li><span>Playing Role</span> {{!playerDetails.PlayerRole ? '-' : playerDetails.PlayerRole}}</li>
                    </ul>

                    <ul>
                        <li><span>Batting Style</span>{{!playerDetails.PlayerBattingStyle ? '-' : playerDetails.PlayerBattingStyle}}</li>
                        <li><span>Bowling Style</span>{{!playerDetails.PlayerBowlingStyle ? '-' : playerDetails.PlayerBowlingStyle}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
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
                            <td><p class="ng-binding"> {{moneyFormat(Contest.EntryFee)}}</p></td>
                        </tr>
                    </tbody>
                </table>
                <form novalidate="" name="joinContestForm" ng-submit="JoinContest(joinContestForm)">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <select class="form-control selectpickerJoinLeague" ng-model="join.UserTeamGUID" name="UserTeamGUID" ng-required="true" >
                                    <option value="">Please Select</option>
                                    <option value="{{teams.UserTeamGUID}}" ng-repeat="teams in userTeams.Records">{{teams.UserTeamName}}</option>
                                </select>
                                <div style="color:red" ng-show="joinSubmitted && joinContestForm.UserTeamGUID.$error.required" class="form-error">
                                    *Please select team to join contest.
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <button class="btn btn-submit theme_bgclr">Join</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<!--User team list to join contest-->
<?php include('innerFooter.php'); ?>
<style> 
    .pitch_view_height{
        height: 100%;
        max-height: 748px !important;
    }
    #change_onpitch.table_scroll:not(.pitch_view_height) {
        max-height: 435px !important;
    }
</style>
<script>
    $('#PitchView-tab').on('click', function () {
        $('#change_onpitch').addClass('pitch_view_height');
    });
    $('#TeamView-tab').on('click', function () {
        $('#change_onpitch').removeClass('pitch_view_height');
    });
</script>