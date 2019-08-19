<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Wallet extends API_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	/*
	Name: 			getWallet
	Description: 	To get wallet data
	URL: 			/admin/wallet/getWallet/	
	*/
	public function getWallet_post()
	{
		$this->form_validation->set_rules('CouponCode', 'CouponCode', 'trim');
		$this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim' . (empty($this->Post['CouponCode']) ? '|required|callback_validateEntityGUID[User,UserID]' : ''));
		$this->form_validation->set_rules('TransactionMode', 'TransactionMode', 'trim|required|in_list[All,WalletAmount,WinningAmount,CashBonus]');
		$this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
		$this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
		$this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
		$this->form_validation->validation($this);  /* Run validation */

		/* Get Wallet Data */
		$WalletDetails = $this->Users_model->getWallet(@$this->Post['Params'], array_merge($this->Post, array('UserID' => @$this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
		if (!empty($WalletDetails)) {
			$this->Return['Data'] = $WalletDetails['Data'];
		}
	}

	/*
	Name: 			getWithdrawals
	Description: 	To get Withdrawal data
	URL: 			/admin/wallet/getWithdrawals/	
	*/
	public function getWithdrawals_post()
	{
		$this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
		$this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
		$this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
		$this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
		$this->form_validation->validation($this);  /* Run validation */

		/* Get Withdrawal Data */
		$WithdrawalsData = $this->Users_model->getWithdrawals(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
		if (!empty($WithdrawalsData)) {
			$this->Return['Data'] = $WithdrawalsData['Data'];
		}
	}
}
