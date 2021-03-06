<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config extends API_Controller_Secure
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Utility_model');
	}

	/*
	Name: 			update
	Description: 	Use to get site config.
	URL: 			/config/getConfigs/	
	*/
	public function getConfigs_post()
	{
		$ConfigData = $this->Utility_model->getConfigs(@$this->Post);
		if (!empty($ConfigData)) {
			$this->Return['Data'] = $ConfigData['Data'];
		}
	}

	/*
	Name: 			update
	Description: 	Use to update site config.
	URL: 			/config/update/	
	*/
	public function update_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('ConfigTypeGUID', 'ConfigTypeGUID', 'trim|required');
		$this->form_validation->set_rules('ConfigTypeValue', 'ConfigTypeValue', 'trim|required|integer');
		$this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */
		/* Validation - ends */

		$this->Utility_model->updateConfig($this->Post['ConfigTypeGUID'], array_merge(array("StatusID" => $this->StatusID), $this->Post));
		$this->Return['Data'] = array();
	}

	/*
		Name : banner list
		Description : User to get banner list
		URL : /config/banner/
	*/
	public function bannerList_post()
	{
		$BannerData = $this->Utility_model->bannerList('', '', TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
		if ($BannerData) {
			$this->Return['Data'] = $BannerData['Data'];
		}
	}

	/*
		Name : add banner
		Description : to add banner
		URL : /config/addBanner/
	*/
	public function addBanner_post()
	{
		$this->form_validation->set_rules('MediaGUIDs', 'MediaGUIDs', 'trim|required');
		$this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */

		/* Add Banner */
		$BannerData = $this->Utility_model->addBanner($this->SessionUserID, array_merge($this->Post), $this->StatusID);
		if (!empty($this->Post['MediaGUIDs'])) {
			$MediaGUIDsArray = explode(",", $this->Post['MediaGUIDs']);
			foreach ($MediaGUIDsArray as $MediaGUID) {
				$EntityData = $this->Entity_model->getEntity('E.EntityID MediaID', array('EntityGUID' => $MediaGUID, 'EntityTypeID' => 14));
				if ($EntityData) {
					$this->Media_model->addMediaToEntity($EntityData['MediaID'], $this->SessionUserID, $BannerData['BannerID']);
				}
			}
		}
		$this->Return['Data'] = array();
		$this->Return['Message'] = "Banner added successfully.";
	}

	/*
	Name: 			getApiLogs
	Description: 	Use to get api logs
	URL: 			/config/getApiLogs/	
	*/
	public function getApiLogs_post()
	{
		$LogsData = $this->Common_model->getApiLogs(@$this->Post, @$this->Post['PageNo'], @$this->Post['PageSize']);
		if (!empty($LogsData)) {
			$this->Return['Data'] = $LogsData['Data'];
		}
		$this->Return['Data']['IsAPILogs'] = API_SAVE_LOG;
	}

	/*
	Name: 			deleteApiLogs
	Description: 	Use to delete api logs
	URL: 			/config/deleteApiLogs/	
	*/
	public function deleteApiLogs_post()
	{
		$this->form_validation->set_rules('LogId', 'LogId', 'trim|required');
		$this->form_validation->validation($this);  
		/* Run validation */

	    $this->Common_model->deleteApiLogs($this->Post['LogId']);
	}

	/*
	Name: 			deleteAllApiLogs
	Description: 	Use to delete all api logs
	URL: 			/config/deleteAllApiLogs/	
	*/
	public function deleteAllApiLogs_post()
	{
		$LogsData = $this->Common_model->deleteAllApiLogs();
	}
}