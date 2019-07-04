<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends API_Controller_Secure
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Recovery_model');
    }

    /*
      Description: 	Use to broadcast message.
      URL: 			/admin/users/broadcast/
     */

    public function broadcast_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->set_rules('Title', 'Title', 'trim|required');
        $this->form_validation->set_rules('Message', 'Title', 'trim|required');
        $this->form_validation->set_rules('MediaGUIDs', 'MediaGUIDs', 'trim'); /* Media GUIDs */
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

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
            } elseif (!empty($this->Post['SMS']) && $this->Post['SMS'] == 1) {
                $this->Return['Message'] = 'SMS broadcasted.';
            } elseif (!empty($this->Post['Notification']) && $this->Post['Notification'] == 1) {
                foreach ($UsersData['Data']['Records'] as $Value) {
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
                if (!empty($InsertData)) {
                    $this->db->insert_batch('tbl_notifications', $InsertData);
                }
                $this->Return['Message'] = 'Notification broadcasted.';
            } else {
                $this->Return['Message'] = 'Please Select broadcast Type.';
            }
        }
    }

    /*
      Name: 		getUsers
      Description: 	Use to get users list.
      URL: 			/admin/users/getProfile
     */

    public function index_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('StoreGUID', 'StoreGUID', 'trim' . ($this->UserTypeID == 4 ? '|required' : '') . '|callback_validateEntityGUID[Store,StoreID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('AdminUsers', 'AdminUsers', 'trim');
        $this->form_validation->set_rules('PageNo', 'PageNo', 'trim|integer');
        $this->form_validation->set_rules('PageSize', 'PageSize', 'trim|integer');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $UsersData = $this->Users_model->getUsers((!empty($this->Post['Params']) ? $this->Post['Params'] : ''), array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($UsersData) {
            $this->Return['Data'] = $UsersData['Data'];
        }
    }

    /*
      Description: 	Use to update user profile info.
      URL: 			/admin/entity/changeStatus/
     */

    public function changeStatus_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->updateUserInfo($this->UserID, array("IsPrivacyNameDisplay" => @$this->Post['IsPrivacyNameDisplay']));
        $this->Entity_model->updateEntityInfo($this->UserID, array("StatusID" => $this->StatusID));
        $this->Return['Data'] = $this->Users_model->getUsers('FirstName,LastName,Email,ProfilePic,Status', array("UserID" => $this->UserID));
        $this->Return['Message'] = "Success.";
    }

    /*
      Description: 	Use to update user details as pan and bank details.
      URL: 			/admin/entity/changeVerificationStatus/
     */
    public function changeVerificationStatus_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('VetificationType', 'VetificationType', 'trim|required|in_list[PAN,BANK]');
        $this->form_validation->set_rules('PanStatus', 'PanStatus', 'trim' . (!empty($this->Post['VetificationType']) && $this->Post['VetificationType'] == 'PAN' ? '|required|callback_validateStatus' : ''));
        $this->form_validation->set_rules('BankStatus', 'BankStatus', 'trim' . (!empty($this->Post['VetificationType']) && $this->Post['VetificationType'] == 'BANK' ? '|required|callback_validateStatus' : ''));
        $this->form_validation->set_rules('Comments', 'Comments', 'trim');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Update User Details */
        $this->Users_model->updateUserInfo($this->UserID, array('PanStatus' => @$this->StatusID,'BankStatus' => @$this->StatusID,'Comments' => @$this->Post['Comments']));

        /* Get User Data */
        $UserData = $this->Users_model->getUsers('FirstName,LastName,Email,ProfilePic,Status,PanStatus,BankStatus,PhoneNumber', array("UserID" => $this->UserID));

        /* Manage Verification Bonus */
        if ($UserData['PanStatus'] == 'Verified' && $UserData['BankStatus'] == 'Verified' && !empty($UserData['PhoneNumber'])) {
            $BonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "VerificationBonus" LIMIT 1');
            if ($BonusData->row()->StatusID == 2) {
                $WalletData = array(
                    "Amount"    => $BonusData->row()->ConfigTypeValue,
                    "CashBonus" => $BonusData->row()->ConfigTypeValue,
                    "TransactionType" => 'Cr',
                    "Narration" => 'Verification Bonus',
                    "EntryDate" => date("Y-m-d H:i:s")
                );
                $this->Users_model->addToWallet($WalletData, $this->UserID, 5);
            }
        }
        $this->Return['Data'] = $UserData;
        $this->Return['Message'] = "Success.";
    }

    /*
      Name: 		updateUserInfo
      Description: 	Use to update user profile info.
      URL: 			/admin/updateUserInfo/
     */
    public function updateUserInfo_post()
    {
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
      Name: 		add
      Description: 	Use to register user to system.
      URL: 			/admin/users/add/
     */

    public function add_post()
    {
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
      Name: 		getWallet
      Description: 	To get wallet data
      URL: 			/admin/users/getWallet/
     */

    public function getWallet_post()
    {
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
      Name: 		getWithdrawals
      Description: 	To get all Withdrawal requests
      URL: 			/admin/users/getWithdrawals/
     */
    public function getWithdrawals_post()
    {
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
	Description: 	Use to update user profile info.
	URL: 			/api_admin/entity/changeWithdrawalStatus/	
	*/
	public function changeWithdrawalStatus_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
		$this->form_validation->set_rules('Comments', 'Comments', 'trim' . (!empty($this->Post['Status']) && $this->Post['Status'] == 'Rejected' ? '|required' : ''));
		$this->form_validation->set_rules('WithdrawalID', 'WithdrawalID', 'trim|required|callback_validateWithdrawalStatus');
		$this->form_validation->validation($this);  /* Run validation */	
        /* Validation - ends */
        
		$this->Users_model->updateWithdrawal(array_merge(@$this->Post, array("StatusID"=>$this->StatusID)));
		$this->Return['Data']=$this->Users_model->getWithdrawals(@$this->Post['Params'],array("WithdrawalID" => @$this->Post['WithdrawalID']));
		$this->Return['Message'] =	"Status has been changed.";
	}

    /*
      Description : To add cash bonus to user
     */
    public function addCashBonus_post()
    {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('Narration', 'Narration', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->addToWallet(array_merge($this->Post, array('CashBonus' => $this->Post['Amount'], 'TransactionType' => 'Cr')), $this->UserID, $this->StatusID);
        $this->Return['Message'] = "Cash bonus added Successfully.";
    }

    /*
      Description : To add cash deposit to user
     */
    public function addCashDeposit_post()
    {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('Narration', 'Narration', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Users_model->addToWallet(array_merge($this->Post, array('WalletAmount' => $this->Post['Amount'], 'TransactionType' => 'Cr')), $this->UserID, $this->StatusID);
        $this->Return['Message'] = "Deposit added Successfully.";
    }

    /*
      Description : To export user withdrawals
     */
    public function exportWithdrawals_post()
    {
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get Withdrawal Data */
        $WithdrawalData = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array("StatusID" => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($WithdrawalData['Data']['TotalRecords'] > 0) {
            foreach ($WithdrawalData['Data']['Records'] as $Key => $Value) {
                $DataArr[] = array(
                            's_no'          => $Key++,
                            'FirstName'     => $Value['FirstName'],
                            'Email'         => $Value['Email'],
                            'PhoneNumber'   => $Value['PhoneNumber'],
                            'Amount'        => $Value['Amount'],
                            'AccountNumber' => $Value['MediaBANK']['MediaCaption']->AccountNumber,
                            'Bank'          => $Value['MediaBANK']['MediaCaption']->Bank,
                            'IFSCCode'      => $Value['MediaBANK']['MediaCaption']->IFSCCode,
                            'EntryDate'     => $Value['EntryDate'],
                            'Status'        => $Value['Status']
                        );
            }
            $FP = fopen('WithdrawalList.csv', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=WithdrawalList.csv');
            fputcsv($FP, array('S.no', 'User Name', 'Email', 'Phone', 'Amount', 'Account Number', 'Bank', 'IFSC Code', 'Request Date', 'Status'));
            foreach ($DataArr as $Row) {
                fputcsv($FP, $Row);
            }
            $this->Return['ResponseCode'] = 200;
            $this->Return['Data'] = BASE_URL . 'WithdrawalList.csv';
        } else {
            $this->Return['Message'] = "Withdrawal history not found.";
        }
    }

    /*
      Description : To export user transactions
     */
    public function exportTransactions_post()
    {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('TransactionMode', 'TransactionMode', 'trim|required|in_list[All,WalletAmount,WinningAmount,CashBonus]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get Wallet Data */
        $WalletData = $this->Users_model->getWallet(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($WalletData['Data']['TotalRecords'] > 0) {
            foreach ($WalletData['Data']['Records'] as $Key => $Value) {
                $DataArr[] = array(
                    's_no'             => $Key++,
                    'TransactionID'    => $Value['TransactionID'],
                    'Narration'        => $Value['Narration'],
                    'TransactionType'  => $Value['TransactionType'],
                    'OpeningBalance'   => $Value['OpeningBalance'],
                    'Amount'           => $Value['Amount'],
                    'ClosingBalance'   => $Value['ClosingBalance'],
                    'AvailableBalance' => ($Value['WalletAmount'] + $Value['CashBonus']) + $Value['WinningAmount'],
                    'EntryDate'        => $Value['EntryDate'],
                    'Status'           => $Value['Status']
                );
            }
            $FP = fopen('TransactionList.csv', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=WithdrawalList.csv');
            fputcsv($FP, array('S.no', 'Transaction ID', 'Narration', 'Transaction Type', 'Opening Balance', 'Amount', 'Closing Balance', 'Available Balance', 'Entry Date', 'Status'));
            foreach ($DataArr as $Row) {
                fputcsv($FP, $Row);
            }
            $this->Return['ResponseCode'] = 200;
            $this->Return['Data'] = BASE_URL . 'TransactionList.csv';
        } else {
            $this->Return['Message'] = "Wallet history not found.";
        }
    }

    /*
      Name: 		getReferredUsers
      Description: 	To get all referred users
      URL: 			/admin/users/getReferredUsers/
     */
    public function getReferredUsers_post()
    {
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

    /**
     * Function Name: validateWithdrawalStatus
     * Description:   To validate withdrawal details
     */
    public function validateWithdrawalStatus($WithdrawalID) {

		/* Validate Withdrawal ID */
		$WithdrawalData = $this->Users_model->getWithdrawals('Status,Amount,UserID,Email', array('WithdrawalID' => $WithdrawalID));
        if (!$WithdrawalData) {
            $this->form_validation->set_message('validateWithdrawalStatus', 'Invalid Withdrawal ID.');
            return FALSE;
		}

		/* Check Withdrawal request is pending ? */
		if ($WithdrawalData['Status'] != "Pending") {
			$this->form_validation->set_message('validateWithdrawalStatus', 'You can update only Pending withdrawal request.');
			return FALSE;
		}
		$this->Post['WithdrawalAmount'] = round($WithdrawalData['Amount'], 1);
		$this->Post['WithdrawalUserID'] = $WithdrawalData['UserID'];
		$this->Post['UserFullName']     = $WithdrawalData['FullName'];
		$this->Post['UserEmail']        = $WithdrawalData['Email'];
		return TRUE;
	}
}
