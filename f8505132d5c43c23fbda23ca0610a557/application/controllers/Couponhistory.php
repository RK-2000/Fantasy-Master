<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Couponhistory extends Admin_Controller_Secure {
	
	/*------------------------------*/
	/*------------------------------*/	
	public function index()
	{
		$load['css']=array(
			'asset/plugins/chosen/chosen.min.css',
			'asset/plugins/daterangepicker/daterangepicker.css'
		);
		$load['js']=array(
			'asset/js/coupon.js',
			'asset/plugins/chosen/chosen.jquery.min.js',
			'asset/plugins/daterangepicker/daterangepicker.js'
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('coupon/coupon_history');
		$this->load->view('includes/footer');
	}



}
