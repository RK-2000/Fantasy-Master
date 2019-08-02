<?php include('header.php');?>
<!--Main container sec start-->
<div class="mainContainer" ng-cloak ng-controller="couponController" ng-init="getCoupons()" >
   <div class="comonBg">
      <div class="matchSection">
         <div class="container">
            <div class="primarHead text-center mb-30">
               <h1>Coupons</h1>
            </div>
            <div class="row">
               <div class="col-sm-4" ng-repeat="coupons in CouponsList">
                     <div class="matchCenterbox couponimgbox">
                        <!-- <img ng-src="{{coupons.CouponTitle}}"> -->
                        <p>{{coupons.CouponTitle}}</p>
                     </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--Main container sec end-->
<?php include('innerFooter.php');?>