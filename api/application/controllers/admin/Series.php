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
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
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
		URL: 			/admin/entity/changeStatus/	
	*/
	public function changeStatus_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|required|callback_validateEntityGUID[Series,SeriesID]');
		$this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
		$this->form_validation->set_rules('AuctionDraftIsPlayed', 'AuctionDraftIsPlayed', 'trim|required');
		$this->form_validation->set_rules('AuctionDraftIsPlayed', 'AuctionDraftIsPlayed', 'trim' . (IS_AUCTION ? '|required' : ''));
		$this->form_validation->validation($this);  /* Run validation */

		/* Validation - ends */
		if (IS_AUCTION && !empty($this->Post['DraftPlayerSelectionCriteria'])) {
			$DraftPlayerSelectionCriteria = array(
												"Wk"	=> $this->Post['DraftPlayerSelectionCriteria'][0],
												"Bat"	=> $this->Post['DraftPlayerSelectionCriteria'][1],
												"Ar"	=> $this->Post['DraftPlayerSelectionCriteria'][2],
												"Bowl"	=> $this->Post['DraftPlayerSelectionCriteria'][3],
											);
		}
		$this->Entity_model->updateEntityInfo($this->SeriesID, array("StatusID"=>$this->StatusID,"AuctionDraftIsPlayed"=>$this->AuctionDraftIsPlayed));
		$this->Sports_model->updateAuctionPlayStatus($this->SeriesID, array("SeriesName"=>$this->Post['SeriesName'],"AuctionDraftIsPlayed"=>@$this->Post['AuctionDraftIsPlayed'],"DraftUserLimit" => @$this->Post['DraftUserLimit'],"DraftTeamPlayerLimit" => @$this->Post['DraftTeamPlayerLimit'],"DraftPlayerSelectionCriteria" => @json_encode(@$DraftPlayerSelectionCriteria)));
		$this->Return['Data']=$this->Sports_model->getSeries('SeriesName,SeriesGUID,StatusID,Status,SeriesStartDate,SeriesEndDate',array('SeriesID' => $this->SeriesID),FALSE,0);
		$this->Return['Message'] =	"Success.";
	}
}
