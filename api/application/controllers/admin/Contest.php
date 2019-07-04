<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contest extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Contest_model');
        $this->load->model('Sports_model');
    }

    /*
      Name: 		add
      Description: 	Use to add contest to system.
      URL: 			/admin/contest/add/
     */

    public function add_post() {
        /* Validation section */
        $this->form_validation->set_rules('ContestName', 'ContestName', 'trim');
        $this->form_validation->set_rules('ContestFormat', 'Contest Format', 'trim|required|in_list[Head to Head,League]');
        $this->form_validation->set_rules('ContestType', 'Contest Type', 'trim|required|in_list[Normal,Hot,Champion,Practice,More,Mega,Winner Takes All,Only For Beginners,Head to Head]');
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('IsConfirm', 'IsConfirm', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('IsAutoCreate', 'Is Auto Create', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('ShowJoinedContest', 'ShowJoinedContest', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric' : ''));
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim|required|in_list[Single,Multiple]');
        $this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim' . (!empty($this->Post['EntryType']) && $this->Post['EntryType'] == 'Multiple' ? '|required|integer' : ''));
        $this->form_validation->set_rules('AdminPercent', 'AdminPercent', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|required|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('CustomizeWinning', 'Customize Winning', 'trim');
        $this->form_validation->set_rules('MatchGUID[]', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
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
        
        $TotalMatches = count($this->Post['MatchGUID']);
        if($TotalMatches > 0){
            for ($I = 0; $I < $TotalMatches; $I++) {
                $MatchID = $this->Entity_model->getEntity('E.EntityID', array('EntityGUID' => $this->Post('MatchGUID')[$I], 'EntityTypeName' => "Matches"));
                if(!$MatchID){
                    $this->Return['ResponseCode'] = 500;
                    $this->Return['Message'] = "Invalid MatchGUID.";
                    exit;
                }
                $MatchIds[] = $MatchID['EntityID'];
            }
        }else{
            $this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Please select matches.";
			exit;
        }

        /* Add Multiple Contests */
        if (!$this->Contest_model->addContest($this->Post, $this->SessionUserID,$MatchIds,$this->SeriesID)) {
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "An error occurred, please try again later.";
		} else {
			$this->Return['Message'] = "Contest created successfully.";
		}
    }

    /*
      Name: 		edit
      Description: 	Use to update contest to system.
      URL: 			/admin/contest/edit/
     */
    public function edit_post() {
        /* Validation section */
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateAnyUserJoinedContest[update]');
        $this->form_validation->set_rules('ContestName', 'ContestName', 'trim|required');
        $this->form_validation->set_rules('ContestFormat', 'Contest Format', 'trim|required|in_list[Head to Head,League]');
        $this->form_validation->set_rules('ContestType', 'Contest Type', 'trim|required|in_list[Normal,Reverse,InPlay,Hot,Champion,Practice,More,Head to Head]');
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('IsConfirm', 'IsConfirm', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('ShowJoinedContest', 'ShowJoinedContest', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric' : ''));
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim|required|in_list[Single,Multiple]');
        $this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim' . (!empty($this->Post['EntryType']) && $this->Post['EntryType'] == 'Multiple' ? '|required|integer' : ''));
        $this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('AdminPercent', 'AdminPercent', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('CustomizeWinning', 'Customize Winning', 'trim');
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

        $this->Contest_model->updateContest($this->Post, $this->SessionUserID, $this->ContestID);
        $this->Return['Message'] = "Contest updated successfully.";
    }

    /*
      Description: To get joined contests data
     */
    public function getUserJoinedContests_post() {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal,Head to Head]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Get Joined Contests Data */
        $JoinedContestData = $this->Contest_model->getJoinedContests(@$this->Post['Params'], array_merge($this->Post, array('SessionUserID' => @$this->UserID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);

        if (!empty($JoinedContestData)) {
            $this->Return['Data'] = $JoinedContestData['Data'];
        }
    }

    /*
      Description: To get private contest detail
     */
    public function getPrivateContest_post() {
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->set_rules('StatusID', 'StatusID', 'trim');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contests Data */
        $ContestData = $this->Contest_model->getContests(@$this->Post['Params'], array_merge($this->Post, array('MatchID' => @$this->MatchID, 'ContestType' => @$this->Post['ContestType'], 'SeriesID' => @$this->SeriesID, 'UserID' => @$this->UserID,'StatusID' => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($ContestData)) {
            $this->Return['Data'] = $ContestData['Data'];
        }
    }

    /*
      Description: To Cancel Contest
     */
    public function cancel_post() {
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Cancel Contests */
        $this->Contest_model->cancelContest(@$this->Post, $this->SessionUserID, $this->ContestID);
        $this->Return['Message'] = "Contest cancelled successfully.";
    }

    /*
      Description: To get contest winning users
     */

    public function getContestWinningUsers_post() {
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contests Winning Users Data */
        $WinningUsersData = $this->Contest_model->getContestWinningUsers(@$this->Post['Params'], array_merge($this->Post, array('ContestID' => $this->ContestID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WinningUsersData)) {
            $this->Return['Data'] = $WinningUsersData['Data'];
        }
    }

    /*
      Description: 	Use to update contest status.
      URL: 			/admin/entity/changeStatus/
     */
    public function changeStatus_post() {
        /* Validation section */
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Entity_model->updateEntityInfo($this->ContestID, array("StatusID" => $this->StatusID));
        $this->Contest_model->updateContest($this->Post, $this->SessionUserID, $this->ContestID);
        $this->Return['Data'] = $this->Contest_model->getContests('Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,SeriesID,MatchID,SeriesGUID,TeamNameLocal,TeamNameVisitor,SeriesName,CustomizeWinning,ContestType', array_merge($this->Post, array('ContestID' => $this->ContestID, 'SessionUserID' => $this->SessionUserID)));
        $this->Return['Message'] = "Success.";
    }


    /**
     * Function Name: validateAnyUserJoinedContest
     * Description:   To validate if any user joined contest
     */
    public function validateAnyUserJoinedContest($ContestGUID, $Type) {
        if ($this->db->query('SELECT COUNT(EntryDate) AS `TotalRecords` FROM `sports_contest_join` WHERE `ContestID` =' . $this->ContestID)->row()->TotalRecords > 0){
        	$this->form_validation->set_message('validateAnyUserJoinedContest', 'You can not '.$Type.' this contest');
        	return FALSE;
        }
        return TRUE;
    }

}

?>