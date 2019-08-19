<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Signup extends API_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Recovery_model');
        $this->load->model('Utility_model');
    }

    /*
      Name: 		Signup
      Description: 	Use to register user to system.
      URL: 			/api/signup/
     */

    public function index_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Email', 'Email', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Direct' ? '|required' : '') . '|valid_email|callback_validateEmail', array('validateEmail' => 'Sorry, But this email is already registered.'));
        $this->form_validation->set_rules('Username', 'Username', 'trim|alpha_dash|callback_validateUsername');
        $this->form_validation->set_rules('Password', 'Password', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Direct' ? '|required' : ''));
        $this->form_validation->set_rules('FirstName', 'FirstName', 'trim');
        $this->form_validation->set_rules('MiddleName', 'MiddleName', 'trim');
        $this->form_validation->set_rules('LastName', 'LastName', 'trim');
        $this->form_validation->set_rules('UserTypeID', 'UserTypeID', 'trim|required|in_list[2,3]');
        $this->form_validation->set_rules('Gender', 'Gender', 'trim|in_list[Male,Female,Other]');
        $this->form_validation->set_rules('BirthDate', 'BirthDate', 'trim|callback_validateDate');
        $this->form_validation->set_rules('Age', 'Age', 'trim|integer');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|callback_validatePhoneNumber|is_unique[tbl_users.PhoneNumber]', array('validatePhoneNumber' => 'Sorry, But this number is already registered.'));
        $this->form_validation->set_rules('Source', 'Source', 'trim|required|callback_validateSource');
        $this->form_validation->set_rules('SourceGUID', 'SourceGUID', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] != 'Direct' ? '|required' : '') . '|callback_validateSourceGUID[' . @$this->Post['Source'] . ']');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|required|callback_validateDeviceType');
        $this->form_validation->set_rules('IPAddress', 'IPAddress', 'trim|callback_validateIP');
        $this->form_validation->set_rules('ReferralCode', 'ReferralCode', 'trim|callback_validateReferralCode');
        $this->form_validation->set_message('is_unique', 'Sorry, But this {field} is already registered.');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        $UserID = $this->Users_model->addUser(array_merge($this->Post, array(
            "Referral" => @$this->Referral, 'Username' => randomString()
        )), $this->Post['UserTypeID'], $this->SourceID, ($this->Post['Source'] != 'Direct' ? '2' : '1') );
        if (!$UserID) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            if (!empty($this->Post['Email'])) {

                /* Send welcome Email to User with Token. (only if source is Direct) */
                send_mail(array(
                    'emailTo'       => $this->Post['Email'],
                    'template_id'   => 'd-8cae2914de5c4e3dbbf8d419e8777dbd',
                    'Subject'       => 'Thank you for registering at ' . SITE_NAME,
                    "Name"          => @$this->Post['FirstName'],
                    'Token'         => ($this->Post['Source'] == 'Direct' ? $this->Recovery_model->generateToken($UserID, 2) : ''),
                    'DeviceTypeID'  => $this->DeviceTypeID
                ));
            }

            /* for update phone number */
            if (!empty($this->Post['PhoneNumber']) && PHONE_NO_VERIFICATION) {

                /* Send change phonenumber SMS to User with Token. */
                $this->Utility_model->sendMobileSMS(array(
                    'PhoneNumber' => $this->Post['PhoneNumber'],
                    'Text' =>  $this->Recovery_model->generateToken($UserID, 3)
                ));
            }

            /* Referal code generate */
            $this->Utility_model->generateReferralCode($UserID);

            /* Send welcome notification */
            $this->Notification_model->addNotification('welcome', 'Welcome to ' . SITE_NAME . '!', $UserID, $UserID, '', 'Hi ' . @$this->Post['FirstName'] . ', Verify your Email and PAN Details and Earn more Cash Bonus.');
            $this->Notification_model->addNotification('welcome', @$this->Post['FirstName'] . ', got Registered', $UserID, ADMIN_ID);

            /* Get user data */
            $UserData = $this->Users_model->getUsers('FirstName,MiddleName,LastName,Email,ProfilePic,UserTypeID,UserTypeName', array(
                'UserID' => $UserID
            ));

            /* Create session only if source is not Direct and account treated as Verified. */
            $UserData['SessionKey'] = '';
            $UserData['SessionKey'] = $this->Users_model->createSession($UserID, array(
                                    "IPAddress" => @$this->Post['IPAddress'],
                                    "SourceID" => $this->SourceID,
                                    "DeviceTypeID" => $this->DeviceTypeID,
                                    "DeviceGUID" => @$this->Post['DeviceGUID'],
                                    "DeviceToken" => @$this->Post['DeviceToken']
                                ));
            $this->Return['Data'] = $UserData;
        }
    }

    /*
      Name: 		verifyEmail
      Description: 	Use to verify Email address and activate account by OTP.
      URL: 			/api/signup/verifyEmail
     */

    public function verifyEmail_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('OTP', 'OTP', 'trim|required|callback_validateToken[2]');
        $this->form_validation->set_rules('Source', 'Source', 'trim|required|callback_validateSource');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|required|callback_validateDeviceType');
        $this->form_validation->set_rules('DeviceGUID', 'DeviceGUID', 'trim');
        $this->form_validation->set_rules('DeviceToken', 'DeviceToken', 'trim');
        $this->form_validation->set_rules('IPAddress', 'IPAddress', 'trim|callback_validateIP');
        $this->form_validation->set_rules('Latitude', 'Latitude', 'trim');
        $this->form_validation->set_rules('Longitude', 'Longitude', 'trim');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        /* Verify Token */
        $UserID = $this->Recovery_model->verifyToken($this->Post['OTP'], 2);

        /* check for email update */
        $UserData = $this->Users_model->getUsers('UserTypeID,FirstName,LastName,Email,EmailForChange,StatusID,ProfilePic', array(
            'UserID' => $UserID
        ));
        if (!empty($UserData['EmailForChange'])) {
            if ($this->Users_model->updateEmail($UserID, $UserData['EmailForChange'])) {
                send_mail(array(
                    'emailTo'       => $UserData['Email'],
                    'template_id'   => 'd-e82c099a9b86439a9f5990722d59d0d6',
                    'Subject'       => 'Your' . SITE_NAME . ' email has been updated!',
                    "Name"          => $UserData['FirstName']
                ));
            }
        } else {
            /* change entity status to activate */
            $this->Entity_model->updateEntityInfo($UserID, array(
                "StatusID" => 2
            ));
            /* Create Session */
            $UserData['SessionKey'] = $this->Users_model->createSession($UserID, array(
                "IPAddress" => @$this->Post['IPAddress'],
                "SourceID" => $this->SourceID,
                "DeviceTypeID" => $this->DeviceTypeID,
                "DeviceGUID" => @$this->Post['DeviceGUID'],
                "DeviceToken" => @$this->Post['DeviceToken'],
                "Latitude" => @$this->Post['Latitude'],
                "Longitude" => @$this->Post['Longitude']
            ));
            $this->Return['Data'] = $UserData;
            $this->Return['Message'] = "Your account has been successfully verified, please login to get access your account.";
        }
        $this->Recovery_model->deleteToken($this->Post['OTP'], 2); /* delete token in any case */
    }

    /*
      Name: 		verifyPhoneNumber
      Description: 	Use to verify phone number and activate account by OTP.
      URL: 			/api/signup/verifyPhoneNumber
     */
    public function verifyPhoneNumber_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('OTP', 'OTP', 'trim|required|callback_validateToken[3]');
        $this->form_validation->set_rules('Source', 'Source', 'trim|callback_validateSource');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|callback_validateDeviceType');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */
        
        $UserID = $this->Recovery_model->verifyToken($this->Post['OTP'], 3);

        /* check for PhoneNo. update */
        $UserData = $this->Users_model->getUsers('UserTypeID,UserID,FirstName,MiddleName,LastName,Email,StatusID,ProfilePic,PhoneNumber,WalletAmount,ReferralCode,TotalCash,PhoneNumberForChange', array(
            'UserID' => $UserID
        ));

        if (!empty($UserData['PhoneNumberForChange'])) {
            if (!$this->Users_model->updatePhoneNumber($UserID, $UserData['PhoneNumberForChange'])) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "An error occurred. Please contact the Admin for more info.";
            } else {
                $this->Entity_model->updateEntityInfo($UserID, array("StatusID" => 2));
                $this->Return['Message'] = "Successfully verified.";
            }
        }
        $this->Recovery_model->deleteToken($this->Post['OTP'], 3); /* delete token in any case */

        /* Create Session */
        $UserData['SessionKey'] = $this->Users_model->createSession($UserID, array(
            "IPAddress" => @$this->Post['IPAddress'],
            "SourceID" => @$this->SourceID,
            "DeviceTypeID" => @$this->DeviceTypeID,
            "DeviceGUID" => @$this->Post['DeviceGUID'],
            "DeviceToken" => @$this->Post['DeviceToken'],
            "Latitude" => @$this->Post['Latitude'],
            "Longitude" => @$this->Post['Longitude']
        ));
        $this->Return['Data'] = $UserData;
    }

    /*
      Name: 		verify
      Description: 	Use to verify email link (For Web)
      URL: 			/api/signup/verify
     */
    public function verify_get()
    {
        $OTP = @$this->input->get('otp');
        $UserID = $this->Recovery_model->verifyToken($OTP, 2);
       
        if (!$UserID) {
            $Msg = "Sorry, but this is an invalid link, or you have already verified your account.";
        } else {
            $UserData = $this->Users_model->getUsers('UserTypeID,FirstName,LastName,Email,EmailForChange,StatusID,ProfilePic', array('UserID' => $UserID));

            /* change entity status to activate */
            $this->Entity_model->updateEntityInfo($UserID, array("StatusID" => 2));
            $this->Users_model->updateEmail($UserID, $UserData['EmailForChange']);
            $this->Recovery_model->deleteToken($OTP, 2); /* delete token in any case */
        }
        echo $this->load->view('email_verify', array('Error' => @$Msg, 'UserData' => @$UserData), true);
    }


    /*
      Name: 		resendverify
      Description: 	Use to resend OTP for Email address verification.
      URL: 			/api/signup/resendverify
     */

    public
    function resendverify_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Keyword', 'Keyword', 'trim|required|valid_email');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|required|callback_validateDeviceType');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        /* Get User Details */
        $UserData = $this->Users_model->getUsers('UserID, FirstName, StatusID', array(
            'Email' => $this->Post['Keyword']
        ));

        if (empty($UserData)) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "If your account is registered here you will receive an email from us.";
        } elseif ($UserData && $UserData['StatusID'] == 2) {
            $this->Return['Message'] = "Your account is already verified.";
        } elseif ($UserData && $UserData['StatusID'] == 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Your account has been deleted. Please contact the Admin for more info.";
        } elseif ($UserData && $UserData['StatusID'] == 4) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Your account has been blocked. Please contact the Admin for more info.";
        } elseif ($UserData && $UserData['StatusID'] == 6) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You have deactivated your account, please contact the Admin to reactivate.";
        } else {
            /* Re-Send welcome Email to User with Token. */
            send_mail(array(
                'emailTo'       => $this->Post['Keyword'],
                'template_id'   => 'd-8cae2914de5c4e3dbbf8d419e8777dbd',
                'Subject'       => 'Verify your account ' . SITE_NAME,
                "Name"          => $UserData['FirstName'],
                'Token'         => $this->Recovery_model->generateToken($UserData['UserID'], 2),
                'DeviceTypeID'  => $this->DeviceTypeID
            ));
            $this->Return['Message'] = "Please check your email for instructions.";
        }
    }

    /*
    Name:           resendVerification
    Description:    Use to resend verification OTP for Phone & Email.
    URL:            /api/signup/resendVerification
    */
    function resendVerification_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Type', 'Type', 'trim|required|in_list[Email,Phone]');
        $this->form_validation->set_rules('Email', 'Email', 'trim' . (($this->Post['Type'] == 'Email') ? '|valid_email|callback_validateEmail' : ''), array('validateEmail' => 'Sorry, But this email is already verified.'));
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim' . (($this->Post['Type'] == 'PhoneNumber') ? '|callback_validatePhoneNumber|is_unique[tbl_users.PhoneNumber]' : ''), array('validatePhoneNumber' => 'Sorry, But this number is already verified.'));
        $this->form_validation->set_message('is_unique', 'Sorry, But this {field} is already verified.');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get User Details */
        $UserID = (!empty($this->UserID)) ? $this->UserID : $this->SessionUserID;
        $UserData = $this->Users_model->getUsers('FirstName,LastName,Email,EmailForChange,PhoneNumberForChange,UserTypeID,UserTypeName', array("UserID" => $UserID));
        if ($this->Post['Type'] == 'Email') {

            /* Send welcome Email to User with Token. */
            send_mail(array(
                'emailTo'       => (!empty($this->Post['Email'])) ? $this->Post['Email'] : $UserData['EmailForChange'],
                'template_id'   => 'd-8cae2914de5c4e3dbbf8d419e8777dbd',
                'Subject'       => 'Verify your Email ' . SITE_NAME,
                "Name"          => $UserData['FirstName'],
                'Token'         => $this->Recovery_model->generateToken($UserID, 2)
            ));
        } else {

            /* Send change phonenumber SMS to User with Token. */
            $this->Utility_model->sendMobileSMS(array(
                'PhoneNumber' => (!empty($this->Post['PhoneNumber'])) ? $this->Post['PhoneNumber'] : $UserData['PhoneNumberForChange'],
                'Text' => $this->Recovery_model->generateToken($UserID, 3)
            ));
        }
    }

    
}
