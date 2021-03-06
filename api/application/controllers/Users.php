<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends API_Controller_Secure
{

    function __construct()
    {
        parent::__construct();
    }

    /*
      Name: 			updateUserInfo
      Description: 	Use to update user profile info.
      URL: 			/user/accountDeactivate/
     */

    public function accountDeactivate_post()
    {
        $this->Entity_model->updateEntityInfo($this->SessionUserID, array("StatusID" => 6));
        $this->Users_model->deleteSessions($this->SessionUserID);
        $this->Return['Message'] = "Your account has been deactivated.";
    }

    /*
      Name: 		toggleAccountDisplay
      Description: 	Use to hide account to others.
      URL: 			/user/toggleAccountDisplay/
     */

    public function toggleAccountDisplay_post()
    {
        $UserData = $this->Users_model->getUsers('StatusID', array('UserID' => $this->SessionUserID));
        if ($UserData['StatusID'] == 2) {
            $this->Entity_model->updateEntityInfo($this->SessionUserID, array("StatusID" => 8));
        } elseif ($UserData['StatusID'] == 8) {
            $this->Entity_model->updateEntityInfo($this->SessionUserID, array("StatusID" => 2));
        }
    }

    /*
      Name: 		search
      Description: 	Use to search users
      URL: 			/api/users/search
     */

    public function search_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Friend,Follow,Followers,Blocked]');
        $this->form_validation->set_rules('UserTypeID', 'UserTypeID', 'trim|in_list[2,3]');
        $this->form_validation->set_rules('PageNo', 'PageNo', 'trim|integer');
        $this->form_validation->set_rules('PageSize', 'PageSize', 'trim|integer');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $UserData = $this->Users_model->getUsers('Rating,ProfilePic', array(
            'SessionUserID' => $this->SessionUserID,
            'Keyword' => @$this->Post['Keyword'],
            'Filter' => @$this->Post['Filter'],
            'SpecialtyGUIDs' => @$this->Post['SpecialtyGUIDs'],
            'UserTypeID' => @$this->Post['UserTypeID'],
            'StatusID' => 2
        ), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($UserData) {
            $this->Return['Data'] = $UserData['Data'];
        }
    }

    /*
      Name: 		getProfile
      Description: 	Use to get user profile info.
      URL: 			/api/user/getProfile
     */

    public function getProfile_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        /* check for self profile or other user profile by GUID */
        $this->UserID = (!empty($this->UserID) ? $this->UserID : $this->SessionUserID);
        /* Basic fields to select */
        $UserData = $this->Users_model->getUsers((!empty($this->Post['Params']) ? $this->Post['Params'] : ''), array('UserID' => $this->UserID));
        if ($UserData) {

            /* To Get Withdrawal Configurations */
            $ConfigTypeValue = (MEMCACHE) ? $this->cache->memcached->get('MinimumWithdrawalLimitBank') : '';
            if(empty($ConfigData)){
                $ConfigData = $this->Utility_model->getConfigs(array('ConfigTypeGUID' => 'MinimumWithdrawalLimitBank'));
                $ConfigTypeValue = (!$ConfigData) ? 200 : $ConfigData['Data']['Records'][0]['ConfigTypeValue'];
                if(MEMCACHE){
                    $this->cache->memcached->save('MinimumWithdrawalLimitBank',$ConfigTypeValue, 3600 * 24 * 10); // Expire in every 10 Days 
                }
            }
            $UserData['ConfigTypeValue'] = $ConfigTypeValue;
            $this->Return['Data'] = $UserData;
        }
    }

    /*
      Name: 		updateUserInfo
      Description: 	Use to update user profile info.
      URL: 			/user/updateProfile/
     */

    public function updateUserInfo_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Email', 'Email', 'trim|valid_email|callback_validateEmail[' . $this->Post['SessionKey'] . ']', array('validateEmail' => 'Sorry, But this email is already verified.'));
        $this->form_validation->set_rules('Username', 'Username', 'trim|alpha_dash|callback_validateUsername[' . $this->Post['SessionKey'] . ']');
        $this->form_validation->set_rules('Gender', 'Gender', 'trim|in_list[Male,Female,Other]');
        $this->form_validation->set_rules('CountryCode', 'Country Code', 'trim|exact_length[2]');
        $this->form_validation->set_rules('BirthDate', 'BirthDate', 'trim|callback_validateDate');
        if (@$this->Post['PhoneNumber']) {
            $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|is_unique[tbl_users.PhoneNumber]|callback_validatePhoneNumber[' . $this->Post['SessionKey'] . ']', array('validatePhoneNumber' => 'Sorry, But this number is already verified.'));
        }
        $this->form_validation->set_message('is_unique', 'Sorry, But this {field} is already verified.');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->updateUserInfo($this->SessionUserID, $this->Post);
        $this->Return['Data'] = $this->Users_model->getUsers('FirstName,LastName,Email,ProfilePic,UserTypeID,UserTypeName', array("UserID" => $this->SessionUserID));
        $this->Return['Message'] = "Profile successfully updated.";
    }

    /*
      Name: 		changePassword
      Description: 	Use to change account login password by user.
      URL: 			/api/users/changePassword
     */

    public function changePassword_post()
    {
        /* Validation section */
        if (!$this->input->post('UserGUID')) {
            $this->form_validation->set_rules('CurrentPassword', 'Current Password', 'trim|callback_validatePassword');
        }
        $this->form_validation->set_rules('Password', 'Password', 'trim|required');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        if (!empty($this->input->post('UserGUID'))) {
            $Request = $this->Users_model->updateUserLoginInfo($this->UserID, array("Password" => $this->Post['Password']), DEFAULT_SOURCE_ID);
        } else {
            $Request = $this->Users_model->updateUserLoginInfo($this->SessionUserID, array("Password" => $this->Post['Password']), DEFAULT_SOURCE_ID);
        }
        if ($Request) {
            $this->Return['Message'] = "New password has been set.";
        }
    }

    /*
      Name: 		referEarn
      Description: 	Use to refer & earn user
      URL: 			/api/users/referEarn
     */
    public function referEarn_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('ReferType', 'Refer Type', 'trim|required|in_list[Phone,Email]');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim' . (!empty($this->Post['ReferType']) && $this->Post['ReferType'] == 'Phone' ? '|required|callback_validateAlreadyRegistered[Phone]' : ''));
        $this->form_validation->set_rules('Email', 'Email', 'trim' . (!empty($this->Post['ReferType']) && $this->Post['ReferType'] == 'Email' ? '|required|valid_email|callback_validateAlreadyRegistered[Email]' : ''));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->referEarn($this->Post, $this->SessionUserID);
        $this->Return['Message'] = "User successfully invited.";
    } 

    /* -----Validation Functions----- */
    /* ------------------------------ */
    function validatePassword($Password)
    {
        if (empty($Password)) {
            $this->form_validation->set_message('validatePassword', '{field} is required.');
            return FALSE;
        }
        $UserData = $this->Users_model->getUsers('', array('UserID' => $this->SessionUserID, 'Password' => $Password));
        if (!$UserData) {
            $this->form_validation->set_message('validatePassword', 'Invalid {field}.');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    /**
     * Function Name: validatePhoneVerification
     * Description:   To validate same phone already verified
     */
    public function validatePhoneVerification($PhoneNumber)
    {
        $Query = $this->db->query('SELECT PhoneNumber FROM tbl_users WHERE PhoneNumber = "'.$PhoneNumber.'" LIMIT 1');
        if ($Query->num_rows() > 0) {
            $this->form_validation->set_message('validatePhoneVerification', 'Current phone number already verified.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validateAlreadyRegistered
     * Description:   To validate already registered number or email
     */
    function validateAlreadyRegistered($Value, $FieldValue)
    {
        $Query = ($FieldValue == 'Email') ? 'SELECT Username FROM `tbl_users` WHERE `Email` = "' . $Value . '" OR `EmailForChange` = "' . $Value . '" LIMIT 1' : 'SELECT Username FROM `tbl_users` WHERE `PhoneNumber` = "' . $Value . '" OR `PhoneNumberForChange` = "' . $Value . '" LIMIT 1';
        if ($this->db->query($Query)->num_rows() > 0) {
            $this->form_validation->set_message('validateAlreadyRegistered', ($FieldValue == 'Email') ? 'Email is already registered' : 'Phone Number is already registered');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /* To get Avatars */
    public function getAvtars_post()
    {
        $Avatars = array();
        $avatarObj1 = new StdClass();
        $avatarObj1->AvatarId = '1';
        $avatarObj1->AvatarImg = '1.png';
        $avatarObj1->AvatarURL = base_url() . 'uploads/profile/picture/1.png';
        array_push($Avatars, $avatarObj1);
        $avatarObj2 = new StdClass();
        $avatarObj2->AvatarId = '2';
        $avatarObj2->AvatarImg = '2.png';
        $avatarObj2->AvatarURL = base_url() . 'uploads/profile/picture/2.png';
        array_push($Avatars, $avatarObj2);
        $avatarObj3 = new StdClass();
        $avatarObj3->AvatarId = '3';
        $avatarObj3->AvatarImg = '3.png';
        $avatarObj3->AvatarURL = base_url() . 'uploads/profile/picture/3.png';
        array_push($Avatars, $avatarObj3);
        $avatarObj4 = new StdClass();
        $avatarObj4->AvatarId = '4';
        $avatarObj4->AvatarImg = '4.png';
        $avatarObj4->AvatarURL = base_url() . 'uploads/profile/picture/4.png';
        array_push($Avatars, $avatarObj4);
        $avatarObj5 = new StdClass();
        $avatarObj5->AvatarId = '5';
        $avatarObj5->AvatarImg = '5.png';
        $avatarObj5->AvatarURL = base_url() . 'uploads/profile/picture/5.png';
        array_push($Avatars, $avatarObj5);
        $avatarObj6 = new StdClass();
        $avatarObj6->AvatarId = '6';
        $avatarObj6->AvatarImg = '6.png';
        $avatarObj6->AvatarURL = base_url() . 'uploads/profile/picture/6.png';
        array_push($Avatars, $avatarObj6);
        $avatarObj7 = new StdClass();
        $avatarObj7->AvatarId = '7';
        $avatarObj7->AvatarImg = '7.png';
        $avatarObj7->AvatarURL = base_url() . 'uploads/profile/picture/7.png';
        array_push($Avatars, $avatarObj7);
        $this->Return['Data']['Records'] = $Avatars;
    }
}
