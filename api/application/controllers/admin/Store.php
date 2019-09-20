<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store extends API_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Store_model');
	}

	/*
	Description: 	Use to add coupon.
	URL: 			/api_admin/store/addCoupon/	
	*/
	public function addCoupon_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
		$this->form_validation->set_rules('CouponCode', 'CouponCode', 'trim|required');
		$this->form_validation->set_rules('CouponValue', 'CouponValue', 'trim|required');
		$this->form_validation->set_rules('CouponType', 'CouponType', 'trim|required');
		$this->form_validation->set_rules('CouponTitle', 'CouponTitle', 'trim|required');
		$this->form_validation->set_rules('CouponDescription', 'CouponDescription', 'trim');
		$this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */
		/* Validation - ends */

		/* check for media present - associate media with this Post */
		if (!empty($this->Post['MediaGUIDs'])) {
			$MediaGUIDsArray = explode(",", $this->Post['MediaGUIDs']);
			foreach ($MediaGUIDsArray as $MediaGUID) {
				$EntityData = $this->Entity_model->getEntity('E.EntityID MediaID', array('EntityGUID' => $MediaGUID, 'EntityTypeID' => 13));
				if ($EntityData) {
					$this->Media_model->addMediaToEntity($EntityData['MediaID'], $this->SessionUserID, $this->TeamID);
				}
				$MediaData = $this->Media_model->getMedia(
					'MediaGUID,M.MediaName',
					array("SectionID" => "Coupon", "MediaID" => $EntityData['MediaID']),
					FALSE
				);
			}
		}
		/* check for media present - associate media with this Post - ends */

		/* Add Coupon */
		$CouponData = $this->Store_model->addCoupon($this->SessionUserID, array_merge($this->Post,array('CouponBanner'=> @$MediaData['MediaName'])), $this->StatusID);
		if($CouponData){
			$this->Return['Message']  =	"Coupon added successfully.";
		}else{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "An error occurred, please try again later.";			
		}
	}

	/*
	Description: 	Use to update coupon.
	URL: 			/api_admin/store/editCoupon/	
	*/ 
	public function editCoupon_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
		$this->form_validation->set_rules('CouponGUID', 'CouponGUID', 'trim|required|callback_validateEntityGUID[Coupon,CouponID]');
		$this->form_validation->set_rules('CouponValidTillDate', 'CouponValidTillDate', 'trim|callback_validateDate');
		$this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */
		/* Validation - ends */

		/* check for media present - associate media with this Post */
		if (!empty($this->Post['MediaGUIDs'])) {
			$MediaGUIDsArray = explode(",", $this->Post['MediaGUIDs']);
			foreach ($MediaGUIDsArray as $MediaGUID) {
				$EntityData = $this->Entity_model->getEntity('E.EntityID MediaID', array('EntityGUID' => $MediaGUID, 'EntityTypeID' => 13));
				if ($EntityData) {
					$this->Media_model->addMediaToEntity($EntityData['MediaID'], $this->SessionUserID, $this->TeamID);
				}
				$MediaData = $this->Media_model->getMedia(
					'MediaGUID,M.MediaName',
					array("SectionID" => "Coupon", "MediaID" => $EntityData['MediaID']),
					FALSE
				);
			}
		}
		/* check for media present - associate media with this Post - ends */

		/* Update Coupon */
		$this->Store_model->updateCoupon($this->CouponID, array_merge($this->Post, array("StatusID" => $this->StatusID,'CouponBanner'=> @$MediaData['MediaName'])));


		$CouponData = $this->Store_model->getCoupons(
			'
			C.CouponValidTillDate
			',
			array("CouponID" => @$this->CouponID)
		);
		$this->Return['Data'] = $CouponData;
		$this->Return['Message']      	=	"Status has been changed.";
	}
}
