<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function send_mail($emailData = array())
{
    require_once APPPATH . 'libraries/sendgrid/vendor/autoload.php';
    $request_body = json_decode('{
            "personalizations": [
            {
                "to": [
                {
                    "email": "' . $emailData['emailTo'] . '"
                }],
                "dynamic_template_data":{
                    "SITE_URL"              :   "' . SITE_HOST . '",
                    "BASE_URL"              :   "' . BASE_URL . '",
                    "ASSET_BASE_URL"        :   "' . ASSET_BASE_URL . '",
                    "SITE_NAME"             :   "' . SITE_NAME . '",
                    "COMPANY_NAME"          :   "' . SITE_NAME . '",
                    "DEFAULT_CURRENCY"      :   "' . DEFAULT_CURRENCY . '",
                    "REFERRAL_SIGNUP_BONUS" :   "' . REFERRAL_SIGNUP_BONUS . '",
                    "FACEBOOK_URL"          :   "' . FACEBOOK_URL . '",
                    "TWITTER_URL"           :   "' . TWITTER_URL . '",
                    "LINKEDIN_URL"          :   "' . LINKEDIN_URL . '",
                    "INSTAGRAM_URL"         :   "' . INSTAGRAM_URL . '",
                    "Name"                  :   "' . $emailData['Name'] . '",
                    "EmailText"             :   "' . $emailData['EmailText'] . '",
                    "PhoneNumber"           :   "' . $emailData['PhoneNumber'] . '",
                    "Title"                 :   "' . $emailData['Title'] . '",
                    "Message"               :   "' . $emailData['Message'] . '",
                    "ContestName"           :   "' . $emailData['ContestName'] . '",
                    "SeriesName"            :   "' . $emailData['SeriesName'] . '",
                    "InviteCode"            :   "' . $emailData['InviteCode'] . '",
                    "MatchNo"               :   "' . $emailData['MatchNo'] . '",
                    "TeamNameLocal"         :   "' . $emailData['TeamNameLocal'] . '",
                    "TeamNameVisitor"       :   "' . $emailData['TeamNameVisitor'] . '",
                    "Token"                 :   "' . $emailData['Token'] . '",
                    "DeviceTypeID"          :   "' . $emailData['DeviceTypeID'] . '",
                    "Amount"                :   "' . $emailData['Amount'] . '",
                    "ReferralCode"          :   "' . $emailData['ReferralCode'] . '",
                    "ReferralURL"           :   "' . $emailData['ReferralURL'] . '",
                    "DATE"                  :   "' . date('Y') . '"
                }
            }
            ],
            "from": {
                "email": "info@example.com"
                },

                "template_id"   : "' . $emailData['template_id'] . '",
                "subject"       : "' . $emailData['Subject'] . '",
                "content"       : [
                {
                    "type": "text/html",
                    "value": "and easy to do anywhere"
                }
                ]
            }');
    // sending email 
    $apiKey = 'SG.utLwQDhwSfik_-oxahrViQ.xu7v9zxwv5u_FM0c506ro6oPf-M8qKvI9djQ_0yt5SU_$#1234154d5s8';
    $sg = new \SendGrid($apiKey);
    $response = $sg->client->mail()->send()->post($request_body);
    $response->statusCode();
    $response->body();
    $response->headers();
    return $true;
}

