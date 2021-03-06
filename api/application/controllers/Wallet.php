<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends API_Controller_Secure
{

    function __construct()
    {
        parent::__construct();
    }

    /*
      Name: 		add
      Description: 	Use to add wallet cash
      URL: 			/wallet/add/
     */

    public function add_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('RequestSource', 'RequestSource', 'trim|required|in_list[Web,Mobile]');
        $this->form_validation->set_rules('CouponGUID', 'CouponGUID', 'trim|callback_validateEntityGUID[Coupon,CouponID]');
        $this->form_validation->set_rules('PaymentGateway', 'PaymentGateway', 'trim|required|in_list[PayUmoney,Paytm,Razorpay,CashFree]');
        $this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric|callback_validateMinimumDepositAmount');
        $this->form_validation->set_rules('Email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|numeric');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $PaymentResponse = $this->Users_model->add($this->Post, $this->SessionUserID, @$this->CouponID);
        if (empty($PaymentResponse)) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Data'] = $PaymentResponse;
            $this->Return['Message'] = "Success.";
        }
    }

    /*
      Name: 		confirm
      Description: 	Use to update payment gateway response
      URL: 			/wallet/confirm/
     */
    public function confirm_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('PaymentGateway', 'PaymentGateway', 'trim|required|in_list[PayUmoney,Paytm,Razorpay,CashFree]');
        $this->form_validation->set_rules('PaymentGatewayStatus', 'PaymentGatewayStatus', 'trim|required|in_list[Success,Failed,Cancelled]');
        $this->form_validation->set_rules('WalletID', 'WalletID', 'trim|required|numeric|callback_validateWalletID');
        $this->form_validation->set_rules('RazorPaymentId', 'RazorPaymentId', 'trim' . (!empty($this->Post['PaymentGateway']) && $this->Post['PaymentGateway'] == 'Razorpay' ? '|required' : ''));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $WalletData = $this->Users_model->confirm($this->Post, $this->SessionUserID);
        if (!$WalletData) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Data'] = $WalletData;
            $this->Return['Message'] = "Success.";
        }
    }

    /*
      Name: 		getWallet
      Description: 	To get wallet data
      URL: 			/wallet/getWallet/
    */
    public function getWallet_post()
    {
        $this->form_validation->set_rules('TransactionMode', 'TransactionMode', 'trim|required|in_list[All,WalletAmount,WinningAmount,CashBonus]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Wallet Data */
        $WalletDetails = $this->Users_model->getWallet(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->SessionUserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WalletDetails)) {
            $this->Return['Data'] = $WalletDetails['Data'];
        }
    }

    /*
	Name: 			withdrawal_add
	Description: 	Use to add withdrawal winning amount 
	URL: 			/wallet/withdrawal/	
	*/
	public function withdrawal_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required');
		$this->form_validation->set_rules('PaymentGateway', 'PaymentGateway', 'trim|required|in_list[Paytm,Bank]');
		$this->form_validation->set_rules('PaytmPhoneNumber', 'PaytmPhoneNumber', 'trim' . (!empty($this->Post['PaymentGateway']) && $this->Post['PaymentGateway'] == 'Paytm' ? '|required' : ''));
		$this->form_validation->set_rules('Amount', 'Amount', 'trim|required|numeric|callback_validateWithdrawalAmount');
		$this->form_validation->validation($this);  /* Run validation */		
		/* Validation - ends */

        /* Withdrawal Data */
		$WithdrawalData = $this->Users_model->withdrawal($this->Post, $this->SessionUserID); 
		if(empty($WithdrawalData)){
			$this->Return['ResponseCode'] 	=	500;
			$this->Return['Message']      	=	"An error occurred, please try again later.";  
		}else{
			$this->Return['Data']      	    =   $WithdrawalData;
			$this->Return['Message']      	=	(!OTP_WITHDRAWAL) ? "Your withdrawal request submitted succefully." : "Success."; 
		}
	}

	/*
	Name: 			withdrawal_confirm
	Description: 	Use to confirm withdrawal winning amount
	URL: 			/wallet/withdrawal_confirm/	
	*/
	public function withdrawal_confirm_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('WithdrawalID', 'WithdrawalID', 'trim|required|callback_validateWithdrawalID');
		$this->form_validation->set_rules('OTP', 'OTP', 'trim|required');
		$this->form_validation->validation($this);  /* Run validation */		
        /* Validation - ends */
        
		$WalletData = $this->Users_model->withdrawal_confirm($this->Post, $this->SessionUserID); 
		if(empty($WalletData)){
			$this->Return['ResponseCode'] 	=	500;
			$this->Return['Message']      	=	"An error occurred, please try again later.";  
		}else{
			$this->Return['Data']      	    =   $WalletData;
			$this->Return['Message']      	=	"Success."; 
		}
	}

    /*
      Name: 		getWithdrawals
      Description: 	To get Withdrawal data
      URL: 			/wallet/getWithdrawals/
     */

    public function getWithdrawals_post()
    {
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Withdrawal Data */
        $WithdrawalsData = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->SessionUserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WithdrawalsData)) {
            $this->Return['Data'] = $WithdrawalsData['Data'];
        }
    }

    /* -----Validation Functions----- */
    /* ------------------------------ */

    /**
     * Function Name: validateMinimumDepositAmount
     * Description:   To validate minimum deposit amount
     */
    public function validateMinimumDepositAmount($Amount)
    {
        /* Get Minimum Deposit Limit */
        $MinimumDepositLimit = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "MinimumDepositLimit" LIMIT 1')->row()->ConfigTypeValue;
        if ($Amount < $MinimumDepositLimit) {
            $this->form_validation->set_message('validateMinimumDepositAmount', 'Minimum deposit amount limit is ' . DEFAULT_CURRENCY . $MinimumDepositLimit);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function Name: validateWalletID
     * Description:   To validate wallet ID
     */
    public function validateWalletID($WalletID)
    {
        $WalletData = $this->Users_model->getWallet('Amount,TransactionID,Status,CouponDetails', array('UserID' => $this->SessionUserID, 'WalletID' => $WalletID));
        if (!$WalletData) {
            $this->form_validation->set_message('validateWalletID', 'Invalid {field}.');
            return FALSE;
        } else {
            $this->Post['Amount'] = round($WalletData['Amount'], 1);
            $this->Post['Status'] = $WalletData['Status'];
            $this->Post['TransactionID'] = $WalletData['TransactionID'];
            $this->Post['CouponDetails'] = $WalletData['CouponDetails'];
            return TRUE;
        }
    }

    /**
     * Function Name: validateWithdrawalAmount
     * Description:   To validate withdrawal amount
     */
    public function validateWithdrawalAmount($Amount)
    {
        /* To Get Withdrawal Configurations */
        $ConfigTypeValue = (MEMCACHE) ? $this->cache->memcached->get('MinimumWithdrawalLimitBank') : '';
        if(empty($ConfigData)){
            $ConfigData = $this->Utility_model->getConfigs(array('ConfigTypeGUID' => 'MinimumWithdrawalLimitBank'));
            $ConfigTypeValue = (!$ConfigData) ? 200 : $ConfigData['Data']['Records'][0]['ConfigTypeValue'];
            if(MEMCACHE){
                $this->cache->memcached->save('MinimumWithdrawalLimitBank',$ConfigTypeValue, 3600 * 24 * 10); // Expire in every 10 Days 
            }
        }
        if ($Amount < $ConfigTypeValue) {
            $this->form_validation->set_message('validateWithdrawalAmount', 'Minimum withdrawal amount limit is ' . DEFAULT_CURRENCY . $ConfigData['Data']['Records'][0]['ConfigTypeValue']);
            return FALSE;
        }

        /* Validate Winning Amount */
        $WinningAmount = $this->db->query('SELECT WinningAmount FROM tbl_users WHERE UserID = ' . $this->SessionUserID . ' LIMIT 1')->row()->WinningAmount;
        if ($Amount > $WinningAmount) {
            $this->form_validation->set_message('validateWithdrawalAmount', 'Withdrawal amount can not greater than to winning amount.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validateAccountStatus
     * Description:   To validate user account status
     */
    public function validateAccountStatus($UserGUID)
    {
        /* Validate account status */
        $userData = $this->Users_model->getUsers('PanStatus,BankStatus', array('UserID' => $this->SessionUserID));
        if ($userData['BankStatus'] != 'Verified') {
            $this->form_validation->set_message('validateAccountStatus', 'Bank account details not verified.');
            return FALSE;
        }
        if ($userData['PanStatus'] != 'Verified') {
            $this->form_validation->set_message('validateAccountStatus', 'Pan card details not verified.');
            return FALSE;
        }

        /* Validate Pending Withdrawal Request */
        if ($this->db->query('SELECT COUNT(EntryDate) TotalRecords FROM `tbl_users_withdrawal` WHERE `UserID` = ' . $this->SessionUserID . ' AND `StatusID` = 1 LIMIT 1')->row()->TotalRecords > 0) {
            $this->form_validation->set_message('validateAccountStatus', 'Your withdrawal request already in pending mode.');
            return FALSE;
        }
        return TRUE;
    }
}
