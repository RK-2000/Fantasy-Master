<!doctype html>
<html>
<head>
  <!-- <meta charset="utf-8"> -->
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>{{SITE_NAME}}</title>
  <style>
  p{margin:0;}
  a{text-decoration: none;}
  @import url(https://fonts.googleapis.com/css?family=Roboto:400,500);
  @media only screen and (max-width:767px) {
    table[class="main-wrapper"] {width:94% !important;}
    td[class="content-padding"] {padding:20px !important;}
    td[class="small"] {width:30% !important; display:block !important; padding:10px !important;}
    img[class="get-startedbtn"]{width:100% !important;}
    td[class="mob-content"] {display:block !important; width:100% !important; padding:0 0 10px 10px !important;}
    td[class="mob-padding"] {padding:10px !important;}
    td[class="add-frnds"] {padding:10px 0 !important;}
    td[class="mob-paddinglr"] {padding:0 10px !important;}
    a {outline:none;}
    img {border:0; outline:none;}
  }
</style>
</head>
<body style="background:#EEEEF0; font-family: 'Roboto', sans-serif;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0; padding:0 0 20px 0; font-size:16px; color:#444444; font-family: 'Roboto', sans-serif; background:#EEEEF0;">
    <tbody>
      <tr>
        <td>
          <table class="main-wrapper" width="610" border="0" cellspacing="0" cellpadding="0" align="center" style=" border-radius:5px;">
            <tr>
              <td colspan="2" class="content-padding" style="padding:40px 40px 20px; border-bottom:1px solid #E5E5E5;background:#FFFFFF;" >
                <p style="text-align: center; margin-bottom: 20px;"> 
                  <a style="outline:none;border:none;margin: 0 auto;" href="{{SITE_URL}}">
                    <img style="outline:none;border:none;max-width: 25%;" src="{{ASSET_BASE_URL}}img/emailer/logo2.png" alt="{{SITE_NAME}}" />
                  </a> 
                </p>
                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">
                Hi,</p>
                
                
                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">{{Message}}</p>
                <br>
                <p>Please click here to visit website <a href="{{SITE_URL}}">{{SITE_URL}}</a> or download the app from <a href="{{SITE_URL}}download-app">{{SITE_URL}}download-app</a></p>
              </td>
            </tr>

            <tr>
              <td style="padding:25px 40px;background:#FFFFFF;" class="content-padding"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                  <tr>
                    <td>
                      <p style="font-family: 'Roboto', sans-serif; font-size:14px; color:#444444; line-height:21px; margin:0;">Regards,</p>
                      <p style="font-family: 'Roboto', sans-serif; font-size:14px; color:#444444; line-height:21px; margin: 4px 0;">Team {{SITE_NAME}}</p>'Let's Start Winning!'
                    </td>
                    <td align="right"><table  border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td align="right" style="margin:0; padding:0;">
                            <a href="{{FACEBOOK_URL}}" style="text-decoration:none;display:inline-block; cursor:pointer; padding-right:0px;"> <img style="outline:none;border:none;" src="{{ASSET_BASE_URL}}img/emailer/btn-fb.png" alt="" /> </a>

                          </td>
                          <td align="right" style="width:40px;margin:0; padding:0;">
                            <a href="{{TWITTER_URL}}" style="text-decoration:none;display:inline-block; cursor:pointer; padding-right:0px;"> <img style="outline:none;border:none;" src="{{ASSET_BASE_URL}}img/emailer/btn-twit.png" alt="" /> </a>
                          </td>
                          <td align="right" style="width:40px;margin:0; padding:0;">
                            <a href="{{LINKEDIN_URL}}" style="text-decoration:none;display:inline-block; cursor:pointer; padding-right:0px;"> <img style="outline:none;border:none;" src="{{ASSET_BASE_URL}}img/emailer/btn-linked.png" alt="" /> </a>
                          </td>
                          <td align="right" style="width:40px;margin:0; padding:0;">
                            <a href="{{INSTAGRAM_URL}}" style="text-decoration:none;display:inline-block; cursor:pointer; padding-right:0px;"> <img style="outline:none;border:none;" src="{{ASSET_BASE_URL}}img/emailer/btn-gplus.png" alt="" /> </a>
                          </td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td align="center" style="font-family: 'Roboto', sans-serif; font-size:13px; color:#999999; padding:10px;background-color: #000;">Copyright © {{DATE}} {{COMPANY_NAME}}.</td>
            </tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>