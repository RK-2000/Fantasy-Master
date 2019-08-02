<?php include('header.php'); ?>
<!--Main container sec start-->
<div class="mainContainer" ng-controller="authController" ng-cloak >
    <section class="bannerSec ">
        <div class="container">
            <h3 class="tagline"> Anybody Can Win </h3>
            <div class="bannerConte row">
                <div class="col-md-6 p-0 d-flex">
                    <div class="bannertext w-100">
                        <h1> Play & Win </h1>
                        <p> Login and Make Your Dreams Come True </p>

                        <div class="form-group">
                            <!-- social login  -->
                            <button class="loginBtn google-plus text-white" g-login ng-click="SocialLogin('Google')"  > Login with Google </button>
                        </div>
                        <div class="form-group">
                            <button class="loginBtn facebook text-white" fb-login ng-click="SocialLogin('Facebook')"> Login with Facebook </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-0">
                    <div class="loginSignup">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='login' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="changeTab('login')">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='signup' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="changeTab('signup')" >Sign Up</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <div class="tab-pane fade {{activeTab=='signup' ? 'active show' : '' }} " id="signup">
                                <form class="form_commen" id="signup" name="signup" ng-submit="signUp(signup)" novalidate="">
                                    <div class="form-group">
                                        <input type="text" name="fullName" ng-model="formData.FirstName" placeholder="Full Name" class="form-control" ng-change="removeMassage()" ng-pattern="/^[a-zA-Z\s]*$/" ng-required="true" >
                                        <div style="color:red" ng-show="signupSubmitted && signup.fullName.$error.required" class="form-error">
                                            *Name is required.
                                        </div>
                                        <div style="color:red" ng-show="signup.fullName.$error.pattern" class="form-error">
                                            *Please enter valid name.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" name="email" ng-model="formData.Email" placeholder="Email Address" class="form-control" ng-change="removeMassage()" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" ng-required="true" >
                                        <div style="color:red" ng-show="signupSubmitted && signup.email.$error.required" class="form-error">
                                            *Email is required.
                                        </div>
                                        <div style="color:red" ng-show="signup.email.$error.pattern" class="form-error">
                                            *Please enter valid email.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Mobile No." name="Mobile" ng-model="formData.PhoneNumber" class="form-control" ng-change="removeMassage()" ng-required="true" numbers-only maxlength="10">
                                        <div style="color:red" ng-show="signupSubmitted && signup.Mobile.$error.required" class="form-error">
                                            *Mobile no. is required.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="referralcode" ng-model="formData.ReferralCode" placeholder="Invite / Referral Code (Optional)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" ng-model="formData.Password" placeholder="Password" class="form-control" ng-change="formData.confrim_password='';removeMassage()"  ng-pattern="/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9@_%]+)$/" ng-minlength="6" ng-required="true" >
                                        <div style="color:red" ng-show="signupSubmitted && signup.password.$error.required" class="form-error">
                                            *Password is required.
                                        </div>
                                        <div style="color:red" ng-show="signup.password.$error.pattern || signup.password.$error.minlength" class="form-error">*Password must have one capital, one number and 6 character long.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confrim_password" ng-model="formData.confrim_password" compare-to="formData.Password" placeholder="Confirm Password" class="form-control" ng-change="removeMassage()" ng-required="true" >
                                        <div style="color:red" ng-show="signupSubmitted && signup.confrim_password.$error.required" class="form-error">
                                            *Confirm Password is required.
                                        </div>
                                        <div style="color:red" ng-show="!signup.confrim_password.$error.required && signup.confrim_password.$error.compareTo">Your passwords must match.</div>
                                    </div>
                                    <div class="form-group checkbox_custom">
                                        <div class="customCheckbox">
                                            <input type="checkbox" ng-required="true" name="agree" ng-model="formData.isagree" ><label> I agree to Fantasy</label>
                                        </div>
                                        <a href="javascript:void(0)" ng-click="openPopup('termsandconditionPopup')">T&amp;C's </a>
                                        <div class="form-error" style="color:red" ng-show="signupSubmitted && signup.agree.$error.required">*You must need to agree with condition.</div>
                                        <div class="form-error" style="color:red" ng-if="sign_error">*{{errormessage}}</div>
                                        <div class="form-error" style="color:green">{{signupSuccess}}</div>
                                    </div>
                                    <div class="form-group">
                                        <button class="loginBtn text-dark"> Sign Up </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane {{activeTab=='login' ? 'active' : '' }}" id="login">
                                <div class="loginbox">
                                    <ul>
                                        <li>
                                            <input type="radio" id="f-option" name="selector" ng-model="loginType" value="email">
                                            <label for="f-option"> Using Password </label>
                                            <div class="check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="s-option" name="selector" ng-model="loginType" value="phone">
                                            <label for="s-option"> Using OTP </label>
                                            <div class="check"><div class="inside"></div></div>
                                        </li>
                                    </ul>
                                </div>
                                <form ng-if="loginType == 'email'" class="form_commen" id="signin" name="signin" ng-submit="signIn(signin)" novalidate="" autocomplete="false">
                                    <div class="form-group">
                                        <input type="text" placeholder="Email/Phone no." name="Keyword" ng-model="loginData.Keyword" class="form-control" ng-change="removeMassage()" ng-required="true">
                                        <div style="color:red" ng-show="LoginSubmitted && signin.Keyword.$error.required" class="form-error">
                                            *Field is required.
                                        </div>
                                        <!-- <div style="color:red" ng-show="signin.Keyword.$error.pattern" class="form-error">
                                            *Please enter valid email.
                                        </div> -->
                                    </div>
                                    <div class="form-group">
                                        <input type="password" placeholder="Password" name="Password" ng-model="loginData.Password" ng-change="removeMassage()"  class="form-control" ng-required="true">
                                        <div style="color:red" ng-show="LoginSubmitted && signin.Password.$error.required" class="form-error">
                                            *Password is required.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="customCheckbox" style="float: left;">
                                            <input type="checkbox" name="remeber_me" ng-model="loginData.remeber_me"><label> Remember Me </label>
                                        </div>

                                        <div class="text-right">
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#forgotPassword" data-dismiss="modal">Forgot Password ?</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="loginBtn" >Log In</button>
                                    </div>
                                </form>
                                <form ng-if="loginType == 'phone'" class="form_commen" id="signinphone" name="signinphone" ng-submit="(isOtp)?signIn(signinphone):OtpSignIn(signinphone)" novalidate="" autocomplete="false">
                                    <div class="form-group" ng-if="!isOtp">
                                        <input type="text" placeholder="Phone no." name="phone" ng-model="loginDataPhone.PhoneNumber" class="form-control" ng-change="removeMassage()" numbers-only maxlength="10" ng-required="true">
                                        <div style="color:red" ng-show="LoginSubmitted && signinphone.phone.$error.required" class="form-error">
                                            *Mobile no. is required.
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="isOtp">
                                        <input type="text" placeholder="OTP" name="otp" ng-model="loginDataPhone.OTP" ng-change="removeMassage()"  class="form-control" ng-required="true" numbers-only>
                                        <div style="color:red" ng-show="LoginSubmitted && signinphone.otp.$error.required" class="form-error">
                                            *OTP is required.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-right">
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#forgotPassword" data-dismiss="modal">Forgot Password ?</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="loginBtn" >Log In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- loginsignup end --->

                </div>
            </div>   
        </div>
    </section>


    <!-- T&C modal start -->
    <div class="modal fade" id="termsandconditionPopup" popup-handler >
        <div class="modal-dialog loginModal">
            <div class="modal-content">
                <div class="modal-body clearfix">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <div >
                        <div class="primarHead text-center mb-30">
                            <h1 style="color: #fac733 !important;">Terms and Conditions</h1>
                        </div>
                        <div class="termContent mCustomScrollbar">

                            <p>The applicable laws in Assam, Odisha and Telangana State restricts player from using the services offered on our website. There may be penalties imposed on such players by the State Government or Central Government of India as the case maybe.</p>

                            <h4>Usage of FSL11</h4>

                            <p>1. Any player visiting FSL11 website for the use of online information and database recovery services, including, participating in the various contests and games (including fantasy games), gaming services, being conducted on FSL11 ("Contests") shall be bound by these Terms and Conditions, and all other rules, regulations and terms of use referred to or offered by FSL11 in relation to any FSL11 Services.</p>

                            <p>2. FSL11 has liberty to modify Terms and Conditions, regulations and terms of use referred to or offered by FSL11 in relation to any FSL11 Services, at any time, by posting same on FSL11. Use of FSL11 has Player's acceptance of such Terms and Conditions, rules and terms of use referred to or offered by FSL11 in relation to any FSL11 Services, as may be amended from time to time. FSL11 may also notify Player of any change or modification in these Terms and Conditions, rules, regulations and terms of use referred to or offered by FSL11, by way of sending an email to Player's registered email address or posting notifications in Player accounts. Player might then exercise options offered in such an email or notification to indicate non-acceptance of modified Terms and Conditions, rules, regulations and terms of use referred to or offered by FSL11. If such options are not exercised by Player within time frame prescribed in email or notification, Player will be deemed to have accepted modified Terms and Conditions, rules, regulations and terms of use referred to or offered by FSL11.</p>

                            <p>3. Certain FSL11 Services being offered on FSL11 may be subject to additional rules and regulations set down in that respect. To the extent that these Terms and Conditions are inconsistent with additional conditions set down, additional conditions shall prevail.</p>
                            <p>4. FSL11 may, at its sole and absolute discretion:
                            <BLOCKQUOTE type="disc">
                                <li>Restrict, suspend, or terminate any Player's access to all or any part of FSL11 or FSL11 Services;</li>
                                <li>Change, suspend, or discontinue all or any part of FSL11 Services;</li>
                                <li> Reject, move, or remove any material that may be submitted by a Player;</li>
                                <li>Move or remove any content that is available on FSL11;</li>
                                <li>Deactivate or delete a Player's account and all related information and files on account; </li>
                                <li>Establish general practices and limits concerning use of FSL11; vii) Revise or make additions to the roster of players available for opting in a Contest on account of revisions to the roster of players involved in the relevant Sports Event;</li>
                                <li>Assign its rights and liabilities to all Player accounts hereunder to any entity (post intimation of such assignment shall be sent to all Players to their registered email ids)</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>5. If any Player breaches, or FSL11 reasonably believes that such Player has breached these Terms and Conditions, or has illegally or inappropriately used FSL11 or the FSL11 Services, FSL11 may, at its sole and absolute discretion, and without any notice to the Player, restrict, suspend or terminate such Player's access to all or any part of FSL11 or the FSL11 Services, deactivate or delete the Player's account and all related information on the account, delete any content posted by the Player on FSL11 and further, take technical and legal steps as it deems necessary.</p>

                            <p>6. If FSL11 charges its Players a platform fee in respect of any FSL11 Services, FSL11 shall, without delay, repay such platform fee in the event of suspension or removal or the Player's account or FSL11 Services on account of any negligence or deficiency on the part of FSL11, but not if such suspension or removal is effected due to:
                            <BLOCKQUOTE type="disc">
                                <li>any breach or inadequate performance by the Player of any of these Terms and Conditions; or </li>
                                <li>all situations beyond the reasonable control of FSL11.</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>7. Players consent to receiving communications such as announcements, administrative messages and advertisements from FSL11 or any of its partners, licensors or associates.</p>

                            <h4>Intellectual Property</h4>

                            <p>1. FSL11 includes a combination of content created by FSL11, its partners, licensors, associates and/or Players. The intellectual property rights ("Intellectual Property Rights") in all software underlying FSL11 and the FSL11 Services and material published on FSL11, including (but not limited to) games, Contests, advertisements, software, photographs, written content, images, graphics, marks, illustrations, audio, logos or video clippings and Flash animation, is owned by FSL11, its partners, licensors and/or associates. Players may not transmit, publish, participate in the transfer or sale of, reproduce, create derivative works of, distribute, publicly perform, publicly display, or in any way exploit any of the materials or content on FSL11 either in whole or in part without express written license from FSL11.</p>

                            <p>2. Players may request permission to use any FSL11 content by writing in to FSL11 Support.</p>

                            <p>3. Players confirm and undertake to not display or use of the names, marks, logos, trademarks, labels, copyrights or intellectual and proprietary rights of any third party on FSL11. Players agree to cover and hold harmless FSL11, its directors, affiliates, employees and assigns against all costs, loss and harm including towards litigation costs and counsel fees, damages, in respect of any third party claims that may be initiated including for infringement of Intellectual Property Rights arising out of such display or use of the names, marks, logos, trademarks, labels, copyrights or intellectual rights on FSL11, by such Player or through the Player's commissions or omissions.</p>

                            <p>4. Players hereby grant to FSL11 and its affiliates, partners, licensors and associate a worldwide, irrevocable, royalty-free, non-exclusive, sub-licensable license to use, reproduce, create derivative works of, distribute, publicly perform, publicly display, transfer, transmit, and/or publish Players' Content for any of the following purposes:
                            <BLOCKQUOTE type="disc">
                                <li>displaying Players' Content on FSL11</li>
                                <li>distributing Players' Content, either by machine or via other media, to other Players looking for downloading or else get it, and/or</li>
                                <li> storing Players' Content in a remote database accessible by end players, for a charge.</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>5. This license shall apply to the distribution and the storage of Players' Content in any form, medium, or technology.</p>

                            <p>6. All names, marks, logos, trademarks, labels, proprietary rights and copyrights or intellectual rights on FSL11 belonging to any person (including Player), entity or third party are recognized as proprietary to the respective owners and any claims, controversy or issues against these names, logos, marks, labels, trademarks, copyrights or intellectual and proprietary rights must be directly addressed to the respective parties under notice to FSL11.</p>

                            <h4>Third Party Sites, Services and Products</h4>

                            <p>1. FSL11 may contain links to other Internet sites owned and operated by third parties. Players' use of each of those sites is subject to the conditions, if any, posted by the sites. FSL11 does not exercise control over any Internet sites apart from FSL11, and cannot be held responsible for any content residing in any third party Internet site. FSL11's inclusion of third-party content or links to third-party Internet sites is not an endorsement by FSL11 of such third-party Internet site.</p>

                            <p>2. Players' correspondence, transactions or related activities with third parties, including payment providers and verification service providers, are solely between the Player and that third party. Players' correspondence, transactions and usage of the services of such third party shall be subject to the terms and conditions, policies and other service terms adopted/implemented by such third party, and the Player shall be solely responsible for reviewing the same prior to transacting or availing of the services of such third party. Player agrees that FSL11 will not be responsible or liable for any loss or damage of any sort incurred as a result of any such transactions with third parties. Any questions, complaints, or claims related to any third party product or service should be directed to the appropriate vendor.</p>

                            <p>3. FSL11 contains content that is created by FSL11 as well as content offered by third parties. FSL11 does not guarantee the accuracy, integrity, quality of the content offered by third parties and such content may not relied upon by the Players in utilizing the FSL11 Services offered on FSL11 including while participating in any of the contests hosted on FSL11.</p>

                            <h4>FSL11 Players Conduct</h4>

                            <p>1. Players agree to abide by these Terms and Conditions and all other rules, regulations and terms of use of the Website. If Player does not abide by these Terms and Conditions and all other rules, regulations and terms of use, FSL11 may, at its sole and absolute discretion, take necessary remedial action, including but not limited to:
                            <BLOCKQUOTE type="disc">
                                <li>restricting, suspending, or terminating any Player's access to all or any part of FSL11 Services;</li>
                                <li>deactivating or deleting a Player's account and all related information and files on the account. Any amount remaining unused in the Player's Game account or Winnings Account on the date of deactivation or deletion shall be transferred to the Player's bank account on record with FSL11 subject to a processing fee (if any) applicable on such transfers as set out herein; or</li>
                                <li>refraining from awarding any prize to such Player.</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>2. Players agree to offer true, accurate, current and complete information at the time of registration and at all other times (as needed by FSL11). Players further agree to update and keep updated their registration information.</p>

                            <p>3. A Player shall not register or operate more than one Player account with FSL11.</p>

                            <p>4. Players agree to ensure that they can receive all communication from FSL11 by marking e-mails from FSL11 as part of their "safe senders" list. FSL11 shall not be held liable if any e-mail remains unread by a Player as a result of such e-mail getting delivered to the Player's junk or spam folder.</p>

                            <p>5. Any password issued by FSL11 to a Player may not be revealed to anyone else. Players may not use anyone else's password. Players are responsible for maintaining the confidentiality of their accounts and passwords. Players agree to immediately notify FSL11 of any unauthorized use of their passwords or accounts or any other breach of security.</p>

                            <p>6. Players agree to exit/log-out of their accounts at the end of each session. FSL11 shall not be responsible for any loss or damage that may result if the Player fails to comply with these needs.</p>

                            <p>7. Players agree not to use cheats, exploits, automation, software, bots, hacks or any unauthorised third party software designed to modify or interfere with FSL11 Services and/or FSL11 experience or assist in such activity.</p>

                            <p>8. Players agree not to copy, modify, rent, lease, loan, sell, assign, distribute, reverse engineer, grant a security interest in, or otherwise transfer any right to the technology or software underlying FSL11 or FSL11 Services.</p>

                            <p>9. Players agree that without FSL11's express written consent, they shall not modify or cause to be modified any files or software that is part of FSL11's Services.</p>

                            <p>10. Players agree not to disrupt, overburden, or aid or assist in the disruption or overburdening of (a) any computer or server used to offer or support FSL11 or the FSL11 Services (each a "Server"); or (2) the enjoyment of FSL11 Services by any other Player or person.</p>

                            <p>11. Players agree not to institute, assist or become involved in any type of attack, including without limitation to distribution of a virus, denial of service, or other attempts to disrupt FSL11 Services or any other person's use or enjoyment of FSL11 Services.</p>

                            <p>12. Players shall not attempt to gain unauthorised access to the Player accounts, Servers or networks connected to FSL11 Services by any means other than the Player interface offered by FSL11, including but not limited to, by circumventing or modifying, attempting to circumvent or modify, or encouraging or assisting any other person to circumvent or modify, any security, technology, device, or software that underlies or is part of FSL11 Services.</p>

                            <p>13. Without limiting the foregoing, Players agree not to use FSL11 for any of the following:
                            <BLOCKQUOTE type="disc">
                                <li>To engage in any obscene, offensive, indecent, racial, communal, anti-national, objectionable, defamatory or abusive action or communication;</li>
                                <li>To harass, stalk, threaten, or otherwise violate any legal rights of other individuals;</li>
                                <li>To publish, post, upload, e-mail, distribute, or disseminate (collectively, "Transmit") any inappropriate, profane, defamatory, infringing, obscene, indecent, or unlawful content;</li>
                                <li>To Transmit files that contain viruses, corrupted files, or any other similar software or programs that may damage or adversely affect the operation of another person's computer, FSL11, any software, hardware, or telecommunications equipment;</li>
                                <li>To advertise, offer or sell any goods or services for any commercial purpose on FSL11 without the express written consent of FSL11;</li>
                                <li>To Transmit content regarding services, products, surveys, contests, pyramid schemes, spam, unsolicited advertising or promotional materials, or chain letters;</li>
                                <li>To advertise, offer or sell any goods or services for any commercial purpose on FSL11 without the express written consent of FSL11;</li>
                                <li>To Transmit content regarding services, products, surveys, contests, pyramid schemes, spam, unsolicited advertising or promotional materials, or chain letters;</li>
                                <li>To download any file, recompile or disassemble or otherwise affect our products that you know or reasonably should know cannot be legally obtained in such manner;</li>
                                <li>To falsify or delete any author attributions, legal or other proper notices or proprietary designations or labels of the origin or the source of software or other material;</li>
                                <li>To restrict or inhibit any other player from using and enjoying any public area within our sites;</li>
                                <li>To collect or store personal information about other Players;</li>
                                <li>To interfere with or disrupt FSL11, servers, or networks;</li>
                                <li>To impersonate any person or entity, including, but not limited to, a representative of FSL11, or falsely state or otherwise misrepresent Player's affiliation with a person or entity;</li>
                                <li>To forge headers or manipulate identifiers or other data in order to disguise the origin of any content transmitted through FSL11 or to manipulate Player's presence on FSL11;</li>
                                <li>To take any action that imposes an unreasonably or disproportionately large load on our infrastructure;</li>
                                <li>To engage in any illegal activities. You agree to use our bulletin board services, chat areas, news groups, forums, communities and/or message or communication facilities (collectively, the "Forums") only to send and receive messages and material that are proper and related to that particular Forum.</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>14. If a Player chooses a username that, in FSL11's considered opinion is obscene, indecent, abusive or that might subject FSL11 to public disparagement or scorn, FSL11 reserves the right, without prior notice to the Player, to change such username and intimate the Player or delete such username and posts from FSL11, deny such Player access to FSL11, or any combination of these options.</p>

                            <p>15. Unauthorized access to FSL11 is a breach of these Terms and Conditions, and a violation of the law. Players agree not to access FSL11 by any means other than through the interface that is offered by FSL11 for use in accessing FSL11. Players agree not to use any automated means, including, without limitation, agents, robots, scripts, or spiders, to access, monitor, or copy any part of our sites, except those automated means that we have approved in advance and in writing.</p>

                            <p>16.  Use of FSL11 is subject to existing laws and legal processes. Nothing contained in these Terms and Conditions shall limit FSL11's right to comply with governmental, court, and law-enforcement requests or needs relating to Players' use of FSL11.</p>

                            <p>17. Players may reach out to FSL11 through Support Team.</p>

                            <p>18. Persons below the age of eighteen (18) years are needed to seek permission or consent from their parents or legal guardians before furnishing data, participating or entering on FSL11 or the FSL11 Services or inter alia, in the contest, uploading pictures, playing games or being part, directly or indirectly, of any activity on FSL11. Entry to FSL11 without consent from parent/s or legal guardian and consequent participation in any activity on FSL11 Website is not permitted and such person is subject to disqualification at the sole and absolute discretion of FSL11, whenever it comes to the knowledge of FSL11.</p>

                            <p>19. FSL11 believes that parents should supervise their children's online activities and consider using parental control tools available from online services and software manufacturers that help offer a child-friendly online environment. These tools can also prevent children from disclosing online their name, address and other personal information without parental permission.</p>

                            <p>20. Although persons below the age of 18 years are allowed to use certain FSL11 Services on the FSL11 with the consent of their parent/s or legal guardians, they may not (where expressly stated in the rules of the Contest) participate in Contests hosted by FSL11.</p>

                            <p>21. FSL11 may not be alleged responsible for any content contributed by Players on the FSL11.</p>

                            <h4>Contests, Participation and Prizes</h4>

                            <p>1. Currently, there are paid versions of the Contests made available by FSL11 Players may participate in the Contests by paying the pre-designated amount as provided on the relevant Contest page. The Participant with the highest aggregate points at the end of the pre-determined round shall be eligible to win a pre-designated prize, as stated on the relevant Contests page.</p>

                            <p>2. A Participant may create different Teams for participation in Contests offered in relation to a Fantasy Sport Event across the FSL11 Services. However, unless FSL11 specifies otherwise in relation to any Contest ("Multiple Entry Contest"), Participants acknowledge and agree that they may enter only one Team in any Contest offered in relation to a Fantasy Sport Event. In the case of a Multiple Entry Contest, a Participant may enter more than one Team in a single Multiple Entry Contest, however on submitting more than one Team for participation in a single Multiple Entry Contest, the Participant will not be permitted to edit or revise the Teams so submitted for participation in such Multiple Entry Contest. In addition, it is expressly clarified that FSL11 may, from time to time, restrict the maximum number of Teams that may be created by a single Player account (for each format of the FSL11 Services) or which a single Player account may enter in a particular Multiple Entry Contest, in each case to such number as determined by FSL11 in its sole discretion.</p>

                            <p>3. FSL11 shall collect a pre-designated fee for access to the FSL11 Services from each Participant in relation to the Contests.</p>

                            <p>4. In the event a Participant indicates, while entering an address, that he/she is a resident of either Assam, Odisha or Telangana, such Participant will not be permitted to proceed to sign up for the round or contest and may not participate in any paid version of the Contests.</p>

                            <h4>Contest Formats</h4>

                            <p>1. FSL11 offers Contests. in two separate formats of FSL11 Services, (1) as a public contest where Players can participate in a Contest with other Players without any restriction on participation and (2) private contests, where Players can invite specific Players into a Contest and restrict participation to such invited Players. All rules applicable to Contests as set out herein shall be applicable to both formats of the Contests.</p>

                            <p>2.Public contest
                            <BLOCKQUOTE type="disc">
                                <li>In the Public contest format of the Contests, FSL11 may offer the Contests in contests comprising of 2 Participants, 3 Participants, 5 Participants, 10 Participants, 100 Participants or any other pre-designated number of Participants.</li>
                                <li>FSL11 may offer this format of the Contests as a paid format and the Winner will be determinable at the end of the round.</li>
                                <li>The number of Participants required to make the Contests operational will be pre-specified and once the number of Participants in such Contests equals the pre-specified number required for that Contests, such Contests shall be operational. In case the number of Participants is less than the pre-specified number at the time of commencement of the round, such Contests will not be operational and the participation fee paid by each Participant shall be returned to the account of such Player without any charge or deduction.</li>
                                <li>In certain Contests across the FSL11 Services, designated as "Confirmed contests", the Contests shall become operational once the number of Participants in such Contest s equals the pre-specified number of winners to be declared in such Contests, even if all available Participant slots (as pre-specified in relation to the Contests) remain unfilled. It is clarified that notwithstanding the activation of such Contests, Participants can continue to join such Contests till either (i) all available Participant slots of such Contests are filled or (ii) the round to which the Contest s relates commences, whichever is earlier. In case such Contests is not operational by the time of the commencement of the round, the participation fee paid by each Participant shall be returned to the account of such Player without any charge or deduction.</li>
                            </BLOCKQUOTE>
                            </p>

                            <p>3. Private contest
                            <BLOCKQUOTE type="disc">
                                <li>In the Private contest format of the Contests, FSL11 enables Players to create a contest ("Private contest") and invite other players, whether existing Players or otherwise, ("Invited Player") to create Teams and participate in the Contests. Players may create a Private contest to consist of a pre-specified number of Participants, that is, consisting of either 2 Participants, 3 Participants, 5 Participants or 10 Participants. The Player creating the Private contest shall submit the participation fee for such Private contest and thereby join that Private contest, shall supply a name for the Private contest and be offered with a unique identification code ("contest Code") (which will be issued to the account of such Player). The Player agrees and understands that once the Private contest is created no change shall be permitted in the terms or constitution of the Private contest, except for a change in the name of the contest. The Player creating the Private contest shall offer FSL11 with the email address or Facebook account username of Invited Players to enable FSL11 to send a message or mail inviting such Invited Player to register with FSL11 (if necessary) and participate in the Private contest in relation to which the invite has been issued.</li>

                                <li>In order to participate in the Private contest, an Invited Player shall input the contest Code associated with the Private contest and submit the participation fee for the Private contest. Once the number of Participants in a Private contest equals the number of pre-specified Participants for that Private contest, the Private contest shall be rendered operative and no other Invited Players or Players shall be permitted to participate in the Private contest. In the event that any Private contest does not contain the pre-specified number of Participants for that Private contest within 1 hour prior to the commencement of the round/Contest, the Participants of such Private contest will be offered with the option to convert the Private contest into a Public contest format, and permit the participation of Players without the contest Code. It is clarified that FSL11 undertakes such conversion in a serialised manner and cannot and does not warrant that any Private contest will be converted into a Public contest format prior to the commencement of the round/Contests or that any Players will join such Contest (s to make it operational. In case the number of Participants in any Private contest (or converted Contests) is less than the pre-specified number at the time of commencement of the round, such Contests will not be operational and the participation fee paid by each Player shall be returned to the account of such Player without any charge or deduction.</li>

                                <li>It is clarified that the participation of Invited Players in any Private contest is subject to the pre-specified number of Participants for that Private contest, and FSL11 shall not be liable to any person for the inability of any Invited Player to participate in any Private contest due to any cause whatsoever, including without limitation due to a hardware or technical malfunction or lack of eligibility of such Invited Player to participate in the Contests.</li>

                            </BLOCKQUOTE>
                            </p>

                            <h4>Eligibility</h4>

                            <P>1. The Contests are open only to persons above the age of 18 years.</P>

                            <p>2. The Contests are open only to persons, currently residing in India.</p>

                            <p>3. FSL11 may, in accordance with the laws prevailing in certain Indian states, bar individuals residing in those states from participating in the Contests. Currently, individuals residing in the Indian states of Assam, Odisha and Telangana may not participate in the paid version of the Contest as the laws of these states bar persons from participating in games of skill where participants are required to pay to enter.</p>

                            <p>4. ersons who wish to participate must have a valid email address.</p>

                            <p>5. Only those Participants who have successfully registered on the FSL11 as well as registered prior to each round in accordance with the procedure outlined above shall be eligible to participate in the Contest and win prizes.</p>

                            <h4>Payment Terms</h4>
                            <BLOCKQUOTE type="disc">
                                <li>In respect of any transactions entered into on the FSL11, including making a payment to participate in the paid versions of Contests, Players agree to be bound by the following payment terms:</li>

                                <li>The payment Players make to participate in the Contests is inclusive of the nominated fee for access to the FSL11 Services charged by FSL11. Subject to these Terms and Conditions, all other amounts collected from the Player are held in escrow until determination of the Winners and distribution of prizes.</li>

                                <li>The Dream 11 portal hosts a number of Contests for which it reserves the right to charge a Platform Fee, which would be specified and notified by FSL11 on the Contest page prior to a Player's joining of such Contest. The Platform Fee and applicable tax thereon will be debited from the Player’s account balance along with the entry-fee for the Contest, and FSL11 shall issue an invoice for such debit to the Player.</li>

                                <li>The Player may participate in a Contest wherein the Player has to contribute a pre-specified contribution towards the Prize Money Pool of such Contest, which will be passed on to the Winners of the Contest after the completion of the Contest as per the terms and conditions of such Contest. It is clarified that FSL11 has no right or interest in this Prize Money Pool, and only acts as an intermediary engaged in collecting and distributing the Prize Money Pool in accordance with the Contest terms and conditions. The amount to be paid-in by the Player towards the Prize Money Pool would also be debited from the Player’s account balance with FSL11.</li>

                                <li>FSL11 provides players with three categories of accounts for the processing and reconciliation of payments in relation to the FSL11 Services: (a) 'Unutilized' Account, (b) Winnings Account, and (c) Cash Bonus Account.</li>

                                <li>The Player's winnings in any Contest will reflect as credits to the Player's Winnings Account. It is clarified that in no instance will FSL11 permit the transfer of any amounts in the Player's accounts to any other category of account held by the player with FSL11 or any third party account, including a bank account held by a third party.</li>

                                <li>Players shall be required to remit the required amount to FSL11 through the designated payment gateway. The payment made shall be credited to the Player’s accounts and each time a Player enters a round, the applicable amount towards participation in the round shall be debited from the Player’s account. In debiting amounts from the Player’s accounts towards the participation fee of such player in any round or Contests, FSL11 shall first debit the Player’s Cash Bonus Account (in accordance with any rules or limitations relating to the use of Cash Bonus as may be prescribed by FSL11 and applicable at such time) , thereafter, any remaining amount of participation fee shall be debited from the Player’s Unutilized Account and thereafter, any remaining amount of participation fee shall be debited from the Player’s Winning Account. In case there is any amount remaining to be paid by the Player in relation to such Player’s participation in any rounds or Contests, the Player will be taken to the designated payment gateway to give effect to such payment. In case any amount added by the Player through such payment gateway exceeds the remaining amount of participation fee, the amount in excess shall be transferred to the Player’s ‘Unutilized’ Account and will be available for use in participation in any rounds or Contests or for withdrawal in accordance with these Terms and Conditions. Debits from the ‘Unutilized’ Account for the purpose of enabling a player’s participation in a Contest shall be made in order of the date of credit of amounts in the ‘Unutilized’ Account, and accordingly amounts credited into ‘Unutilized’ Account earlier in time shall be debited first.</li>

                                <li>A Player shall be permitted to withdraw any amounts credited into such Player's 'Unutilized' Account for any reason whatsoever by contacting FSL11 Customer Support. All amounts credited into a Player's 'Unutilized' Account must be utilised within 335 days of credit. In case any unutilised amount lies in the 'Unutilized' Account after the completion of 335 days from the date of credit of such amount, FSL11 reserves the right to forfeit such unutilised amount, without liability or obligation to pay any compensation to the Player.</li>

                                <li>Withdrawal of any amount standing to the Player's credit in the Winnings Account may be made by way of a request to FSL11 but shall occur automatically upon completion of 335 days from the date of credit of such amount in the Player's Winnings Account. In either case, FSL11 shall effect an online transfer to the Player's bank account on record with FSL11 within a commercially reasonable period of time. Such transfer will reflect as a debit to the Player's Winnings Account. FSL11 shall not charge any processing fee for the online transfer of such amount from the Winnings Account to the Player's bank account on record with FSL11. Players are requested to note that they will be required to provide valid photo identification and address proof documents for proof of identity and address in order for FSL11 to process the withdrawal request. The name mentioned on the Player's photo identification document should correspond with the name provided by the Player at the time of registration on FSL11, as well as the name and address existing in the records of the Player's bank account as provided to FSL11. In the event that no bank account has been registered by the Player against such Player's account with FSL11, or the Player has not verified his/her Player account with FSL11, to FSL11's satisfaction and in accordance with these Terms and Conditions, FSL11 shall provide such Player with a notification to the Player's email address as on record with FSL11 at least 30 days prior to the Auto Transfer Date, and in case the Player fails to register a bank account with his/her Player Account and/or to verify his/her Player Account by the Auto Transfer Date, FSL11 shall be entitled to forfeit any amounts subject to transfer on the Auto Transfer Date. Failure to provide FSL11 with a valid bank account or valid identification documents (to FSL11's satisfaction) may result in the forfeiture of any amounts subject to transfer in accordance with this clause.</li>

                                <li>The Cash Bonus Account shall contain amounts gratuitously issued by FSL11 to the Player for use in participation in any Contests and no Player shall be permitted to transfer or request the transfer of any amount in to the Cash Bonus Account. The usage of any amounts issued and present in the Cash Bonus Account shall be subject to the limitations and restrictions, including without limitation, restrictions as to time within which such amount must be used, as applied by FSL11 and notified to the Player at the time of issue of such amount. The issue of any amount to the Cash Bonus Account is subject to the sole discretion of FSL11 and cannot be demanded by any Player as a matter of right. The issue of any such amount by Deam11 on any day shall not entitle the player to demand the issuance of such amount at any subsequent period in time nor create an expectation of recurring issue of such amount by FSL11 to such Player. The amount standing to the credit of the Player in the Cash Bonus Account may be used by such Player for the sole purpose of setting off against the participation fee in any Contest, in accordance with these Terms and Conditions. The amount standing to the credit of the Player in such Player's Cash Bonus Account shall not be withdraw-able or transferrable to any other account of the Player, including the bank account of such Player, or of any other Player or person, other that as part of the winnings of a Player in any Contests. In case the Player terminates his/her account with FSL11 or such account if terminated by FSL11, all amounts standing to the credit of such Player in the Cash Bonus Account shall return to FSL11 and the Player shall not have any right or interest in such amounts.</li>

                                <li>Players agree that once they confirm a transaction on FSL11, they shall be bound by and make payment for that transaction.</li>

                                <li>The Player acknowledges that transactions on FSL11 may take up to 24 hours to be processed. Any amount paid or transferred into the Player's 'Unutilized' Account or Winnings Account may take up to 24 hours to reflect in the Player's 'Unutilized' Account or Winnings Account balance. Similarly, money debited from the Player's Cash Bonus Account, 'Unutilized' Account or Winnings Account may take up to 24 hours to reflect in the Player's 'Unutilized' Account or Winnings Account balance. Players agree not to raise any complaint or claim against FSL11 in respect of any delay, including any lost opportunity to join any Contest or round due to delay in crediting of transaction amount into any of the Player's accounts
                                </li>

                                <li>A transaction, once confirmed, is final and no cancellation is permissible. However, FSL11 may, at its sole and absolute discretion, permit a Player to cancel a transaction and refund the amount paid:</p>

                                    <p>xiii) If the Player sends a written request to FSL11 from the registered email Id to cancel such payment; or</li>

                                <li>If the payment is made for participation in the paid versions of the Contests, the cancellation request must be received at least 2 days prior to the commencement of the round in respect of which the payment is made; FSL11 shall not be liable to refund any amount thereafter.</li>

                                <li>FSL11 may, at its sole and absolute discretion, refund the amount to the Player after deducting applicable cancellation charges and taxes. At the time of the transaction, Players may also be required to take note of certain additional terms and conditions and such additional terms and conditions shall also govern the transaction. To the extent that the additional terms and conditions contain any clause that is conflicting with the present terms and conditions, the additional terms and conditions shall prevail.</li>
                            </BLOCKQUOTE>

                            <h4>Tabulation of fantasy points</h4>

                            <p>1. FSL11 may obtain the score feed and other information required for the computation and tabulation of fantasy points from third party service providers. In the rare event that any error in the computation or tabulation of fantasy points, selection of winners, etc., as a result of inaccuracies in or incompleteness of the feed provided by the third party service provider comes to its attention, FSL11 shall use best efforts to rectify such error prior to the distribution of prizes. However, FSL11 hereby clarifies that it relies on the accuracy and completeness of such third party score/statistic feeds and does not itself warrant or make any representations concerning the accuracy thereof and, in any event, shall take no responsibility for inaccuracies in computation and tabulation of fantasy points or the selection of winners as a result of any inaccurate or incomplete scores/statistics received from such third party service provider. Players and Participants agree not to make any claim or raise any complaint against FSL11 in this respect.</p>

                            <h4>Selection and Verification of Winners and Conditions relating to the Prizes</h4>

                            <p>1. Selection of Winners </p>

                            <BLOCKQUOTE type="disc">
                                <li>Winners will be decided on the basis of the scores of the Teams in a designated round of the Contest(s). The Participant(s) owning the Team(s) with the highest aggregate score in a particular round shall be declared the Winner(s) except in sleeping 11 contests where Teams(s) with lowest aggregate score shall be declared the Winners(s) .In certain pre-specified Contests, FSL11 may declare more than one Winner and distribute prizes to such Winners in increasing order of their Team's aggregate score at the end of the designated round of the Contest. The contemplated number of Winners and the prize due to each Winner in such Contest shall be as specified on the Contest page prior to the commencement of the Contest. Participants creating Teams on behalf of any other Participant or person shall be disqualified. In the event of a tie, the winning Participants shall be declared Winners and the prize shall be equally divided among such Participants. FSL11 shall not be liable to pay any prize if it is discovered that the Winner(s) have not abided by these Terms and Conditions, and other rules and regulations in relation to the use of the FSL11.com, Contest, “Fantasy Rules”, etc.</li>
                            </BLOCKQUOTE>

                            <p>2. Contacting Winners</p>
                            <BLOCKQUOTE type="disc">
                                <li>Winners shall be contacted by FSL11 or the third party conducting the Contest on the e-mail address provided at the time of registration. The verification process and the documents required for the collection of prize shall be detailed to the Winners at this stage. As a general practice, winners will be required to provide following documents:</li>
                                <li>Self attested Photocopy of the member’s PAN card; Self attested Photocopy of a government-issued residence proof; Member’s bank account details and proof of the same. </li>

                                <li>FSL11 shall not permit a Winner to withdraw his/her prize(s)/accumulated winnings unless the above-mentioned documents have been received and verified within the time-period stipulated by FSL11. The Member represents and warrants that the documents provided in the course of the verification process are true copies of the original documents to which they relate. </li>

                                <li>Participants are required to provide proper and complete details at the time of registration. FSL11 shall not be responsible for communications errors, commissions or omissions including those of the Participants due to which the results may not be communicated to the Winner. </li>

                                <li>The list of Winners shall be posted on a separate web-page on the FSL11.com. The winners will also be intimated by e-mail. </li>

                                <li>In the event that a Participant has been declared a Winner on the abovementioned web-page but has not received any communication from FSL11, such Participant may contact FSL11 within the time specified on the webpage.</li>
                            </BLOCKQUOTE>

                            <p>3. Verification Process</p>

                            <p>Only those Winners who successfully complete the verification process and provide the required documents within the time limit specified by FSL11 shall be permitted to withdraw/receive their accumulated winnings (or any part thereof). FSL11 shall not entertain any claims or requests for extension of time for submission of documents.</p>

                            <p>FSL11 shall scrutinise all documents submitted and may, at its sole and absolute discretion, disqualify any Winner from withdrawing his accumulated winnings (or any part thereof) on the following grounds:</p>

                            <BLOCKQUOTE type="disc">
                                <li>Determination by FSL11 that any document or information submitted by the Participant is incorrect, misleading, false, fabricated, incomplete or illegible; or Participant does not fulfill the Eligibility Criteria; or Any other ground. Taxes Payable</li>

                                <li>All prizes shall be subject to deduction of tax (“TDS”) as per the Income Tax Act 1961. Winners will be provided TDS certificates in respect of such tax deductions. The Winners shall be responsible for payment of any other applicable tax, including but not limited to, income tax, gift tax, etc. in respect of the prize money.</li>
                            </BLOCKQUOTE>

                            <p>4. Miscellaneous</p>

                            <p>i) The decision of FSL11 with respect to the awarding of prizes shall be final, binding and non-contestable.</p>

                            <p>Participants playing the paid formats of the Contest(s) confirm that they are not residents of any of the following Indian states – Assam, Odisha and Telangana. If it is found that a Participant playing the paid formats of the Contest(s) is a resident of any of the abovementioned states, FSL11 shall disqualify such Participant and forfeit any prize won by such Participant. Further FSL11 may, at its sole and absolute discretion, suspend or terminate such Participant’s account with FSL11.com. Any amount remaining unused in the Member’s Game Account or Winnings Account on the date of deactivation or deletion shall be reimbursed to the Member by an online transfer to the Member’s bank account on record with FSL11, subject to the processing fee (if any) applicable on such transfers as set out herein.</p>

                            <p>If it is found that a Participant playing the paid formats of the Contest(s) is under the age of eighteen (18), FSL11 shall be entitled, at its sole and absolute discretion, to disqualify such Participant and forfeit his/her prize. Further, FSL11 may, at its sole and absolute discretion, suspend or terminate such Participant’s account.</p>

                            <p>To the extent permitted by law, FSL11 makes no representations or warranties as to the quality, suitability or merchantability of any prizes and shall not be liable in respect of the same.</p>

                            <p>FSL11 may, at its sole and absolute discretion, vary or modify the prizes being offered to winners. Participants shall not raise any claim against FSL11 or question its right to modify such prizes being offered, prior to closure of the Contest.</p>

                            <p>FSL11 will not bear any responsibility for the transportation or packaging of prizes to the respective winners. FSL11 shall not be held liable for any loss or damage caused to any prizes at the time of such transportation.</p>

                            <p>The Winners shall bear the shipping, courier or any other delivery cost in respect of the prizes.</p>

                            <p>The Winners shall bear all transaction charges levied for delivery of cash prizes</p>

                            <p>All prizes are non-transferable and non-refundable. Prizes cannot be exchanged / redeemed for cash or kind. No cash claims can be made in lieu of prizes in kind.</p>

                            <h4>Publicity</h4>

                            <p>Acceptance of a prize by the Winner constitutes permission for FSL11, and its affiliates to use the Winner's name, likeness, voice and comments for advertising and promotional purposes in any media worldwide for purposes of advertising and trade without any further permissions or consents and / or additional compensation whatsoever. 
                                The Winners further undertake that they will be available for promotional purposes as planned and desired by FSL11 without any charge. The exact dates remain the sole discretion of FSL11. Promotional activities may include but not be limited to press events, internal meetings and ceremonies/functions.</p>

                            <h4>General Conditions</h4>

                            <p>If it comes to the notice of FSL11 that any governmental, statutory or regulatory compliances or approvals are required for conducting any Contests or if it comes to the notice of FSL11 that conduct of any such Contests is prohibited, then FSL11 shall withdraw and / or cancel such Contests without prior notice to any Participants or winners of any Contests. Players agree not to make any claim in respect of such cancellation or withdrawal of the Contest, or contest it in any manner. 
                                Employees, directors, affiliates, relatives and family members of FSL11, will not be eligible to participate in any Contests.</p>

                            <h4>Complaints and Dispute Resolution</h4>

                            <p>You can directly escalate to our management team by contacting support@FSL11.com</p>

                            <p>he courts of competent jurisdiction at Jalaun shall have exclusive jurisdiction to determine any and all disputes arising out of, or in connection with, the Amusement Facilities provided by FSL11.com (including the Contest(s)), the construction, validity, interpretation and enforceability of these Terms and Conditions, or the rights and obligations of the member(s) (including Participants) or FSL11.com, as well as the exclusive jurisdiction to grant interim or preliminary relief in case of any dispute referred to arbitration as given below. All such issues and questions shall be governed and construed in accordance with the laws of the Republic of India.</p>

                            <p>In the event of any legal dispute (which may be a legal issue or question) which may arise, the party raising the dispute shall provide a written notification (“Notification”) to the other party. On receipt of Notification, the parties shall first try to resolve the dispute through discussions. In the event that the parties are unable to resolve the dispute within fifteen (15) days of receipt of Notification, the dispute shall be settled by arbitration.</p>

                            <p>The place of arbitration shall be Bangalore, India. All arbitration proceedings shall be conducted in English and in accordance with the provisions of the Arbitration and Conciliation Act, 1996, as amended from time to time.</p>

                            <p>The arbitration award will be final and binding on the Parties, and each Party will bear its own costs of arbitration and equally share the fees of the arbitrator unless the arbitral tribunal decides otherwise. The arbitrator shall be entitled to pass interim orders and awards, including the orders for specific performance and such orders would be enforceable in competent courts. The arbitrator shall give a reasoned award.</p>

                            <p>Nothing contained in these Terms and Conditions shall prevent FSL11.com from seeking and obtaining interim or permanent equitable or injunctive relief, or any other relief available to safeguard FSL11.com’s interest prior to, during or following the filing of arbitration proceedings or pending the execution of a decision or award in connection with any arbitration proceedings from any court having jurisdiction to grant the same. The pursuit of equitable or injunctive relief shall not constitute a waiver on the part of FSL11.com to pursue any remedy for monetary damages through the arbitration described herein.</p>

                            <h4>Law and Jurisdiction</h4>

                            <p>These Terms, and the agreement of which they form part, are governed by the laws of the Republic of India. The courts of competent jurisdiction at Jalaun shall have exclusive jurisdiction. The place of arbitration shall be Jalaun, India.</p>

                            <h4>Limitation Of Liability</h4>

                            <p>You agree that, to the maximum extent permitted by law, We are not liable to You or anyone else for any loss or damage (including, without limitation, any direct, indirect, special or consequential loss) arising as a result of breach of these Terms, in tort (including negligence) or otherwise arising out of, or in connection, with:</p>
                            <BLOCKQUOTE type="disc">
                                <li>the use of Our Websites;</li>
                                <li>any Content You upload or otherwise provide;</li>
                                <li>the use by Us of information provided by You to Us through Our Websites;</li>
                                <li>being unable to access Our Websites for whatever reason and however arising, including (without limitation) negligence;</li>
                                <li>the failure of Our Websites for whatever reason and however arising including (without limitation) negligence;</li>
                                <li>the use of Your password by You or any third party to whom You have made the password available.</li>
                            </BLOCKQUOTE>

                            <p>We expressly limit, and You agree, Our liability for breach of a condition or warranty implied or a consumer guarantee imposed by virtue of any legislation to the supply of the services again or the payment of the cost of having the services supplied again (the choice of which is to be at Our sole discretion).</p>


                            <p>You indemnify Us against any action, liability, claim, loss, damage, proceeding, expense (including legal costs) suffered or incurred by Us, arising from, or which is directly or indirectly, related to:</p>
                            <BLOCKQUOTE type="disc">
                                <li>Your breach or non-observance of any of these Term</li>
                                <li>any Content You upload or otherwise provide;</li>
                                <li>any breach or inaccuracy in any representations or warranties made to Us; and/or</li>
                                <li>any breach, or alleged breach, of intellectual or other proprietary rights or interests of third parties.</li>
                            </BLOCKQUOTE>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- T&C modal ends -->

    <!--forgotpaasword-->

    <div class="modal fade centerPopup" id="forgotPassword" popup-handler>
        <div class="modal-dialog custom_popup small_popup">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Forgot Your Password ?</h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup">
                    <form class="form_commen" id="forgotPasswordForm" name="forgotPasswordForm" ng-submit="sendEmailForgotPassword(forgotPasswordForm)" novalidate="">
                        <div class="form-group">
                            <label>No worries! Enter your Email/Phone no. below and we’ll send you a recovery otp.</label>
                            <input placeholder="Email/Phone no." class="form-control" name="Keyword" type="text" ng-model="forgotPasswordData.Keyword" ng-required="true" >
                            <div style="color:red" ng-show="forgotEmailSubmitted && forgotPasswordForm.Keyword.$error.required" class="form-error" >
                                *Field is required.
                            </div>
                        </div>
                        <div class="button_right text-center">
                            <button class="btn btn-submit theme_bgclr">SEND</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- verify forgot password -->
    <div class="modal fade centerPopup" id="verifyForgotPassword" popup-handler >
        <div class="modal-dialog custom_popup small_popup">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Forgot Your Password ?</h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup">
                    <form class="form_commen" name="verifyforgotPassword" ng-submit="verifyForgotPassword(verifyforgotPassword)" novalidate="">
                        <div class="form-group">
                            <label>No worries! Enter your OTP below sent to your registered Email/Phone.</label>
                            <input placeholder="OTP" name="opt" ng-model="forgotPassword.OTP" numbers-only class="form-control" type="text" ng-required="true">
                            <div style="color:red" ng-show="forgotPasswordSubmitted && verifyforgotPassword.opt.$error.required" class="form-error">
                                *OTP is required.
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" ng-model="forgotPassword.Password" placeholder="New Password" class="form-control" ng-change="removeMassage()"  ng-pattern="/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9@_%]+)$/" ng-minlength="6" ng-required="true" >
                            <div style="color:red" ng-show="forgotPasswordSubmitted && verifyforgotPassword.password.$error.required" class="form-error">
                                *Password is required.
                            </div>
                            <div style="color:red" ng-show="verifyforgotPassword.password.$error.pattern || verifyforgotPassword.password.$error.minlength" class="form-error">*Password must have one capital, one number and 6 character long.
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="password" ng-model="forgotPassword.confirmPass" compare-to="forgotPassword.Password" name="confirmPass" placeholder="Confirm New Password" class="form-control" ng-required="true" ng-change="removeMassage()">
                            <div style="color:red" ng-show="forgotPasswordSubmitted && verifyforgotPassword.confirmPass.$error.required" class="form-error">
                                *Confirm password is required.
                            </div>
                            <div style="color:red" ng-show="!verifyforgotPassword.confirmPass.$error.required && verifyforgotPassword.confirmPass.$error.compareTo">Your passwords must match.</div>
                        </div>
                        <div class="button_right text-center">
                            <button class="btn btn-submit theme_bgclr">SUBMIT</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Verify mobile -->
    <div class="modal fade centerPopup" id="verifyMobile" popup-handler  >
        <div class="modal-dialog custom_popup small_popup">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Verify Mobile</h4>
                </div>
                <div class="modal-body clearfix comon_body ammount_popup">
                    <form class="form_commen" name="verifyOTP" ng-submit="verifySignupOTP(verifyOTP)" novalidate="">
                        <div class="form-group">
                            <label>Enter your OTP below sent to your registered Mobile no.</label>
                            <input placeholder="OTP" name="opt" ng-model="OTP" numbers-only class="form-control" type="text" ng-required="true">
                            <div style="color:red" ng-show="verifyOTPSubmitted && verifyOTP.opt.$error.required" class="form-error">
                                *OTP is required.
                            </div>
                        </div>
                        <div class="button_right text-center">
                            <button class="btn btn-submit theme_bgclr">Verify</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include('footerHome.php'); ?>

