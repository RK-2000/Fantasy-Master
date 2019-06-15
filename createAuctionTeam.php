<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="createAuctionTeamController" ng-init="seriesPlayers(true);getAuctionUsers();getContest();getBidPlayer();getMySquad();auctionBidTimeManagement();" ng-cloak >
    <div class="bg_light pt-5 auction-page">

        <div ng-if="BreakTime != 0" class="auctionTeam_header">
            <div class="time-left auctionTeam_div">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="sec1 col-sm-12">
                                <h2 class="dark-text">Break Time</h2>
                                <span class="live_counter" ><p id="break">{{BreakTime| secondsToDateTime | date:'mm:ss'}}s</p></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div ng-if="BreakTime == 0" class="auctionTeam_header">
            <div class="row bg-white auction_title pt-2">
                <div class=" col-sm-12 text-center mb-2">
                    <h3 ng-if="ContestInfo.AuctionStatus == 'Pending'">Auction Start In</h3> 
                    <h3 ng-if="ContestInfo.AuctionStatus != 'Pending'">Auction Status</h3> 
                    <span class="live_counter" ng-if="ContestInfo.AuctionStatus == 'Pending'"><p id="demo" timer-text="{{ContestInfo.LeagueJoinDateTime}}" timer-data="{{ContestInfo.LeagueJoinDateTime}}" ng-bind-html="clock | trustAsHtml"  match-status="{{ContestInfo.Status}}" class="ng-binding"></p></span> 
                    <span class="auction_started" ng-if="ContestInfo.AuctionStatus != 'Pending'"><p id="demo">{{ContestInfo.AuctionStatus}}</p></span> 
                </div>
            </div>
        </div>


        <div class="container-fluid" >
           <div class="auction_bg">
                <div class="row auction_toggle pt-3">
                    <div class="col-md-4">
                        <div class="auction_order h-100">
                            <div class="creatTeamHead text-center">
                                <h5 class="themeClr">Auction Order</h5>
                            </div>
                            <div class="creatTeamTable">
                            </div>
                            <div class="player_tab_table h-100">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Player Name</th>
                                            <th>Status</th>
                                            <th>Sold at</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table_scroll mCustomScrollbar h-100">
                                    <table class="table-fixed">
                                        <tbody>
                                            <tr ng-repeat="player in players track by player.PlayerGUID" ng-if="players.length > 0" style="background-color: {{(player.PlayerStatus == 'Live')?'#34AF56':''}}">
                                                <td>
                                                    <figure class="mb-0">
                                                        <img ng-src="{{player.PlayerPic}}" class="img-fluid" width="40" alt=""
                                                            ></figure>
                                                    <span>{{player.PlayerName}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="sold" ng-if="player.PlayerStatus == 'Upcoming' || player.PlayerStatus == 'Unsold'" >{{player.PlayerStatus}}</span>
                                                    <span class="light" ng-if="player.PlayerStatus == 'Live'" >{{player.PlayerStatus}}</span>
                                                    <span class="light theme_txtclr text_status"  ng-if="player.PlayerStatus == 'Sold'" >{{player.PlayerStatus}}</span>
                                                </td>
                                                <td>
                                                    <span class="light theme_txtclr text_status" ng-if="player.PlayerStatus == 'Sold'" >{{numDifferentiation(player.BidSoldCredit)}}</span>

                                                </td>
                                            </tr>
                                            <tr ng-if="players == ''">
                                                <td colspan="3">No Player Available</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="background-color: #0b1225;">
                        <div class="auction_order ">
                            <div class="time-left w-100">
                                <div class="row align-items-center justify-content-between">                                    
                                    <div class="sec1 col-md-3">
                                        <h2>Time Left</h2>
                                        <h3><span>{{counter| secondsToDateTime | date:'ss'}}</span> Sec</h3>
                                    </div>
                                    <div class="sec1 col-md-2">
                                        <img src="assets/img/icon1.png" alt=""/>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sec2">
                                            <!-- <button class="btn btnCmn btn-submit pull-right theme_bgclr" ng-click="offline();" >Offline </button> -->
                                            <button class="mb-2 btn btnCmn btn-submit pull-right theme_bgclr" ng-click="TimerStatus == 'hold' ? holdTimer() : resumeTimer()" ng-disabled="BidDisabled && user_details.UserGUID != TimerHoldeUserGUID">{{TimerStatus}} </button>
                                            <p>Remaining Time Bank <span class="theme_txtclr" ng-show="user_details.UserGUID == TimerHoldeUserGUID">{{UserHoldTime| secondsToDateTime | date:'mm:ss'}}s</span><span class="theme_txtclr" ng-if="user_details.UserGUID != TimerHoldeUserGUID">{{UserHoldTime1| secondsToDateTime | date:'mm:ss'}}s</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="time-content">
                                <figure class="mb-0">
                                    <img ng-src="{{livePlayerInfo.PlayerPic}}" class="img-fluid" width="40"  alt="" /> 
                                    <h6 class="text-white">{{livePlayerInfo.PlayerName}}</h6>
                                </figure>
                                <hr>
                                <ul class="firstsec">
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Batting Style</span> </a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Bowling Style</span> </a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Matches </span></a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Runs </span></a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">100s/50s </span></a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Bat Average</span> </a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">SR  </span></a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Wickets  </span></a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Bowl Average </span> </a></li>
                                    <li><a href="javascript:void(0)"><span class="theme_txtclr">Economy  </span></a></li>
                                </ul>
                                <ul class="secondsec">
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.PlayerBattingStyle}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.PlayerBowlingStyle}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Matches}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Runs}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Hundreds}}/{{livePlayerInfo.Fifties}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Average}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.StrikeRate}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Wickets}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.BowlAverage}}</a></li>
                                    <li><a href="javascript:void(0)">{{livePlayerInfo.Economy}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" >
                        <div class="auction_order h-100">
                            <div class="creatTeamHead text-center">
                                <h5 class="themeClr">Available Budget</h5>
                            </div>
                            <div class="player_tab_table h-100">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Users</th>
                                            <th>Available Budget</th>
                                            <th>Time Bank left</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table_scroll mCustomScrollbar h-100">
                                    <table class="table-fixed">
                                        <tbody>
                                            <tr ng-repeat="user in ContestUserList track by user.UserGUID " style="background-color:{{(user.UserGUID == BidUserInfo.UserGUID)?'green':''}};background-color:{{((user.UserGUID == TimerHoldeUserGUID) ? '#f9c02d !important' : '')}}">
                                                <td>
                                                    <i class="fa fa-circle" aria-hidden="true" style="color:{{(user.AuctionUserStatus == 'Online')?'green':'red'}};position: absolute;font-size: 13px;left: 5px;top: 5px;"></i>
                                                    <figure class="mb-0"><img ng-src="{{user.ProfilePic}}" class="img-fluid" width="30" alt=""></figure>
                                                    <span >{{user.FirstName}}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{numDifferentiation(user.AuctionBudget)}}
                                                </td>
                                                <td ng-if="user.UserGUID == TimerHoldeUserGUID">{{UserHoldTime|  secondsToDateTime | date:'mm:ss'}} s</td>
                                                <td ng-if="user.UserGUID != TimerHoldeUserGUID">{{user.AuctionTimeBank|  secondsToDateTime | date:'mm:ss'}} s</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom-cour arrow-div  mt-3 mb-3">
                    <a href="#"> <span> Click Here To Expand  </span> <span> Click Here To Collapse </span></a>
                </div>

                <div class="row pt-2 pb-5 align-items-stretch">
                    <div class="col-md-4">
                        <div class="auction_order h-100">
                            <div class="creatTeamHead text-center">
                                <h5 class="themeClr"> My Auction Assistant </h5>
                            </div>
                            <div class="player_tab_table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th> Player Name </th>
                                            <th> PURCHASE Limit </th>
                                            <th ng-show="UserTeamGUID">
                                                <span>  Off </span> 
                                                <label class="switch">
                                                    <input type="checkbox" ng-change="changeAssistantStatus(AssistantStatus)" ng-model="AssistantStatus">
                                                    <span class="slider round"></span> 
                                                </label><span>  On </span> </th>
                                                <!-- <th>Amount</th>
                                                <th>Status</th> -->
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table_scroll mCustomScrollbar">
                                    <table class="table-fixed">
                                        <tbody>
                                            <tr ng-repeat="player in Squadplayers track by player.PlayerGUID" ng-if="Squadplayers.length > 0">
                                                <td>
                                                    <figure class="mb-0"><img ng-src="{{player.PlayerPic}}" class="img-fluid" width="30" alt=""></figure>
                                                    <span>{{player.PlayerName}}</span>
                                                </td>
                                                <td class=" text-center">{{numDifferentiation(player.BidCredit)}}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="{{(AssistantStatus)?'greenbtn':'text-gray'}}">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr colspan="3" ng-if='Squadplayers.length == 0'>
                                                <td>No Player Available.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="groundBtn">
                                <a href="javascript:void(0)" class="btn btnCmn pull-right" ng-click="openSquardPlayerModal();" ng-show="ContestInfo.AuctionStatus != 'Completed'">Add Players</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-0" style="background-color: #0B1125;">
                        <div class="current-bt h-100">
                            <div class="text-center current-cont p-1">
                                <h5> Current Bid </h5>
                                <h4> <strong> {{BidAmount}} </strong></h4>
                                <h5> {{BidUserName}} </h5>
                            </div>
                            <div class="enter-bit text-center mt-3 py-3">
                                <h4>Raise Bid</h4>
                                <div class="select_option_item">
                                    <!-- data-live-search="true" class="selectpicker" -->
                                    <select class="selectpicker"  ng-model="bid" class="selectpicker">
                                        <option value="0">Select Bid Amount</option>
                                        <option ng-repeat="lakh in BidOptiones"  value="{{lakh * 100000}}">{{lakh}} Lacs</option>
                                        <option ng-repeat="cr in BidOptiones" value="{{cr * 10000000}}">{{cr}} Crs</option>
                                        <option value="1000000000">100 Crs</option>
                                    </select>
                                </div>
                                <div class="py-2">
                                    <button ng-disabled="BidDisabled" class="btn btnCmn hover_btn" style="background-color: var(--greenclr) !important;color: #fff !important;font-size: 15px !important;" ng-click="raiseBid(bid)">RAISE</button>
                                </div>
                                <div class=" bid-history mt-2" >
                                    <a href="#"  data-toggle="modal" ng-click="getBidHistory(true)" data-target="#bid-history">Bid History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="auction_order h-100">
                            <div class="creatTeamHead text-center" ng-if="AuctionTopPlayerSubmitted == 'Yes'">
                                <p class="squard_submmited">Your Squad are Submitted.</p>
                            </div>
                            <div class="creatTeamHead text-center">
                                <h5 class="themeClr"> My Squad </h5>
                            </div>
                            <div class="player_tab_table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th> Player Name </th>
                                            <th> Purchase At </th>
                                            <th ng-if="ContestInfo.AuctionStatus == 'Completed' && AuctionTopPlayerSubmitted == 'No'">Select</th>
                                            <th ng-if="ContestInfo.AuctionStatus == 'Completed' && AuctionTopPlayerSubmitted == 'Yes'">Position</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table_scroll mCustomScrollbar">
                                    <table class="table-fixed">
                                        <tbody>
                                            <tr ng-repeat="squad in MySquadPlayers track by squad.PlayerGUID" ng-if="MySquadPlayers.length > 0">
                                                <td>
                                                    <figure class="mb-0"><img ng-src="{{squad.PlayerPic}}" class="img-fluid" width="30" alt=""></figure>
                                                    <span>{{squad.PlayerName}}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{numDifferentiation(squad.BidCredit)}}
                                                </td>
                                                <td ng-if="ContestInfo.AuctionStatus == 'Completed' && AuctionTopPlayerSubmitted == 'No'">
                                                    <a href="javascript:void(0)" class="{{!squad.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="addRemoveAuctionPlayer(squad.IsAdded, squad)" >
                                                        <i class="fa fa-{{!squad.IsAdded ? 'plus' : 'times' }}"></i>
                                                    </a>
                                                </td>
                                                <td ng-if="AuctionTopPlayerSubmitted == 'Yes'">
                                                    <p class="captaine auction_captain" style="background-color: rgb(245, 166, 35) !important;" ng-show="squad.PlayerPosition == 'Captain'">C</p> 
                                                    <p class="captaine auction_captain" style="background-color: rgb(74, 144, 226) !important;" ng-show="squad.PlayerPosition == 'ViceCaptain'">VC</p> 
                                                    <p class="captaine auction_captain" ng-show="squad.PlayerPosition == 'Player'">P</p> 
                                                </td>
                                            </tr>
                                            <tr colspan="2" ng-if='MySquadPlayers.length == 0'>
                                                <td>No Player Available.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="groundBtn">
                                <button type="button" class="btn btnCmn pull-right theme_bgclr" ng-disabled="FinalAuctionPlayersCount < 1" ng-click="openPopup('selectCaptainViceCaptainModal');" ng-show="ContestInfo.AuctionStatus == 'Completed' && AuctionTopPlayerSubmitted == 'No'">Submit Team</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Select pre-draft team player start here-->
        <div class="modal fade centerPopup" id="select_pre_draft_player" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
            <div class="modal-dialog custom_popup  modal-md">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Players</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="d-flex search-selector mb-3">
                            <div class="col-md-7 col-sm-7">
                                <div class="search-form">
                                    <input class="form-control" type="search" ng-model-options="{ allowInvalid: true, debounce: 200 }" ng-model="SearchSquadPlayer" placeholder="Search Player" aria-label="Search">
                                    <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button> -->
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 d-flex justify-content-center">
                                <button class="btn btn-outline-success my-2 my-sm-0 theme_bgclr" type="button" ng-disabled="TotalAssistantPlayers == 0" ng-click="SaveTeam();">Selected players</button>
                                <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="button" ng-click="clear_pre_team_players()" >Clear</button> -->
                            </div>
                        </div>
                        <div class="player_tab_table">
                            <table class="table-fixed text-center">
                                <thead>
                                    <tr>
                                        <th>Player Name</th>
                                        <th>Amount </th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="table_scroll mCustomScrollbar">
                                <table class="table-fixed">
                                    <tbody>
                                        <tr ng-repeat="player in AllPlayers| filter:{PlayerName: SearchSquadPlayer} track by player.PlayerGUID" ng-if="players.length > 0">
                                            <td>
                                                <figure class="mb-0 mr-2"><img ng-src="{{player.PlayerPic}}" class="img-fluid" width="30" alt=""></figure>
                                                {{player.PlayerName}}
                                            </td>
                                            <td>
                                                <!-- <input type="text" name="amount" placeholder="Enter Amount" ng-model="player.BidCredit" numbers-only  /> -->
                                                <div class="select_option">
                                                    <select ng-model="player.BidCredit" >
                                                        <option value="">Select Amount</option>
                                                        <option  ng-repeat="lakh in BidOptiones" value="{{lakh * 100000}}">{{lakh}} Lacs</option>
                                                        <option  ng-repeat="cr in BidOptiones" value="{{cr * 10000000}}">{{cr}} Crs</option>
                                                        <option value="1000000000">100 Cr</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="{{!player.IsAdded ? 'greenbtn' : 'closebtn' }}" ng-click="addRemovePlayer(player.IsAdded, player)" >
                                                    <i class="fa fa-{{!player.IsAdded ? 'plus' : 'minus' }}"></i>
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

        <!--Select pre-draft team player end here-->
    </div>
