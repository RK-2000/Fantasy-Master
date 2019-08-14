    <?php include('header.php');?>
    <!--Main container sec start-->
    <div class="mainContainer" ng-controller="inviteController" ng-cloak >
        <div class="refer_banner_img">  
            <div class="container">
                 <div class="row">
                     <div class="col-md-8 offset-md-2 text-center refer_banner_content">
                        <h1 class="mb-2"> Refer and Earn </h1>
                        <p> If you love fantasy sports, it is very likely that your friends are also equally passionate 
                            about this game. FSL11.com offers a very lucrative referral program for it's players. <!-- When 
                            your friend signs up with your invite code, verifies his account and adds a minimum amount 
                            of Rs.100 then you & your friend get Rs.25 and Rs.50 bonus amount respectively.You can share
                            our invite code with upto 6 friends.  -->
                        </p>
                     </div>
                 </div>

                 <div class="row">
                        <div class="referRightBox col-md-4 offset-md-4 mt-4">
                            <div class="h1 invite-code ">{{user_details.ReferralCode}}</div>
                            <div class="h5">Your invite code</div>
                        
                            <div class="viaTabPar">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                    <a class="nav-link {{activeTab=='viaSms' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="inviteTab('viaSms')">
                                    Share Via SMS  </a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link {{activeTab=='viaMail' ? 'active' : '' }}"  data-toggle="tab" href="javascript:void(0)" ng-click="inviteTab('viaMail')">Share Via Mail</a>
                                    </li>
                                </ul>

                                <div class="tab-content ">
                                    <div id="viaSms" class="tab-pane fade {{activeTab=='viaSms' ? 'active show' : '' }} ">
                                        <form name="mobileForm" ng-submit="InviteFriend(mobileForm,'Phone')" novalidate="">
                                            <div class="form-group">
                                                <input type="text" ng-model="inviteField.PhoneNumber" name="PhoneNumber" class="form-control" placeholder="Enter Mobile Number" ng-required="true">
                                                <div style="color:red" ng-show="inviteSubmitted && mobileForm.PhoneNumber.error.required" class="form-error">
                                                    * Mobile number is required.
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-submit btn-block text-uppercase theme_bgclr">Invite</button>
                                            </div>   
                                        </form>
                                    </div>

                                    <div id="viaMail" class="tab-pane fade {{activeTab=='viaMail' ? 'active show' : '' }}">
                                        <form name="emailForm" ng-submit="InviteFriend(emailForm,'Email')" novalidate="">
                                            <div class="form-group">
                                                <input type="email" class="form-control" ng-model="inviteField.Email" name="Email" placeholder="Enter email" ng-required="true">
                                                <div style="color:red" ng-show="inviteSubmitted && emailForm.Email.error.required" class="form-error">
                                                    * Email is required.
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-submit btn-block text-uppercase theme_bgclr">Invite</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="listPar">
                                    <p>Share with Friends via Social</p>
                                    
                                    <ul>
                                        <li>
                                            <a class="fb" href="javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                            
                                        </li>
                                        <li>
                                            <a class="tw" href="javascript:void(0)" data-js="twitter-share"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a class="inst" href="https://www.instagram.com/accounts/login" target="_blank" ><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a class="wt" href="https://api.whatsapp.com/send?text=Here Rs.50 to play Fantasy Cricket on FSL11. Click https://www.fsl11.com to share to download the app and use my code: *{{user_details.ReferralCode}}* or use the link to register https://www.fsl11.com/authenticate?referral= {{user_details.ReferralCode}}. " target="_blank"><i class="fa fa-whatsapp"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>    
                        </div>
                    </div>

            </div>
        </div>
            <div class="referearnSec">
                <div class="container">
                    <div class="pt-5 text-center mb-3 refer_bottom_sec">
                        <h4> HOW IT WORKS </h4>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <figure>
                                    <img src="assets/img/user.svg" alt="" />
                                </figure>
                                <h5> Enlist </h5>
                                <p> <strong> Refer friends with your Invite Code. </strong> When they join they become your recruit.</p>
                            </div>
                            <div class="col-md-4">
                            <figure>
                                    <img src="assets/img/cricket.svg" alt="" />
                                </figure>
                                <h5> Play </h5>
                                <p> <strong> When your new recruit plays ₹ 50 </strong> in paid contests and verifies their account, your recruit becomes your qualified referral.</p>
                            </div>
                            <div class="col-md-4">
                                <figure>
                                    <img src="assets/img/dollar.svg" alt="" />
                                </figure>
                                <h5> Earn </h5>
                                <p> Once qualified, you'll <strong> earn ₹ 50 </strong> and your buddy will <strong> get ₹ 50 </strong> to play on FSL11 !</p>
                            </div>
                        </div>
                    </div>
               </div>
        </div>
    </div>
    <!--Main container sec end-->
    <?php include('innerFooter.php');?>

<script type="text/javascript">

    $('.fb').click( function() 
    {
        var shareurl = $(this).data('shareurl');
        window.open('https://www.facebook.com/dialog/feed?app_id=2261225134126697&picture=http://www.fbrell.com/f8.jpg&name=FSL11&description=homeschool%20.versus%20.awayschool%20@%20.venue%20@%20.datetime&caption=Here Rs.50 to play Fantasy Cricket on FSL11. Click https://www.fsl11.com to share to download the app and use my code '+$(".invite-code").text()+' to register.&link=https://www.fsl11.com/authenticate?referral='+$(".invite-code").text(), 'Fantasy', 
        'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
        return false;
    });

var twitterShare = document.querySelector('[data-js="twitter-share"]');

twitterShare.onclick = function(e) {
  e.preventDefault();    
      var twitterWindow = window.open("https://twitter.com/intent/tweet?text=Here's Rs.50 to play Fantasy Cricket on FSL11. Click https://www.fsl11.com/authenticate?referral="+$(".invite-code").text()+" to share to download the app and use my code "+$(".invite-code").text()+" to register.", 'twitter-popup', 'height=350,width=600');
      if(twitterWindow.focus) { twitterWindow.focus(); }
        return false;
    }
       
</script>