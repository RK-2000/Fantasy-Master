<!DOCTYPE html>
<?php 
if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
        $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') && $_SERVER['HTTP_HOST'] != 'localhost') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
const VERSION = 4.7;
?>
<html lang="en" data-ng-app="FSL11" ng-cloak >
    <head>
        <title>Download Fantasy Cricket App | Daily Fantasy Cricket App</title>
        <meta name="description" content="Daily Fantasy Cricket App- Download the best Fantasy Cricket App in India, Play Fantasy Cricket and win cash prize daily in this ICC Cricket World Cup.">
        <meta name="keywords" content="play fantasy cricket and win cash daily, play fantasy cricket and win real cash, play cricket and win cash prize daily, play fantasy cricket, play cricket and win cash prizes,fantasy cricket app, daily fantasy cricket app, Fantasy Cricket, Fantasy Cricket Website, Fantasy Cricket sports, Fantasy Cricket League,Fantasy Sports,Online Fantasy Games,Cricket Fantasy Team,Fantasy Gaming, Online Cricket,Cricket Betting Tips,Fantasy Cricket World Cup 2019, ICC Cricket World Cup Fantasy League">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
        <link rel="icon" type="png/jpg" href="assets/img/fav1.png"> 
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="index_file/custom.css?version=<?= VERSION ?>">
        <link href="assets/css/custom.css?version=<?= VERSION ?>" rel="stylesheet">
        <link href="assets/css/draftcustom.css?version=<?= VERSION ?>" rel="stylesheet">
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138966359-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-138966359-1');
        </script>
    </head>

<div class="mainContainer" ng-controller="contactController" ng-cloak >
<nav class="navbar navbar-expand-lg  site_header">
            <div class="container">
                <div class="col-sm-4 col-xs-12">
                    <a href="index" class="site-logo navbar-brand"><img src="index_file/images/logo.png" alt="logo" width="150"></a>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <ul class="nav justify-content-end login_menu d-flex">
                        <!-- <li class="nav-item"><a class="nav-link" href="authenticate?type=login"><button type="button" class="btn text-white px-4"> Login </button></a></li>
                        <li class="nav-item"><a class="nav-link" href="authenticate?type=signup"><button type="button" class="btn text-white px-4"> Sign Up </button></a></li> -->
                    </ul>
                </div>
            </div>
        </nav> 

    <main class="contentSec">
        <div class="app_banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 flex_center">
                        <div class="getFanasy">
                            <h2 class="mt-5 text-white">  Download the FSL11 Mobile App to  Win Real Money </h2>

                            <h5 class="mt-3 text-white"> Download our Fantasy Cricket App, play fantasy sports anywhere and get an opportunity to win real cash prizes. </h5>

                            <h5 class="mt-3 text-white"> Get the app link via SMS </h5>

                        </div>
                        <form name="sendLinkForm" ng-submit="SendLink(sendLinkForm)" novalidate="">
                            <div class="input-field mt-4">
                                <input id="mobileNumber" type="tel" class=" text-left py-2 pl-3  mr-2" name="phoneNumber" ng-model="sendLinkForm.PhoneNumber" ng-required="true" placeholder="Enter Mobile Number">

                                <a class="send-link" href="javascript:void(0)"><button class="btn text-white py-2 px-5"><big>Send Link</big></button></a>
                                </br>
                                <span style="color:red" ng-show="downloadFormSubmitted && sendLinkForm.phoneNumber.$error.required" class="form-error">
                                    *Phone number is required.
                                </span>

                            </div>
                        </form>
                        <div>
                            <ul class="btns black_downloads mt-3">
                            <li class="mr-3">
                            <a  href="https://fsl11.com/android/FSL11.apk" download data-toggle="tooltip" title="" data-original-title="Download Now">
                                <span class="icon"><img src="index_file/images/android.svg" alt=""> </span>
                                <span class="text">
                                <span class="small">FSL11 On</span>
                                <span class="big"> Android </span>
                                </span>
                            </a></li>
                            <li>
                            <a href="#" data-toggle="tooltip" title="" data-original-title="Coming Soon">
                                <span class="icon"><img src="index_file/images/apple.svg" alt=""> </span>
                                <span class="text">
                                    <span class="small">FSL11 On</span>
                                    <span class="big"> iOS </span>
                                </span>
                            </a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-5 offset-md-1">
                        <div class="">
                            <img src="index_file/images/mobail_app.png" class="img-fluid my-5">
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <section class="stepDwnldSec py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="steToDwnld text-center mb-5">
                            <h3 class="mb-1"> STEPS TO DOWNLOAD OUR APP </h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="img-box2-1">
                            <center><img src="index_file/images/step_1.png" class="img-fluid"></center>
                        </div>
                        <div class="step1 mt-4">
                            <h3 class="text-center"> Step 1 </h3>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="img-box2-2">
                            <center><img src="index_file/images/step_2.png" class="img-fluid"></center>
                        </div>
                        <div class="step1 mt-4">
                            <h3 class="text-center"> Step 2 </h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="img-box2-3">
                            <center><img src="index_file/images/step_3.png" class="img-fluid"></center>
                        </div>
                        <div class="step3 mt-4">
                            <h3 class="text-center"> Step 3 </h3>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="img-box2-4">
                            <center><img src="index_file/images/step_4.png" class="img-fluid"></center>
                        </div>
                        <div class="step4 mt-4">
                            <h3 class="text-center"> Step 4 </h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="img-box2-2">
                            <center><img src="index_file/images/step_5.png" class="img-fluid"></center>
                        </div>
                        <div class="step1 mt-4">
                            <h3 class="text-center"> Step 5 </h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="img-box2-3">
                            <center><img src="index_file/images/step_6.png" class="img-fluid"></center>
                        </div>
                        <div class="step3 mt-4">
                            <h3 class="text-center"> Step 6 </h3>
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
                        <p> I enjoy playing fantasy cricket at FSL11. Everything is totally hassle free here and 
                            therefore I have referred a lot of friends. I become a fan of FSL11 now.  </p>
                        <div class="clientImgbox">
                            <div class="clientImg">
                                <img src="index_file/images/team2.jpg" class="img-circle" alt="" />
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
                                <img src="index_file/images/client.png" class="img-circle" alt="" />
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
                                <img src="index_file/images/team3.png" class="img-circle" alt=""/>
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
                                <img src="index_file/images/team1.jpg" class="img-circle" alt=""/>
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
                                <img src="index_file/images/team4.jpg" class="img-circle" alt=""/>
                            </div>
                            <h4> Rohan Patil </h4>
                            <!-- <span>Freelancer</span> -->
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
</div>

<?php include('footerHome.php'); ?>