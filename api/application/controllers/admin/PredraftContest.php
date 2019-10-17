<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PredraftContest extends API_Controller_Secure
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('PredraftContest_model');
		$this->load->model('Sports_model');
		$this->load->model('Contest_model');
	}

	/*
	Name: 			add
	Description: 	Use to add predraft contest to system.
	URL: 			/admin/predraftcontest/add/
	*/
	public function add_post() {
      

        /* Validation section */
        $this->form_validation->set_rules('DraftFormat', 'Draft Format', 'trim|required|in_list[Head to Head,League]');
		$this->form_validation->set_rules('DraftType', 'Draft Type', 'trim' . (!empty($this->Post['DraftFormat']) && $this->Post['DraftFormat'] == 'League' ? '|required|in_list[Normal,Hot,Champion,Practice,More,Mega,Winner Takes All,Only For Beginners]' : ''));
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsConfirm', 'IsConfirm', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsAutoCreate', 'Is Auto Create', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('ShowJoinedDraft', 'ShowJoinedDraft', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('DraftSize', 'DraftSize', 'trim' . (!empty($this->Post['DraftFormat']) && $this->Post['DraftFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric' : ''));
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|in_list[Single,Multiple]' : ''));
		$this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim' . (!empty($this->Post['EntryType']) && $this->Post['EntryType'] == 'Multiple' ? '|required|integer' : ''));
		$this->form_validation->set_rules('AdminPercent', 'AdminPercent', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
    
		if ($this->Post['IsPaid'] == 'Yes' && !empty($this->Post['CustomizeWinning']) && is_array($this->Post['CustomizeWinning'])) {
            $TotalWinners = $TotalPercent = $TotalWinningAmount = 0;
            foreach ($this->Post['CustomizeWinning'] as $Key => $Value) {
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][From]', 'From', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][To]', 'To', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][Percent]', 'Percent', 'trim|required|numeric');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][WinningAmount]', 'WinningAmount', 'trim|required|numeric');
                $TotalWinners += ($Value['To'] - $Value['From']) + 1;
                $TotalPercent += $Value['Percent'];
                $TotalWinningAmount += (($Value['To'] - $Value['From']) + 1) * $Value['WinningAmount'];
                if($Key > 0){
					if($this->Post['CustomizeWinning'][$Key]['WinningAmount'] >= $this->Post['CustomizeWinning'][$Key-1]['WinningAmount']){
						$this->Return['ResponseCode'] = 500;
						$this->Return['Message']      = "Winning amount ".($Key+1).",can not greater than or equals to Winning amount ".$Key;
						exit;
					}
				}
            }

            /* Check Total No Of Winners */
            if ($TotalWinners != $this->Post['NoOfWinners']) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winners should be equals to No Of Winners.";
                exit;
            }

            /* Check Total Percent */
            if ($TotalPercent < 90 || $TotalPercent > 100) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winners Percent should be 90% to 100%.";
                exit;
            }

            /* Check Total Winning Amount */
            if ($TotalWinningAmount > $this->Post['WinningAmount']) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winning Amount should be less than or equals to Winning Amount";
                exit;
            }
        }
        if ($this->Post['IsPaid'] == 'Yes' && (empty($this->Post['CustomizeWinning']) || !is_array($this->Post['CustomizeWinning']))) {
            $this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
        }
        $this->form_validation->set_message('regex_match', '{field} value should be between 0 to 100.');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
		
		/* Add Pre Draft */
    	$PreDraft = $this->PredraftContest_model->addDraft($this->Post, $this->SessionUserID);
        if (!$PreDraft) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
        	$this->PredraftContest_model->createPreDraftContest($PreDraft);
            $this->Return['Message'] = "Pre Draft created successfully.";
        }
    }

	/*
	Name: 			edit
	Description: 	Use to update predraft contest to system.
	URL: 			/admin/predraftcontest/edit/
	*/
	public function edit_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('PredraftContestID', 'PredraftContestID', 'trim|required|callback_validatePredraftContestID');
        $this->form_validation->set_rules('DraftFormat', 'Draft Format', 'trim|required|in_list[Head to Head,League]');
		$this->form_validation->set_rules('DraftType', 'Draft Type', 'trim' . (!empty($this->Post['DraftFormat']) && $this->Post['DraftFormat'] == 'League' ? '|required|in_list[Normal,Hot,Champion,Practice,More,Mega,Winner Takes All,Only For Beginners]' : ''));
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsConfirm', 'IsConfirm', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsAutoCreate', 'Is Auto Create', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('ShowJoinedDraft', 'ShowJoinedDraft', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('DraftSize', 'DraftSize', 'trim' . (!empty($this->Post['DraftFormat']) && $this->Post['DraftFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric' : ''));
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|in_list[Single,Multiple]' : ''));
		$this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim' . (!empty($this->Post['EntryType']) && $this->Post['EntryType'] == 'Multiple' ? '|required|integer' : ''));
		$this->form_validation->set_rules('AdminPercent', 'AdminPercent', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));

		if ($this->Post['IsPaid'] == 'Yes' && !empty($this->Post['CustomizeWinning']) && is_array($this->Post['CustomizeWinning'])) {
            $TotalWinners = $TotalPercent = $TotalWinningAmount = 0;
            foreach ($this->Post['CustomizeWinning'] as $Key => $Value) {
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][From]', 'From', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][To]', 'To', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][Percent]', 'Percent', 'trim|required|numeric');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][WinningAmount]', 'WinningAmount', 'trim|required|numeric');
                $TotalWinners += ($Value['To'] - $Value['From']) + 1;
                $TotalPercent += $Value['Percent'];
                $TotalWinningAmount += (($Value['To'] - $Value['From']) + 1) * $Value['WinningAmount'];
                if($Key > 0){
					if($this->Post['CustomizeWinning'][$Key]['WinningAmount'] >= $this->Post['CustomizeWinning'][$Key-1]['WinningAmount']){
						$this->Return['ResponseCode'] = 500;
						$this->Return['Message']      = "Winning amount ".($Key+1).",can not greater than or equals to Winning amount ".$Key;
						exit;
					}
				}
            }

            /* Check Total No Of Winners */
            if ($TotalWinners != $this->Post['NoOfWinners']) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winners should be equals to No Of Winners.";
                exit;
            }

            /* Check Total Percent */
            if ($TotalPercent < 90 || $TotalPercent > 100) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winners Percent should be 90% to 100%.";
                exit;
            }

            /* Check Total Winning Amount */
            if ($TotalWinningAmount > $this->Post['WinningAmount']) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winning Amount should be less than or equals to Winning Amount";
                exit;
            }
        }
        if ($this->Post['IsPaid'] == 'Yes' && (empty($this->Post['CustomizeWinning']) || !is_array($this->Post['CustomizeWinning']))) {
            $this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
        }
        $this->form_validation->set_message('regex_match', '{field} value should be between 0 to 100.');
        $this->form_validation->validation($this);  /* Run validation */
		/* Validation - ends */
		
		/* Update Pre Draft Contest */
		$this->PredraftContest_model->updateDraft($this->Post, $this->SessionUserID, $this->PredraftContestID);
		$this->Return['Message'] = "Pre Draft updated successfully."; 

    }
    
    /*
    Description: To get pre draft contest data
    */
    public function getPredraft_post()
    {
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|in_list[Yes,No,All]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Pre Draft Contest Data */
        $PreDraftData = $this->PredraftContest_model->getPredraftContest(@$this->Post['Params'], array_merge($this->Post, array('ContestID' => @$this->ContestID, 'MatchID' => @$this->MatchID, 'ContestType' => @$this->Post['ContestType'], 'SeriesID' => @$this->SeriesID, 'UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID, 'StatusID' => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($PreDraftData)) {
            $this->Return['Data'] = $PreDraftData['Data'];
        }
    }

	/*
	Name: 			delete
	Description: 	Use to delete predraft contest to system.
	URL: 			/admin/predraftcontest/delete/
	*/
	public function delete_post()
	{
		$this->form_validation->set_rules('PredraftContestID', 'PredraftContestID', 'trim|required|callback_validatePredraftContestID');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Delete predraft contest Data */
		if($this->PredraftContest_model->deleteDraft($this->SessionUserID, $this->Post['PredraftContestID'])){
			$this->Return['Message'] =	"Draft deleted successfully."; 
		}else{
			$this->Return['ResponseCode'] 	=	500;
			$this->Return['Message']      	=	"An error occurred, please try again later.";
		}
	}

    /*
      Description:  Use to update predraftcontest status.
      URL:          /admin/predraftcontest/changeStatus/
     */
    public function changeStatus_post() {
        /* Validation section */
        $this->form_validation->set_rules('PredraftContestID', 'PredraftContestID', 'trim|required|callback_validatePredraftContestID');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Change Status */
        $this->PredraftContest_model->changeStatus($this->Post['PredraftContestID'], array("StatusID" => $this->StatusID));
    }

	/**
     * Function Name: validatePredraftContestID
     * Description:   To validate predraft contest ID
     */
    public function validatePredraftContestID($PredraftContestID) {
		$Query = $this->db->query('SELECT Privacy FROM `sports_predraft_contest` WHERE `PredraftContestID` = '.$PredraftContestID.' LIMIT 1');
        if($Query->num_rows() == 0){
        	$this->form_validation->set_message('validatePredraftContestID', 'Invalid Predraft Contest ID.');
        	return FALSE;
        }
        return TRUE;
    }

}
?>
