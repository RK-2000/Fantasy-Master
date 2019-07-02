<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contest extends API_Controller_Secure {

    function __construct() {
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
    public function add_post() {

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
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]|callback_validateMatchDateTime');
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
        }else{
            $this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Customize winning data is required.";
			exit;
        }
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $ContestID = $this->Contest_model->addContest(array_merge($this->Post,array('IsPaid' => 'Yes','Privacy' => 'Yes','ShowJoinedContest' => 'Yes','IsConfirm' => 'No','CashBonusContribution' => 0,'AdminPercent' => ADMIN_CONTEST_PERCENT,'ContestType' => ($this->Post['ContestSize'] == 2) ? 'Head to Head' : 'Normal')), $this->SessionUserID, $this->MatchID, $this->SeriesID);
        if (!$ContestID) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "An error occurred, please try again later.";
        } else {
            $this->Return['Message'] = "Contest created successfully.";
            $this->Return['Data']['ContestGUID'] = $this->Contest_model->getContests('CustomizeWinning,MatchScoreDetails,UserID,ContestFormat,ContestType,Privacy,IsPaid,WinningAmount,ContestSize,EntryFee,NoOfWinners,EntryType,SeriesID,MatchID,UserInvitationCode', array('ContestID' => $ContestID));
        }
    }

    /*
    Description: To get contests data
    */
    public function getContests_post() {
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|in_list[Yes,No,All]');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('GUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
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
        $ContestData = $this->Contest_model->getContests(@$this->Post['Params'], array_merge($this->Post, array('ContestID' => @$this->ContestID,'MatchID' => @$this->MatchID, 'ContestType' => @$this->Post['ContestType'], 'SeriesID' => @$this->SeriesID, 'UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID, 'StatusID' => @$this->StatusID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($ContestData)) {
            $this->Return['Data'] = $ContestData['Data'];
        }
    }

    /*
      Description: To get contests data (By Type)
     */
    public function getContestsByType_post() {

        $this->form_validation->set_rules('Privacy', 'Privacy', 'trim|required|in_list[Yes,No,All]');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
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

        foreach ($ContestTypes as $key => $Contests) {
            array_push($ContestData, $this->Contest_model->getContests(@$this->Post['Params'], array_merge($this->Post, array('MatchID' => @$this->MatchID, 'UserID' => @$this->UserID, 'SessionUserID' => $this->SessionUserID), $Contests['Where']), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize'])['Data']);
            $ContestData[$key]['Key'] = $Contests['Key'];
            $ContestData[$key]['TagLine'] = $Contests['TagLine'];
        }
        $Statics = $this->db->query('SELECT(
                    SELECT COUNT(J.EntryDate) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . $this->SessionUserID . '" AND C.MatchID = "' . $this->MatchID . '" 
                    )as JoinedContest,( 
                    SELECT COUNT(UserTeamName) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . $this->SessionUserID . '" AND MatchID = "' . $this->MatchID . '"
                ) as TotalTeams')->row();
        if (!empty($ContestData)) {
            $this->Return['Data']['Results'] = $ContestData;
            $this->Return['Data']['Statics'] = $Statics;
        }
    }

    /*
      Name: 		join
      Description: 	Use to join contest to system.
      URL: 			/contest/join/
     */
    public function join_post() {
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateUserJoinContest');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Join Contests */
        $Contest = $this->Contest_model->getContests('MatchStartDateTimeUTC,GameTimeLive', array('StatusID' => array(1, 2), 'ContestID' => $this->ContestID));
        $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
        $Contest['GameTimeLive'] = 0;
        if ($Contest['GameTimeLive'] > 0) {
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $Contest['GameTimeLive'] * 60;
        } else {
            $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
        }
        if ($MatchStartDateTime > $CurrentDateTime) {
            $JoinContest = $this->Contest_model->joinContest($this->Post, $this->SessionUserID, $this->ContestID, $this->MatchID, $this->UserTeamID);
            if (!$JoinContest) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "An error occurred, please try again later.";
            } else {
                $this->Return['Data'] = $JoinContest;
                $this->Return['Message'] = "Contest joined successfully.";
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This contest has already started.";
        }
    }

    /*
      Name: 		switchTeam
      Description: 	Use to  switch team with joined contest.
      URL: 			/contest/switchTeam/
     */
    public function switchTeam_post() {
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]|callback_validateUserJoinContest');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Join Contests */
        $Contest = $this->Contest_model->getContests('MatchStartDateTimeUTC,GameTimeLive', array('StatusID' => array(1, 2), 'ContestID' => $this->ContestID));
        $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
        $Contest['GameTimeLive'] = 0;
        if ($Contest['GameTimeLive'] > 0) {
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $Contest['GameTimeLive'] * 60;
        } else {
            $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
        }
        if ($MatchStartDateTime > $CurrentDateTime) {
            $JoinContest = $this->Contest_model->joinContest($this->Post, $this->SessionUserID, $this->ContestID, $this->MatchID, $this->UserTeamID);
            if (!$JoinContest) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "An error occurred, please try again later.";
            } else {
                $this->Return['Message'] = "Contest Switched successfully.";
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This contest has already started.";
        }
    }

    /*
      Description: To get joined contests data
     */
    public function getJoinedContests_post() {
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

    public function getJoinedContestsUsers_post() {
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Contest Status */ 
        $Contest = $this->Contest_model->getContests('Status',array('ContestID' => $this->Post['ContestID']));
        if($Contest['Status'] == 'Pending' || $Contest['Status'] == 'Cancelled'){
            
            /* Get Joined Contest Users Data (MySQL) */
            $JoinedContestData = $this->Contest_model->getJoinedContestsUsers(@$this->Post['Params'], array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'ContestID' => $this->ContestID), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        }else{
            
            /* Get Joined Contest Users Data (MongoDB) */
            $JoinedContestData = $this->Contest_model->getJoinedContestsUsersMongoDB(array_merge($this->Post,array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'ContestID' => $this->ContestID)), @$this->Post['PageNo'], @$this->Post['PageSize']);
            if(!$JoinedContestData){
                $JoinedContestData = $this->Contest_model->getJoinedContestsUsers(@$this->Post['Params'], array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'ContestID' => $this->ContestID), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
            }
        }
        if (!empty($JoinedContestData)) {
            $this->Return['Data'] = $JoinedContestData['Data'];
        }
    }

    /*
      Description: To get joined contest users data (MongoDB)
     */

    public function getJoinedContestsUsersMongoDB_post() {
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Joined Contest Users Data */
        $JoinedContestData = $this->Contest_model->getJoinedContestsUsersMongoDB(array_merge($this->Post,array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'ContestID' => $this->ContestID)), @$this->Post['PageNo'], @$this->Post['PageSize']);
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
      Name: 		addUserTeam
      Description: 	Use to create team to system.
      URL: 			/api/contest/addUserTeam/
     */

    public function addUserTeam_post() {
        /* Validation section */
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay]');
        $this->form_validation->set_rules('MatchInning', 'MatchInning', 'trim' . (!empty($this->Post['UserTeamType']) && $this->Post['UserTeamType'] == 'InPlay' ? '|required' : ''));
        // $this->form_validation->set_rules('UserTeamName', 'UserTeamName', 'trim|required');
        $this->form_validation->set_rules('UserTeamPlayers', 'UserTeamPlayers', 'trim');
        // print_r($this->Post['UserTeamPlayers']);
        // exit;
        if (!empty($this->Post['UserTeamPlayers']) && is_array($this->Post['UserTeamPlayers'])) {
            $AllPlayersLimit = ($this->Post['UserTeamType'] == 'InPlay') ? 6 : 11;
            $PlayersLimit = ($this->Post['UserTeamType'] == 'InPlay') ? 4 : 9;
            if (count($this->Post['UserTeamPlayers']) != $AllPlayersLimit) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Team Players length should be " . $AllPlayersLimit . ".";
                exit;
            }
            $PlayerPoisitions = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerPosition'));
            if ($PlayerPoisitions['Captain'] != 1 || $PlayerPoisitions['ViceCaptain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select 1 Captain & 1 Vice Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['Captain']) && $PlayerPoisitions['Captain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only 1 Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['ViceCaptain']) && $PlayerPoisitions['ViceCaptain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only 1 Vice Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['Player']) && $PlayerPoisitions['Player'] != $PlayersLimit) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only " . $PlayersLimit . " Players.";
                exit;
            }
            foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
                $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerGUID]', 'PlayerGUID', 'trim|required|callback_validateEntityGUID[Players,PlayerID]');
                $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerPosition]', 'PlayerPosition', 'trim|required|in_list[Captain,ViceCaptain,Player]');
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "User Team Players Required.";
            exit;
        }
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */


        foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
            $Where = array(
                'MatchID' => $this->MatchID,
                'PlayerID' => $Value['PlayerID']
            );
            $Role = $this->Sports_model->getPlayers('PlayerID,PlayerRole', $Where, FALSE, 0);
            $this->Post['UserTeamPlayers'][$Key]["PlayerRole"] = $Role['PlayerRole'];
        }
        $PlayerRoles = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerRole'));

        /* To validate WICKETKEEPER */
        if (!isset($PlayerRoles['WicketKeeper'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You have to pick 1 wicket keeper.";
            exit;
        } else if ($PlayerRoles['WicketKeeper'] > 1) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You can only pick 1 wicket keeper.";
            exit;
        }

        /* To validate BATSMAN */
        if (!isset($PlayerRoles['Batsman'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 3 & Max 5 Batsman';
            exit;
        } else if ($PlayerRoles['Batsman'] < 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 3 Batsmen';
            exit;
        } else if ($PlayerRoles['Batsman'] > 5) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 5 batsmen allowed';
            exit;
        }

        /* To validate BOWLER */
        if (!isset($PlayerRoles['Bowler'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 3 & Max 5 Bowler';
            exit;
        } else if ($PlayerRoles['Bowler'] < 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 3 Bowler';
            exit;
        } else if ($PlayerRoles['Bowler'] > 5) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 5 Bowler allowed';
            exit;
        }

        /* To validate ALLROUNDER */
        if (!isset($PlayerRoles['AllRounder'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 1 & Max 3 all-rounder';
            exit;
        } else if ($PlayerRoles['AllRounder'] < 1) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 1 all-rounder';
            exit;
        } else if ($PlayerRoles['AllRounder'] > 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 3 all-rounder allowed';
            exit;
        }

        $MatchData = $this->Sports_model->getMatches('MatchStartDateTimeUTC', array('MatchID' => $this->MatchID));
        $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
        $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
        $MatchStartDateTime = strtotime($MatchData['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
        if ($MatchStartDateTime > $CurrentDateTime) {
            $UserTeams = $this->Contest_model->getUserTeams("UserTeamID", array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID), TRUE);
            if (!empty($UserTeams)) {
                if ($UserTeams['Data']['TotalRecords'] >= 6) {
                    $this->Return['ResponseCode'] = 500;
                    $this->Return['Message'] = "You can not create more then 6 team on single match.";
                    exit;
                }
                $Flag = false;
                $Uct = 0;
                $AllPlayerList = $this->Post['UserTeamPlayers'];
                foreach ($AllPlayerList as $Key => $Rows) {
                    $PlayerIDs = $this->Sports_model->getPlayers('PlayerID', array('PlayerGUID' => $Rows['PlayerGUID']));
                    $AllPlayerList[$Key]['PlayerID'] = $PlayerIDs['PlayerID'];
                }
                foreach ($UserTeams['Data']['Records'] as $Rows) {
                    if ($Uct != 0) {
                        if ($Flag == false) {
                            break;
                        } else {
                            $Flag = false;
                        }
                    }
                    $Uct++;
                    foreach ($AllPlayerList as $Ply) {
                        $Where = array(
                            'UserTeamID' => $Rows['UserTeamID'],
                            'PlayerID' => $Ply['PlayerID'],
                            'PlayerPosition' => $Ply['PlayerPosition'],
                            'MatchID' => $this->MatchID
                        );
                        $UserTeamPlayer = $this->Contest_model->getUserTeamPlayers("PlayerID", $Where, FALSE);
                        if (empty($UserTeamPlayer)) {
                            $Flag = true;
                        }
                    }
                }
            } else {
                $Flag = true;
            }
            if ($Flag) {
                $UserTeam = $this->Contest_model->addUserTeam($this->Post, $this->SessionUserID, $this->MatchID);
                if (!$UserTeam) {
                    $this->Return['ResponseCode'] = 500;
                    $this->Return['Message'] = "An error occurred, please try again later.";
                } else {
                    $this->Return['Data']['UserTeamGUID'] = $UserTeam;
                    $this->Return['Message'] = "Team created successfully.";
                }
            } else {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You've already created this team. Change your Playing (XI) and/or Captain & Vice-Captain";
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This match has already started.";
        }
    }
    
    /*
      Name: 		editUserTeam
      Description: 	Use to update team to system.
      URL: 			/api/contest/editUserTeam/
     */

    public function editUserTeam_post() {
        /* Validation section */
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|required|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay]');
        $this->form_validation->set_rules('UserTeamName', 'UserTeamName', 'trim|required');
        $this->form_validation->set_rules('UserTeamPlayers', 'UserTeamPlayers', 'trim');

        if (!empty($this->Post['UserTeamPlayers']) && is_array($this->Post['UserTeamPlayers'])) {
            if (count($this->Post['UserTeamPlayers']) != 11) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Team Players length should be 11.";
                exit;
            }
            $PlayerPoisitions = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerPosition'));
            if ($PlayerPoisitions['Captain'] != 1 || $PlayerPoisitions['ViceCaptain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select 1 Captain & 1 Vice Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['Captain']) && $PlayerPoisitions['Captain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only 1 Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['ViceCaptain']) && $PlayerPoisitions['ViceCaptain'] != 1) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only 1 Vice Captain.";
                exit;
            } else if (!empty($PlayerPoisitions['Player']) && $PlayerPoisitions['Player'] != 9) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You can select only 9 Players.";
                exit;
            }
            foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
                $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerGUID]', 'PlayerGUID', 'trim|required|callback_validateEntityGUID[Players,PlayerID]');
                $this->form_validation->set_rules('UserTeamPlayers[' . $Key . '][PlayerPosition]', 'PlayerPosition', 'trim|required|in_list[Captain,ViceCaptain,Player]');
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "User Team Players Required.";
            exit;
        }
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        foreach ($this->Post['UserTeamPlayers'] as $Key => $Value) {
            $Where = array(
                'MatchID' => $this->MatchID,
                'PlayerID' => $Value['PlayerID']
            );
            $Role = $this->Sports_model->getPlayers('PlayerID,PlayerRole', $Where, FALSE, 0);
            $this->Post['UserTeamPlayers'][$Key]["PlayerRole"] = $Role['PlayerRole'];
        }
        $PlayerRoles = array_count_values(array_column($this->Post['UserTeamPlayers'], 'PlayerRole'));

        /* To validate WICKETKEEPER */
        if (!isset($PlayerRoles['WicketKeeper'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You have to pick 1 wicket keeper.";
            exit;
        } else if ($PlayerRoles['WicketKeeper'] > 1) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "You can only pick 1 wicket keeper.";
            exit;
        }

        /* To validate BATSMAN */
        if (!isset($PlayerRoles['Batsman'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 3 & Max 5 Batsman';
            exit;
        } else if ($PlayerRoles['Batsman'] < 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 3 Batsmen';
            exit;
        } else if ($PlayerRoles['Batsman'] > 5) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 5 batsmen allowed';
            exit;
        }

        /* To validate BOWLER */
        if (!isset($PlayerRoles['Bowler'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 3 & Max 5 Bowler';
            exit;
        } else if ($PlayerRoles['Bowler'] < 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 3 Bowler';
            exit;
        } else if ($PlayerRoles['Bowler'] > 5) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 5 Bowler allowed';
            exit;
        }

        /* To validate ALLROUNDER */
        if (!isset($PlayerRoles['AllRounder'])) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'You have to pick Min 1 & Max 3 all-rounder';
            exit;
        } else if ($PlayerRoles['AllRounder'] < 1) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Every team needs atleast 1 all-rounder';
            exit;
        } else if ($PlayerRoles['AllRounder'] > 3) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = 'Max 3 all-rounder allowed';
            exit;
        }

        /** validate Advance or safe Play * */
        $AdvanceSalePlay = $this->Contest_model->ValidateAdvanceSafePlay($this->MatchID, $this->SessionUserID, $this->UserTeamID);
        if (!$AdvanceSalePlay) {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This team already joined with advance contest please sitch or create new team.Advance contest has already started";
            exit;
        }

        $MatchData = $this->Sports_model->getMatches('MatchStartDateTimeUTC', array('MatchID' => $this->MatchID));
        $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
        $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
        $MatchStartDateTime = strtotime($MatchData['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
        if ($MatchStartDateTime > $CurrentDateTime) {
            $UserTeams = $this->Contest_model->getUserTeams("UserTeamID", array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID), TRUE);
            if (!empty($UserTeams)) {
                if ($UserTeams['Data']['TotalRecords'] >= 6) {
                    $this->Return['ResponseCode'] = 500;
                    $this->Return['Message'] = "You can not create more then 6 team on single match.";
                    exit;
                }
                $Flag = false;
                $Uct = 0;
                $AllPlayerList = $this->Post['UserTeamPlayers'];
                foreach ($AllPlayerList as $Key => $Rows) {
                    $PlayerIDs = $this->Sports_model->getPlayers('PlayerID', array('PlayerGUID' => $Rows['PlayerGUID']));
                    $AllPlayerList[$Key]['PlayerID'] = $PlayerIDs['PlayerID'];
                }
                foreach ($UserTeams['Data']['Records'] as $Rows) {
                    if ($Uct != 0) {
                        if ($Flag == false) {
                            break;
                        } else {
                            $Flag = false;
                        }
                    }
                    $Uct++;
                    foreach ($AllPlayerList as $Ply) {
                        $Where = array(
                            'UserTeamID' => $Rows['UserTeamID'],
                            'PlayerID' => $Ply['PlayerID'],
                            'PlayerPosition' => $Ply['PlayerPosition'],
                            'MatchID' => $this->MatchID
                        );
                        $UserTeamPlayer = $this->Contest_model->getUserTeamPlayers("PlayerID", $Where, FALSE);
                        if (empty($UserTeamPlayer)) {
                            $Flag = true;
                        }
                    }
                }
            } else {
                $Flag = true;
            }
            if ($Flag) {
                if (!$this->Contest_model->editUserTeam($this->Post, $this->UserTeamID, $this->MatchID)) {
                    $this->Return['ResponseCode'] = 500;
                    $this->Return['Message'] = "An error occurred, please try again later.";
                } else {
                    $this->Return['Message'] = "Team updated successfully.";
                }
            } else {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "You've already created this team. Change your Playing (XI) and/or Captain & Vice-Captain";
            }
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This match has already started.";
        }
    }

    /*
      Name: 		switchUserTeam
      Description: 	Use to  switch user team with joined contest.
      URL: 			/contest/switchUserTeam/
     */

    public function switchUserTeam_post() {
        $this->form_validation->set_rules('UserTeamGUID[]', 'UserTeamGUID', 'trim|required');
        $this->form_validation->set_rules('OldUserTeamGUID[]', 'OldUserTeamGUID', 'trim|required');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|required|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Join Contests */
        $Contest = $this->Contest_model->getContests('MatchStartDateTimeUTC,GameTimeLive', array('StatusID' => array(1, 2), 'ContestID' => $this->ContestID));
        $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
        $Contest['GameTimeLive'] = 0;
        if ($Contest['GameTimeLive'] > 0) {
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $Contest['GameTimeLive'] * 60;
        } else {
            $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
            $MatchStartDateTime = strtotime($Contest['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
        }
        if ($MatchStartDateTime > $CurrentDateTime) {
            $UserTeamGUID = json_decode($this->Post['UserTeamGUID']);
            $OldUserTeamGUID = json_decode($this->Post['OldUserTeamGUID']);
            foreach ($UserTeamGUID as $key => $Rows) {
                $UserTeamIDNew = $this->Entity_model->getEntity("EntityID", array("EntityGUID" => $Rows, 'EntityTypeName' => 'User Teams'));
                $UserTeamIDOld = $this->Entity_model->getEntity("EntityID", array("EntityGUID" => $OldUserTeamGUID[$key], 'EntityTypeName' => 'User Teams'));
                $this->Contest_model->switchUserTeam($this->SessionUserID, $this->ContestID, $UserTeamIDNew['EntityID'], $UserTeamIDOld['EntityID']);
            }
            $this->Return['Message'] = "Team switched successfully.";
        } else {
            $this->Return['ResponseCode'] = 500;
            $this->Return['Message'] = "Sorry! This match has already started.";
        }
    }

    /*
      Description: To get user teams data
     */
    public function getUserTeams_post() {
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('ContestGUID', 'ContestGUID', 'trim|callback_validateEntityGUID[Contest,ContestID]');
        $this->form_validation->set_rules('UserGUID', 'UserGUID', 'trim|required|callback_validateEntityGUID[User,UserID]');
        $this->form_validation->set_rules('UserTeamGUID', 'UserTeamGUID', 'trim|callback_validateEntityGUID[User Teams,UserTeamID]');
        $this->form_validation->set_rules('UserTeamType', 'UserTeamType', 'trim|required|in_list[Normal,InPlay,All]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Normal]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get User Teams Data */
        if ($this->SessionUserID != $this->UserID) {
            $MatchData = $this->Sports_model->getMatches('MatchStartDateTimeUTC', array('MatchID' => $this->MatchID));
            $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
            $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
            $MatchStartDateTime = strtotime($MatchData['MatchStartDateTimeUTC']) - $ClosedInMinutes * 60;
            if ($MatchStartDateTime > $CurrentDateTime) {
                $this->Return['ResponseCode'] = 500;
                $this->Return['Message'] = "Please wait, Match has not started yet!";
                exit;
            }
        }
        $UserTeams = $this->Contest_model->getUserTeams(@$this->Post['Params'], array_merge($this->Post, array('UserID' => $this->SessionUserID, 'MatchID' => $this->MatchID, 'UserTeamID' => @$this->UserTeamID, 'TeamsContestID' => @$this->ContestID)), (!empty($this->Post['UserTeamGUID'])) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($UserTeams)) {
            $this->Return['Data'] = (!empty($this->Post['UserTeamGUID'])) ? $UserTeams : $UserTeams['Data'];
        }
    }

    /*
      Description: To download contest teams
    */
    public function downloadTeams_post() {
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
      Description : To create winners breakout
     */
    public function WinningBreakups_post() {
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
    public function validateInviteCode($UserInvitationCode) {
        $ContestData = $this->Contest_model->getContests('Status,TeamNameShortLocal,TeamNameShortVisitor',array('UserInvitationCode' => $UserInvitationCode));
        if (!$ContestData) {
            $this->form_validation->set_message('validateInviteCode', 'Invalid Contest invite code.');
            return FALSE;
        }
        if($ContestData['Status'] != 'Pending'){
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
    public function validateMatchStatus($UserTeamGUID) {
        $MatchStatus = $this->db->query("SELECT E.StatusID FROM sports_users_teams UT, tbl_entity E WHERE UT.MatchID = E.EntityID AND UT.UserTeamGUID = '" . $UserTeamGUID . "' ")->row()->StatusID;
        if ($MatchStatus != 1) {
            $this->form_validation->set_message('validateMatchStatus', 'Sorry, you can not edit team.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validateUserJoinContest
     * Description:   To validate user join contest
     */
    public function validateUserJoinContest($ContestGUID) {

        $ContestData = $this->Contest_model->getContests('MatchID,ContestSize,Privacy,IsPaid,EntryType,EntryFee,UserInvitationCode,ContestID,ContestType,UserJoinLimit,CashBonusContribution', array('ContestID' => $this->ContestID));
        if (!empty($ContestData)) {
            /* Get Match Status */
            $MatchData = $this->Sports_model->getMatches('MatchType,Status,MatchScoreDetails,MatchGUID', array('MatchID' => $ContestData['MatchID']));
            if ($MatchData['Status'] != 'Pending') {
                $this->form_validation->set_message('validateUserJoinContest', 'You can join only upcoming matches contest.');
                return FALSE;
            }

            /* Check Join Contest Size Limit */
            if ($this->db->query('SELECT COUNT(*) AS `TotalRecords` FROM `sports_contest_join` WHERE `ContestID` =' . $ContestData['ContestID'])->row()->TotalRecords >= $ContestData['ContestSize']) {
                $this->form_validation->set_message('validateUserJoinContest', 'Join Contest limit is exceeded.');
                return FALSE;
            }

            /* To Check If Contest Is Already Joined */
            $JoinContestWhere = array('SessionUserID' => $this->SessionUserID, 'ContestID' => $ContestData['ContestID']);
            if ($ContestData['EntryType'] == 'Multiple') {

                /* Get User Join Limit */
                if ($this->db->query('SELECT COUNT(*) AS `TotalJoined` FROM `sports_contest_join` WHERE `ContestID` =' . $ContestData['ContestID'] . ' AND UserID = ' . $this->SessionUserID)->row()->TotalJoined >= $ContestData['UserJoinLimit']) {
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
                $this->load->model('Users_model');
                $UserData = $this->Users_model->getUsers('TotalCash,WalletAmount,WinningAmount,CashBonus', array('UserID' => $this->SessionUserID));
                $this->Post['WalletAmount'] = $UserData['WalletAmount'];
                $this->Post['WinningAmount'] = $UserData['WinningAmount'];
                $this->Post['CashBonus'] = $UserData['CashBonus'];

                $ContestEntryRemainingFees = @$ContestData['EntryFee'];
                $CashBonusContribution = @$ContestData['CashBonusContribution'];
                $WalletAmountDeduction = 0;
                $WinningAmountDeduction = 0;
                $CashBonusDeduction = 0;
                if (!empty($CashBonusContribution) && @$UserData['CashBonus'] > 0) {
                    $CashBonusContributionAmount = $ContestEntryRemainingFees * ($CashBonusContribution / 100);
                    if (@$UserData['CashBonus'] >= $CashBonusContributionAmount) {
                        $CashBonusDeduction = $CashBonusContributionAmount;
                    } else {
                        $CashBonusDeduction = @$UserData['CashBonus'];
                    }
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $CashBonusDeduction;
                }
                if ($ContestEntryRemainingFees > 0 && @$UserData['WinningAmount'] > 0) {
                    if (@$UserData['WinningAmount'] >= $ContestEntryRemainingFees) {
                        $WinningAmountDeduction = $ContestEntryRemainingFees;
                    } else {
                        $WinningAmountDeduction = @$UserData['WinningAmount'];
                    }
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WinningAmountDeduction;
                }
                if ($ContestEntryRemainingFees > 0 && @$UserData['WalletAmount'] > 0) {
                    if (@$UserData['WalletAmount'] >= $ContestEntryRemainingFees) {
                        $WalletAmountDeduction = $ContestEntryRemainingFees;
                    } else {
                        $WalletAmountDeduction = @$UserData['WalletAmount'];
                    }
                    $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WalletAmountDeduction;
                }
                if ($ContestEntryRemainingFees > 0) {
                    $this->form_validation->set_message('validateUserJoinContest', 'Insufficient wallet amount.');
                    return FALSE;
                }
            }
            $this->Post['IsPaid'] = $ContestData['IsPaid'];
            $this->Post['EntryFee'] = $ContestData['EntryFee'];
            $this->Post['CashBonusContribution'] = $ContestData['CashBonusContribution'];
            return TRUE;
        } else {
            $this->form_validation->set_message('validateUserJoinContest', 'Invalid ContestGUID.');
            return FALSE;
        }
    }

    /**
     *  Description : To validate contest match status 
    */
    public function validateContestMatchStatus() {

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
    public function validateContestInviteCode() {

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
    public function validateMatchDateTime($MatchGUID) {
        $ClosedInMinutes = $this->Settings_model->getSiteSettings("MatchLiveTime");
        if($ClosedInMinutes > 0){
            $MatchStartDateTime = strtotime($this->db->query('SELECT MatchStartDateTime FROM sports_matches WHERE MatchID = '.$this->MatchID.' LIMIT 1')->row()->MatchStartDateTime) - ($ClosedInMinutes * 60); // convert into seconds
            if ($MatchStartDateTime < strtotime(date('Y-m-d H:i:s'))) {
                $this->form_validation->set_message('validateMatchDateTime', 'You can create contest only for upcoming matches.');
                return FALSE;
            }
        }
        return TRUE;
    }

    /* 
     * Description : To validate contest size 
    */
    public function validateContestSize() {
        if ($this->Post['ContestSize'] < 2) {
            $this->form_validation->set_message('validateContestSize', 'Why play alone? Need atleast 2 members!');
            return FALSE;
        }
        return TRUE;
    }

}