</div>
<!--Main container sec end-->


<div class="modal fade centerPopup" popup-handler id="bid-history" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
    <div class="modal-dialog custom_popup modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title theme_txtclr">Bid History</h4>
            </div>
            <div class="modal-body clearfix comon_body ammount_popup">
                <div class="player_tab_table">
                    <table>
                        <thead>
                            <tr>
                                <th>Users</th>
                                <th>Bid Value</th>  
                                <th>Time Stamp</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="table_scroll" scrolly>
                        <table class="table-fixed">
                            <tbody>
                                <tr ng-repeat="bid in bidHistory" ng-if="bidHistory.length > 0">
                                    <td>
                                        <figure class="mb-0 mr-1"><img ng-src="{{bid.ProfilePic}}" class="img-fluid" width="30" alt=""></figure>
                                        <span>{{bid.FirstName}}</span>
                                    <td>
                                        {{numDifferentiation(bid.BidCredit)}}
                                    </td>
                                    <td>{{bid.DateTime| myDateFormat}}</td>
                                </tr>
                                <tr ng-if="bidHistory.length == 0">
                                    <td colspan="3">No Bid  Histroy Available.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Captain And Vice-captain-->
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
                        <select class="form-control" ng-model="Captain" ng-change="selectCaptain(Captain)" >
                            <option value="">Please Select</option>
                            <option ng-repeat="player in FinalAuctionPlayers" value="{{player.PlayerGUID}}" ng-if="player.PlayerPosition != 'ViceCaptain'" >{{player.PlayerName}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="select_cap"><label>Vice Captain</label> <figure><img width="30px" src="assets/img/vice-captain-label.png" alt="" /></figure></div>
                        <select class="form-control" ng-model="ViceCaptain" ng-change="selectViceCaptain(ViceCaptain)" >
                            <option value="">Please Select</option>
                            <option ng-repeat="player in FinalAuctionPlayers" ng-if="player.PlayerPosition != 'Captain'" value="{{player.PlayerGUID}}">{{player.PlayerName}}</option>
                        </select>
                    </div>
                    <div class="button_right text-center">
                        <a class="btn btn-submit theme_bgclr" ng-click="CreateAuctionTeam()"> Save Team </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('innerFooter.php'); ?>
