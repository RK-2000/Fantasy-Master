

<div class="container" ng-controller="PageController"> 
 <div id="logo" class="text-center"><img src="<?php echo API_URL;?>asset/img/emailer/logo2.png"></div> 
  <!-- Form -->
  <div class="col-12 col-sm-11 col-md-8 col-lg-6 col-xl-5 login-block">
    <h1 class="h3">Forgot your password?</h1>
    <br>
    <p>Please enter your username/email address, and we will email you a link to reset your password.</p>       
    <br>
    <form method="post" id="recovery_form" name="recovery_form"  autocomplete='off'>
      <div class="form-group">
        <input type="text" name="Keyword" class="form-control form-control-lg" placeholder="Username/Email"  autofocus="">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success btn-sm" ng-disabled="processing" ng-click="recovery()">Submit</button>
        <span class="float-right"><a href="signin" class="a">Sign in?</a></span>
      </div>
    </form>
  </div>
</div><!-- / container -->