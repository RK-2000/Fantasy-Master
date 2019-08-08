<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contest extends API_Controller_Secure
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Contest_model');
        $this->load->model('Sports_model');
        $this->load->model('Settings_model');
        mongoDBConnection();
    }

    /*
      Name: 		add
      Description: 	Use to add contest to system.
      URL: 			/contest/add/
     */
    public function add_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('ContestName', 'ContestName', 'trim');
        $this->form_validation->set_rules('ContestFormat', 'Contest Format', 'trim|required|in_list[Head to Head,League]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required|integer');
        $this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim' . (!empty($this->Post['ContestFormat']) && $this->Post['ContestFormat'] == 'League' ? '|required|integer' : ''));
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim|required|numeric');
        $this->form_validation->set_rules('NoOfWinners', 'NoOfWinners', 'trim|required|integer');
        $this->form_validation->set_rules('EntryType', 'EntryType', 'trim|required|in_list[Single,Multiple]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|required|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('CustomizeWinning', 'Customize Winning', 'trim');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]|callback_validateMatchDateTime[Contest]');
        if ($this->Post['WinningAmount'] > 0 && $this->Post['ContestSize'] > 2 && !empty($this->Post['CustomizeWinning']) && is_array($this->Post['CustomizeWinning'])) {
            $TotalWinners = $TotalPercent = $TotalWinningAmount = 0;
            foreach ($this->Post['CustomizeWinning'] as $Key => $Value) {
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][From]', 'From', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][To]', 'To', 'trim|required|integer');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][Percent]', 'Percent', 'trim|required|numeric');
                $this->form_validation->set_rules('CustomizeWinning[' . $Key . '][WinningAmount]', 'WinningAmount', 'trim|required|numeric');
                $TotalWinners += ($Value['To'] - $Value['From']) + 1;
                $TotalPercent += $Value['Percent'];
                $TotalWinningAmount += (($Value['To'] - $Value['From']) + 1) * $Value['WinningAmount'];
                if ($Key > 0) {
                    if ($this->Post['CustomizeWinning'][$Key]['WinningAmount'] >= $this->Post['CustomizeWinning'][$Key - 1]['WinningAmount']) {
                        $this->Return['ResponseCode'] = 500;
                        $this->Return['Message']      = "Winning amount " . ($Key + 1) . ",can not greater than or equals to Winning amount " . $Key;
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
        if ($this->Post['WinningAmount'] > 0 && $this->Post['ContestSize'] > 2 && (empty($this->Post['CustomizeWinning']) || !is_array($this->Post['CustomizeWinning']))) {
            $this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
        }
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $ContestID = $this->Contest_model->addContest(array_merge($this->Post, array('IsPaid' => 'Yes', 'Privacy' => 'Yes', 'IsAutoCreate' => 'No', 'ShowJoinedContest' => 'Yes', 'IsConfirm' => 'No', 'CashBonusContribution' => 0, 'AdminPercent' => ADMIN_CONTEST_PERCENT, 'ContestType' => ($this->Post['ContestSize'] == 2) ? 'Head to Head' : 'Normal')), $this->SessionUserID, array($this->MatchID), $this->SeriesID);
        if (!$ContestID) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Message'] = "Contest created successfully.";
            $this->Return['Data']['ContestGUID'] = $this->Contest_model->getContests('CustomizeWinning,MatchScoreDetails,ContestFormat,ContestType,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,UserInvitationCode', array('ContestID' => $ContestID));
        }
    }

    /*
    Description: To get contests data
    */
    public function getContests_post()
    {
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|in_list[Yes,No,All]');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->set_rules('UserInvitationCode', 'UserInvitationCode', 'trim' . (!empty($this->Post['Privacy']) && $this->Post['Privacy'] == 'Yes' ? '|required' : ''));
        $this->form_validation->set_rules('StatusID', 'StatusID', 'trim');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('ContestType', 'ContestType', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contests Data */
        $ContestData = $this->Contest_model->getContests(@$this->Post['Params'], array_merge($this->Post, array('ContestID' => @$this->ContestID, 'MatchID' => @$this->MatchID, 'ContestType' => @$this->Post['ContestType'], 'SeriesID' => @$this->SeriesID, 'UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID, 'StatusID' => @$this->StatusID)),(!empty($this->ContestID) || !empty($this->Post['UserInvitationCode'])) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($ContestData)) {
            $this->Return['Data'] = (!empty($this->ContestID) || !empty($this->Post['UserInvitationCode'])) ? $ContestData : $ContestData['Data'];
        }
    }

    /*
      Description: To get contests data (By Type)
     */
    public function getContestsByType_post()
    {

        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No,All]');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal,Head to Head]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contests Data */
        $ContestData = array();
        $ContestTypes[] = array('Key' => 'Hot Contest', 'TagLine' => 'Filling Fast. Join Now!', 'Where' => array('ContestType' => 'Hot'));
        $ContestTypes[] = array('Key' => 'Contests for Champions', 'TagLine' => 'High Entry Fees, Intense Competition', 'Where' => array('ContestType' => 'Champion'));
        $ContestTypes[] = array('Key' => 'Head To Head Contest', 'TagLine' => 'The Ultimate Face Off', 'Where' => array('ContestType' => 'Head to Head'));
        $ContestTypes[] = array('Key' => 'Practice Contest', 'TagLine' => 'Hone Your Skills', 'Where' => array('ContestType' => 'Practice'));
        $ContestTypes[] = array('Key' => 'More Contest', 'TagLine' => 'Keep Winning!', 'Where' => array('ContestType' => 'More'));
        $ContestTypes[] = array('Key' => 'Mega Contest', 'TagLine' => 'Get ready for mega winnings!', 'Where' => array('ContestType' => 'Mega'));
        $ContestTypes[] = array('Key' => 'Winner Takes All', 'TagLine' => 'Everything To Play For', 'Where' => array('ContestType' => 'Winner Takes All'));
        $ContestTypes[] = array('Key' => 'Only For Beginners', 'TagLine' => 'Play Your First Contest Now', 'Where' => array('ContestType' => 'Only For Beginners'));
        if ($this->Post['Privacy'] == 'All' || $this->Post['Privacy'] == 'Yes') {
            $ContestTypes[] = array('Key' => 'Private Contest', 'TagLine' => 'Play with friends and Family', 'Where' => array('ContestType' => 'Normal','Privacy' => 'Yes'));
        }

        foreach ($ContestTypes as $key => $Contests) {
            array_push($ContestData, $this->Contest_model->getContests(@$this->Post['Params'], array_merge($this->Post, array('MatchID' => @$this->MatchID, 'UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID, 'StatusID' => @$this->StatusID), $Contests['Where']), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize'])['Data']);
            $ContestData[$key]['Key'] = $Contests['Key'];
            $ContestData[$key]['TagLine'] = $Contests['TagLine'];
        }
        if (!empty($ContestData)) {
            $this->Return['Data']['Results'] = $ContestData;
            $this->Return['Data']['Statics'] = $this->Contest_model->contestStatics($this->SessionUserID,$this->MatchID);
        }
    }

    /*
      Name: 		addUserTeam
      Description: 	Use to create team to system.
      URL: 			/api/contest/addUserTeam/
     */

    public function addUserTeam_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay]');
        $this->form_validation->set_rules('MatchInning', 'MatchInning', 'trim' . (!empty($this->Post['UserTeamType']) && $this->Post['UserTeamType'] == 'InPlay' ? '|required' : ''));
        foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
            $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerGUID]', 'PlayerGUID', 'trim|required');
            $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerPosition]', 'PlayerPosition', 'trim|required|in_list[Captain,ViceCaptain,Player]');
        }
        $this->form_validation->set_rules('UserTeamPlayers', 'UserTeamPlayers', 'trim|callback_validateUserTeamPlayers[Add]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Add User Team */
        $UserTeam = $this->Contest_model->addUserTeam($this->Post, $this->SessionUserID, $this->MatchID);
        if (!$UserTeam) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Data']['UserTeamGUID'] = $UserTeam;
            $this->Return['Message'] = "Team created successfully.";
        }
    }

    /*
      Name: 		editUserTeam
      Description: 	Use to update team to system.
      URL: 			/api/contest/editUserTeam/
     */

    public function editUserTeam_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay]');
        foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
            $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerGUID]', 'PlayerGUID', 'trim|required');
            $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerPosition]', 'PlayerPosition', 'trim|required|in_list[Captain,ViceCaptain,Player]');
        }
        $this->form_validation->set_rules('UserTeamPlayers', 'UserTeamPlayers', 'trim|callback_validateUserTeamPlayers[Edit]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Edit  User Team */
        if (!$this->Contest_model->editUserTeam($this->Post, $this->UserTeamID, $this->MatchID)) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Message'] = "Team updated successfully.";
        }
    }

    /*
	Name: 			switchUserTeam
	Description: 	Use to  switch user team with joined contest.
	URL: 			/contest/switchUserTeam/
	 */
	public function switchUserTeam_post()
	{
		$this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]|callback_validateMatchStatus');
		$this->form_validation->set_rules('OldUserTeamGUID', 'OldUserTeamGUID', 'trim|required');
		$this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateContestStatus');
		$this->form_validation->validation($this);  /* Run validation */	

		/* Swicth Team */
		$this->Contest_model->switchUserTeam($this->SessionUserID, $this->ContestID, $this->UserTeamID, $this->OldUserTeamID);
		$this->Return['Message'] = "Team switched successfully.";
	}

    /*
      Description: To get user teams data
     */
    public function getUserTeams_post()
    {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]|callback_validateMatchDateTime[UserTeams]');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay,All]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get User Teams Data */
        $UserTeams = $this->Contest_model->getUserTeams(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'UserTeamID' => @$this->UserTeamID, 'TeamsContestID' => @$this->ContestID)), (!empty($this->Post['UserTeamGUID'])) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($UserTeams)) {
            $this->Return['Data'] = (!empty($this->Post['UserTeamGUID'])) ? $UserTeams : $UserTeams['Data'];
        }
    }

    /*
      Description: To download contest teams
    */
    public function downloadTeams_post()
    {
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]|callback_validateContestMatchStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contests Teams Data */
        $UserTeams = $this->Contest_model->downloadTeams(array_merge($this->Post, array('ContestID' => $this->ContestID, 'MatchID' => $this->MatchID)));
        if (!empty($UserTeams)) {
            $this->Return['Data'] = $UserTeams;
        }
    }

    /*
      Name: 		join
      Description: 	Use to join contest to system.
      URL: 			/contest/join/
     */
    public function join_post()
    {
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateUserJoinContest');
        $this->form_validation->validation($this);  /* Run validation */

        /* Join Contests */
        $JoinContest = $this->Contest_model->joinContest($this->Post, $this->SessionUserID, $this->ContestID, $this->MatchID, $this->UserTeamID);
        if (!$JoinContest) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Data'] = $JoinContest;
            $this->Return['Message'] = "Contest joined successfully.";
        }
    }

    /*
      Description: To get joined contests data
     */
    public function getJoinedContests_post()
    {
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal,Head to Head]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Joined Contests Data */
        $JoinedContestData = $this->Contest_model->getJoinedContests(@$this->Post['Params'], array_merge($this->Post, array('MatchID' => @$this->MatchID, 'SessionUserID' => $this->SessionUserID, 'StatusID' => $this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($JoinedContestData)) {
            $this->Return['Data'] = $JoinedContestData['Data'];
        }
    }


    /*
      Description: To get joined contest users data
     */

    public function getJoinedContestsUsers_post()
    {
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contest Status */
        $Contest = $this->Contest_model->getContests('Status', array('ContestID' => $this->Post['ContestID']));
        if ($Contest['Status'] == 'Pending' || $Contest['Status'] == 'Cancelled') {

            /* Get Joined Contest Users Data (MySQL) */
            $JoinedContestData = $this->Contest_model->getJoinedContestsUsers(@$this->Post['Params'], array('UserID' => $this->SessionUserID, 'MatchID' => @$this->MatchID, 'ContestID' => $this->ContestID), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        } else {

            /* Get Joined Contest Users Data (MongoDB) */
            $JoinedContestData = $this->Contest_model->getJoinedContestsUsersMongoDB(array_merge($this->Post, array('UserID' => $this->SessionUserID, 'MatchID' => @$this->MatchID, 'ContestID' => $this->ContestID)), @$this->Post['PageNo'], @$this->Post['PageSize']);
            if (!$JoinedContestData) {
                $JoinedContestData = $this->Contest_model->getJoinedContestsUsers(@$this->Post['Params'], array('UserID' => $this->SessionUserID, 'MatchID' => @$this->MatchID, 'ContestID' => $this->ContestID), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
            }
        }
        if (!empty($JoinedContestData)) {
            $this->Return['Data'] = $JoinedContestData['Data'];
        }
    }

    /*
      Name: 		invite
      Description: 	Use to invite contest
      URL: 			/api/contest/invite
     */
    public function invite_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('ReferType', 'Refer Type', 'trim|required|in_list[Phone,Email]');
        $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim' . (!empty($this->Post['ReferType']) && $this->Post['ReferType'] == 'Phone' ? '|required' : ''));
        $this->form_validation->set_rules('Email', 'Email', 'trim' . (!empty($this->Post['ReferType']) && $this->Post['ReferType'] == 'Email' ? '|required|valid_email' : ''));
        $this->form_validation->set_rules('UserInvitationCode', 'User Invitation Code', 'trim|required|callback_validateInviteCode');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Contest_model->inviteContest($this->Post, $this->SessionUserID);
        $this->Return['Message'] = "Successfully invited.";
    }

    /*
      Description:  Use to create pre draft contest
      URL:      /api/contest/createPreContest
     */
    public function createPreContest_get()
    {
        $this->load->model('PreContest_model');
        $this->PreContest_model->createPreContest();
    }

    /*
      Description : To create winners breakout
     */
    public function WinningBreakups_post()
    {
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('WinningAmount', 'WinningAmount', 'trim|required');
        $this->form_validation->set_rules('ContestSize', 'ContestSize', 'trim|required|numeric|callback_validateContestSize');
        $this->form_validation->set_rules('EntryFee', 'EntryFee', 'trim|required');
        $this->form_validation->set_rules('IsPaid', 'IsPaid', 'trim|required|in_list[Yes,No]');
        $this->form_validation->validation($this);  /* Run validation */

        $Result = $this->Contest_model->getWinningBreakup('', array_merge($this->Post, array('MatchID' => $this->MatchID, 'UserID' => $this->UserID)), TRUE, 0);
        if ($Result) {
            $this->Return['Data'] = $Result['Data'];
            $this->Return['Message'] = "Winning Breakup successfully.";
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        }
    }

    /* -----Validation Functions----- */
    /* ------------------------------ */

    /**
     * Function Name: validateInviteCode
     * Description:   To validate contest invite code
     */
    public function validateInviteCode($UserInvitationCode)
    {
        $ContestData = $this->Contest_model->getContests('Status,TeamNameShortLocal,TeamNameShortVisitor', array('UserInvitationCode' => $UserInvitationCode));
        if (!$ContestData) {
            $this->form_validation->set_message('validateInviteCode', 'Invalid Contest invite code.');
            return FALSE;
        }
        if ($ContestData['Status'] != 'Pending') {
            $this->form_validation->set_message('validateInviteCode', 'You can invite users only for upcoming contest.');
            return FALSE;
        }
        $this->Post['TeamNameShortLocal']   = $ContestData['TeamNameShortLocal'];
        $this->Post['TeamNameShortVisitor'] = $ContestData['TeamNameShortVisitor'];
        return TRUE;
    }

    /**
     * Function Name: validateMatchStatus
     * Description:   To validate match status
     */
    public function validateMatchStatus($UserTeamGUID)
    {
        $MatchStatus = $this->db->query("SELECT E.StatusID FROM sports_users_teams UT, tbl_entity E WHERE UT.MatchID = E.EntityID AND UT.UserTeamGUID = '" . $UserTeamGUID . "' ")->row()->StatusID;
        if ($MatchStatus != 1) {
            $this->form_validation->set_message('validateMatchStatus', 'Sorry, you can not switch team.');
            return FALSE;
        }
        return TRUE;
    }

    /**
	 * Function Name: validateContestStatus
	 * Description:   To validate contest status
	 */
	public function validateContestStatus($ContestGUID)
	{
		$ContestStatus = $this->db->query("SELECT E.StatusID FROM sports_contest C, tbl_entity E WHERE C.ContestID = E.EntityID AND C.ContestGUID = '" . $ContestGUID . "' ")->row()->StatusID;
		if ($ContestStatus != 1) {
			$this->form_validation->set_message('validateContestStatus', 'Sorry, you can not switch team.');
			return FALSE;
        }
        
        /* Validate Old User Team GUID */
        $Query = $this->db->query('SELECT UserTeamID FROM sports_users_teams WHERE UserTeamGUID = "' . $this->Post['OldUserTeamGUID'] . '" LIMIT 1');
        if($Query->num_rows() > 0){
            $this->OldUserTeamID  = $Query->row()->UserTeamID;
        }else{
            $this->form_validation->set_message('validateContestStatus', 'Invalid OldUserTeamGUID.');
            return FALSE;
        }

        /* To Check If Contest Is Joined With Old Team*/
        $Where = array('SessionUserID' => $this->SessionUserID, 'ContestID' => $this->ContestID, 'UserTeamID' => $this->OldUserTeamID);
            $Response = $this->Contest_model->getJoinedContests('', $Where, TRUE, 1, 1);
        if (empty($Response['Data']['TotalRecords'])) {
            $this->form_validation->set_message('validateContestStatus', 'You can switch team only with joined contest.');
            return FALSE;
        }

        /* To Check If Contest Is Already Joined With New Team*/
        $Query = $this->db->query('SELECT ContestID FROM sports_contest_join WHERE UserID = '.$this->SessionUserID.' AND ContestID = '.$this->ContestID.' AND UserTeamID = '.$this->UserTeamID.' LIMIT 1');
        if ($Query->num_rows() > 0) {
            $this->form_validation->set_message('validateContestStatus', 'Contest already joined with this team.');
            return FALSE;
        }
        return TRUE;
	}

    /**
     * Function Name: validateUserJoinContest
     * Description:   To validate user join contest
     */
    public function validateUserJoinContest($ContestGUID)
    {
        $ContestData = $this->Contest_model->getContests('MatchID,ContestSize,Privacy,IsPaid,EntryType,EntryFee,UserInvitationCode,ContestID,UserJoinLimit,CashBonusContribution,MatchStartDateTimeUTC,GameTimeLive,IsAutoCreate,TotalJoined', array('ContestID' => $this->ContestID));
        if (!empty($ContestData)) {

            /* To Check Match Start Date Time */
            if ($ContestData['GameTimeLive'] > 0) {
                $MatchStartDateTime = strtotime($ContestData['MatchStartDateTimeUTC']) - $ContestData['GameTimeLive'] * 60;
            } else {
                $MatchStartDateTime = strtotime($ContestData['MatchStartDateTimeUTC']) - $this->Settings_model->getSiteSettings("MatchLiveTime") * 60;
            }
            if ($MatchStartDateTime <= strtotime(date('Y-m-d H:i:s'))) {
                $this->form_validation->set_message('validateUserJoinContest', 'You can join only upcoming matches contest.');
                return FALSE;
            }

            /* Get Match Status */
            $MatchData = $this->Sports_model->getMatches('Status', array('MatchID' => $ContestData['MatchID']));
            if ($MatchData['Status'] != 'Pending') {
                $this->form_validation->set_message('validateUserJoinContest', 'You can join only upcoming matches contest.');
                return FALSE;
            }

            /* Check Join Contest Size Limit */
            if ($this->db->query('SELECT COUNT(EntryDate) `TotalRecords` FROM `sports_contest_join` WHERE `ContestID` =' . $ContestData['ContestID'])->row()->TotalRecords >= $ContestData['ContestSize']) {
                $this->form_validation->set_message('validateUserJoinContest', 'Join Contest limit is exceeded.');
                return FALSE;
            }

            /* To Check If Contest Is Already Joined */
            $JoinContestWhere = array('SessionUserID' => $this->SessionUserID, 'ContestID' => $ContestData['ContestID']);
            if ($ContestData['EntryType'] == 'Multiple') {

                /* Get User Join Limit */
                if ($this->db->query('SELECT COUNT(EntryDate) `TotalJoined` FROM `sports_contest_join` WHERE `ContestID` =' . $ContestData['ContestID'] . ' AND UserID = ' . $this->SessionUserID)->row()->TotalJoined >= $ContestData['UserJoinLimit']) {
                    $this->form_validation->set_message('validateUserJoinContest', 'You can join this contest only ' . $ContestData['UserJoinLimit'] . ' times.');
                    return FALSE;
                }
                $JoinContestWhere['UserTeamID'] = $this->UserTeamID;
            }
            $Response = $this->Contest_model->getJoinedContests('', $JoinContestWhere, TRUE, 1, 1);
            if (!empty($Response['Data']['TotalRecords'])) {
                $this->form_validation->set_message('validateUserJoinContest', 'Contest is already joined.');
                return FALSE;
            }

            /* To Check User Team Match Details */
            if (!$this->Contest_model->getUserTeams('', array('UserTeamID' => $this->UserTeamID, 'MatchID' => $ContestData['MatchID']))) {
                $this->form_validation->set_message('validateUserJoinContest', 'Invalid UserTeamGUID.');
                return FALSE;
            }

            /* To Check Contest Privacy */
            if ($ContestData['Privacy'] == 'Yes') {
                if (empty($this->Post['UserInvitationCode'])) {
                    $this->form_validation->set_message('validateUserJoinContest', 'The User Invitation Code field is required.');
                    return FALSE;
                }
                if ($ContestData['UserInvitationCode'] != $this->Post['UserInvitationCode']) {
                    $this->form_validation->set_message('validateUserJoinContest', 'Invalid User Invitation Code.');
                    return FALSE;
                }
            }

            /* To Check Wallet Amount, If Contest Is Paid */
            if ($ContestData['IsPaid'] == 'Yes') {

                /* Get User Wallet Details */
                $UserData = $this->Users_model->getUsers('TotalCash,WalletAmount,WinningAmount,CashBonus', array('UserID' => $this->SessionUserID));
                $this->Post['WalletAmount']  = $UserData['WalletAmount'];
                $this->Post['WinningAmount'] = $UserData['WinningAmount'];
                $this->Post['CashBonus']     = $UserData['CashBonus'];

                /* Calculate Wallet Amount */
                $ContestEntryRemainingFees = @$ContestData['EntryFee'];
                $CashBonusContribution = @$ContestData['CashBonusContribution'];
                $WalletAmountDeduction = $WinningAmountDeduction = $CashBonusDeduction = 0;
                if (!empty($CashBonusContribution) && @$UserData['CashBonus'] > 0) {
                    $CashBonusContributionAmount = $ContestEntryRemainingFees * ($CashBonusContribution / 100);
                    $CashBonusDeduction = (@$UserData['CashBonus'] >= $CashBonusContributionAmount) ? $CashBonusContributionAmount : @$UserData['CashBonus'];
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $CashBonusDeduction;
                }
                if ($ContestEntryRemainingFees > 0 && @$UserData['WinningAmount'] > 0) {
                    $WinningAmountDeduction = (@$UserData['WinningAmount'] >= $ContestEntryRemainingFees) ? $ContestEntryRemainingFees : @$UserData['WinningAmount'];
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WinningAmountDeduction;
                }
                if ($ContestEntryRemainingFees > 0 && @$UserData['WalletAmount'] > 0) {
                    $WalletAmountDeduction = (@$UserData['WalletAmount'] >= $ContestEntryRemainingFees) ? $ContestEntryRemainingFees : @$UserData['WalletAmount'];
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WalletAmountDeduction;
                }
                if ($ContestEntryRemainingFees > 0) {
                    $this->Return['Data'] = $UserData;
                    $this->form_validation->set_message('validateUserJoinContest', 'Insufficient wallet amount.');
                    return FALSE;
                }
                $this->Post['CashBonusDeduction']     = $CashBonusDeduction;
                $this->Post['WinningAmountDeduction'] = $WinningAmountDeduction;
                $this->Post['WalletAmountDeduction']  = $WalletAmountDeduction;
            }
            $this->Post['IsPaid']       = $ContestData['IsPaid'];
            $this->Post['EntryFee']     = $ContestData['EntryFee'];
            $this->Post['IsAutoCreate'] = $ContestData['IsAutoCreate'];
            $this->Post['ContestSize']  = $ContestData['ContestSize'];
            $this->Post['TotalJoined']  = $ContestData['TotalJoined'];
            $this->Post['CashBonusContribution'] = $ContestData['CashBonusContribution'];
        } else {
            $this->form_validation->set_message('validateUserJoinContest', 'Invalid ContestGUID.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     *  Description : To validate contest match status 
     */
    public function validateContestMatchStatus()
    {

        /* Get Match Status */
        $MatchData = $this->Sports_model->getMatches('Status', array('MatchID' => @$this->MatchID));
        if (!in_array($MatchData['Status'], array('Running', 'Completed'))) {
            $this->form_validation->set_message('validateContestMatchStatus', 'You can download teams only for running & completed matches.');
            return FALSE;
        }

        /* Get Total Joined Teams Count */
        $TotalJoined = $this->db->query('SELECT COUNT(ContestID) AS `TotalJoined` FROM `sports_contest_join` WHERE `ContestID` =' . @$this->ContestID)->row()->TotalJoined;
        if ($TotalJoined == 0) {
            $this->form_validation->set_message('validateContestMatchStatus', 'No one has joined this contest.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     *  Description : To validate contest invite code
     */
    public function validateContestInviteCode()
    {

        /** check invite code * */
        $ContestID = $this->db->query("SELECT ContestID FROM `sports_contest` WHERE `UserInvitationCode` ='" . $this->Post['UserInvitationCode'] . "'")->row()->ContestID;
        if (!$ContestID) {
            $this->form_validation->set_message('validateContestInviteCode', 'Invalid contest code.');
            return FALSE;
        }
        $ContestID = $this->db->query("SELECT ContestID FROM `sports_contest` WHERE `UserInvitationCode` ='" . $this->Post['UserInvitationCode'] . "' AND `MatchID` ='" . @$this->MatchID . "'")->row()->ContestID;
        if (!$ContestID) {
            $this->form_validation->set_message('validateContestInviteCode', 'This contest code not valid for this match.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validateMatchDateTime
     * Description:   To validate match date time
     */
    public function validateMatchDateTime($MatchGUID, $Module)
    {
        $MatchStartDateTime = $this->db->query('SELECT MatchStartDateTime FROM sports_matches WHERE MatchID = ' . $this->MatchID . ' LIMIT 1')->row()->MatchStartDateTime;
        $MatchStartDateTime = strtotime($MatchStartDateTime)  - ($this->Settings_model->getSiteSettings("MatchLiveTime") * 60); // convert into seconds
        if ($Module == 'Contest' && strtotime(date('Y-m-d H:i:s')) >= $MatchStartDateTime) {
            $this->form_validation->set_message('validateMatchDateTime', 'You can create contest only for upcoming matches.');
            return FALSE;
        }else if ($Module == 'UserTeams' && $this->SessionUserID != $this->UserID && $MatchStartDateTime > strtotime(date('Y-m-d H:i:s'))) {
            $this->form_validation->set_message('validateMatchDateTime', 'Please wait, Match has not started yet.');
            return FALSE;
        }
        return TRUE;
    }

    /* 
     * Description : To validate contest size 
    */
    public function validateContestSize()
    {
        if ($this->Post['ContestSize'] < 2) {
            $this->form_validation->set_message('validateContestSize', 'Why play alone? Need atleast 2 members!');
            return FALSE;
        }
        return TRUE;
    }

    /* 
     * Description : To validate user team players
    */
    public function validateUserTeamPlayers($UserTeamPlayers, $Action)
    {
        /* Validate Match Start Datetime */
        $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
        $MatchStartDateTime = strtotime($this->db->query('SELECT MatchStartDateTime FROM sports_matches WHERE MatchID = ' . $this->MatchID . ' LIMIT 1')->row()->MatchStartDateTime) - ($ClosedInMinutes * 60); // convert into seconds
        if ($MatchStartDateTime < strtotime(date('Y-m-d H:i:s'))) {
            $this->form_validation->set_message('validateUserTeamPlayers', 'You can create team only for upcoming matches.');
            return FALSE;
        }

        /* Validate Players */
        if (!empty($this->Post['UserTeamPlayers']) && is_array($this->Post['UserTeamPlayers'])) {
            $AllPlayersLimit = (@$this->Post['UserTeamType'] == 'InPlay') ? 6 : 11;
            $PlayersLimit    = (@$this->Post['UserTeamType'] == 'InPlay') ? 4 : ((IS_VICECAPTAIN) ? 9 : 10);
            if (count($this->Post['UserTeamPlayers']) != $AllPlayersLimit) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Team Players length should be " . $AllPlayersLimit . ".");
                return FALSE;
            }

            /* To Get all players positions */
            $PlayerPoisitions = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerPosition'));

            /* To Check Captain Position */
            if (empty($PlayerPoisitions['Captain'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select a Captain.");
                return FALSE;
            } else if ($PlayerPoisitions['Captain'] > 1) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select only 1 Captain.");
                return FALSE;
            }

            /* To Check ViceCaptain Position */
            if (IS_VICECAPTAIN && empty($PlayerPoisitions['ViceCaptain'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select a Vice Captain.");
                return FALSE;
            } else if (IS_VICECAPTAIN && $PlayerPoisitions['ViceCaptain'] > 1) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select only 1 Vice Captain.");
                return FALSE;
            }

            /* To Check Player Position */
            if (empty($PlayerPoisitions['Player'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select players.");
                return FALSE;
            } else if ($PlayerPoisitions['Player'] < $PlayersLimit) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select " . $PlayersLimit . " players.");
                return FALSE;
            } else if ($PlayerPoisitions['Player'] > $PlayersLimit) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select only " . $PlayersLimit . " players.");
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('validateUserTeamPlayers', 'User Team Players Required.');
            return FALSE;
        }

        /* Validate Player GUID & Role */
        foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
            $PlayerData = $this->Sports_model->getPlayers('TeamID,PlayerID,PlayerRole', array('MatchID' => $this->MatchID, 'PlayerGUID' => $Value['PlayerGUID']));
            if (!$PlayerData) {
                $this->form_validation->set_message('validateUserTeamPlayers', 'Invalid PlayerGUID.');
                return FALSE;
            }
            $this->Post['UserTeamPlayers'][$Key]["TeamID"]     = $PlayerData['TeamID'];
            $this->Post['UserTeamPlayers'][$Key]["PlayerID"]   = $PlayerData['PlayerID'];
            $this->Post['UserTeamPlayers'][$Key]["PlayerRole"] = $PlayerData['PlayerRole'];
        }

        /* Validate Team Player Limit (Maximum 7 Players Allowed, From Each Team) */
        $TeamPlayers = array_count_values(array_column($this->Post['UserTeamPlayers'], 'TeamID'));
        foreach ($TeamPlayers as $Key => $TeamValue) {
            if ($TeamValue[$Key] > 7) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select maximum 7 players from each teams.");
                return FALSE;
            }
        }

        /* To check if user team player roles is fixed */
        if (IS_USER_TEAMS_ROLES) {

            /* To Check All Player Role's */
            $PlayerRoles = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerRole'));

            /* Validate WicketKeeper */
            if (empty($PlayerRoles['WicketKeeper'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select a Wicket Keeper.");
                return FALSE;
            } else if ($PlayerRoles['WicketKeeper'] > 1) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select only 1 Wicket Keeper.");
                return FALSE;
            }

            /* Validate Batsman */
            if (empty($PlayerRoles['Batsman'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select Batsman.");
                return FALSE;
            } else if ($PlayerRoles['Batsman'] < 3) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You have to select minimum 3 Batsman.");
                return FALSE;
            } else if ($PlayerRoles['Batsman'] > 5) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select maximum 5 Batsman.");
                return FALSE;
            }

            /* Validate AllRounder */
            if (empty($PlayerRoles['AllRounder'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select All Rounder.");
                return FALSE;
            } else if ($PlayerRoles['AllRounder'] < 1) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You have to select minimum 1 All Rounder.");
                return FALSE;
            } else if ($PlayerRoles['AllRounder'] > 3) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select maximum 3 All Rounder.");
                return FALSE;
            }

            /* Validate Bowler */
            if (empty($PlayerRoles['Bowler'])) {
                $this->form_validation->set_message('validateUserTeamPlayers', "Please select Bowler.");
                return FALSE;
            } else if ($PlayerRoles['Bowler'] < 3) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You have to select minimum 3 Bowler.");
                return FALSE;
            } else if ($PlayerRoles['Bowler'] > 5) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can select maximum 5 Bowler.");
                return FALSE;
            }
        }

        /* To check Max User Teams Limit */
        if (IS_USER_TEAMS_LIMIT && $Action == 'Add') {
            if ($this->db->query('SELECT COUNT(UserTeamName) TotalUserTeams FROM sports_users_teams WHERE UserID = ' . $this->SessionUserID . ' AND MatchID = ' . $this->MatchID)->row()->TotalUserTeams > USER_TEAMS_LIMIT) {
                $this->form_validation->set_message('validateUserTeamPlayers', "You can create maximum " . USER_TEAMS_LIMIT . " teams for each match.");
                return FALSE;
            }
        }

        /* To check same players teams */
        $AllPlayersIds  = array_column($this->Post['UserTeamPlayers'], 'PlayerID'); // Sort Players ID In Ascending Order
        $AllPlayerRoles = array_column($this->Post['UserTeamPlayers'], 'PlayerPosition');
        $PlayerString   = '';
        for ($I = 0; $I < 11; $I++) {
            $PlayerString .= $AllPlayersIds[$I] . $AllPlayerRoles[$I];
        }
        $Query = $this->db->query("SELECT (SELECT GROUP_CONCAT(CONCAT(UTP.PlayerID, '', UTP.PlayerPosition) SEPARATOR '') FROM sports_users_team_players UTP WHERE UTP.UserTeamID = TP.UserTeamID ORDER BY UTP.PlayerID ASC) UserTeamPlayers FROM sports_users_teams TP WHERE TP.UserID = " . $this->SessionUserID . " AND TP.MatchID = " . $this->MatchID . " HAVING UserTeamPlayers = '" . $PlayerString . "'");
        if ($Query->num_rows() > 0) {
            $this->form_validation->set_message('validateUserTeamPlayers', "You've already created this team. Change your Playing (XI) and/or Captain & Vice-Captain.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validateMatchLiveTime
     * Description:   To validate match live time
     */
    public function validateMatchLiveTime($ContestGUID)
    {
        /* Get Contest Details */
        $Contest = $this->Contest_model->getContests('MatchStartDateTimeUTC,GameTimeLive', array('StatusID' => array(1, 2), 'ContestID' => $this->ContestID));
        if ($Contest['GameTimeLive'] > 0) {
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $Contest['GameTimeLive'] * 60;
        } else {
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $this->Settings_model->getSiteSettings("MatchLiveTime") * 60;
        }
        if ($MatchStartDateTime <= strtotime(date('Y-m-d H:i:s'))) {
            $this->form_validation->set_message('validateMatchLiveTime', 'You can switch teams only for upcoming matches.');
            return FALSE;
        }
        return TRUE;
    }

}
