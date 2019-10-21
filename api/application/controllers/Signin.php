<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Signin extends API_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Recovery_model');
    }

    /*
      Name: 		Login
      Description: 	Verify login and activate session
      URL: 			/api/signin/
     */

    public function index_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Keyword', 'Keyword', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Direct' ? '|required' : ''));
        $this->form_validation->set_rules('Password', 'Password', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Direct' ? '|required' : ''));
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Otp' ? '|required' : ''));
        $this->form_validation->set_rules('OTP', 'OTP', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Otp' ? '|required' : ''));
        $this->form_validation->set_rules('Source', 'Source', 'trim|required|callback_validateSource');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|required|callback_validateDeviceType');
        $this->form_validation->set_rules('IPAddress', 'IPAddress', 'trim|callback_validateIP');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if (!empty($this->Post['PhoneNumber']) && !empty($this->Post['OTP'])) {
            $UserID = $this->Recovery_model->verifyToken($this->Post['OTP'], 3);
            if (!$UserID) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Invalid OTP";
                exit;
            }
            $UserData = $this->Users_model->getUsers('UserTypeID,UserID,FirstName,MiddleName,LastName,Email,StatusID,ProfilePic,PhoneNumber,WalletAmount,ReferralCode,TotalCash', array('PhoneNumber' => $this->Post['PhoneNumber'], 'UserID' => $UserID));
        } else {
            $UserData = $this->Users_model->getUsers('UserTypeID,UserID,FirstName,MiddleName,LastName,Email,StatusID,ProfilePic,PhoneNumber,WalletAmount,ReferralCode,TotalCash', array('LoginKeyword' => @$this->Post['Keyword'], 'Password' => $this->Post['Password'], 'SourceID' => $this->SourceID));
        }

        if (!$UserData) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Invalid login credentials.";
        } elseif ($UserData && $UserData['StatusID'] == 1) {
            $this->Return['ResponseCode'] = 501;
            $this->Return['Message'] = "You have not activated your account yet, please verify your email address first.";
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
            /* Create Session */
            $UserData['SessionKey'] = $this->Users_model->createSession($UserData['UserID'], array(
                "IPAddress" => @$this->Post['IPAddress'],
                "SourceID" => $this->SourceID,
                "DeviceTypeID" => $this->DeviceTypeID,
                "DeviceGUID" => @$this->Post['DeviceGUID'],
                "DeviceToken" => @$this->Post['DeviceToken'],
                "Latitude" => @$this->Post['Latitude'],
                "Longitude" => @$this->Post['Longitude']
            ));
            $this->Return['Data'] = $UserData;
        }
        /* unset output parameters */
        unset($this->Return['Data']->UserID);
        unset($this->Return['Data']->StatusID);
        /* unset output parameters - ends */
    }

    /*
      Name: 		Signin with OTP
      Description: 	Signin using OTP
      URL: 			/api/signin/OtpSignIn/
     */

    public function OtpSignIn_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Otp' ? '|required' : ''));
        $this->form_validation->set_rules('Source', 'Source', 'trim|required|callback_validateSource');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get User Details */
        $UserData = $this->Users_model->getUsers('UserID,FirstName,LastName,Email,StatusID,PhoneNumber', array('PhoneNumber' => @$this->Post['PhoneNumber']));
        if(!$UserData){
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Invalid Phone number.";
        } elseif ($UserData && $UserData['StatusID'] == 1) {
            $this->Return['ResponseCode'] = 501;
            $this->Return['Message'] = "You have not activated your account yet, please verify your email address first.";
        } elseif ($UserData && $UserData['StatusID'] == 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Your account has been deleted. Please contact the Admin for more info.";
        } elseif ($UserData && $UserData['StatusID'] == 4) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Your account has been blocked. Please contact the Admin for more info.";
        } elseif ($UserData && $UserData['StatusID'] == 6) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You have deactivated your account, please contact the Admin to reactivate.";
        }else{

            /* Send OTP */
            $this->Utility_model->sendMobileSMS(array(
                'PhoneNumber' => $UserData['PhoneNumber'],
                'Text'        => $this->Recovery_model->generateToken($UserData['UserID'], 3)
            ));
            $this->Return['Data'] = $UserData;
        }
    }

    /*
      Name: 		Logout
      Description: 	Delete session
      URL: 			/api/signin/signout/
     */
    public function signout_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->deleteSession($this->Post['SessionKey']); /* Delete session */
    }
}
