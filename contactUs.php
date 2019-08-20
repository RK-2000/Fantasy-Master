<?php 
    include('header.php');
?>

<!--Main container sec start-->
<div class="mainContainer" ng-controller="contactController" ng-cloak >
    <div class="mt-5">
        <div class="top-header-title">
            <h3> Contact Us </h3>
        </div>
        <div class="contectSec">
            <div class="container mt-4">
                <div class="contactBox">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="contectInformation">
                                <ul>
                                   <li><i class="fa fa-home"></i> BRAINYBUCKS GAMES PRIVATE LIMITED  </li>
                                    <li><i class="fa fa-phone"></i>+91-9090909090</li>
                                    <li><i class="fa fa-envelope"></i> info@example.com </li>
                                    <li><i class="fa fa-map-marker"></i>
                                        22-23-24, R S Bhandari Marg, Indore Madhya Pradesh, India, 452001 </li>
                                </ul>
                            </div>
                            <div class="listPar">
                                <p>Let's Connect</p>
                                <ul>
                                    <li><a class="fb" href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                    <li><a class="tw" href="https://twitter.com" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a class="gplus"  href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                    <li><a class="inst" href="https://www.instagram.com/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <form class="form_commen" name="contactUsForm" ng-submit="contactUS(contactUsForm)" novalidate="">
                            <div class="contactField">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Name" ng-model="contactForm.Name" name="name" ng-required="true">
                                    <div style="color:red" ng-show="submitted && contactUsForm.name.$error.required" class="form-error">
                                      *Name is required.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" ng-model="contactForm.Email" name="email" ng-required="true" ng-change="removeMassage()" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" >
                                    <div style="color:red" ng-show="submitted && contactUsForm.email.$error.required" class="form-error">
                                      *Email is required.
                                    </div>
                                    <div style="color:red" ng-show="contactUsForm.email.$error.pattern" class="form-error">
                                      *Please enter valid email.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Phone" ng-model="contactForm.PhoneNumber" name="phoneNumber" ng-required="true">
                                    <div style="color:red" ng-show="submitted && contactUsForm.phoneNumber.$error.required" class="form-error">
                                      *Phone number is required.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Subject" ng-model="contactForm.Title" name="subject" ng-required="true">
                                    <div style="color:red" ng-show="submitted && contactUsForm.subject.$error.required" class="form-error">
                                      *Subject is required.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Message" ng-model="contactForm.Message" name="message" ng-required="true" ></textarea>
                                    <div style="color:red" ng-show="submitted && contactUsForm.message.$error.required" class="form-error">
                                      *Message is required.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p style="color: red;" ng-if="errorStatus==500">{{errorMessage}}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-submit">SEND MESSAGE</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<!--Main container sec end-->
<?php include('innerFooter.php');?>