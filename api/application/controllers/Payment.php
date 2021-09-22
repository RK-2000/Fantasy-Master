<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
    }

    /*
      Description:  Use to manage razorpay webhook response
      URL:      /api/payment/razorpayWebhook
     */

    public function razorpayWebhook() {
        $this->Users_model->razorpayWebhook(file_get_contents("php://input"));
    }

}



