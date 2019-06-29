<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PreContest extends API_Controller_Secure
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('PreContest_model');
		$this->load->model('Sports_model');
		$this->load->model('Contest_model');
	}

	/*
	Name: 			add
	Description: 	Use to add contest to system.
	URL: 			/api_admin/contest/add/
	*/
	public function add_post() {
        /* Validation section */
        $this->form_validation->set_rules('ContestName', 'ContestName', 'trim');
        $this->form_validation->set_rules('ContestFormat', 'Contest Format', 'trim|required|in_list[Head to Head,League]');
		$this->form_validation->set_rules('ContestType', 'Contest Type', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|in_list[Normal,Hot,Champion,Practice,More,Mega,Winner Takes All,Only For Beginners]' : ''));
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsAutoCreate', 'Is Auto Create', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('ShowJoinedContest', 'ShowJoinedContest', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric' : ''));
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim|required|in_list[Single,Multiple]');
        $this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim' . (!empty($this->Post['EntryType']) && $this->Post['EntryType'] == 'Multiple' ? '|required|integer' : ''));
        $this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim' . (!empty($this->Post['IsPaid']) && $this->Post['IsPaid'] == 'Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
        $this->form_validation->set_rules('CustomizeWinning', 'Customize Winning', 'trim');

        if (!empty($this->Post['CustomizeWinning']) && is_array($this->Post['CustomizeWinning'])) {
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
					if($this->Post['CustomizeWinning'][$Key]['Percent'] >= $this->Post['CustomizeWinning'][$Key-1]['Percent']){
						$this->Return['ResponseCode'] = 500;
						$this->Return['Message']      = "Winning percent ".($Key+1).",can not greater than or equals to Winning percent ".$Key;
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
            if ($TotalPercent < 100 || $TotalPercent > 100) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winners Percent should be 100%.";
                exit;
            }

            /* Check Total Winning Amount */
            if ($TotalWinningAmount != $this->Post['WinningAmount']) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Customize Winning Amount should be equals to Winning Amount";
                exit;
            }
        }else{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
		}

	    if($this->Post['IsPaid'] == 'Yes' && $this->Post['ContestSize'] > 0)
		{	    

			$TotalEntry   = $this->Post['EntryFee'] * $this->Post['ContestSize'];
			$AdminAmount  = ($TotalEntry * $this->Post['AdminPercent']) / 100;
			$AdminAmount = floor($AdminAmount);
			$TotalWinning = $TotalEntry - $AdminAmount;

			if($TotalWinning != $this->Post['WinningAmount'])
			{
				$this->Return['ResponseCode'] = 500;
				$this->Return['Message'] = "Winning amount should be equal to ".$TotalWinning;
				exit;
			}
		}
        
        $this->form_validation->set_message('regex_match', '{field} value should be between 0 to 100.');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
           
    	$Insert = $this->PreContest_model->addContest($this->Post, $this->SessionUserID);

        if (!$Insert) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
        	 /*$this->PreContest_model->addContest($Insert);*/
             $this->Return['Message'] = "Contest created successfully.";
        }
    }

	/*
	Name: 			edit
	Description: 	Use to update contest to system.
	URL: 			/api_admin/contest/edit/
	*/
	public function edit_post()
	{
		
		/* Validation section */
		$this->form_validation->set_rules('PreContestID', 'PreContestID', 'trim|required');
		$this->form_validation->set_rules('ContestName', 'ContestName','trim|required');
		$this->form_validation->set_rules('ContestFormat', 'Contest Format', 'trim|required|in_list[Head to Head,League]');
		$this->form_validation->set_rules('ContestType', 'Contest Type', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|in_list[Normal,Hot,Champion,Practice,More,Mega,Winner Takes All,Only For Beginners]' : ''));
		$this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('IsAutoCreate', 'Is Auto Create', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('ShowJoinedContest', 'ShowJoinedContest', 'trim|required|in_list[Yes,No]');
		$this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
		$this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim'.(!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat']=='League' ? '|required|integer' : ''));
		$this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim'.(!empty($this->Post['IsPaid']) && $this->Post['IsPaid']=='Yes' ? '|required|numeric' : ''));
		$this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim'.(!empty($this->Post['IsPaid']) && $this->Post['IsPaid']=='Yes' ? '|required|integer' : ''));
		$this->form_validation->set_rules('EntryType', 'EntryType', 'trim|required|in_list[Single,Multiple]');
		$this->form_validation->set_rules('UserJoinLimit', 'UserJoinLimit', 'trim'.(!empty($this->Post['EntryType']) && $this->Post['EntryType']=='Multiple' ? '|required|integer' : ''));
		$this->form_validation->set_rules('CashBonusContribution', 'CashBonusContribution', 'trim'.(!empty($this->Post['IsPaid']) && $this->Post['IsPaid']=='Yes' ? '|required|numeric|regex_match[/^[0-9][0-9]?$|^100$/]' : ''));
		$this->form_validation->set_rules('CustomizeWinning', 'Customize Winning', 'trim');
		if(!empty($this->Post['CustomizeWinning']) && is_array($this->Post['CustomizeWinning'])){
			$TotalWinners = $TotalPercent =  $TotalWinningAmount = 0;
			foreach($this->Post['CustomizeWinning'] as $Key => $Value){
				$this->form_validation->set_rules('CustomizeWinning['.$Key.'][From]', 'From', 'trim|required|integer');
				$this->form_validation->set_rules('CustomizeWinning['.$Key.'][To]', 'To', 'trim|required|integer');
				$this->form_validation->set_rules('CustomizeWinning['.$Key.'][Percent]', 'Percent', 'trim|required|numeric');
				$this->form_validation->set_rules('CustomizeWinning['.$Key.'][WinningAmount]', 'WinningAmount', 'trim|required|numeric');
				$TotalWinners += ($Value['To'] - $Value['From']) + 1;
				$TotalPercent += $Value['Percent'];
				$TotalWinningAmount += (($Value['To'] - $Value['From']) + 1) * $Value['WinningAmount'];

				if($Key > 0){
					if($this->Post['CustomizeWinning'][$Key]['Percent'] >= $this->Post['CustomizeWinning'][$Key-1]['Percent']){
						$this->Return['ResponseCode'] = 500;
						$this->Return['Message']      = "Winning percent ".($Key+1).",can not greater than or equals to Winning percent ".$Key;
						exit;
					}
				}
			}	

			/* Check Total No Of Winners */
			if($TotalWinners != $this->Post['NoOfWinners']){
				$this->Return['ResponseCode'] =	500;
				$this->Return['Message']      =	"Customize Winners should be equals to No Of Winners.";
				exit;
			}

			/* Check Total Percent */
			if($TotalPercent < 100 || $TotalPercent > 100){
				$this->Return['ResponseCode'] =	500;
				$this->Return['Message']      =	"Customize Winners Percent should be 100%.";
				exit;
			}

			/* Check Total Winning Amount */
			if($TotalWinningAmount != $this->Post['WinningAmount']){
				$this->Return['ResponseCode'] =	500;
				$this->Return['Message']      =	"Customize Winning Amount should be equals to Winning Amount";
				exit;
			}
		}else{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
		}
		
		if($this->Post['IsPaid'] == 'Yes' && $this->Post['ContestSize'] > 0)
		{	       
			$TotalEntry   = $this->Post['EntryFee'] * $this->Post['ContestSize'];
			$AdminAmount  = ($TotalEntry * $this->Post['AdminPercent']) / 100;
			$AdminAmount = floor($AdminAmount);
			$TotalWinning = $TotalEntry - $AdminAmount;
			if($TotalWinning != $this->Post['WinningAmount'])
			{
				$this->Return['ResponseCode'] = 500;
				$this->Return['Message'] = "Winning amount should be equal to ".$TotalWinning;
				exit;
			}
		}
		$this->form_validation->set_message('regex_match', '{field} value should be between 0 to 100.');
		$this->form_validation->validation($this);  /* Run validation */		
		/* Validation - ends */
		
		$this->PreContest_model->updateContest($this->Post, $this->SessionUserID, $this->PreContestID);
		$this->Return['Message']      	=	"Contest updated successfully."; 

	}

	/*
	Description: To get private contest detail
	*/
	public function getPrivateContest_post()
	{
		$this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
		$this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No]');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Get Contests Data */
		$ContestData = $this->Contest_model->getContests(@$this->Post['Params'],array_merge($this->Post, array('UserID' => $this->UserID)),TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
		if(!empty($ContestData)){
			$this->Return['Data'] = $ContestData['Data'];
		}
	}

	/*
	Description: To Cancel Contest
	*/
	public function cancel_post()
	{
		$this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateContestStatus');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Cancel Contests */
		$this->Contest_model->cancelContest(@$this->Post, $this->SessionUserID, $this->ContestID);
		$this->Return['Message'] = "Contest cancelled successfully.";
	}


    /**
     * Function Name: validateContestStatus
     * Description:   To validate contest status
     */
    public function validateContestStatus($ContestGUID)
    {
    	$ContestData = $this->Contest_model->getContests('Status,IsPaid,SeriesName,ContestName,MatchNo,TeamNameLocal,TeamNameVisitor,EntryFee',array('ContestID'=>$this->ContestID));
    	if($ContestData['Status'] == 'Pending'){
    		$this->Post['IsPaid']          = $ContestData['IsPaid'];
    		$this->Post['EntryFee']        = $ContestData['EntryFee'];
    		$this->Post['SeriesName']      = $ContestData['SeriesName'];
    		$this->Post['ContestName']     = $ContestData['ContestName'];
    		$this->Post['MatchNo']         = $ContestData['MatchNo'];
    		$this->Post['TeamNameLocal']   = $ContestData['TeamNameLocal'];
    		$this->Post['TeamNameVisitor'] = $ContestData['TeamNameVisitor'];
    		return TRUE;
    	}else{
    		$this->form_validation->set_message('validateContestStatus', 'You can not cancel this contest.');
			return FALSE;
    	}
    }

    /*
	Description: To get contests data
	*/
	public function getPreContestByType_post()
	{
		
		$this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No,All]');
		$this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
		//$this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
		$this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
		$this->form_validation->set_rules('Filter', 'Filter', 'trim');
		$this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
		$this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Get Contests Data */

		$ContestData = array();

		$ContestTypes[] = array('Key'=>'Hot Contest','TagLine'=>'Filling Fast. Join Now!','Where'=> array('ContestType'=>'Hot'));
		$ContestTypes[] = array('Key'=>'Contests for Champions','TagLine'=>'High Entry Fees, Intense Competition','Where'=> array('ContestType'=>'Champion'));
		$ContestTypes[] = array('Key'=>'Head To Head Contest','TagLine'=>'The Ultimate Face Off','Where'=> array('ContestType'=>'Head to Head'));
		$ContestTypes[] = array('Key'=>'Practice Contest','TagLine'=>'Hone Your Skills','Where'=> array('ContestType'=>'Practice'));
		$ContestTypes[] = array('Key'=>'More Contest','TagLine'=>'Keep Winning!','Where'=> array('ContestType'=>'More'));
		$ContestTypes[] = array('Key'=>'Mega Contest','TagLine'=>'Get ready for mega winnings!','Where'=> array('ContestType'=>'Mega'));
		$ContestTypes[] = array('Key'=>'Winner Takes All','TagLine'=>'Everything To Play For','Where'=> array('ContestType'=>'Winner Takes All'));
		$ContestTypes[] = array('Key'=>'Only For Beginners','TagLine'=>'Play Your First Contest Now','Where'=> array('ContestType'=>'Only For Beginners'));

		foreach($ContestTypes as $key=>$Contests){
			
			array_push($ContestData,$this->PreContest_model->getPreContest(@$this->Post['Params'],array_merge($this->Post, array('UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID),$Contests['Where']),TRUE, @$this->Post['PageNo'], @$this->Post['PageSize'])['Data']);
			$ContestData[$key]['Key'] = $Contests['Key'];
			$ContestData[$key]['TagLine'] = $Contests['TagLine'];

		}
		
		$Statics =  $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $this->MatchID . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                    )as NormalContest,
                    ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $this->MatchID . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                    )as ReverseContest,(
                    SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C, `tbl_entity` E WHERE C.ContestID = J.ContestID AND C.ContestID = E.EntityID AND E.StatusID != 3 AND J.UserID = "' . $this->SessionUserID . '" AND C.MatchID = "' . $this->MatchID . '" 
                    )as JoinedContest,( 
                    SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . $this->SessionUserID . '" AND MatchID = "' . $this->MatchID . '"
                ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $this->MatchID . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
          
		if(!empty($ContestData)){
			$this->Return['Data']['Results'] = $ContestData;
			$this->Return['Data']['Statics'] = $Statics;
			
		}
	}

	/*
	Description: To get contest detail
	*/
	public function getPreContest_post()
	{
		$this->form_validation->set_rules('PreContestID', 'PreContestID', 'trim');
		//$this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Get Contests Data */
		$ContestData = $this->PreContest_model->getPreContest(@$this->Post['Params'],array_merge($this->Post, array('PreContestID' => @$this->Post['PreContestID'] , 'SessionUserID' => @$this->SessionUserID)),TRUE,@$this->Post['PageNo'], @$this->Post['PageSize']);
		
		if(!empty($ContestData)){
			$this->Return['Data'] = $ContestData['Data'];
		}
	}

    /*
	Description: 	Use to update contest status.
	URL: 			/api_admin/entity/changeStatus/	
	*/
	public function changeStatus_post()
	{
		/* Validation section */
		$this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
		$this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
		$this->form_validation->validation($this);  /* Run validation */		
		/* Validation - ends */
		$this->Entity_model->updateEntityInfo($this->ContestID, array("StatusID"=>$this->StatusID));
		$this->Return['Data']	= $this->Contest_model->getContests('Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,SeriesID,MatchID,SeriesGUID,TeamNameLocal,TeamNameVisitor,SeriesName,CustomizeWinning,ContestType',array_merge($this->Post, array('ContestID' => $this->ContestID, 'SessionUserID' => $this->SessionUserID)));
		$this->Return['Message']      	=	"Status has been changed.";
	}

	/*
	Name: 			delete
	Description: 	Use to delete contest to system.
	URL: 			/contest/delete/
	*/
	public function delete_post()
	{
		$this->form_validation->set_rules('PreContestID', 'PreContestID', 'trim|required');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Delete Contests Data */
		
		$this->PreContest_model->deleteContest($this->SessionUserID, $this->Post['PreContestID']);
		$this->Return['Message'] =	"Contest deleted successfully."; 
	}
}
