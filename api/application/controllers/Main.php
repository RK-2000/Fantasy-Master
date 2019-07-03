<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MAIN_Controller
{
	public function index()
	{
		echo "This is a sample page.";
	}

	/*
      Description: 	Use to see php logs.
      URL: 			/api/main/logs/
     */
	public function logs()
	{
		$this->load->library('logviewer');
		$this->load->view('logs', $this->logviewer->get_logs());
	}

	public function upload()
	{
		$this->load->view('upload');
	}

	/*
      Description: 	Use to handle paytm response
      URL: 			/api/main/paytmResponse/
     */
	public function paytmResponse()
	{
		$this->Users_model->paytmResponse($this->input->post());
	}
}
