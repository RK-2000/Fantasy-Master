<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Series extends API_Controller_Secure
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Sports_model');
	}

	/*
	Description: To get series data
	*/
	public function getSeries_post()
    {
		$this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Matches Data */
        $SeriesData = $this->Sports_model->getSeries(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => @$this->SeriesID, 'StatusID' => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($SeriesData)) {
            $this->Return['Data'] = $SeriesData['Data'];
        }
    }

	/*
		Description: 	Use to update series status.
		URL: 			/admin/series/changeStatus/	
	*/
	public function changeStatus_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|required|callback_validateEntityGUID[Series,SeriesID]');
		$this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */
		/* Validation - ends */

		/* Update Series Details */
		$this->Sports_model->updateSeriesData($this->SeriesID, array("SeriesName"=>$this->Post['SeriesName'],"StatusID"=>$this->StatusID));
		$this->Return['Data']=$this->Sports_model->getSeries('SeriesName,Status,',array('SeriesID' => $this->SeriesID));
		$this->Return['Message'] =	"Success.";
	}
}
