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
                    <img style="outline:none;border:none;max-width: 40%;" src="{{ASSET_BASE_URL}}img/emailer/logo.png" alt="{{SITE_NAME}}" />
                  </a> 
                </p>
                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">
                Hi {{Name}},</p>

                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">We received a forgot password request associated with this email address. If you made this request, please follow the instructions below. </p>


                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">{{EmailText}}</p>

                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">{{Token}}</p>


                <p style="font-family: 'Roboto', sans-serif; font-size:16px; color:#444444; font-weight:500; margin-bottom:30px;">If you did not request you can safely ignore this email. Rest assured your account is safe.</p>
               <p style="font-family: 'Roboto', sans-serif; font-size:14px; color:#444444; line-height:21px; margin:0;">Thanks,</p>
                      <p style="font-family: 'Roboto', sans-serif; font-size:14px; color:#444444; line-height:21px; margin: 4px 0;">Team {{SITE_NAME}}
                  </p>
                </td>
              </tr>                
              <tr>
                <td style="padding:25px 40px;background:#FFFFFF;" class="content-padding">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td style="text-align:center;width:1%">                                  
                        <div style="height:40px;width:40px;margin:0 auto;background:#08348161;border-radius:50%;color:#083481;line-height:44px;font-size:21px">₹</div>
                                                        
                        <div style="font-family:Montserrat,Droid Sans,Lucida Sans Unicode,Lucida Grande,Helvetica,Georgia,Arial;font-size:13px;color:#2c2c2c;font-weight:normal;line-height:15px;padding:0px;padding:14px 0" align="center">Invite your friends &amp; earn  as they play!</div>
                        <a href="{{SITE_URL}}referAndEarn" style="font-family:Montserrat,Arial,Helvetica;padding:6px 24px;display:inline-block;font-size:11px;font-weight:500;color:#083481;border-radius:20px;border:1px solid #083481">   INVITE NOW                  
                        </a>
                      </td>
                                                          
                      <td style="text-align:center;width:1%;border-left:1px solid #0b348130">
                        <div style="height:40px;width:40px;margin:0 auto;background:#08348161;border-radius:50%;color:#083481;line-height:44px;font-size:21px">✆</div>
                        <div style="font-family:Montserrat,Droid Sans,Lucida Sans Unicode,Lucida Grande,Helvetica,Georgia,Arial;font-size:13px;color:#2c2c2c;font-weight:normal;line-height:15px;padding:0px;padding:14px 0" align="center">In case of any query</div>
                           <a href="{{SITE_URL}}contactUs" style="font-family:Montserrat,Arial,Helvetica;padding:6px 24px;display:inline-block;font-size:11px;font-weight:500;color:#083481;border-radius:20px;border:1px solid #083481">CONTACT US             
                           </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center" style="font-family: 'Roboto', sans-serif; font-size:13px; color:#999999; padding:10px;background-color: #000;">
                  <a style="padding-right: 10px;" href="{{FACEBOOK_URL}}"><img width="35px" src="{{ASSET_BASE_URL}}/img/emailer/facebook.png" alt="facebook" target="_blank"/></a>
                  <a style="padding-right: 10px;" href="{{TWITTER_URL}}"><img width="35px" src="{{ASSET_BASE_URL}}/img/emailer/twitter.png" alt="twitter" target="_blank"/></a>
                   <a style="padding-right: 10px;" href="{{GOOGLE_PLUS_URL}}"><img width="35px" src="{{ASSET_BASE_URL}}/img/emailer/google-plus.png" alt="googleplus" target="_blank"/></a> 
                  <a href="{{INSTAGRAM_URL}}"><img width="35px" src="{{ASSET_BASE_URL}}/img/emailer/instagram.png" alt="instagram" target="_blank"/></a><br><br>
                  <p>Copyright © {{DATE}} {{COMPANY_NAME}}.</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>