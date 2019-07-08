<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends Admin_Controller_Secure {
	
	/*------------------------------*/
	/*------------------------------*/	
	public function index()
	{
		$load['css']=array(
			'asset/plugins/chosen/chosen.min.css',
			'asset/plugins/daterangepicker/daterangepicker.css'
		);
		$load['js']=array(
			'asset/js/'.$this->ModuleData['ModuleName'].'.js',
			'asset/plugins/chosen/chosen.jquery.min.js',
			'asset/plugins/daterangepicker/daterangepicker.js'
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('user/user_list');
		$this->load->view('includes/footer');
	} 



}
