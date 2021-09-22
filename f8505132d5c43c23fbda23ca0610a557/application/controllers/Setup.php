<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setup extends Admin_Controller_Secure {
	
	/*------------------------------*/
	/*------------------------------*/	
	public function index()
	{
		$load['css']=array(
		
		);
		$load['js']=array(
			'asset/js/'.$this->ModuleData['ModuleName'].'.js',
			
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('setup/setup');
		$this->load->view('includes/footer');
	}
	public function corehrsetup()
	{
		$load['css']=array(
		
		);
		$load['js']=array(
			'asset/js/setup.js',
			
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('setup/setupCorehr');
		$this->load->view('includes/footer');
	}
	public function group()
	{
		$load['css']=array(
			'asset/plugins/chosen/chosen.min.css',
		);
		$load['js']=array(
			'asset/js/group.js',	
			'asset/plugins/chosen/chosen.jquery.min.js',		
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('setup/group');
		$this->load->view('includes/footer');
	}
	public function setupmodule()
	{
		$load['css']=array(

		);
		$load['js']=array(
			'asset/js/'.$this->ModuleData['ModuleName'].'.js',
			
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('setup/moduleSection');
		$this->load->view('includes/footer');
	}
		public function setupuseredit()
	{
		$load['css']=array(
				
		);
		$load['js']=array(
			'asset/js/'.$this->ModuleData['ModuleName'].'.js',
			
		);	

		$this->load->view('includes/header',$load);
		$this->load->view('includes/menu');
		$this->load->view('setup/setupUseredit');
		$this->load->view('includes/footer');
	}



}
