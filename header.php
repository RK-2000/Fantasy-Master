<!DOCTYPE html>
<html  lang="en" data-ng-app="FANTASY" ng-cloak  >
<head>
    <?php include('MetaData.php') ?>
    <meta charset="utf-8">
    <meta name="keywords" content="play fantasy cricket and win cash daily, play fantasy cricket and win real cash, play cricket and win cash prize daily, play fantasy cricket, play cricket and win cash prizes,fantasy cricket app, daily fantasy cricket app, Fantasy Cricket, Fantasy Cricket Website, Fantasy Cricket sports, Fantasy Cricket League,Fantasy Sports,Online Fantasy Games,Cricket Fantasy Team,Fantasy Gaming, Online Cricket,Cricket Betting Tips,Fantasy Cricket World Cup 2019, ICC Cricket World Cup Fantasy League,fantasy cricket app download, best fantasy cricket app, daily fantasy cricket app download">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet">
    <link rel="icon" type="png/jpg" href="assets/img/fav1.png"> 
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.mCustomScrollbar.css">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/slick-theme.css" rel="stylesheet">
    <link href="assets/css/custom.css?version=<?= VERSION ?>" rel="stylesheet">
    <link href="assets/css/draftcustom.css?version=<?= VERSION ?>" rel="stylesheet">
    <link href="assets/css/responsive.css?version=<?= VERSION ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/custom.css?version=<?= VERSION ?>">
    <link href="https://cdn.gitcdn.link/cdn/angular/bower-material/v1.1.10/angular-material.css" rel="stylesheet">
    <script src="auctionDraft/node_modules/socket.io-client/dist/socket.io.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138966359-1"></script>
</head>

