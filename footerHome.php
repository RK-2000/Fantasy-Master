<!--Footer sec start-->
<footer id="footer" class="footerSec">
    <div class="container">
        <div class="footRow mb-4">
            <div class="footCol">
                <div class="quicLink">
                    <ul>
                        <li>
                            <a href="javascript:;"> <strong> Quick Links </strong></a>
                        </li>
                        <li>
                            <a href="AboutUs"> About Us </a>
                        </li>
                        <li>
                            <a href="contactUs"> Contact Us </a>
                        </li>
                        <li>
                            <a href="Howtoplay"> How to Play </a>
                        </li>
                        <li>
                            <a href="download-app"> Download App </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footCol">
                <div class="quicLink">
                   <ul>
                        <li>
                            <a href="javascript:;"> <strong> Support </strong></a>
                        </li>
                        <li>
                            <a href="Legalities"> Legality </a>
                        </li>
                        <li>
                            <a href="Faq"> FAQs </a>
                        </li>
                        <li>
                            <a href="RefundPolicy"> Refund Policy </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footCol">
                <div class="quicLink">
                   <ul>
                        <li>
                            <a href="javascript:;"> <strong> News & Resources  </strong></a>
                        </li>
                        <li>
                            <a href="PointSystem"> Fantasy Points System  </a>
                        </li>
                        <li>
                            <a href="privacyPolicy"> Privacy Policy </a>
                        </li>
                        <li>
                            <a href="TermConditions"> Term & Conditions </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="footCol">
                <div class="searchBox">
                    <ul>
                        <li>
                            <a href="javascript:;" style="color:var(--primaryclr)"><strong>  Stay in touch with us  </strong></a>
                        </li>
                    </ul>
                    <ul class="mediaIcon">
                        <li>
                            <a class="fb"  href="<?php echo FACEBOOK_URL; ?>" target="_blank">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a class="tw"  href="<?php echo TWITTER_URL; ?>" target="_blank">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a class="gplus" href="<?php echo LINKDIN_URL; ?>" target="_blank">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a class="inst" href="<?php echo INSTAGRAM_URL; ?>" target="_blank">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <div class="footer_bottom">
        <div class="container">
            <div class="row">
               <div class="text-center col-md-10 offset-md-1">
                    <div class="copyright text-center">Copyright ?? <?php echo SITE_NAME; ?> Pvt. Ltd. All Rights Reserved.</div>
                    <p> <?php echo SITE_NAME; ?> is not affiliated in any way to and claims no association, <?php echo SITE_NAME; ?> acknowledges that the ICC, BCCI, 
                        IPL and its franchises/teams. Own all proprietary names and marks relating to the relevant tournament or 
                        competition. Residents of the states of Assam, Odisha and Telangana, and where otherwise prohibited by 
                        law are not eligible to enter <?php echo SITE_NAME; ?> leagues.</p>
            </div>
        </div>
    </div>
</footer>

<div loading class="loderBG flex-container" id="loderBG">
    <img src="assets/img/loader.svg">
</div> 
<?php include('config.php') ?>
<add-cash></add-cash>
<!--Footer sec end-->
</main>
<script src="assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.min.js"></script>

<!-- load angular -->
<script src="assets/js/angular-modules/angular.min.js"></script>
<!-- angular storage -->
<script src="assets/js/angular-modules/ngStorage.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular-cookies.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!-- MAIN CONTROLLER -->
<script src="assets/js/app.js?version=<?= VERSION ?>"></script>

<script type="text/javascript">
    var base_url = "<?php echo $base_url;?>";
    var UserGUID, UserTypeID, ParentCategoryGUID = '';
    app.constant('sportsCollection', {
        teamPlayerLimit: 11
    });
    app.constant('environment', {
        base_url: "<?php echo $base_url;?>",
        api_url: "<?php echo $api_url;?>",
        image_base_url: '<?php echo $base_url; ?>assets/img/',
        brand_name: '<?php echo SITE_NAME; ?>'
    });
    app.config(function(socialProvider){
        socialProvider.setGoogleKey("597256090889-rmtpdn95747lqh1iufuc2n6210s7bf2j.apps.googleusercontent.com");
        socialProvider.setFbKey({appId: "2261225134126697", apiVersion: "v2.11"});
    });
</script>

<!-- header controller -->

<!-- common service -->
<script src="assets/js/services/database.fac.js?version=<?= VERSION ?>"></script>
<!-- common directive -->
<script src="assets/js/directive/design-directive.lib.js?version=<?= VERSION ?>"></script>
<!-- helper -->
<script src="assets/js/helper/helper.js?version=<?= VERSION ?>"></script>
<!-- validations -->
<script src="assets/js/directive/validation.lib.js?version=<?= VERSION ?>"></script>
<!-- social ligin library -->
<script src="assets/js/angularjs-social-login/angularjs-social-login.js?version=<?= VERSION ?>"></script>


<script src="assets/js/ng-infinite-scroll.min.js"></script>

<!-- profile controller -->
<script src="assets/js/controllers/profile.js?version=<?= VERSION ?>"></script>

<!-- file upload -->
<script src="assets/js/jquery.form.js"></script>

<!-- settings controller -->
<script src="assets/js/controllers/settings.js?version=<?= VERSION ?>"></script>
<!-- Angular animate js -->
<script src="assets/js/angular-animate.min.js"></script>

<!-- settings controller -->
<script src="assets/js/controllers/contactUs.js?version=<?= VERSION ?>"></script>
<!-- Angular animate js -->
<script src="assets/js/angular-animate.min.js"></script>
<!-- toaster message -->
<script src="https://cdn.jsdelivr.net/npm/angular-toastr@2/dist/angular-toastr.tpls.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/angular-toastr@2/dist/angular-toastr.css">
<!-- datepicker -->
<link href="assets/js/angular-modules/angularjs-datepicker/src/css/angular-datepicker.css" rel="stylesheet" type="text/css" />
<script src="assets/js/angular-modules/angularjs-datepicker/src/js/angular-datepicker.js"></script>
<!-- header controller -->
<script src="assets/js/controllers/header.js?version=<?= VERSION ?>"></script>
<!-- auth controller -->
<script src="assets/js/controllers/auth.js?version=<?= VERSION ?>"></script>
<!-- point system controller -->
<script src="assets/js/controllers/pointSystem.js?version=<?= VERSION ?>"></script>
<!-- Home controller -->
<script src="assets/js/controllers/home.js?version=<?= VERSION ?>"></script>

<script src="assets/js/angular-modules/ng-file-upload-master/dist/ng-file-upload.min.js"></script> 

<script src="assets/js/angular-modules/ng-file-upload-master/dist/ng-file-upload-shim.min.js"></script>
 <script src="assets/js/ngprogress/build/ngprogress.js"></script>
<link rel="stylesheet" href="assets/js/ngprogress/ngProgress.css">
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ngInfiniteScroll/1.3.0/ng-infinite-scroll.min.js"></script>
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/jquery.hover-slider.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/custom.js?version=<?= VERSION ?>"></script>
<!-- Test -->
</body>

</html>


