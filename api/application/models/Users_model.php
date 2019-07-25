<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Utility_model');
    }

    /*
      Description: 	ADD user to system.
      Procedures:
      1. Add user to user table and get UserID.
      2. Save login info to users_login table.
      3. Save User details to users_profile table.
      4. Genrate a Token for Email verification and save to tokens table.
      5. Send welcome Email to User with Token.
     */
    function addUser($Input = array(), $UserTypeID, $SourceID, $StatusID = 1)
    {
        $this->db->trans_start();
        $EntityGUID = get_guid();

        /* Add user to entity table and get EntityID. */
        $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 1, "StatusID" => $StatusID));
        /* Add user to user table . */
        if (!empty($Input['PhoneNumber']) && PHONE_NO_VERIFICATION) {
            $Input['PhoneNumberForChange'] = $Input['PhoneNumber'];
            unset($Input['PhoneNumber']);
        }
        $InsertData = array_filter(array(
            "UserID" => $EntityID,
            "UserGUID" => $EntityGUID,
            "UserTypeID" => $UserTypeID,
            "StoreID" => @$Input['StoreID'],
            "FirstName" => @ucfirst(strtolower($Input['FirstName'])),
            "MiddleName" => @ucfirst(strtolower($Input['MiddleName'])),
            "LastName" => @ucfirst(strtolower($Input['LastName'])),
            "About" => @$Input['About'],
            "ProfilePic" => @$Input['ProfilePic'],
            "ProfileCoverPic" => @$Input['ProfileCoverPic'],
            "Email" => ($SourceID != 1) ? @strtolower($Input['Email']) : '',
            "EmailForChange" => ($SourceID == 1) ? @strtolower($Input['Email']) : '',
            "Username" => @strtolower($Input['Username']),
            "Gender" => @$Input['Gender'],
            "BirthDate" => @$Input['BirthDate'],
            "Address" => @$Input['Address'],
            "Address1" => @$Input['Address1'],
            "Postal" => @$Input['Postal'],
            "CountryCode" => @$Input['CountryCode'],
            "TimeZoneID" => @$Input['TimeZoneID'],
            "Latitude" => @$Input['Latitude'],
            "PanStatus" => @$Input['PanStatus'],
            "BankStatus" => @$Input['BankStatus'],
            "Longitude" => @$Input['Longitude'],
            "PhoneNumber" => @$Input['PhoneNumber'],
            "PhoneNumberForChange" => @$Input['PhoneNumberForChange'],
            "Website" => @strtolower($Input['Website']),
            "FacebookURL" => @strtolower($Input['FacebookURL']),
            "TwitterURL" => @strtolower($Input['TwitterURL']),
            "ReferredByUserID" => @$Input['Referral']->UserID,
        ));
        $this->db->insert('tbl_users', $InsertData);

        /* Manage Singup Bonus */
        $BonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "SignupBonus" LIMIT 1');
        if ($BonusData->row()->StatusID == 2) {
            $WalletData = array(
                "Amount" => $BonusData->row()->ConfigTypeValue,
                "CashBonus" => $BonusData->row()->ConfigTypeValue,
                "TransactionType" => 'Cr',
                "Narration" => 'Signup Bonus',
                "EntryDate" => date("Y-m-d H:i:s")
            );
            $this->addToWallet($WalletData, $EntityID, 5);
            $this->Notification_model->addNotification('bonus', 'Signup Bonus', $EntityID, $EntityID, '', '' . DEFAULT_CURRENCY . $BonusData->row()->ConfigTypeValue . 'has been credited in your Wallet');
        }

        /* Save login info to users_login table. */
        $InsertData = array_filter(array(
            "UserID" => $EntityID,
            "Password" => md5(($SourceID == '1' ? $Input['Password'] : $Input['SourceGUID'])),
            "SourceID" => $SourceID,
            "EntryDate" => date("Y-m-d H:i:s")
        ));
        $this->db->insert('tbl_users_login', $InsertData);

        /* save user settings */
        $this->db->insert('tbl_users_settings', array("UserID" => $EntityID));

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $EntityID;
    }

    /*
      Description: 	Use to update user profile info.
     */

    function updateUserInfo($UserID, $Input = array())
    {

        $UpdateArray = array_filter(array(
            "UserTypeID" => @$Input['UserTypeID'],
            "FirstName" => @ucfirst(strtolower($Input['FirstName'])),
            "MiddleName" => @ucfirst(strtolower($Input['MiddleName'])),
            "LastName" => @ucfirst(strtolower($Input['LastName'])),
            "About" => @$Input['About'],
            "About1" => @$Input['About1'],
            "About2" => @$Input['About2'],
            "ProfilePic" => @$Input['ProfilePic'],
            "ProfileCoverPic" => @$Input['ProfileCoverPic'],
            "Email" => @strtolower($Input['Email']),
            "Username" => @strtoupper($Input['Username']),
            "Gender" => @$Input['Gender'],
            "BirthDate" => @$Input['BirthDate'],
            "Age" => @$Input['Age'],
            "Height" => @$Input['Height'],
            "Weight" => @$Input['Weight'],
            "Address" => @$Input['Address'],
            "Address1" => @$Input['Address1'],
            "Postal" => @$Input['Postal'],
            "CountryCode" => @$Input['CountryCode'],
            "TimeZoneID" => @$Input['TimeZoneID'],
            "CityName" => @$Input['CityName'],
            "StateName" => @$Input['StateName'],
            "Latitude" => @$Input['Latitude'],
            "Longitude" => @$Input['Longitude'],
            "LanguageKnown" => @$Input['LanguageKnown'],
            "PhoneNumber" => @$Input['PhoneNumber'],
            "IsPrivacyNameDisplay" => @$Input['IsPrivacyNameDisplay'],
            "Website" => @strtolower($Input['Website']),
            "FacebookURL" => @strtolower($Input['FacebookURL']),
            "TwitterURL" => @strtolower($Input['TwitterURL']),
            "GoogleURL" => @strtolower($Input['GoogleURL']),
            "InstagramURL" => @strtolower($Input['InstagramURL']),
            "LinkedInURL" => @strtolower($Input['LinkedInURL']),
            "WhatsApp" => @strtolower($Input['WhatsApp'])
        ));

        if (isset($Input['LastName']) && $Input['LastName'] == '') {
            $UpdateArray['LastName'] = null;
        }
        if (isset($Input['Username']) && $Input['Username'] == '') {
            $UpdateArray['Username'] = null;
        }
        if (isset($Input['Gender']) && $Input['Gender'] == '') {
            $UpdateArray['Gender'] = null;
        }
        if (isset($Input['BirthDate']) && $Input['BirthDate'] == '') {
            $UpdateArray['BirthDate'] = null;
        }
        if (isset($Input['Address']) && $Input['Address'] == '') {
            $UpdateArray['Address'] = null;
        }
        if (isset($Input['PhoneNumber']) && $Input['PhoneNumber'] == '') {
            $UpdateArray['PhoneNumber'] = null;
        }
        if (isset($Input['Website']) && $Input['Website'] == '') {
            $UpdateArray['Website'] = null;
        }
        if (isset($Input['FacebookURL']) && $Input['FacebookURL'] == '') {
            $UpdateArray['FacebookURL'] = null;
        }
        if (isset($Input['TwitterURL']) && $Input['TwitterURL'] == '') {
            $UpdateArray['TwitterURL'] = null;
        }
        if (isset($Input['PhoneNumber']) && $Input['PhoneNumber'] == '') {
            $UpdateArray['PhoneNumber'] = null;
        }

        /* for change email address */
        if (!empty($UpdateArray['Email']) || !empty($UpdateArray['PhoneNumber'])) {
            $UserData = $this->Users_model->getUsers('Email,FirstName,PhoneNumber', array('UserID' => $UserID));
        }

        /* for update email address */
        if (!empty($UpdateArray['Email'])) {
            if ($UserData['Email'] != $UpdateArray['Email']) {
                $UpdateArray['EmailForChange'] = $UpdateArray['Email'];

                /* Genrate a Token for Email verification and save to tokens table. */
                send_mail(array(
                    'emailTo' => $UpdateArray['EmailForChange'],
                    'template_id' => 'd-c9a4320dc3f740799d1d5861e032df59',
                    'Subject' => SITE_NAME . ", OTP for change of email address",
                    "Name" => $UserData['FirstName'],
                    'Token' => $this->Recovery_model->generateToken($UserID, 2)
                ));
                unset($UpdateArray['Email']);
            }
        }


        /* for update phone number */
        if (!empty($UpdateArray['PhoneNumber']) && PHONE_NO_VERIFICATION && !isset($Input['SkipPhoneNoVerification'])) {
            if ($UserData['PhoneNumber'] != $UpdateArray['PhoneNumber']) {

                $UpdateArray['PhoneNumberForChange'] = $UpdateArray['PhoneNumber'];

                /* Send change phonenumber SMS to User with Token. */
                $this->load->model('Recovery_model');
                $this->Utility_model->sendMobileSMS(array(
                    'PhoneNumber' => $UpdateArray['PhoneNumberForChange'],
                    'Text' => $this->Recovery_model->generateToken($UserID, 3)
                ));
                unset($UpdateArray['PhoneNumber']);
            }
        }
        if (!empty($Input['PanStatus'])) {
            $UpdateArray['PanStatus'] = $Input['PanStatus'];
            $Type = 'Pancard';
            if (!empty($Input['Comments'])) {
                $MediaData = $this->Media_model->getMedia('MediaGUID, M.MediaCaption', array("SectionID" => 'PAN', "EntityID" => $UserID), FALSE);
            }
        }
        if (!empty($Input['BankStatus'])) {
            $Type = 'Bank Detail';
            $UpdateArray['BankStatus'] = $Input['BankStatus'];
            if (!empty($Input['Comments'])) {
                $MediaData = $this->Media_model->getMedia('MediaGUID, M.MediaCaption', array("SectionID" => 'BankDetail', "EntityID" => $UserID), FALSE);
            }
        }

        if (!empty($Input['Comments'])) {
            $MediaGUID = $MediaData['MediaGUID'];
            $Caption = json_decode($MediaData['MediaCaption']);
            $Caption->RejectReason = $Input['Comments'];
            $UpdateCaption['MediaCaption'] = json_encode($Caption);

            /* Update Verification Comment */
            $this->db->where('MediaGUID', $MediaGUID);
            $this->db->limit(1);
            $this->db->update('tbl_media', $UpdateCaption);

            $this->Notification_model->addNotification('verify', 'Verification Rejected', $UserID, $UserID, '', 'Your ' . $Type . ' verification has been Rejected due to ' . $Input['Comments']);
        }

        if (!empty($Input['UpdateBankInfo']) && $Input['UpdateBankInfo'] == 'Yes') {
            $MediaData = $this->Media_model->getMedia('MediaGUID, M.MediaCaption', array("SectionID" => 'BankDetail', "EntityID" => $UserID), FALSE);

            $Caption = json_decode($MediaData['MediaCaption']);
            $Caption->FullName      = $Input['Name'];
            $Caption->Bank          = $Input['Bank'];
            $Caption->AccountNumber = $Input['AccountNumber'];
            $Caption->IFSCCode      = $Input['IFSCCode'];
            $UpdateCaption['MediaCaption'] = json_encode($Caption);

            /* Update Bank Info */
            $this->db->where('MediaGUID', $MediaData['MediaGUID']);
            $this->db->limit(1);
            $this->db->update('tbl_media', $UpdateCaption);
        }

        /* Update User details to users table. */
        if (!empty($UpdateArray)) {
            $this->db->where('UserID', $UserID);
            $this->db->limit(1);
            $this->db->update('tbl_users', $UpdateArray);

            if ($UpdateArray['BankStatus'] == 2 || $UpdateArray['PanStatus'] == 2) {
                $this->Notification_model->addNotification('verify', 'Verified', $UserID, $UserID, '', 'Your ' . $Type . ' verification has been Approved.');
            }
        }

        if (!empty($Input['InterestGUIDs'])) {
            /* Revoke categories - starts */
            $this->db->where(array("EntityID" => $UserID));
            $this->db->delete('tbl_entity_categories');
            /* Revoke categories - ends */

            /* Assign categories - starts */
            $this->load->model('Category_model');
            foreach ($Input['InterestGUIDs'] as $CategoryGUID) {
                $CategoryData = $this->Category_model->getCategories('CategoryID', array('CategoryGUID' => $CategoryGUID));
                if ($CategoryData) {
                    $InsertCategory[] = array('EntityID' => $UserID, 'CategoryID' => $CategoryData['CategoryID']);
                }
            }
            if (!empty($InsertCategory)) {
                $this->db->insert_batch('tbl_entity_categories', $InsertCategory);
            }
            /* Assign categories - ends */
        }


        if (!empty($Input['SpecialtyGUIDs'])) {
            /* Revoke categories - starts */
            $this->db->where(array("EntityID" => $UserID));
            $this->db->delete('tbl_entity_categories');
            /* Revoke categories - ends */

            /* Assign categories - starts */
            $this->load->model('Category_model');
            foreach ($Input['SpecialtyGUIDs'] as $CategoryGUID) {
                $CategoryData = $this->Category_model->getCategories('CategoryID', array('CategoryGUID' => $CategoryGUID));
                if ($CategoryData) {
                    $InsertCategory[] = array('EntityID' => $UserID, 'CategoryID' => $CategoryData['CategoryID']);
                }
            }
            if (!empty($InsertCategory)) {
                $this->db->insert_batch('tbl_entity_categories', $InsertCategory);
            }
            /* Assign categories - ends */
        }

        $this->Entity_model->updateEntityInfo($UserID, array('StatusID' => @$Input['StatusID']));
        return TRUE;
    }

    /*
      Description: 	Use to set user new password.
     */

    function updateUserLoginInfo($UserID, $Input = array(), $SourceID)
    {
        $UpdateArray = array_filter(array(
            "Password" => (!empty($Input['Password']) ? md5($Input['Password']) : ''),
            "ModifiedDate	" => (!empty($Input['Password']) ? date("Y-m-d H:i:s") : ''),
            "LastLoginDate" => @$Input['LastLoginDate']
        ));

        /* Update User Login details */
        $this->db->where(array('UserID' => $UserID, 'SourceID' => $SourceID));
        $this->db->limit(1);
        $this->db->update('tbl_users_login', $UpdateArray);

        if (!empty($Input['Password'])) {
            /* Send Password Assistance Email to User with Token (If user is not Pending or Email-Confirmed then email send without Token). */
            $UserData = $this->db->query('SELECT FirstName,Email FROM tbl_users WHERE UserID = ' . $UserID . ' LIMIT 1');
            send_mail(array(
                'emailTo' => $UserData->row()->Email,
                'template_id' => 'd-574034ab7ba64733bdfbd7edcde56a7c',
                'Subject' => SITE_NAME . " Password Assistance",
                "Name" => $UserData->row()->FirstName
            ));
        }
        return TRUE;
    }

    /*
      Description: 	Use to set new email address of user.
     */

    function updateEmail($UserID, $Email)
    {
        /* check new email address is not in use */
        if ($this->db->query('SELECT UserID FROM tbl_users WHERE Email = "' . $Email . '" LIMIT 1')->num_rows() == 0) {
            $this->db->trans_start();

            /* update profile table */
            $this->db->where('UserID', $UserID);
            $this->db->limit(1);
            $this->db->update('tbl_users', array("Email" => $Email, "EmailForChange" => null));

            /* Delete session */
            $this->db->limit(1);
            $this->db->delete('tbl_users_session', array('UserID' => $UserID));
            /* Delete session - ends */

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /*
      Description: 	Use to set new email address of user.
     */

    function updatePhoneNumber($UserID, $PhoneNumber)
    {
        /* check new PhoneNumber is not in use */
        $UserData = $this->Users_model->getUsers('StatusID,PanStatus,BankStatus,PhoneNumber', array('PhoneNumber' => $PhoneNumber));
        if (!$UserData) {
            $this->db->trans_start();
            /* update profile table */
            $this->db->where('UserID', $UserID);
            $this->db->limit(1);
            $this->db->update('tbl_users', array("PhoneNumber" => $PhoneNumber, "PhoneNumberForChange" => null));

            /* change entity status to activate */
            if ($UserData['StatusID'] == 1) {
                $this->Entity_model->updateEntityInfo($UserID, array("StatusID" => 2));
            }

            /* Manage Verification Bonus */
            if ($UserData['PanStatus'] == 'Verified' && $UserData['BankStatus'] == 'Verified' && empty($UserData['PhoneNumber'])) {
                $BonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "VerificationBonus" LIMIT 1');
                if ($BonusData->row()->StatusID == 2) {
                    $WalletData = array(
                        "Amount" => $BonusData->row()->ConfigTypeValue,
                        "CashBonus" => $BonusData->row()->ConfigTypeValue,
                        "TransactionType" => 'Cr',
                        "Narration" => 'Verification Bonus',
                        "EntryDate" => date("Y-m-d H:i:s")
                    );
                    $this->addToWallet($WalletData, $UserID, 5);
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /*
      Description: 	Use to get single user info or list of users.
      Note:			$Field should be comma seprated and as per selected tables alias.
     */

    function getUsers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'RegisteredOn' => 'DATE_FORMAT(CONVERT_TZ(E.EntryDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") RegisteredOn',
                'LastLoginDate' => 'DATE_FORMAT(CONVERT_TZ(UL.LastLoginDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") LastLoginDate',
                'Rating' => 'E.Rating',
                'UserTypeName' => 'UT.UserTypeName',
                'IsAdmin' => 'UT.IsAdmin',
                'UserID' => 'U.UserID',
                'UserTypeID' => 'U.UserTypeID',
                'FirstName' => 'U.FirstName',
                'MiddleName' => 'U.MiddleName',
                'LastName' => 'U.LastName',
                'ProfilePic' => 'IF(U.ProfilePic IS NULL,CONCAT("' . BASE_URL . '","uploads/profile/picture/","user-img.svg"),CONCAT("' . BASE_URL . '","uploads/profile/picture/",U.ProfilePic)) AS ProfilePic',
                'ProfileCoverPic' => 'IF(U.ProfilePic IS NULL,CONCAT("' . BASE_URL . '","uploads/profile/cover/","default.jpg"),CONCAT("' . BASE_URL . '","uploads/profile/picture/",U.ProfileCoverPic)) AS ProfileCoverPic',
                'About' => 'U.About',
                'About1' => 'U.About1',
                'About2' => 'U.About2',
                'Email' => 'U.Email',
                'EmailForChange' => 'U.EmailForChange',
                'Username' => 'U.Username',
                'Gender' => 'U.Gender',
                'BirthDate' => 'U.BirthDate',
                'Address' => 'U.Address',
                'Address1' => 'U.Address1',
                'Postal' => 'U.Postal',
                'CountryCode' => 'U.CountryCode',
                'CountryName' => 'CO.CountryName',
                'CityName' => 'U.CityName',
                'StateName' => 'U.StateName',
                'PhoneNumber' => 'U.PhoneNumber',
                'Email' => 'U.Email',
                'PhoneNumberForChange' => 'U.PhoneNumberForChange',
                'Website' => 'U.Website',
                'FacebookURL' => 'U.FacebookURL',
                'TwitterURL' => 'U.TwitterURL',
                'GoogleURL' => 'U.GoogleURL',
                'InstagramURL' => 'U.InstagramURL',
                'LinkedInURL' => 'U.LinkedInURL',
                'WhatsApp' => 'U.WhatsApp',
                'WalletAmount' => 'U.WalletAmount',
                'WinningAmount' => 'U.WinningAmount',
                'CashBonus' => 'U.CashBonus',
                'TotalCash' => '(U.WalletAmount + U.WinningAmount + U.CashBonus) AS TotalCash',
                'ReferralCode' => '(SELECT ReferralCode FROM tbl_referral_codes WHERE tbl_referral_codes.UserID=U.UserID LIMIT 1) AS ReferralCode',
                'ReferredByUserID' => 'U.ReferredByUserID',
                'ModifiedDate' => 'E.ModifiedDate',
                'Status' => 'CASE E.StatusID
										when "1" then "Pending"
										when "2" then "Verified"
										when "3" then "Deleted"
										when "4" then "Blocked"
										when "8" then "Hidden"		
									END as Status',
                'PanStatus' => 'CASE U.PanStatus
										when "1" then "Pending"
										when "2" then "Verified"
                                        when "3" then "Rejected"    
										when "9" then "Not Submitted"   
									END as PanStatus',
                'BankStatus' => 'CASE U.BankStatus
    										when "1" then "Pending"
    										when "2" then "Verified"
    										when "3" then "Rejected"	
                                            when "9" then "Not Submitted"   
    									END as BankStatus',
                'ReferredCount' => '(SELECT COUNT(UserGUID) FROM `tbl_users` WHERE `ReferredByUserID` = U.UserID) AS ReferredCount',
                'StatusID' => 'E.StatusID',
                'PanStatusID' => 'U.PanStatus',
                'BankStatusID' => 'U.BankStatus',
                'IsPrivacyNameDisplay' => 'U.IsPrivacyNameDisplay',
                'PushNotification' => 'US.PushNotification',
                'PhoneStatus' => 'IF(U.PhoneNumber IS NULL, "Pending", "Verified") as PhoneStatus',
                'EmailStatus' => 'IF(U.Email IS NULL, "Pending", "Verified") as EmailStatus'
            );
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('U.UserGUID, U.UserID,  CONCAT_WS(" ",U.FirstName,U.LastName) FullName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);


        /* distance calculation - starts */
        /* this is called Haversine formula and the constant 6371 is used to get distance in KM, while 3959 is used to get distance in miles. */
        if (!empty($Where['Latitude']) && !empty($Where['Longitude'])) {
            $this->db->select("(3959*acos(cos(radians(" . $Where['Latitude'] . "))*cos(radians(E.Latitude))*cos(radians(E.Longitude)-radians(" . $Where['Longitude'] . "))+sin(radians(" . $Where['Latitude'] . "))*sin(radians(E.Latitude)))) AS Distance", false);
            $this->db->order_by('Distance', 'ASC');

            if (!empty($Where['Radius'])) {
                $this->db->having("Distance <= " . $Where['Radius'], null, false);
            }
        }
        /* distance calculation - ends */

        $this->db->from('tbl_entity E');
        $this->db->from('tbl_users U');
        $this->db->where("U.UserID", "E.EntityID", FALSE);

        if (array_keys_exist($Params, array('UserTypeName', 'IsAdmin')) || !empty($Where['IsAdmin'])) {
            $this->db->from('tbl_users_type UT');
            $this->db->where("UT.UserTypeID", "U.UserTypeID", FALSE);
        }
        $this->db->join('tbl_users_login UL', 'U.UserID = UL.UserID', 'left');
        $this->db->join('tbl_users_settings US', 'U.UserID = US.UserID', 'left');

        if (array_keys_exist($Params, array('CountryName'))) {
            $this->db->join('set_location_country CO', 'U.CountryCode = CO.CountryCode', 'left');
        }
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = trim($Where['Keyword']);
            $this->db->group_start();
            $this->db->like("U.FirstName", $Where['Keyword']);
            $this->db->or_like("U.LastName", $Where['Keyword']);
            $this->db->or_like("U.Username", $Where['Keyword']);
            $this->db->or_like("U.Email", $Where['Keyword']);
            $this->db->or_like("U.EmailForChange", $Where['Keyword']);
            $this->db->or_like("U.PhoneNumber", $Where['Keyword']);
            $this->db->or_like("U.PhoneNumberForChange", $Where['Keyword']);
            $this->db->or_like("CONCAT_WS('',U.FirstName,U.Middlename,U.LastName)", preg_replace('/\s+/', '', $Where['Keyword']), FALSE);
            $this->db->group_end();
        }

        if (!empty($Where['SourceID'])) {
            $this->db->where("UL.SourceID", $Where['SourceID']);
        }
        if (!empty($Where['UserTypeID'])) {
            $this->db->where_in("U.UserTypeID", $Where['UserTypeID']);
        }
        if (!empty($Where['UserIDIn'])) {
            $this->db->where_in("U.UserID", $Where['UserIDIn']);
        }
        if (!empty($Where['UserTypeIDNot']) && $Where['UserTypeIDNot'] == 'Yes') {
            $this->db->where("U.UserTypeID!=", $Where['UserTypeIDNot']);
        }
        if (!empty($Where['UserID'])) {
            $this->db->where("U.UserID", $Where['UserID']);
        }
        if (!empty($Where['UserIDNot'])) {
            $this->db->where("U.UserID!=", $Where['UserIDNot']);
        }
        if (!empty($Where['UserGUID'])) {
            $this->db->where("U.UserGUID", $Where['UserGUID']);
        }
        if (!empty($Where['ReferredByUserID'])) {
            $this->db->where("U.ReferredByUserID", $Where['ReferredByUserID']);
        }
        if (!empty($Where['Username'])) {
            $this->db->where("U.Username", $Where['Username']);
        }
        if (!empty($Where['Email'])) {
            $this->db->where("U.Email", $Where['Email']);
        }
        if (!empty($Where['PhoneNumber'])) {
            $this->db->where("U.PhoneNumber", $Where['PhoneNumber']);
        }
        if (!empty($Where['LoginKeyword'])) {
            $this->db->group_start();
            $this->db->where("U.Email", $Where['LoginKeyword']);
            $this->db->or_where("U.EmailForChange", $Where['LoginKeyword']);
            $this->db->or_where("U.Username", $Where['LoginKeyword']);
            $this->db->or_where("U.PhoneNumber", $Where['LoginKeyword']);
            $this->db->group_end();
        }
        if (!empty($Where['Password'])) {
            $this->db->where("UL.Password", md5($Where['Password']));
        }

        if (!empty($Where['IsAdmin'])) {
            $this->db->where("UT.IsAdmin", $Where['IsAdmin']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['PanStatus'])) {
            $this->db->where("U.PanStatus", $Where['PanStatus']);
        }
        if (!empty($Where['BankStatus'])) {
            $this->db->where("U.BankStatus", $Where['BankStatus']);
        }

        if (!empty($Where['EntryFrom'])) {
            $this->db->where("DATE(E.EntryDate) >=", $Where['EntryFrom']);
        }
        if (!empty($Where['EntryTo'])) {
            $this->db->where("DATE(E.EntryDate) <=", $Where['EntryTo']);
        }
        if (!empty($Where['ListType'])) {
            $this->db->where("DATE(E.EntryDate) =", date("Y-m-d"));
        }
        if (!empty($Where['ForVerify']) && $Where['ForVerify'] == 'Yes') {
            $this->db->where("U.PanStatus !=", 9);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence']) && in_array($Where['Sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('U.UserID', 'DESC');
        }


        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
        } else {
            $this->db->limit(1);
        }

        $Query = $this->db->get();
       
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $Record) {

                /* get attached media */
                if (in_array('MediaPAN', $Params)) {
                    $MediaData = $this->Media_model->getMedia('MediaGUID,DATE_FORMAT(CONVERT_TZ(EntryDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") EntryDate, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,"110_",M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,	M.MediaCaption', array("SectionID" => 'PAN', "EntityID" => $Record['UserID']), FALSE);
                    $Record['MediaPAN'] = ($MediaData ? $MediaData : array('EntryDate' => '', 'MediaGUID' => '', 'MediaURL' => '', 'MediaThumbURL' => '', 'MediaCaption' => ''));
                }

                if (in_array('MediaBANK', $Params)) {
                    $MediaData = $this->Media_model->getMedia('MediaGUID,DATE_FORMAT(CONVERT_TZ(EntryDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") EntryDate, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,"110_",M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,	M.MediaCaption', array("SectionID" => 'BankDetail', "EntityID" => $Record['UserID']), FALSE);
                    $Record['MediaBANK'] = ($MediaData ? $MediaData : array('EntryDate' => '', 'MediaGUID' => '', 'MediaURL' => '', 'MediaThumbURL' => '', 'MediaCaption' => ''));
                }

                /* Get Wallet Data */
                if (in_array('Wallet', $Params)) {
                    $WalletData = $this->getWallet('Amount,Currency,PaymentGateway,TransactionType,TransactionID,EntryDate,Narration,Status,OpeningBalance,ClosingBalance', array('UserID' => $Where['UserID'], 'TransactionMode' => 'WalletAmount'), TRUE);
                    $Record['Wallet'] = ($WalletData) ? $WalletData['Data']['Records'] : array();
                }

                /* Get Playing History Data */
                if (in_array('PlayingHistory', $Params)) {
                    $Record['PlayingHistory'] = $this->db->query('SELECT (
                                                        SELECT COUNT(DISTINCT JC.MatchID) FROM sports_contest_join JC, tbl_entity E  WHERE JC.ContestID = E.EntityID AND E.StatusID != 3 AND JC.UserID = "' . $Where['UserID'] . '"
                                                        ) TotalJoinedMatches,
                                                        (SELECT COUNT(DISTINCT S.SeriesID) FROM sports_contest_join CJ, sports_matches M,sports_series S,tbl_entity E WHERE E.EntityID = CJ.ContestID AND E.StatusID != 3 AND S.SeriesID = M.SeriesID AND CJ.MatchID = M.MatchID AND CJ.UserID = "' . $Where['UserID'] . '" ) TotalJoinedSeries,
                                                        (SELECT COUNT(JC.ContestID) FROM sports_contest_join JC,tbl_entity E WHERE JC.ContestID = E.EntityID AND E.StatusID != 3 AND JC.UserID = "' . $Where['UserID'] . '" ) TotalJoinedContest,
                                                        (SELECT COUNT(JC.ContestID) FROM sports_contest_join JC,tbl_entity E WHERE JC.ContestID = E.EntityID AND E.StatusID != 3 AND JC.UserID = "' . $Where['UserID'] . '" AND JC.UserWinningAmount > 0 ) TotalJoinedContestWinning')->row();
                }
                if (!$multiRecords) {
                    return $Record;
                }
                $Records[] = $Record;
            }
            $Return['Data']['Records'] = $Records;
            return $Return;
        }
        return FALSE;
    }

    /*
      Description: 	Use to create session.
     */

    function createSession($UserID, $Input = array())
    {
        /* Multisession handling */
        if (!MULTISESSION) {
            $this->db->delete('tbl_users_session', array('UserID' => $UserID));
        }

        /* Multisession handling - ends */
        $InsertData = array_filter(array(
            'UserID' => $UserID,
            'SessionKey' => get_guid(),
            'IPAddress' => @$Input['IPAddress'],
            'SourceID' => (!empty($Input['SourceID']) ? $Input['SourceID'] : DEFAULT_SOURCE_ID),
            'DeviceTypeID' => (!empty($Input['DeviceTypeID']) ? $Input['DeviceTypeID'] : DEFAULT_DEVICE_TYPE_ID),
            'DeviceGUID' => @$Input['DeviceGUID'],
            'DeviceToken' => @$Input['DeviceToken'],
            'EntryDate' => date("Y-m-d H:i:s"),
        ));

        $this->db->insert('tbl_users_session', $InsertData);
        /* update current date of login */
        $this->updateUserLoginInfo($UserID, array("LastLoginDate" => date("Y-m-d H:i:s")), $InsertData['SourceID']);
        /* Update Latitude, Longitude */
        if (!empty($Input['Latitude']) && !empty($Input['Longitude'])) {
            $this->updateUserInfo($UserID, array("Latitude" => $Input['Latitude'], "Longitude" => $Input['Longitude']));
        }
        return $InsertData['SessionKey'];
    }

    /*
      Description: 	Use to get UserID by SessionKey and validate SessionKey.
     */

    function checkSession($SessionKey)
    {
        $this->db->select('UserID');
        $this->db->from('tbl_users_session');
        $this->db->where("SessionKey", $SessionKey);
        $this->db->limit(1);
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            return $Query->row()->UserID;
        }
        return FALSE;
    }

    /*
      Description: 	Use to delete Session.
     */
    function deleteSession($SessionKey)
    {
        $this->db->limit(1);
        $this->db->delete('tbl_users_session', array('SessionKey' => $SessionKey));
        return TRUE;
    }

    /*
      Description: To user refer & earn
    */
    function referEarn($Input = array(), $SessionUserID)
    {
        /* Get User Details */
        $UserData = $this->Users_model->getUsers('FirstName,ReferralCode', array('UserID' => $SessionUserID));
        $InviteURL = SITE_HOST . ROOT_FOLDER . 'authenticate?referral=' . $UserData['ReferralCode'];
        if ($Input['ReferType'] == 'Email' && !empty($Input['Email'])) {
            send_mail(array(
                'emailTo' => $Input['Email'],
                'template_id' => 'd-e20ad55aa8fd4c59b0c314a0a0086311',
                'Subject' => 'Refer & Earn - ' . SITE_NAME,
                "Name" => $UserData['FirstName'],
                "ReferralCode" => $UserData['ReferralCode'],
                'ReferralURL' => $InviteURL
            ));
        } else if ($Input['ReferType'] == 'Phone' && !empty($Input['PhoneNumber'])) {

            /* Send referral SMS to User with referral url */
            $this->Utility_model->sendSMS(array(
                'PhoneNumber' => $Input['PhoneNumber'],
                'Text' => "Your Friend " . $UserData['FirstName'] . " just got registered with us and has referred you. Use his/her referral code: " . $UserData['ReferralCode'] . " Use the link provided to get " . DEFAULT_CURRENCY . REFERRAL_SIGNUP_BONUS . " signup bonus. " . $InviteURL
            ));
        }
    }

    /* -----Wallet Functions----- */
    /* --------------------------- */

    /*
      Description: To add data into user wallet
     */
    function addToWallet($Input = array(), $UserID, $StatusID = 1)
    {
        $this->db->trans_start();
        $OpeningWalletAmount = $this->getUserWalletOpeningBalance($UserID, 'ClosingWalletAmount');
        $OpeningWinningAmount = $this->getUserWalletOpeningBalance($UserID, 'ClosingWinningAmount');
        $OpeningCashBonus = $this->getUserWalletOpeningBalance($UserID, 'ClosingCashBonus');
        $InsertData = array_filter(array(
            "UserID" => $UserID,
            "Amount" => @$Input['Amount'],
            "OpeningWalletAmount" => $OpeningWalletAmount,
            "OpeningWinningAmount" => $OpeningWinningAmount,
            "OpeningCashBonus" => $OpeningCashBonus,
            "WalletAmount" => @$Input['WalletAmount'],
            "WinningAmount" => @$Input['WinningAmount'],
            "CashBonus" => @$Input['CashBonus'],
            "ClosingWalletAmount" => ($StatusID == 5) ? (($OpeningWalletAmount != 0) ? ((@$Input['TransactionType'] == 'Cr') ? $OpeningWalletAmount + @$Input['WalletAmount'] : $OpeningWalletAmount - @$Input['WalletAmount']) : @$Input['WalletAmount']) : $OpeningWalletAmount,
            "ClosingWinningAmount" => ($StatusID == 5) ? (($OpeningWinningAmount != 0) ? ((@$Input['TransactionType'] == 'Cr') ? $OpeningWinningAmount + @$Input['WinningAmount'] : $OpeningWinningAmount - @$Input['WinningAmount']) : @$Input['WinningAmount']) : $OpeningWinningAmount,
            "ClosingCashBonus" => ($StatusID == 5) ? (($OpeningCashBonus != 0) ? ((@$Input['TransactionType'] == 'Cr') ? $OpeningCashBonus + @$Input['CashBonus'] : $OpeningCashBonus - @$Input['CashBonus']) : @$Input['CashBonus']) : $OpeningCashBonus,
            "Currency" => @$Input['Currency'],
            "CouponCode" => @$Input['CouponCode'],
            "PaymentGateway" => @$Input['PaymentGateway'],
            "TransactionType" => @$Input['TransactionType'],
            "TransactionID" => (!empty($Input['TransactionID'])) ? $Input['TransactionID'] : substr(hash('sha256', mt_rand() . microtime()), 0, 20),
            "Narration" => @$Input['Narration'],
            "EntityID" => @$Input['EntityID'],
            "UserTeamID" => @$Input['UserTeamID'],
            "CouponDetails" => @$Input['CouponDetails'],
            "PaymentGatewayResponse" => @$Input['PaymentGatewayResponse'],
            "EntryDate" => date("Y-m-d H:i:s"),
            "StatusID" => $StatusID
        ));
        $this->db->insert('tbl_users_wallet', $InsertData);
        $WalletID = $this->db->insert_id();

        /* Update User Balance */
        if ($StatusID == 5) {
            switch (@$Input['Narration']) {
                case 'Deposit Money':
                case 'Admin Deposit Money':
                    $this->db->set('WalletAmount', 'WalletAmount+' . @$Input['Amount'], FALSE);
                    break;
                case 'Join Contest Winning':
                    $this->db->set('WinningAmount', 'WinningAmount+' . @$Input['WinningAmount'], FALSE);
                    break;
                case 'Join Contest':
                    $this->db->set('WalletAmount', 'WalletAmount-' . @$Input['WalletAmount'], FALSE);
                    $this->db->set('WinningAmount', 'WinningAmount-' . @$Input['WinningAmount'], FALSE);
                    $this->db->set('CashBonus', 'CashBonus-' . @$Input['CashBonus'], FALSE);
                    break;
                case 'Cancel Contest':
                    $this->db->set('WalletAmount', 'WalletAmount+' . @$Input['WalletAmount'], FALSE);
                    $this->db->set('WinningAmount', 'WinningAmount+' . @$Input['WinningAmount'], FALSE);
                    $this->db->set('CashBonus', 'CashBonus+' . @$Input['CashBonus'], FALSE);
                    break;
                case 'Wrong Winning Distribution':
                    $this->db->set('WinningAmount', 'WinningAmount-' . @$Input['WinningAmount'], FALSE);
                    break;

                case 'Signup Bonus':
                case 'Verification Bonus':
                case 'First Deposit Bonus':
                case 'Referral Bonus':
                case 'Admin Cash Bonus':
                case 'Coupon Discount':
                    $this->db->set('CashBonus', 'CashBonus+' . @$Input['Amount'], FALSE);
                    break;
                case 'Withdrawal Request':
                    $this->db->set('WinningAmount', 'WinningAmount-' . @$Input['WinningAmount'], FALSE);
                    if (@$Input['WithdrawalStatus'] == 1) {
                        $this->db->set('WithdrawalHoldAmount', 'WithdrawalHoldAmount+' . @$Input['WinningAmount'], FALSE);
                    }
                    break;
                case 'Withdrawal Reject':
                    $this->db->set('WinningAmount', 'WinningAmount+' . @$Input['WinningAmount'], FALSE);
                    $this->db->set('WithdrawalHoldAmount', 'WithdrawalHoldAmount-' . @$Input['WinningAmount'], FALSE);
                    break;
                default:
                    break;
            }
            $this->db->where('UserID', $UserID);
            $this->db->limit(1);
            $this->db->update('tbl_users');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $WalletID;
    }

    /*
      Description: To get user wallet opening balance
     */

    function getUserWalletOpeningBalance($UserID, $Field)
    {
        $Query = $this->db->query('SELECT IF(' . $Field . ' IS NULL,0,' . $Field . ') Amount FROM `tbl_users_wallet` WHERE StatusID = 5 AND `UserID` = ' . $UserID . ' AND WalletID > 589482 ORDER BY `WalletID` DESC LIMIT 1');
        if ($Query->num_rows() > 0) {
            return $Query->row()->Amount;
        } else {
            return $this->db->query('SELECT ' . str_replace("Closing", "", $Field) . ' Amount FROM `tbl_users` WHERE `UserID` = ' . $UserID . ' LIMIT 1')->row()->Amount;
        }
    }

    /*
      Description: To get user wallet details
     */

    function getWalletDetails($UserID)
    {
        return $this->db->query('SELECT `WalletAmount`,`WinningAmount`,`CashBonus`,(WalletAmount + WinningAmount + CashBonus) AS TotalCash FROM `tbl_users` WHERE `UserID` =' . $UserID . ' LIMIT 1')->row();
    }

    /*
      Description: To add amount in user wallet
     */
    function add($Input = array(), $UserID, $CouponID = NULL)
    {
        /* Get Coupon Details */
        if (!empty($CouponID)) {
            $this->load->model('Store_model');
            $CouponDetailsArr = $this->Store_model->getCoupons('CouponCode,CouponType,CouponValue', array('CouponID' => $CouponID));
            $CouponDetailsArr['DiscountedAmount'] = ($CouponDetailsArr['CouponType'] == 'Flat' ? $CouponDetailsArr['CouponValue'] : ($Input['Amount'] / 100) * $CouponDetailsArr['CouponValue']);
        }

        /* Add Wallet Pre Request */
        $TransactionID = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $InsertData = array(
            "Amount" => @$Input['Amount'],
            "WalletAmount" => @$Input['Amount'],
            "PaymentGateway" => $Input['PaymentGateway'],
            "CouponDetails" => (!empty($CouponID)) ? json_encode($CouponDetailsArr) : NULL,
            "CouponCode" => (!empty($CouponID)) ? $CouponDetailsArr['CouponCode'] : NULL,
            "TransactionType" => 'Cr',
            "TransactionID" => $TransactionID,
            "Narration" => 'Deposit Money',
            "EntryDate" => date("Y-m-d H:i:s")
        );
        $WalletID = $this->addToWallet($InsertData, $UserID);
        if (!$WalletID) {
            return FALSE;
        }

        $PaymentResponse = array();
        if ($Input['PaymentGateway'] == 'PayUmoney') {

            /* Generate Payment Hash */
            $Amount = (strpos(@$Input['Amount'], '.') !== FALSE) ? @$Input['Amount'] : @$Input['Amount'] . '.0';
            $HashString = PAYUMONEY_MERCHANT_KEY . '|' . $TransactionID . "|" . $Amount . "|" . $WalletID . "|" . @$Input['FirstName'] . "|" . @$Input['Email'] . "|||||||||||" . PAYUMONEY_SALT;

            /* Generate Payment Value */
            $PaymentResponse['Action'] = PAYUMONEY_ACTION_KEY;
            $PaymentResponse['MerchantKey'] = PAYUMONEY_MERCHANT_KEY;
            $PaymentResponse['Salt'] = PAYUMONEY_SALT;
            $PaymentResponse['MerchantID'] = PAYUMONEY_MERCHANT_ID;
            $PaymentResponse['Hash'] = strtolower(hash('sha512', $HashString));
            $PaymentResponse['TransactionID'] = $TransactionID;
            $PaymentResponse['Amount'] = $Amount;
            $PaymentResponse['Email'] = @$Input['Email'];
            $PaymentResponse['PhoneNumber'] = @$Input['PhoneNumber'];
            $PaymentResponse['FirstName'] = @$Input['FirstName'];
            $PaymentResponse['ProductInfo'] = $WalletID;
            $PaymentResponse['SuccessURL'] = SITE_HOST . ROOT_FOLDER . 'myAccount?status=success';
            $PaymentResponse['FailedURL'] = SITE_HOST . ROOT_FOLDER . 'myAccount?status=failed';
        } elseif ($Input['PaymentGateway'] == 'Paytm') {

            /* Require Paytm Library */
            require_once  APPPATH . '/third_party/Paytm.php';
            $PaytmObj = new Paytm();

            /* Generate Paytm Checksum */
            $ParamList = array();
            $PaymentResponse['MerchantID'] = $ParamList['MID'] = ($Input['RequestSource'] == 'Web') ? PAYTM_MERCHANT_ID : APP_PAYTM_MERCHANT_ID;
            $PaymentResponse['OrderID'] = $ParamList['ORDER_ID'] = $WalletID;
            $PaymentResponse['CustomerID'] = $ParamList['CUST_ID'] = "CUST" . $UserID;
            $PaymentResponse['IndustryTypeID'] = $ParamList['INDUSTRY_TYPE_ID'] = ($Input['RequestSource'] == 'Web') ? PAYTM_INDUSTRY_TYPE_ID : APP_PAYTM_INDUSTRY_TYPE_ID;
            $PaymentResponse['ChannelID'] = $ParamList['CHANNEL_ID'] = ($Input['RequestSource'] == 'Web') ? 'WEB' : 'WAP';
            $PaymentResponse['Amount'] = $ParamList['TXN_AMOUNT'] = $Input['Amount'];
            $PaymentResponse['Website'] = $ParamList['WEBSITE'] = ($Input['RequestSource'] == 'Web') ? PAYTM_WEBSITE_WEB : APP_PAYTM_WEBSITE_APP;
            $PaymentResponse['CallbackURL'] = $ParamList['CALLBACK_URL'] = ($Input['RequestSource'] == 'Web') ? SITE_HOST . ROOT_FOLDER . 'api/main/paytmResponse' : 'https://' . APP_PAYTM_DOMAIN . '/theia/paytmCallback?ORDER_ID=' . $WalletID;
            $PaymentResponse['TransactionURL'] = ($Input['RequestSource'] == 'Web') ? PAYTM_TXN_URL : APP_PAYTM_TXN_URL;
            $PaymentResponse['CheckSumHash'] = $PaytmObj->generatePaytmCheckSum($ParamList, ($Input['RequestSource'] == 'Web') ? PAYTM_MERCHANT_KEY : APP_PAYTM_MERCHANT_KEY);
        } elseif ($Input['PaymentGateway'] == 'Razorpay') {

            $PaymentResponse['MerchantKey'] = RAZORPAY_KEY_ID;
            $PaymentResponse['MerchantName'] = SITE_NAME;
            $PaymentResponse['Amount'] = @$Input['Amount'] * 100;
            $PaymentResponse['OrderID'] = $WalletID;
        } else if ($Input['PaymentGateway'] == 'CashFree') {

            /* Get Order Token */
            $PaymentResponse['OrderToken'] = '';
            $CURL = curl_init();
            curl_setopt_array($CURL, array(
                CURLOPT_URL => CASHFREE_URL . "api/v2/cftoken/order",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(array('orderId' => $WalletID, 'orderAmount' => $Input['Amount'], 'orderCurrency' => DEFAULT_CURRENCY_CODE)),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "x-client-id: " . CASHFREE_APP_ID,
                    "x-client-secret: " . CASHFREE_SECRET_KEY
                ),
            ));
            $Response = curl_exec($CURL);
            $Error = curl_error($CURL);
            curl_close($CURL);
            if ($Error) {
                $PaymentResponse['ErrorMsg'] = $Error;
            } else {
                $Result = json_decode($Response, TRUE);
                if ($Result['status'] == 'OK') { // SUCCESS
                    $PaymentResponse['AppID'] = CASHFREE_APP_ID;
                    $PaymentResponse['OrderID'] = $WalletID;
                    $PaymentResponse['OrderToken'] = $Result['cftoken'];
                    $PaymentResponse['Amount'] =  $Input['Amount'];
                    $PaymentResponse['Currency'] =  DEFAULT_CURRENCY_CODE;
                    $PaymentResponse['NotifyURL'] =  BASE_URL . 'utilities/cashFreeWebHookResponse';
                } else { // ERROR
                    $PaymentResponse['ErrorMsg'] = (!empty($Result['error'])) ? $Result['error'] : 'Error occured while generating payment token.';
                }
            }
        }
        return $PaymentResponse;
    }

    /*
      Description: To confirm payment gateway response
     */
    function confirm($Input = array(), $UserID)
    {
        /* Update Order Status */
        $UpdataData['PaymentGatewayResponse'] = @$Input['PaymentGatewayResponse'];
        $UpdataData['ModifiedDate'] = date("Y-m-d H:i:s");

        /* Razorpay */
        if ($Input['PaymentGateway'] == 'Razorpay') {
            return TRUE; // Manage via WebHook
        } else if ($Input['PaymentGateway'] == 'CashFree') {
            $CURL = curl_init();
            curl_setopt_array($CURL, array(
                CURLOPT_URL => CASHFREE_URL . "api/v1/order/info/status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "appId=" . CASHFREE_APP_ID . "&secretKey=" . CASHFREE_SECRET_KEY . "&orderId=" . $Input['WalletID'],
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            $Response = curl_exec($CURL);
            $Error = curl_error($CURL);
            curl_close($CURL);
            if ($Error) {
                echo "cURL Error CashFree #:" . $Error;
                die;
            } else {
                $UpdataData['PaymentGatewayResponse'] = $Response;
                $Response = json_decode($Response, TRUE);
                if ($Response['status'] == 'OK') {
                    $Input['PaymentGatewayStatus'] = ($Response['orderStatus'] == 'PAID' && $Response['txStatus'] == 'SUCCESS') ? 'Success' : 'Failed';
                } else {
                    $Input['PaymentGatewayStatus'] = 'Failed';
                }
            }
        }
        $UpdataData['StatusID'] = ($Input['PaymentGatewayStatus'] == 'Failed' || $Input['PaymentGatewayStatus'] == 'Cancelled') ? 3 : 5;
        if ($UpdataData['StatusID'] == 5) {
            $UpdataData['ClosingWalletAmount'] = @$Input['OpeningWalletAmount'] + @$Input['Amount'];
        }
        $this->db->where(array('WalletID' => $Input['WalletID'], 'UserID' => $UserID, 'StatusID' => 1));
        $this->db->limit(1);
        $this->db->update('tbl_users_wallet', $UpdataData);
        if ($this->db->affected_rows() <= 0) {
            return FALSE;
        }

        /* Update user main wallet amount */
        if ($Input['PaymentGatewayStatus'] == 'Success') {
            $this->manageSuccessTransaction($UserID, @$Input['Amount'], @$Input['CouponDetails']);
        }
        return $this->getWalletDetails($UserID);
    }

    /*
      Description: To manage success transaction
    */
    function manageSuccessTransaction($UserID, $Amount, $CouponDetails = array())
    {
        /* Update User Wallet */
        $this->db->set('WalletAmount', 'WalletAmount+' . $Amount, FALSE);
        $this->db->where('UserID', $UserID);
        $this->db->limit(1);
        $this->db->update('tbl_users');

        /* Check Coupon Details */
        if (!empty($CouponDetails)) {
            $WalletData = array(
                "Amount" => $CouponDetails['DiscountedAmount'],
                "CashBonus" => $CouponDetails['DiscountedAmount'],
                "TransactionType" => 'Cr',
                "Narration" => 'Coupon Discount',
                "EntryDate" => date("Y-m-d H:i:s")
            );
            $this->addToWallet($WalletData, $UserID, 5);
        }

        /* Manage First Deposit & Referral Bonus */
        $TotalDeposits = $this->db->query('SELECT COUNT(EntryDate) TotalDeposits FROM `tbl_users_wallet` WHERE `UserID` = ' . $UserID . ' AND Narration = "Deposit Money" AND StatusID = 5')->row()->TotalDeposits;
        if ($TotalDeposits == 1) { // On First Successful Transaction

            /* Get Deposit Bonus Data */
            $DepositBonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "FirstDepositBonus" LIMIT 1');
            if ($DepositBonusData->row()->StatusID == 2) {

                $MinimumFirstTimeDepositLimit = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "MinimumFirstTimeDepositLimit" LIMIT 1');
                $MaximumFirstTimeDepositLimit = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "MaximumFirstTimeDepositLimit" LIMIT 1');

                if ($MinimumFirstTimeDepositLimit->row()->ConfigTypeValue <= $Amount && $MaximumFirstTimeDepositLimit->row()->ConfigTypeValue >= $Amount) {
                    /* Update Wallet */
                    $FirstTimeAmount = ($Amount * $DepositBonusData->row()->ConfigTypeValue) / 100;
                    $WalletData = array(
                        "Amount" => $FirstTimeAmount,
                        "CashBonus" => $FirstTimeAmount,
                        "TransactionType" => 'Cr',
                        "Narration" => 'First Deposit Bonus',
                        "EntryDate" => date("Y-m-d H:i:s")
                    );
                    $this->addToWallet($WalletData, $UserID, 5);
                }
            }

            /* Get User Data */
            $UserData = $this->db->query('SELECT ReferredByUserID FROM tbl_users WHERE UserID = ' . $UserID . ' LIMIT 1');
            if ($UserData->num_rows() > 0) {

                /* Get Referral To Bonus Data */
                $ReferralToBonus = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "ReferToDepositBonus" LIMIT 1');
                if ($ReferralToBonus->row()->StatusID == 2 && $ReferralToBonus->row()->ConfigTypeValue > 0) {

                    /* Update Wallet */
                    $WalletData = array(
                        "Amount" => $ReferralToBonus->row()->ConfigTypeValue,
                        "CashBonus" => $ReferralToBonus->row()->ConfigTypeValue,
                        "TransactionType" => 'Cr',
                        "Narration" => 'Referral Bonus',
                        "EntryDate" => date("Y-m-d H:i:s")
                    );
                    $this->addToWallet($WalletData, $UserID, 5);
                }

                /* Get Referral By Bonus Data */
                $ReferralByBonus = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "ReferByDepositBonus" LIMIT 1');
                if ($ReferralByBonus->row()->StatusID == 2 && $ReferralByBonus->row()->ConfigTypeValue > 0) {

                    /* Update Wallet */
                    $WalletData = array(
                        "Amount" => $ReferralByBonus->row()->ConfigTypeValue,
                        "CashBonus" => $ReferralByBonus->row()->ConfigTypeValue,
                        "TransactionType" => 'Cr',
                        "Narration" => 'Referral Bonus',
                        "EntryDate" => date("Y-m-d H:i:s")
                    );
                    $this->addToWallet($WalletData, $UserData['ReferredByUserID'], 5);
                }
            }
        }
    }

    /*
        Description: Use to manage razorpay webhook response
    */
    function razorpayWebhook($WebhookResp)
    {

        /* Decode Response */
        $PayResponse = json_decode($WebhookResp, TRUE);
        $PayResponse = $PayResponse['payload']['payment']['entity'];
        $WalletID    = $PayResponse['notes']['order_id'];

        /* Get Wallet Details */
        $WalletData = $this->Users_model->getWallet('OpeningWalletAmount,Amount,TransactionID,CouponDetails,UserID', array('WalletID' => $WalletID));
        $UserID     = $WalletData['UserID'];

        /* Update wallet details */
        $UpdataData = array_filter(array(
            'PaymentGatewayResponse' => $WebhookResp,
            'ModifiedDate' => date("Y-m-d H:i:s"),
            'StatusID' => ($PayResponse['status'] == "authorized") ? 5 : 3
        ));
        if ($UpdataData['StatusID'] == 5) {
            $UpdataData['ClosingWalletAmount'] = $WalletData['OpeningWalletAmount'] + $WalletData['Amount'];
        }
        $this->db->where(array('WalletID' => $WalletID, 'UserID' => $UserID, 'StatusID' => 1));
        $this->db->limit(1);
        $this->db->update('tbl_users_wallet', $UpdataData);
        if ($this->db->affected_rows() <= 0)
            return FALSE;

        /* Update user main wallet amount */
        if ($UpdataData['StatusID'] == 5) {
            $this->manageSuccessTransaction($UserID, $WalletData['Amount'], $WalletData['CouponDetails']);
        }
        return TRUE;
    }

    /*
        Description: Use to manage cashfree webhook response
    */
    function cashFreeWebHookResponse($WebhookResp)
    {
        $WalletID  = $WebhookResp['orderId'];

        /* Get Wallet Details */
        $WalletData = $this->Users_model->getWallet('OpeningWalletAmount,Amount,TransactionID,CouponDetails,UserID', array('WalletID' => $WalletID));
        $UserID     = $WalletData['UserID'];

        /* Update wallet details */
        $UpdataData = array_filter(array(
            'PaymentGatewayResponse' => json_encode($WebhookResp),
            'ModifiedDate' => date("Y-m-d H:i:s"),
            'StatusID' => ($WebhookResp['txStatus'] == "SUCCESS") ? 5 : 3
        ));
        if ($UpdataData['StatusID'] == 5) {
            $UpdataData['ClosingWalletAmount'] = $WalletData['OpeningWalletAmount'] + $WalletData['Amount'];
        }
        $this->db->where(array('WalletID' => $WalletID, 'UserID' => $UserID, 'StatusID' => 1));
        $this->db->limit(1);
        $this->db->update('tbl_users_wallet', $UpdataData);
        if ($this->db->affected_rows() <= 0)
            return FALSE;

        /* Update user main wallet amount */
        if ($UpdataData['StatusID'] == 5) {
            $this->manageSuccessTransaction($UserID, $WalletData['Amount'], $WalletData['CouponDetails']);
        }
        return TRUE;
    }

    /*
        Description: Use to manage paytm response (Web)
    */
    function paytmResponse($Data)
    {
        /* Require Paytm Library */
        require_once  APPPATH . '/third_party/Paytm.php';
        $PaytmObj = new Paytm();

        /* Get User ID */
        $UserID = $this->db->query('SELECT `UserID` FROM `tbl_users_wallet` WHERE `WalletID` = ' . $Data["ORDERID"] . ' LIMIT 1')->row()->UserID;
        $PaymentResponse = array();
        $PaymentResponse['WalletID'] = $Data["ORDERID"];
        $PaymentResponse['PaymentGatewayResponse'] = json_encode($Data);
        if ($Data["STATUS"] == "TXN_SUCCESS" && $PaytmObj->verifychecksum_e($Data, PAYTM_MERCHANT_KEY, $Data['CHECKSUMHASH']) == "TRUE") {

            /* Update Transaction (Success) */
            $PaymentResponse['PaymentGatewayStatus']   = 'Success';
            $PaymentResponse['Amount']                 = $Data['TXNAMOUNT'];
            $this->confirm($PaymentResponse, $UserID);
            redirect(SITE_HOST . ROOT_FOLDER . 'myAccount?status=success');
        }

        /* Update Transaction (Failed) */
        $PaymentResponse['PaymentGatewayStatus'] = 'Failed';
        $this->confirm($PaymentResponse, $UserID);
        redirect(SITE_HOST . ROOT_FOLDER . 'myAccount?status=failed');
    }

    /*
      Description: To get user wallet data
    */
    function getWallet($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserID' => 'W.UserID',
                'Amount' => 'W.Amount',
                'OpeningWalletAmount' => 'W.OpeningWalletAmount',
                'OpeningWinningAmount' => 'W.OpeningWinningAmount',
                'OpeningCashBonus' => 'W.OpeningCashBonus',
                'WalletAmount' => 'W.WalletAmount',
                'WinningAmount' => 'W.WinningAmount',
                'CashBonus' => 'W.CashBonus',
                'ClosingWalletAmount' => 'W.ClosingWalletAmount',
                'ClosingWinningAmount' => 'W.ClosingWinningAmount',
                'ClosingCashBonus' => 'W.ClosingCashBonus',
                'WithdrawalHoldAmount' => 'W.WithdrawalHoldAmount',
                'Currency' => 'W.Currency',
                'PaymentGateway' => 'W.PaymentGateway',
                'CouponDetails' => 'W.CouponDetails',
                'TransactionType' => 'W.TransactionType',
                'TransactionID' => 'W.TransactionID',
                'OpeningBalance' => '(W.OpeningWalletAmount + W.OpeningWinningAmount + W.OpeningCashBonus) OpeningBalance',
                'ClosingBalance' => '(W.ClosingWalletAmount + W.ClosingWinningAmount + W.ClosingCashBonus) ClosingBalance',
                'Narration' => 'W.Narration',
                'EntryDate' => 'DATE_FORMAT(CONVERT_TZ(W.EntryDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") EntryDate',
                'Status' => 'CASE W.StatusID
                                        when "1" then "Pending"
                                        when "3" then "Failed"
                                        when "5" then "Completed"
                                    END as Status',
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }

        if (in_array('WalletDetails', $Params)) {
            $WalletData = $this->db->query('SELECT WalletAmount,WinningAmount,CashBonus,(WalletAmount + WinningAmount + CashBonus) TotalCash from tbl_users where UserID = ' . $Where['UserID'])->row();
            $Return['Data']['WalletAmount'] = $WalletData->WalletAmount;
            $Return['Data']['CashBonus'] = $WalletData->CashBonus;
            $Return['Data']['WinningAmount'] = $WalletData->WinningAmount;
            $Return['Data']['TotalCash'] = $WalletData->TotalCash;
        }
        if (in_array('VerificationDetails', $Params)) {
            $UserVerificationData = $this->Users_model->getUsers('Status,PanStatus,BankStatus,PhoneStatus', array('UserID' => $Where['UserID']));
            $Return['Data']['Status'] = $UserVerificationData['Status'];
            $Return['Data']['PanStatus'] = $UserVerificationData['PanStatus'];
            $Return['Data']['BankStatus'] = $UserVerificationData['BankStatus'];
            $Return['Data']['PhoneStatus'] = $UserVerificationData['PhoneStatus'];
        }
        $this->db->select('W.WalletID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_users_wallet W');
        if (!empty($Where['WalletID'])) {
            $this->db->where("W.WalletID", $Where['WalletID']);
        }
        if (!empty($Where['CouponCode'])) {
            $this->db->where("W.CouponCode", $Where['CouponCode']);
        }
        if (!empty($Where['UserID'])) {
            $this->db->where("W.UserID", $Where['UserID']);
        }
        if (!empty($Where['PaymentGateway'])) {
            $this->db->where("W.PaymentGateway", $Where['PaymentGateway']);
        }
        if (!empty($Where['TransactionType'])) {
            $this->db->where("W.TransactionType", $Where['TransactionType']);
        }
        if (!empty($Where['Narration'])) {
            $this->db->where("W.Narration", $Where['Narration']);
        }
        if (!empty($Where['EntityID'])) {
            $this->db->where("W.EntityID", $Where['EntityID']);
        }
        if (!empty($Where['UserTeamID'])) {
            $this->db->where("W.UserTeamID", $Where['UserTeamID']);
        }
        if (!empty($Where['TransactionMode']) && $Where['TransactionMode'] != 'All') {
            $this->db->where("W." . $Where['TransactionMode'] . ' >', 0);
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'FailedCompleted') {
            $this->db->where_in("W.StatusID", array(3, 5));
        }
        if (!empty($Where['FromDate'])) {
            $this->db->where("W.EntryDate >=", $Where['FromDate']);
        }
        if (!empty($Where['ToDate'])) {
            $this->db->where("W.EntryDate <=", $Where['ToDate']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("W.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('W.WalletID', 'DESC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('CouponDetails', $Params)) {
                        $Records[$key]['CouponDetails'] = (!empty($Record['CouponDetails'])) ? json_decode($Record['CouponDetails']) : new stdClass();
                    }
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('CouponDetails', $Params)) {
                    $Record['CouponDetails'] = (!empty($Record['CouponDetails'])) ? json_decode($Record['CouponDetails']) : new stdClass();
                }
                return $Record;
            }
        } else {
            return $Return;
        }
        return FALSE;
    }

    /*
        Description: To add withdrawal request
    */
    public function withdrawal($Input = array(), $UserID)
    {
        $this->db->trans_start();

        /* Insert Withdrawal Add Request */
        $OTP = random_string('numeric', 6);
        $InsertData = array_filter(array(
            "UserID" => $UserID,
            "Amount" => @$Input['Amount'],
            "PaytmPhoneNumber" => @$Input['PaytmPhoneNumber'],
            "OTP" => $OTP,
            "IsOTPVerified" => (OTP_WITHDRAWAL) ? "No" : "Yes",
            "PaymentGateway" => $Input['PaymentGateway'],
            "EntryDate" => date("Y-m-d H:i:s"),
            "StatusID" => 1,
        ));
        $this->db->insert('tbl_users_withdrawal', $InsertData);
        $WithdrawalID = $this->db->insert_id();

        /* Verify OTP Send SMS */
        if (OTP_WITHDRAWAL) {
            $this->Utility_model->sendMobileSMS(array(
                'PhoneNumber' => $Input['PaytmPhoneNumber'],
                'Text' => SITE_NAME . ", OTP to verify withdrawal request. is: $OTP",
            ));
        } else {
            $WalletData = array(
                "Amount" => @$Input['Amount'],
                "WinningAmount" => @$Input['Amount'],
                "TransactionType" => 'Dr',
                "Narration" => 'Withdrawal Request',
                "EntryDate" => date("Y-m-d H:i:s"),
                "WithdrawalStatus" => 1
            );
            $this->addToWallet($WalletData, $UserID, 5);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return array('WithdrawalID' => $WithdrawalID, 'WalletDetails' => $this->getWalletDetails($UserID));
    }

    /*
        Description: To confirm withdrawal request
    */
    public function withdrawal_confirm($Input = array(), $UserID)
    {
        /* Withdraw to Account */
        if ($Input['PaymentGateway'] == 'Paytm') {
            $StatusID = 3;
            $Data = array(
                "request" => array(
                    "requestType" => 'NULL',
                    "merchantGuid" => PAYTM_MERCHANT_GUID,
                    "merchantOrderId" => "Order" . substr(hash('sha256', mt_rand() . microtime()), 0, 10),
                    "salesWalletGuid" => PAYTM_SALES_WALLET_GUID,
                    "payeeEmailId" => "",
                    "payeePhoneNumber" => @$Input['PaytmPhoneNumber'],
                    "payeeSsoId" => "",
                    "appliedToNewUsers" => "N",
                    "amount" => @$Input['Amount'],
                    "currencyCode" => DEFAULT_CURRENCY_CODE,
                ),
                "metadata" => "Wihtdrawal Money",
                "ipAddress" => '127.0.01',
                "platformName" => "PayTM",
                "operationType" => "SALES_TO_USER_CREDIT",
            );
            /* Generate CheckSum */
            $RequestData = json_encode($Data);
            $ChecksumHash = $this->getChecksumFromString($RequestData, PAYTM_MERCHANT_GRATIFICATION_KEY);
            $HeaderValue = array('Content-Type:application/json', 'mid:' . PAYTM_MERCHANT_GUID, 'checksumhash:' . $ChecksumHash);

            /* CURL Request */
            $CURL = curl_init(PAYTM_GRATIFICATION_URL);
            curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $RequestData);
            curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($CURL, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($CURL, CURLOPT_HTTPHEADER, $HeaderValue);
            $Info = curl_getinfo($CURL);
            $PaymentGatewayResponse = @json_decode(curl_exec($CURL), true);

            $StatusArr = array("FAILURE" => 3, "SUCCESS" => 5, "PENDING" => 1); // NEED TO MANAGE WEBHOOKS IN PENDING CASE
            $StatusID = (!empty($PaymentGatewayResponse)) ? $StatusArr[$PaymentGatewayResponse['status']] : 3;
        } else {
            $StatusID = 1;
        }

        $this->db->trans_start();

        /* Update Withdrawal Request */
        $UpdateData = array(
            "IsOTPVerified" => "Yes",
            "PaymentGatewayResponse" => (!empty($PaymentGatewayResponse)) ? json_encode($PaymentGatewayResponse) : null,
            "ModifiedDate" => date("Y-m-d H:i:s"),
            "StatusID" => $StatusID,
        );
        $this->db->where('WithdrawalID', $Input['WithdrawalID']);
        $this->db->limit(1);
        $this->db->update('tbl_users_withdrawal', $UpdateData);

        /* Update user winning amount */
        if ($StatusID == 5 || $StatusID == 1) {
            $WalletData = array(
                "Amount" => @$Input['Amount'],
                "WinningAmount" => @$Input['Amount'],
                "TransactionType" => 'Dr',
                "Narration" => 'Withdrawal Request',
                "EntryDate" => date("Y-m-d H:i:s"),
                "WithdrawalStatus" => $StatusID
            );
            $this->addToWallet($WalletData, $UserID, 5);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return $this->getWalletDetails($UserID);
    }

    /*
      Description: 	Use to update withdrawl status (Admin).
    */
    function updateWithdrawal($WithdrawalID, $Input = array())
    {
        $this->db->trans_start();

        $Comments = ($Input['StatusID'] == 3) ? $Input['Comments'] : '';

        /* Get User Details. */
        $UserData = $this->db->query('SELECT U.UserID,U.FirstName,U.Email,U.WithdrawalHoldAmount,W.Amount FROM tbl_users_withdrawal W, tbl_users U WHERE W.UserID = U.UserID AND W.StatusID = 1 AND W.WithdrawalID = '.$WithdrawalID.' LIMIT 1')->row();

        if (@$Input['StatusID'] == 2) {

            /* Updating Hold Amount */
            $this->db->set('WithdrawalHoldAmount', 'WithdrawalHoldAmount-' . @$Input['WithdrawalAmount'], false);
            $this->db->where('UserID', $UserData->UserID);
            $this->db->limit(1);
            $this->db->update('tbl_users');

            /* Send Notification */
            $this->Notification_model->addNotification('Withdrawal', 'Withdrawal Request Approved', $UserData->UserID, $UserData->UserID, '', 'Your withdrawal request for ' . DEFAULT_CURRENCY . $UserData->Amount . ' has been approved by admin and will be transferred to your given account details within 3-4 working days.');

            /* Send withdrawal Email to User */
            send_mail(array(
                'emailTo' => $UserData->Email,
                'template_id' => 'd-cca9f427f5c44f8d831ab1710c0b4d47',
                'Subject' => 'Withdrawal Request Confirmed - ' . SITE_NAME,
                "Name" => $UserData->FirstName,
                "Amount" => $UserData->Amount
            ));
        } else if (@$Input['StatusID'] == 3) {

            /* Send Notification */
            $this->Notification_model->addNotification('Withdrawal', 'Withdrawal Request Declined', $UserData->UserID, $UserData->UserID, '', 'Your withdrawal request for ' . DEFAULT_CURRENCY . $UserData->Amount . ' has been declined by admin for ' . $Comments);

            /* Add Wallet Entry */
            $WalletData = array(
                "Amount" => $UserData->WithdrawalHoldAmount,
                "WinningAmount" => $UserData->WithdrawalHoldAmount,
                "TransactionType" => 'Cr',
                "Narration" => 'Withdrawal Reject',
                "EntryDate" => date("Y-m-d H:i:s"),
                "WithdrawalStatus" => 3
            );
            $this->addToWallet($WalletData, $UserData->UserID, 5);
        }

        /* Update Withdrawal Status */
        $this->db->where('WithdrawalID', $WithdrawalID);
        $this->db->limit(1);
        $this->db->update('tbl_users_withdrawal', array_filter(array('StatusID' => $Input['StatusID'],'ModifiedDate' => date("Y-m-d H:i:s"),'Comments' => $Comments)));

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return TRUE;
    }

    /*
      Description: To get user withdrawals data
    */
    function getWithdrawals($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserID' => 'W.UserID',
                'Amount' => 'W.Amount',
                'Email' => 'U.Email',
                'PhoneNumber' => 'U.PhoneNumber',
                'FirstName' => 'U.FirstName',
                'Middlename' => 'U.Middlename',
                'LastName' => 'U.LastName',
                'Comments' => 'W.Comments',
                'ProfilePic' => 'IF(U.ProfilePic IS NULL,CONCAT("' . BASE_URL . '","uploads/profile/picture/","default.jpg"),CONCAT("' . BASE_URL . '","uploads/profile/picture/",U.ProfilePic)) ProfilePic',
                'PaymentGateway' => 'W.PaymentGateway',
                'EntryDate' => 'DATE_FORMAT(CONVERT_TZ(W.EntryDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") EntryDate',
                'Status' => 'CASE W.StatusID
                                    when "1" then "Pending"
                                    when "2" then "Verified"
                                    when "3" then "Rejected"
                                END as Status',
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('W.WithdrawalID,W.UserID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_users_withdrawal W,tbl_users U');
        $this->db->where("W.UserID", "U.UserID", FALSE);

        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = trim($Where['Keyword']);
            $this->db->group_start();
            $this->db->like("U.FirstName", $Where['Keyword']);
            $this->db->or_like("U.LastName", $Where['Keyword']);
            $this->db->or_like("U.Email", $Where['Keyword']);
            $this->db->or_like("CONCAT_WS('',U.FirstName,U.Middlename,U.LastName)", preg_replace('/\s+/', '', $Where['Keyword']), FALSE);
            $this->db->group_end();
        }
        if (!empty($Where['WithdrawalID'])) {
            $this->db->where("W.WithdrawalID", $Where['WithdrawalID']);
        }
        if (!empty($Where['UserID'])) {
            $this->db->where("W.UserID", $Where['UserID']);
        }
        if (!empty($Where['PaymentGateway'])) {
            $this->db->where("W.PaymentGateway", $Where['PaymentGateway']);
        }
        if (!empty($Where['FromDate'])) {
            $this->db->where("W.EntryDate >=", $Where['FromDate']);
        }
        if (!empty($Where['ToDate'])) {
            $this->db->where("W.EntryDate <=", $Where['ToDate']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("W.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('W.WithdrawalID', 'ASC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();

        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                foreach ($Query->result_array() as $Record) {
                    /* get attached media */
                    if (in_array('MediaBANK', $Params)) {
                        $MediaData = $this->Media_model->getMedia('MediaGUID, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,"110_",M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,    M.MediaCaption', array("SectionID" => 'BankDetail', "EntityID" => $Record['UserID']), FALSE);
                        $MediaData['MediaCaption'] = json_decode($MediaData['MediaCaption']);
                        $Record['MediaBANK'] = ($MediaData ? $MediaData : new stdClass());
                    }
                    $Records[] = $Record;
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();

                /* get attached media */
                if (in_array('MediaBANK', $Params)) {
                    $MediaData = $this->Media_model->getMedia('MediaGUID, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,"110_",M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,M.MediaCaption', array("SectionID" => 5, "EntityID" => $Record['UserID']), FALSE);
                    if ($MediaData)
                        $MediaData['MediaCaption'] = json_decode($MediaData['MediaCaption']);
                    $Record['MediaBANK'] = ($MediaData ? $MediaData : new stdClass());
                }
                return $Record;
            }
        }
        return FALSE;
    }
}