<body ng-controller="MainController" ng-cloak >

    <!--Header sec start-->
    <header class="headerSec {{!isLoggedIn ? 'withoutLogin' : '' }}" id="header" ng-controller="headerController">
        <div class="headerBottom">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg" ng-if="!isLoggedIn">
                    <a class="navbar-brand text-white signin_logo" href="{{base_url}}" ng-if="type !='mobile'"> 
                        <img src="assets/img/logo2.png" alt="" ng-if="secondLevelLocation !='authenticate'" style="position: absolute; left: 0;"> 
                        <img src="assets/img/logo.png" alt="" ng-if="secondLevelLocation == 'authenticate'">
                    </a>
                    <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navb">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                </nav>
                <nav class="navbar navbar-expand-lg" ng-if="isLoggedIn" ng-init="getNotifications()">
                    <a class="navbar-brand text-white" href="{{base_url}}lobby">
                        <img src="assets/img/logo.png" alt=""> 
                    </a>
                    <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navb">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="navb">
                            <ul class="navbar-nav rightNav" >               
                            <li class="nav-item wallet_icon"> 
                                <a href="myAccount" data-toggle="tooltip" title="Click to view wallet"><img src="assets/img/wallet.svg" alt="wallet"/></a>
                                <a href="myAccount" data-toggle="tooltip" title="Click to view wallet"><img src="assets/img/wallet-yello.svg" alt="wallet"></a>
                            </li>
                            <li class="nav-item notification">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell" aria-hidden="true"> </i>
                                    <span class="badge" ng-if="notificationCount > 0">{{notificationCount}}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <h4>Notifications Center</h4>
                                    <ul>
                                        <li style="cursor: pointer" ng-repeat="notifications in notificationList" ng-click="(notifications.StatusID == 1)?readNotification(notifications.NotificationID):''" >
                                            <div><span>{{notifications.NotificationText}}</span><span>{{notifications.NotificationMessage}}</span></div>
                                            <span class="">{{notifications.EntryDate | myDateFormat}}</span>
                                        </li>
                                        <li ng-if="notificationList.length == 0">No unread notification.</li>
                                    </ul>
                                </div>
                            </li>

                                <li class="dropdown accountDrop bdr d-flex">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-question" aria-hidden="true"  style="font-size: 25px; margin-top: -3px;"></i></a>
                                        <div class="dropdown-menu">
                                           <h4 class="dropdown_title"> Help center  </h4>
                                            <ul>
                                                <li><a href="AboutUs"> About Us  </a></li>
                                                <li><a href="contactUs"> Contact Us  </a></li>
                                                <li><a href="Faq">FAQs  </a></li>
                                                <li><a href="Legalities">Legality  </a></li>
                                                <li><a href="PointSystem">Point System  </a></li>
                                                <li><a href="privacyPolicy">Privacy Policy  </a></li>
                                                <li><a href="TermConditions">Term & Conditions  </a></li>
                                            </ul>
                                        </div>
                                </li>

                                <li class="dropdown accountDrop mr-2">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <cite><img ng-src="{{profileDetails.ProfilePic}}" style="width: 40px;"></cite> <!--<span>{{profileDetails.FirstName}}</span>--></a>
                                        <div class="dropdown-menu">
                                            <div class="profile-header px-3 border-bottom">
                                                <div class="row">
                                                   <h4 class="dropdown_title"> User settings </h4> 
                                                    <ul class="col-md-12 border-bottom px-3 pb-2">
                                                        <li> <span> BALANCE </span> : {{moneyFormat(profileDetails.TotalCash)}} </li>
                                                    </ul>

                                                    <div class="d-flex w-100 pt-2 pb-2">
                                                        <div class="col-md-6 border-right"><a  href="javascript:void(0)" ng-click="openPopup('add_money');"> <i class="fa fa-money" aria-hidden="true"></i> Add Cash </a></div>
                                                        <div class="col-md-6"><a href="javascript:void(0)" ng-click="openPopup('withdrawPopup')"><i class="fa fa-credit-card" aria-hidden="true"></i> Withdraw </a></div>
                                                    </div>
                                                </div>    
                                            </div>
                                            <ul>
                                                <li><a href="profile"> <i class="fa fa-fw fa-user"></i> Profile</a></li>
                                                <li><a href="myAccount"><i class="fa fa-user-circle"></i> My Account</a></li>
                                                <li><a href="changePassword"><i class="fa fa-user-circle"></i> Change Password</a></li>
                                                <li><a href="settings"><i class="fa fa-fw fa-gear"></i> Verify Accounts</a></li>
                                                <li><a href="javascript:void(0)" ng-click="logout()"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                                            </ul>
                                        </div>
                                </li>

                                <li class="nav-item  text-right">
                                    <b style="color:#12c451;"> {{moneyFormat(profileDetails.TotalCash)}} </b>
                                    <p style="text-align: right;"> BALANCE </p>
                                </li>
                                <li>
                                   <button type="button" class="btnTrans btn bggreen" ng-click="openPopup('add_money');" data-toggle="tooltip" title="Click to Add Cash"> Add Funds </button>
                                </li>
                            </ul>
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item {{headerActiveMenu=='lobby' ? 'active' : '' }}">
                                    <a class="nav-link" href="lobby"> DFS  </a>
                                </li>
                                <li class="nav-item {{headerActiveMenu=='leagueCenter' ? 'active' : '' }} ">
                                    <a class="nav-link" href="leagueCenter">  My League </a>
                                </li>
                                <li class="nav-item {{headerActiveMenu=='referAndEarn' ? 'active' : '' }}  ">
                                    <a class="nav-link" href="referAndEarn"> Refer &amp; Earn </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                   <div class="onlyMobileMenu " ng-if="isLoggedIn" >
                        <ul class="navbar-nav rightNav" >

                            <li class="nav-item wallet_icon"> 
                                <a href="myAccount"><img src="assets/img/wallet.svg" alt="wallet"/></a>
                                <a href="myAccount"><img src="assets/img/wallet-yello.svg" alt="wallet"></a>
                            </li>
                            <li class="nav-item">
                                <span class="mr-2">&#8377;</span> <b style="color:#12c451;"> {{profileDetails.TotalCash}}</b>

                                <button type="button" class="btnTrans btn" ng-click="openPopup('add_money');">+</button>
                            </li>

                            <li class="nav-item notification">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell" aria-hidden="true"> </i>
                                    <span class="badge" ng-if="notificationCount > 0">{{notificationCount}}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <h4> Notifications Center </h4>
                                    <ul>
                                        <li ng-repeat="notifications in notificationList">
                                            <p>{{notifications.NotificationText}}</p>
                                            <p class="text-dark">{{notifications.NotificationMessage}}</p>
                                            <span>{{notifications.EntryDate | myDateFormat}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            </ul>
                    </div>
                </div>
            </div>
        </header>
    <!--Header sec end-->