/*------------------------------*/
/*------------------------------*/
function sendPushMessage($UserID, $Message, $Data = array())
{
    if (!isset($Data['content_available'])) {
        $Data['content_available'] = 1;
    }
    $Obj = &get_instance();
    $Obj->db->select('U.UserTypeID, US.DeviceTypeID, US.DeviceToken');
    $Obj->db->from('tbl_users_session US');
    $Obj->db->from('tbl_users U');
    $Obj->db->where("US.UserID", $UserID);
    $Obj->db->where("US.UserID", "U.UserID", FALSE);
    $Obj->db->where("US.DeviceToken!=", '');
    $Obj->db->where('US.DeviceToken is NOT NULL', NULL, FALSE);
    if (!MULTISESSION) {
        $Obj->db->limit(1);
    }
    $Query = $Obj->db->get();
    if ($Query->num_rows() > 0) {
        foreach ($Query->result_array() as $Notifications) {
            if ($Notifications['DeviceTypeID'] == 2) { /*I phone */
                pushNotificationIphone($Notifications['DeviceToken'], $Notifications['UserTypeID'], $Message, 0, $Data);
            } elseif ($Notifications['DeviceTypeID'] == 3) { /* android */
                pushNotificationAndroid($Notifications['DeviceToken'], $Notifications['UserTypeID'], $Message, $Data);
            }
        }
    }
}
/*------------------------------*/
/*------------------------------*/
function pushNotificationAndroid($DeviceIDs, $UserTypeID, $Message, $Data = array())
{
    //API URL of FCM
    $URL = 'https://fcm.googleapis.com/fcm/send';
    /*ApiKey available in:  Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
    if ($UserTypeID == 2) {
        if (ENVIRONMENT == 'production') {
            $ApiKey = 'AIzaSyDZvMfF2HbbG_tuEOQQjzeBDXa7EKPth5M';
        } else {
            $ApiKey = 'AIzaSyDZvMfF2HbbG_tuEOQQjzeBDXa7EKPth5M';
        }
    } else {
        if (ENVIRONMENT == 'production') {
            $ApiKey = 'AIzaSyBe5p4qjA1aID7H0gGADnnhQXspHzIgrLk';
        } else {
            $ApiKey = 'AIzaSyBe5p4qjA1aID7H0gGADnnhQXspHzIgrLk';
        }
    }
    $Fields = array('registration_ids' => array($DeviceIDs), 'data' => array("Message" => $Message, "Data" => $Data));
    //header includes Content type and api key
    $Headers = array('Content-Type:application/json', 'Authorization:key=' . $ApiKey);
    $Ch = curl_init();
    curl_setopt($Ch, CURLOPT_URL, $URL);
    curl_setopt($Ch, CURLOPT_POST, true);
    curl_setopt($Ch, CURLOPT_HTTPHEADER, $Headers);
    curl_setopt($Ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($Ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($Ch, CURLOPT_POSTFIELDS, json_encode($Fields));
    $Result = curl_exec($Ch);
    $obj = &get_instance();
    /*Save Log*/
    if (API_SAVE_LOG) {
        mongoDBConnection();
        $obj->fantasydb->log_pushdata->insertOne(array('Body' => json_encode(array_merge($Headers, $Fields), 1), 'DeviceTypeID' => '3', 'Return' => $Result, 'EntryDate' => date("Y-m-d H:i:s")));
    }
    if ($Result === FALSE) {
        die('FCM Send Error: ' . curl_error($Ch));
    }
    curl_close($Ch);
    return $Result;
}
/*------------------------------*/
/*------------------------------*/
function pushNotificationIphone($DeviceToken = '', $UserTypeID, $Message = '', $Badge = 1, $Data = array())
{
    $Badge = ($Badge == 0 ? 1 : 0);
    $Pass = '123456';
    $Body['aps'] = $Data;
    $Body['aps']['alert'] = $Message;
    $Body['aps']['badge'] = (int)$Badge;
    /* End of Configurable Items */
    $Ctx = @stream_context_create();
    // assume the private key passphase was removed.
    stream_context_set_option($Ctx, 'ssl', 'passphrase', $Pass);
    if (ENVIRONMENT == 'production') {
        $Certificate = 'app2-ck-live.pem';
        @stream_context_set_option($Ctx, 'ssl', 'local_cert', $Certificate);
        $Fp = @stream_socket_client('ssl://gateway.push.apple.com:2195', $Err, $Errstr, 60, STREAM_CLIENT_CONNECT, $Ctx); //For Live
    } else {
        $Certificate = 'app2-ck-dev.pem';
        @stream_context_set_option($Ctx, 'ssl', 'local_cert', $Certificate);
        $Fp = @stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $Err, $Errstr, 60, STREAM_CLIENT_CONNECT, $Ctx); //For Testing
    }

    if (!$Fp) {
        return "Failed to connect $Err $Errstr";
    } else {
        try {
            $obj = &get_instance();
            /*Save Log*/
            if (API_SAVE_LOG) {
                mongoDBConnection();
                $obj->fantasydb->log_pushdata->insertOne(array('Body' => json_encode($Body, 1), 'DeviceTypeID' => '2', 'Return' => $Certificate, 'EntryDate' => date("Y-m-d H:i:s")));
            }
            $Payload = @json_encode($Body, JSON_NUMERIC_CHECK);
            $Msg = @chr(0) . @pack("n", 32) . @pack('H*', @str_replace(' ', '', $DeviceToken)) . @pack("n", @strlen($Payload)) . $Payload;
            @fwrite($Fp, $Msg);
            @fclose($Fp);
        } catch (Exception $E) {
            return 'Caught exception';
        }
    }
}

