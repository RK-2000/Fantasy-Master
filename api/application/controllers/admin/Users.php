<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Recovery_model');
    }

    /*
      Description: 	Use to broadcast message.
      URL: 			/api_admin/users/broadcast/
     */

    public function broadcast_post() {
        /* Validation section */
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->set_rules('Title', 'Title', 'trim|required');
        $this->form_validation->set_rules('Message', 'Title', 'trim|required');
        $this->form_validation->set_rules('MediaGUIDs', 'MediaGUIDs', 'trim'); /* Media GUIDs */
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* check for media present - associate media with this Post - ends */

        $UsersData = $this->Users_model->getUsers('
			U.UserID,	
			U.Username,
            U.Email,
            U.PhoneNumber
			', array('AdminUsers' => 'No'), TRUE, 1, 10000000);
        if ($UsersData) {
            $NotificationText = $this->Post['Title'];
            $NotificationMessage = $this->Post['Message'];
            $InsertData = array();
            if (!empty($this->Post['Email']) && $this->Post['Email'] == 1) {
                $this->Return['Message'] = 'Email broadcasted.';
            }elseif(!empty($this->Post['SMS']) && $this->Post['SMS'] == 1) {
                $this->Return['Message'] = 'SMS broadcasted.';
            }elseif(!empty($this->Post['Notification']) && $this->Post['Notification'] == 1) {
                foreach ($UsersData['Data']['Records'] as $Value) {
                    /* send notification - starts */
                    /* $this->Notification_model->addNotificationBroadcast('broadcast', $NotificationText, $this->SessionUserID, $Value['UserID'], '' , $NotificationMessage); */
                    /* send notification - ends */
                    $InsertData[] = array_filter(array(
                        "NotificationPatternID" => 2,
                        "UserID" => $this->SessionUserID,
                        "ToUserID" => $Value['UserID'],
                        "RefrenceID" => "",
                        "NotificationText" => $NotificationText,
                        "NotificationMessage" => $NotificationMessage,
                        "MediaID" => "",
                        "EntryDate" => date("Y-m-d H:i:s")
                    ));
                }
                if(!empty($InsertData)){
                  $this->db->insert_batch('tbl_notifications', $InsertData);   
                }
                $this->Return['Message'] = 'Notification broadcasted.';
            }else{
                $this->Return['Message'] = 'Please Select broadcast Type.';
            }
        }
    }

    /*
      Name: 			getUsers
      Description: 	Use to get users list.
      URL: 			/api_admin/users/getProfile
     */

    public function index_post() {
        /* Validation section */
        $this->form_validation->set_rules('StoreGUID', 'StoreGUID', 'trim' . ($this->UserTypeID == 4 ? '|required' : '') . '|callback_validateEntityGUID[Store,StoreID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('AdminUsers', 'AdminUsers', 'trim');
        $this->form_validation->set_rules('PageNo', 'PageNo', 'trim|integer');
        $this->form_validation->set_rules('PageSize', 'PageSize', 'trim|integer');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* $UsersData=$this->Users_model->getUsers('RegisteredOn,LastLoginDate,UserTypeName, FullName, Email, Username, ProfilePic, Gender, BirthDate, PhoneNumber, Status, StatusID',$this->Post, TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']); */

        $UsersData = $this->Users_model->getUsers((!empty($this->Post['Params']) ? $this->Post['Params'] : ''), array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($UsersData) {
            $this->Return['Data'] = $UsersData['Data'];
        }
    }

    /*
      Description: 	Use to update user profile info.
      URL: 			/api_admin/entity/changeStatus/
     */

    public function changeStatus_post() {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        $this->Users_model->updateUserInfo($this->UserID, array("IsPrivacyNameDisplay" => $this->Post['IsPrivacyNameDisplay']));
        $this->Entity_model->updateEntityInfo($this->UserID, array("StatusID" => $this->StatusID));

        $this->Return['Data'] = $this->Users_model->getUsers('FirstName,LastName,Email,ProfilePic,Status', array("UserID" => $this->UserID));
        $this->Return['Message'] = "Status has been changed.";
    }

    /*
      Description: 	Use to update user details as pan and bank details.
      URL: 			/api_admin/entity/changeVerificationStatus/
     */

    public function changeVerificationStatus_post() {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('VetificationType', 'VetificationType', 'trim|required');
        if ($this->Post['VetificationType'] == 'PAN') {
            $this->form_validation->set_rules('PanStatus', 'PanStatus', 'trim|required|callback_validateStatus');
        }
        if ($this->Post['VetificationType'] == 'BANK') {
            $this->form_validation->set_rules('BankStatus', 'BankStatus', 'trim|required|callback_validateStatus');
        }
        $this->form_validation->validation($this);  /* Run validation */

        /* Validation - ends */
        if ($this->Post['VetificationType'] == 'PAN' && !empty($this->Post['PanStatus'])) {
            $UpdateData = array("PanStatus" => $this->StatusID);
        }
        if ($this->Post['VetificationType'] == 'BANK' && !empty($this->Post['BankStatus'])) {
            $UpdateData = array("BankStatus" => $this->StatusID);
        }
        if (!empty($this->Post['Comments'])) {
            $UpdateData['Comments'] = $this->Post['Comments'];
        }

        $UsersData = $this->Users_model->getUsers((!empty($this->Post['Params']) ? $this->Post['Params'] : ''), array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);

        $this->Users_model->updateUserInfo($this->UserID, $UpdateData);

        /* Get User Data */
        $UserData = $this->Users_model->getUsers('FirstName,LastName,Email,ProfilePic,Status,PanStatus,BankStatus,PhoneNumber', array("UserID" => $this->UserID));

        /* Manage Verification Bonus */
        if ($UserData['PanStatus'] == 'Verified' && $UserData['BankStatus'] == 'Verified' && !empty($UserData['PhoneNumber'])) {
            $BonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "VerificationBonus" LIMIT 1');
            if ($BonusData->row()->StatusID == 2) {
                $TransactionID = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                $WalletData = array(
                    "Amount" => $BonusData->row()->ConfigTypeValue,
                    "CashBonus" => $BonusData->row()->ConfigTypeValue,
                    "TransactionType" => 'Cr',
                    "TransactionID" => $TransactionID,
                    "Narration" => 'Verification Bonus',
                    "EntryDate" => date("Y-m-d H:i:s")
                );
                $this->Users_model->addToWallet($WalletData, $this->UserID, 5);
            }
        }
        $this->Return['Data'] = $UserData;
        $this->Return['Message'] = "Status has been changed.";
    }

    /*
      Name: 			updateUserInfo
      Description: 	Use to update user profile info.
      URL: 			/api_admin/updateUserInfo/
     */

    public function updateUserInfo_post() {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->set_rules('UserTypeID', 'User Type', 'trim|in_list[3,4]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->updateUserInfo($this->UserID, array_merge($this->Post, array("StatusID" => @$this->StatusID, "SkipPhoneNoVerification" => true)));
        $this->Return['Data'] = $this->Users_model->getUsers('StatusID,Status,ProfilePic,Email,Username,Gender,BirthDate,PhoneNumber,UserTypeName,RegisteredOn,LastLoginDate', array("UserID" => $this->UserID));
        $this->Return['Message'] = "Successfully updated.";
    }

    /*
      Name: 			add
      Description: 	Use to register user to system.
      URL: 			/api_admin/users/add/
     */

    public function add_post() {
        /* Validation section */
        $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|callback_validateEmail');
        $this->form_validation->set_rules('Password', 'Password', 'trim' . (empty($this->Post['Source']) || $this->Post['Source'] == 'Direct' ? '|required' : ''));
        $this->form_validation->set_rules('FirstName', 'FirstName', 'trim|required');
        $this->form_validation->set_rules('LastName', 'LastName', 'trim');
        $this->form_validation->set_rules('UserTypeID', 'UserTypeID', 'trim|required|in_list[1,2,3,4]');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|callback_validatePhoneNumber');
        $this->form_validation->set_rules('Source', 'Source', 'trim|required|callback_validateSource');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');

        $this->form_validation->set_rules('StoreGUID', 'StoreGUID', 'trim|callback_validateEntityGUID[Store,StoreID]');

        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $UserID = $this->Users_model->addUser($this->Post, $this->Post['UserTypeID'], $this->SourceID, $this->StatusID);
        if (!$UserID) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            /* Send welcome Email to User with login details */
            // sendMail(array(
            // 	'emailTo' 		=> $this->Post['Email'],			
            // 	'emailSubject'	=> "Your Login Credentials - ".SITE_NAME,
            // 	'emailMessage'	=> emailTemplate($this->load->view('emailer/adduser',array("Name" =>  $this->Post['FirstName'], 'Password' => $this->Post['Password']),TRUE)) 
            // ));

            send_mail(array(
                'emailTo' => $this->Post['Email'],
                'template_id' => 'd-2baba49071954ed98fee6f146b5168e2',
                'Subject' => 'Your Login Credentials -' . SITE_NAME,
                "Name" => $this->Post['FirstName'],
                'Password' => $this->Post['Password']
            ));
            return true;
        }
    }

    /*
      Name: 			getWallet
      Description: 	To get wallet data
      URL: 			/users/getWallet/
     */

    public function getWallet_post() {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('TransactionMode', 'TransactionMode', 'trim|required|in_list[All,WalletAmount,WinningAmount,CashBonus]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Wallet Data */
        $WalletDetails = $this->Users_model->getWallet(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WalletDetails)) {
            $this->Return['Data'] = $WalletDetails['Data'];
        }
    }

    /*
      Name: 			getWithdrawals
      Description: 	To get all Withdrawal requests
      URL: 			/users/getWithdrawals/
     */

    public function getWithdrawals_post() {
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Withdrawal Data */
        $WithdrawalsData = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WithdrawalsData)) {
            $this->Return['Data'] = $WithdrawalsData['Data'];
        }
    }

    /*
      Name: 			getWithdrawal
      Description: 	To get Withdrawal data
      URL: 			/users/getWithdrawals/
     */

    public function getWithdrawal_post() {
        $this->form_validation->set_rules('WithdrawalID', 'WithdrawalID', 'trim|required');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Withdrawal Data */
        $WithdrawalsData = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array('WithdrawalID' => @$this->Post['WithdrawalID'])), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WithdrawalsData)) {
            $this->Return['Data'] = $WithdrawalsData['Data'];
        }
    }

    public function export_Withdrawal_list_post() {
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Withdrawal Data */
        $from_date = $this->input->post('FromDate');
        $to_date = $this->input->post('ToDate');
        $user_type = 2;

        $requestList = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);

        $requestList = $requestList['Data']['Records'];

        if ($requestList) {
            $print_array = array();
            $i = 1;
            foreach ($requestList as $value) {
                $print_array[] = array(
                    's_no' => $i,
                    'UserID' => $value['UserID'],
                    'FirstName' => $value['FirstName'],
                    'Email' => $value['Email'],
                    'PhoneNumber' => $value['PhoneNumber'],
                    'Amount' => $value['Amount'],
                    'AccountNumber' => $value['MediaBANK']['MediaCaption']->AccountNumber,
                    'Bank' => $value['MediaBANK']['MediaCaption']->Bank,
                    'IFSCCode' => $value['MediaBANK']['MediaCaption']->IFSCCode,
                    'EntryDate' => $value['EntryDate'],
                    'Status' => $value['Status']);
                $i++;
            }

            $fp = fopen('WithdrawalList.csv', 'w');

            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=WithdrawalList.csv');
            fputcsv($fp, array('S.no', 'User Id', 'User Name', 'Email', 'Phone', 'Amount', 'AccountNumber', 'Bank', 'IFSCCode', 'Request Date', 'Status'));

            foreach ($print_array as $row) {
                fputcsv($fp, $row);
            }

            $this->Return['ResponseCode'] = 200;
            $this->Return['Message'] = "Successfully Exported";
            $this->Return['Data'] = BASE_URL . 'WithdrawalList.csv';
        } else {
            $this->Return['Message'] = "Something Went Wrong";
        }
    }

    public function export_Transactions_list_post() {
       $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('TransactionMode', 'TransactionMode', 'trim|required|in_list[All,WalletAmount,WinningAmount,CashBonus]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        $from_date = $this->input->post('FromDate');
        $to_date = $this->input->post('ToDate');
        $user_type = 2;

        /* Get Wallet Data */
        $requestList = $this->Users_model->getWallet(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);

        $requestList = $requestList['Data']['Records'];

        if ($requestList) {
            $print_array = array();
            $i = 1;
            foreach ($requestList as $value) {
                $print_array[] = array(
                    's_no' => $i,
                    'TransactionID' => $value['TransactionID'],
                    'Narration' => $value['Narration'],
                    'TransactionType' => $value['TransactionType'],
                    'OpeningBalance' => $value['OpeningBalance'],
                    'Amount' => $value['Amount'],
                    'ClosingBalance' => $value['ClosingBalance'],
                    'AvailableBalance' => ($value['WalletAmount'] + $value['CashBonus']) + $value['WinningAmount'],
                    'EntryDate' => $value['EntryDate'],
                    'Status' => $value['Status']);
                $i++;
            }

            $fp = fopen('TransactionList.csv', 'w');

            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=WithdrawalList.csv');
            fputcsv($fp, array('S.no', 'Transaction ID', 'Narration', 'Transaction Type', 'OpeningBalance', 'Amount', 'ClosingBalance', 'AvailableBalance', 'Entry Date', 'Status'));

            foreach ($print_array as $row) {
                fputcsv($fp, $row);
            }

            $this->Return['ResponseCode'] = 200;
            $this->Return['Message'] = "Successfully Exported";
            $this->Return['Data'] = BASE_URL . 'TransactionList.csv';
        } else {
            $this->Return['Message'] = "Something Went Wrong";
        }
    }

    /*
      Description: 	Use to update user profile info.
      URL: 			/api_admin/entity/changeStatus/
     */

    public function changeWithdrawalStatus_post() {
        /* Validation section */
        $this->form_validation->set_rules('WithdrawalID', 'WithdrawalID', 'trim|required');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        $this->Users_model->updateWithdrawal(@$this->Post['WithdrawalID'], array("StatusID" => $this->StatusID, "Comments" => $this->Post['Comments']));
        $this->Return['Data'] = $this->Users_model->getWithdrawals(@$this->Post['Params'], array("WithdrawalID" => @$this->Post['WithdrawalID']));
        $this->Return['Message'] = "Status has been changed.";
    }

    /*
      Description : To add cash bonus to user

     */

    public function addCashBonus_post() {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('Narration', 'Narration', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        $this->Users_model->addToWallet(array_merge($this->Post, array('CashBonus' => $this->Post['Amount'], 'TransactionType' => 'Cr')), $this->UserID, $this->StatusID);
        $this->Return['Message'] = "Cash bonus added Successfully.";
    }

    /*
      Description : To add cash deposit to user

     */

    public function addCashDeposit_post() {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('Narration', 'Narration', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        $this->Users_model->addToWallet(array_merge($this->Post, array('WalletAmount' => $this->Post['Amount'], 'TransactionType' => 'Cr')), $this->UserID, $this->StatusID);
        $this->Return['Message'] = "Cash added Successfully.";
    }

    /*
      Name: 			getReferredUsers
      Description: 	To get all referred users
      URL: 			/users/getReferredUsers/
     */

    public function getReferredUsers_post() {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Referred Users Data */
        $ReferredUsersData = $this->Users_model->getUsers(@$this->Post['Params'], array('ReferredByUserID' => $this->UserID, 'OrderBy' => @$this->Post['OrderBy'], 'Sequence' => @$this->Post['Sequence']), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($ReferredUsersData)) {
            $this->Return['Data'] = $ReferredUsersData['Data'];
        }
    }

}
