<!DOCTYPE html>
<?php 
/*if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
        $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') && $_SERVER['HTTP_HOST'] != 'localhost') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}*/
/*if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') {
header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://www.' . substr($_SERVER['HTTP_HOST'], 0).$_SERVER['REQUEST_URI']);
exit;
}*/
const VERSION = 4.3;
?>
<html lang="en" data-ng-app="FSL11" ng-cloak >
    <head>
        <title>Play Fantasy Cricket | Play Fantasy Cricket and Win Cash Daily</title>
        <meta charset="utf-8">
        <meta name="description" content="Play fantasy Cricket in Cricket World Cup and win cash daily at India's premier fantasy game platform FSL 11. Play fantasy cricket leagues and win cash prize.">
        <meta name="keywords" content="play fantasy cricket and win cash daily, play fantasy cricket and win real cash, play cricket and win cash prize daily, play fantasy cricket, play cricket and win cash prizes,fantasy cricket app, daily fantasy cricket app, Fantasy Cricket, Fantasy Cricket Website, Fantasy Cricket sports, Fantasy Cricket League,Fantasy Sports,Online Fantasy Games,Cricket Fantasy Team,Fantasy Gaming, Online Cricket,Cricket Betting Tips,Fantasy Cricket World Cup 2019, ICC Cricket World Cup Fantasy League">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
        <link rel="icon" type="png/jpg" href="assets/img/fav1.png"> 
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link href="assets/css/custom.css?version=<?= VERSION ?>" rel="stylesheet">
        <link href="assets/css/draftcustom.css?version=<?= VERSION ?>" rel="stylesheet">
        <link rel="stylesheet" href="assets/custom.css?version=<?= VERSION ?>">
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138966359-1"></script>
    </head>
    <body ng-controller="HomeController" ng-init="getMatches()" ng-cloak>
   
        <nav class="navbar navbar-expand-lg  site_header">
            <div class="container d-block">
                <div class="row align-items-center">
                    <div class="col-sm-4 col-5">
                        <a href="index" class="site-logo navbar-brand"><img src="assets/images/logo.png" alt="logo" width="150"></a>
                    </div>
                    <div class="col-sm-8 col-7">
                        <ul class="nav justify-content-end login_menu d-flex">
                            <!-- <li class="nav-item"><a class="nav-link" href="index">Login </a></li>
                            <li class="nav-item"><a class="nav-link" href="index">Signup</a></li> -->
                            <li class="nav-item"><a class="nav-link" href="authenticate?type=login"><button type="button" class="btn text-white px-4"> Login </button></a></li>
                            <li class="nav-item"><a class="nav-link" href="authenticate?type=signup"><button type="button" class="btn text-white px-4"> Sign Up </button></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>  
        <main class="hero">
            <section class="home_banner">
                <div class="container">
                    <div class="banner_content">      
                        <div class="swiper-container banner__slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <h1 class="mb-2">INDIA’S MOST FAVOURITE <br> SPORTS GAME PLATFORM!</h1>
                                    <p> FSL11 is more than just a Fantasy Sport. It’s the best way to watch the games, create your teams, win real cash, and bring the action right into your living room. </p>
                                    <!-- <a href="#download_app" onclick="window.open('#download_app','_self')" class="btn btnPrimary mb-4"> Get The App </a> -->
                                    <a href="download-app" class="btn btnPrimary mb-4"> Get The App </a>
                                </div>
                            </div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>              
                    </div>
                </div>
                <div class="img-box">
                    <img src="assets/images/banner2.png" alt="" width="510" class="img-fluid">
                </div>
            </section>
            <section class="py-5 lovedByUsers">
                <div class="container">
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <div class="col-sm-6 cont1">
                                    <div class="company_info_item mb-3 d-block p-3">
                                        <img src="assets/images/user.svg" alt="" width="25" class="pb-3">
                                        <div>
                                            <h3> Loved by 10 <br> lacs Users </h3>
                                        </div>
                                    </div>
                                    <div class="company_info_item mb-3 d-block p-3">
                                        <img src="assets/images/dice.svg" alt="" width="25" class="pb-3">
                                        <div>
                                            <h3> Multiple Drafts <br> Available </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 cont2">
                                    <div class="company_info_item mb-3 d-block p-3">
                                        <img src="assets/images/idea.svg" alt="" width="25" class="pb-3">
                                        <div>
                                            <h3> Unique User <br> Experience </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- <div class="img-box">
                              <img src="images/banner2.png" class="img-fluid">
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="dots-img">
                    <img src="assets/images/dots.png" alt="" width="160" class="img-fluid">
                </div>
            </section>
            <section id="howToPlay" class="howToPaySec text-black burger"> <!--style="background-image:url(assets/img/play-bg.jpg);"-->
                <div class="container">
                    <div class="text-center primarHead">
                        <h3 class="mb-5 comman_heading text-uppercase text-white text-center">lots of ways to win</h3>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 text-center">
                            <span class="icons text-center"><img src="assets/images/freeGame.svg" alt="" class="img-fluid pb-3"></span>
                            <h5 class="text-capitalize text-white mb-5">Play Free contest</h5>
                        </div>
                        <div class="col-sm-3 text-center">
                            <span class="icons text-center"><img src="assets/images/winCash.svg" alt="" class="img-fluid pb-3"></span>
                            <h5 class="text-capitalize text-white mb-5"> Win Big Cash prizes in public contest</h5>
                        </div>
                        <div class="col-sm-3 text-center">
                            <span class="icons text-center"><img src="assets/images/privat.svg" class="img-fluid pb-3" alt=""></span>
                            <h5 class="text-capitalize text-white mb-5"> Play Private Contest and Beat your friends </h5>
                        </div>
                        <div class="col-sm-3 text-center">
                            <span class="icons text-center"><img src="assets/images/buildOwnContest.svg" alt="" class="img-fluid pb-3"></span>
                            <h5 class="text-capitalize text-white"> Dont need to finish first to win </h5>
                        </div>
                    </div>
                </div>
            </section>
            <section class="downloadapp burger upcomingMatches" id="Download"> <!-- style="background-image:url(assets/img/download-appbg.jpg);" -->
                <div class="container">
                    <div class="row align-items-center">
                        <div class="upcoming">
                            <h2 class="comman_heading text-uppercase mb-md-5 mb-4 ml-3"> upcoming matches </h2>
                        </div>
                    </div>
                    <div class="home-slider">
                        <div class="" slick-custom-carousel ng-if="silder_visible">
                            <div class="slider lobby_page_slider" slick-custom-carousel ng-if="silder_visible" >
                                <div class="" ng-repeat="matches in MatchesList"  >
                                    <div class="slider_item ">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="getDailyFantasy" id="download_app">
                <div class="container">
                    <div class="row py-5">
                        <div class="col-sm-6">
                            <div class="img-box3">
                                <img src="assets/images/GetDailyFantasy.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="playStor">
                                <h4 class="text-dark text-uppercase pt-5" style="letter-spacing: 1px; line-height: 30px;"> get daily fantasy sports at <br> your fingertips anytime, anywhere. </h4>
                            </div>
                            <ul class="btns black_downloads mt-4">

                                <li class="mr-1" data-toggle="tooltip" title="Download Now"><a href="https://fsl11.com/android/FSL11.apk" download>
                                        <span class="icon">
                                            <img src="assets/images/android.svg" alt=""></span>
                                        <span class="text"><span class="small">FSL11 On</span><span class="big"> Android </span></span></a>
                                </li>

                                <li data-toggle="tooltip" title="Coming Soon"><a href="#">
                                        <span class="icon"><img src="assets/images/apple.svg" alt=""></span>
                                        <span class="text"><span class="small">Download On</span><span class="big"> APPSTORE </span></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bottom-banner-img py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 bottom-banner my-5">
                            <div class="logo-img text-center my-5">
                                <a href="#"><img src="assets/images/logo.png" alt="logo" width="150"></a>
                            </div>
                            <div class="freeEntry text-center text-white text-uppercase mb-5">
                                <h3 style="line-height: 40px;"> GET ₹50 BONUS AND FREE ENTRY TO <br> DAILY BONUS CONTEST ON YOUR FIRST LOGIN* </h3>
                            </div>
                            <div class="row justify-content-center">
                                <div class="signup_btn mb-5">
                                    <a href="javascript:void(0)" g-login ng-click="SocialLogin('Google')"><button type="button" class="btn-grn text-uppercase mb-3"> Login with Google </button></a>
                                    <a href="javascript:void(0)"fb-login ng-click="SocialLogin('Facebook')"><button type="button" class="btn-blu text-uppercase fb"> Login with Facebook  </button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="clientSec burger bg-light" id="testimonials">
                <div class="container">
                    <div class="bdr_lr">
                        <h3> Pro Fantasy Gamers </h3>
                    </div>
                    <div class="" id="clientSlider">

                       <div class="clientslide">
                            <p> Very good app and payments is very fast  . I get payment within 1 day . Very trusted app.
                             Always helped if i faced some problem ,got quick response . Please try it and recover your loss on other app.  </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/sumanbiswas.jpg" class="img-circle" alt="" />
                                </div>
                                <h4> Suman Biswas </h4>
                                <!-- <span> Freelancer </span> -->
                            </div>
                        </div>

                        <div class="clientslide">
                            <p> I enjoy playing fantasy cricket at FSL11. Everything is totally hassle free here and 
                                therefore I have referred a lot of friends. I become a fan of FSL11 now.  </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/team2.jpg" class="img-circle" alt="" />
                                </div>
                                <h4> Mohit Bakshi </h4>
                                <!-- <span> Freelancer </span> -->
                            </div>
                        </div>
                        <div class="clientslide">
                            <p> My first year at FSL11 is going awesome. Thank You for providing an amazing gaming 
                                experience. I tried so many platforms to play fantasy cricket and finally got what I
                                 was looking for from a long time.</p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/client.png" class="img-circle" alt="" />
                                </div>
                                <h4> Sneha Mishra </h4>
                                <!-- <span>Freelancer</span> -->
                            </div>
                        </div>
                        <div class="clientslide">
                            <p> FSL11 made my favorite sport “Cricket” more interesting. The concept of the app is unique and 
                                exciting. I would really like to recommend the app to everyone. </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/team3.png" class="img-circle" alt=""/>
                                </div>
                                <h4> Anjali Khare </h4>
                                <!-- <span>Freelancer</span> -->
                            </div>
                        </div>
                        <div class="clientslide">
                            <p> The user interface of the app is really wonderful. 
                                Easy and quick withdrawal makes this platform more trustworthy in comparing to others. </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/team1.jpg" class="img-circle" alt=""/>
                                </div>
                                <h4> Vivan Pandit</h4>
                                <!-- <span>Freelancer</span> -->
                            </div>
                        </div>
                        <div class="clientslide">
                            <p> Both website and apps of FSL11 works smoothly. My cricketing skills helped me in winning 
                                a good amount at FSL11. I have a very good experience till now and I am sure that they will 
                                make me and all other users feel delight in future as well. </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/team4.jpg" class="img-circle" alt=""/>
                                </div>
                                <h4> Rohan Patil </h4>
                                <!-- <span>Freelancer</span> -->
                            </div>
                        </div>
                        <div class="clientslide">
                            <p> This is the best fantasy app according To me, the app system is very fast and smooth, I play daily and win lot more money. Thanks To FSL11 app and the Team. </p>
                            <div class="clientImgbox">
                                <div class="clientImg">
                                    <img src="assets/images/team5.jpg" class="img-circle" alt=""/>
                                </div>
                                <h4> Santoo Sumit </h4>
                                <!-- <span>Freelancer</span> -->
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        
              <?php include('footerHome.php');?>

        </main>
    </body>

    <script>
        /***** Slick slider Testimonial ***********/

        $(document).ready(function () {

        /*------------------------------- 4.Header sticky -------------------------*/
        // Hide Header on on scroll down
        var NavBar = $('.site_header ');
        var didScroll;
        var lastScrollTop = 0;
        var navbarHeight = NavBar.outerHeight();
        $(window).scroll(function (event) {
            didScroll = true;
        });
        setInterval(function () {
            if (didScroll) {
                hasScrolled();
                didScroll = false;
            }
        }, 100);

        function hasScrolled() {
            var st = $(this).scrollTop();
            if (st + $(window).height() < $(document).height()) {
                NavBar.addClass('sticky_header');
                if (st == 0) {
                    NavBar.removeClass('sticky_header');
                }
            }
            lastScrollTop = st;
        }

        /* timer js Start */
        let daysEl = document.getElementById("days")
        let hoursEl = document.getElementById("hours")
        let minutesEl = document.getElementById("minutes")
        let secondsEl = document.getElementById("seconds")

        // Final date
        let countDownDate = new Date("May 12, 2019 00:00:00").getTime();

        // Timer
        let timer = setInterval(function () {

            let currentDate = new Date().getTime();
            let distance = countDownDate - currentDate;

            // Time calculations for days, hours, minutes and seconds
            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Fix for 0
            days = days < 10 ? "0" + days : days
            hours = hours < 10 ? "0" + hours : hours
            minutes = minutes < 10 ? "0" + minutes : minutes
            seconds = seconds < 10 ? "0" + seconds : seconds

            // Output calculations
            daysEl.innerHTML = days
            hoursEl.innerHTML = hours
            minutesEl.innerHTML = minutes
            secondsEl.innerHTML = seconds

            // If the count down is over, write some text 
            if (distance < 0) {
                clearInterval(timer)
                alert("Here we are")
            }
        }, 1000);
    });
        /* timer js END */
    </script>
</html>