function boradcastPushNotifications($Title, $Message)
{
    if (ENVIRONMENT == "production") {

        /*Send Notifications*/
        $PostFCM = array();
        $PostFCM['to'] = '/topics/' . FIREBASE_CHANNEL_NAME;
        $PostFCM['data'] = array(
            'badges' => 1,
            'title' => $Title,
            'message' => $Message
        );
        $CURL = curl_init();
        curl_setopt_array($CURL, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($PostFCM, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                "authorization: key=AIzaSyBe5p4qjA1aID7H0gGADnnhQXspHzIgrLk",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $Response = curl_exec($CURL);
        if (API_SAVE_LOG) {
            $obj = &get_instance();
            mongoDBConnection();
            $obj->fantasydb->log_pushdata->insertOne(array('Body' => json_encode($PostFCM, 1), 'DeviceTypeID' => '3', 'Return' => $Response, 'EntryDate' => date("Y-m-d H:i:s")));
        }
        $Err = curl_error($CURL);
        curl_close($CURL);
        if ($Err) {
            return false;
        } else {
            return true;
        }
    } 
}

/*------------------------------*/
/*------------------------------*/
function sendMail($Input = array())
{
    $CI = &get_instance();
    $CI->load->library('email');
    $config['protocol'] = SMTP_PROTOCOL;
    $config['smtp_host'] = SMTP_HOST;
    $config['smtp_port'] = SMTP_PORT;
    $config['smtp_user'] = SMTP_USER;
    $config['smtp_pass'] = SMTP_PASS;
    $config['charset'] = "utf-8";
    $config['mailtype'] = "html";
    $config['wordwrap'] = TRUE;
    $config['smtp_crypto'] = SMTP_CRYPTO;
    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");
    $CI->email->clear();
    $CI->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
    $CI->email->reply_to(NOREPLY_EMAIL, NOREPLY_NAME);
    $CI->email->to($Input['emailTo']);
    if (defined('TO_BCC') && !empty(TO_BCC)) {
        $CI->email->bcc(TO_BCC);
    }
    if (!empty($Input['emailBcc'])) {
        $CI->email->bcc($Input['emailBcc']);
    }
    $CI->email->subject($Input['emailSubject']);
    $CI->email->message($Input['emailMessage']);
    if (@$CI->email->send()) {
        return true;
    } else {
        //echo $CI->email->print_debugger();
        return false;
    }
}
/*------------------------------*/
/*------------------------------*/
function emailTemplate($HTML)
{
    $CI = &get_instance();
    return $CI->load->view("emailer/layout", array("HTML" => $HTML), TRUE);
}
/*------------------------------*/
/*------------------------------*/
function checkDirExist($DirName)
{
    if (!is_dir($DirName)) mkdir($DirName, 0777, true);
}
/*------------------------------*/
/*------------------------------*/
function validateEmail($Str)
{
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $Str)) ? FALSE : TRUE;
}
/*------------------------------*/
/*------------------------------*/
function validateDate($Date)
{
    if (strtotime($Date)) {
        return true;
    } else {
        return false;
    }
}
/*------------------------------*/
/*------------------------------*/
function paginationOffset($PageNo, $PageSize)
{
    if (empty($PageNo)) {
        $PageNo = 1;
    }
    $Offset = ($PageNo - 1) * $PageSize;
    return (int)$Offset;
}
/*------------------------------*/
/*------------------------------*/
function get_guid()
{
    if (function_exists('com_create_guid')) {
        return strtolower(com_create_guid());
    } else {
        mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
        return strtolower($uuid);
    }
}
/*------------------------------*/
/*------------------------------*/
function dateDiff($FromDateTime, $ToDateTime)
{
    $start = date_create($FromDateTime);
    $end = date_create($ToDateTime); // Current time and date
    return $diff = date_diff($start, $end);
    echo 'The difference is ';
    echo $diff->y . ' years, ';
    echo $diff->m . ' months, ';
    echo $diff->d . ' days, ';
    echo $diff->h . ' hours, ';
    echo $diff->i . ' minutes, ';
    echo $diff->s . ' seconds';
    // Output: The difference is 28 years, 5 months, 19 days, 20 hours, 34 minutes, 36 seconds
    echo 'The difference in days : ' . $diff->days;
    // Output: The difference in days : 10398

}
/*------------------------------*/
/*------------------------------*/
function diffInHours($startdate, $enddate)
{
    return abs(strtotime($enddate) - strtotime($startdate)) / 3600;
}
/*------------------------------*/
/*------------------------------*/
function array_keys_exist(array $needles, array $StrArray)
{
    foreach ($needles as $needle) {
        if (in_array($needle, $StrArray))
            return true;
    }
    return false;
}
/*------------------------------*/
/*------------------------------*/
function randomString($length = 6)
{
    $str = "";
    $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}
/*------------------------------*/
/*------------------------------*/
function mongoDBConnection()
{
    /* Require MongoDB Library & Connection */
    $Obj = &get_instance();
    require_once getcwd() . '/vendor/autoload.php';
    switch (ENVIRONMENT) {
        case 'local':
            $Obj->ClientObj = new MongoDB\Client("mongodb://localhost:27017");
            break;
        case 'testing':
            $Obj->ClientObj = new MongoDB\Client("mongodb://192.168.1.251:27017");
            break;
        case 'demo':
            $Obj->ClientObj = new MongoDB\Client("mongodb://localhost:58017");
            break;
        default :
            $Obj->ClientObj = new MongoDB\Client("mongodb://root:root@localhost:27017");
           break;
    }
    $Obj->fantasydb = $Obj->ClientObj->fantasymaster;
}
