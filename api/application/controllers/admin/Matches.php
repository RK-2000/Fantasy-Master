<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Matches extends API_Controller_Secure
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Sports_model');
    }

    /*
      Description: To get matches data
      URL: /admin/matches/getMatches/
     */

    public function getMatches_post()
    {
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('TeamGUID', 'TeamGUID', 'trim|callback_validateEntityGUID[Teams,TeamID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Keyword', 'Search Keyword', 'trim');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Today,Series]');
        $this->form_validation->set_rules('OrderBy', 'OrderBy', 'trim');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Matches Data */
        $MatchesData = $this->Sports_model->getMatches(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => $this->SeriesID, 'TeamID' => @$this->TeamID,'MatchID' => @$this->MatchID)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
      
        if (!empty($MatchesData)) {
            $this->Return['Data'] = $MatchesData['Data'];
        }
    }

    /*
      Description: 	Use to update user profile info.
      URL: /admin/matches/changeStatus/
     */
    public function changeStatus_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|required|callback_validateStatus');
        $this->form_validation->set_rules('MatchClosedInMinutes', 'Match Closed In Minutes', 'trim|integer|max_length[3]');
        $this->form_validation->set_rules('CancelContest', 'CancelContest', 'trim');
        $this->form_validation->set_message('max_length', '%s: the minimum of characters is %s');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Update Match Details */
        $this->Sports_model->updateMatchDetails($this->MatchID, $this->Post);
        
        /* Cancel All Contests */
        if(@$this->Post['CancelContest'] == 'Yes'){
            $this->db->query('update `tbl_entity` E, sports_contest C SET E.StatusID = 3 WHERE  E.EntityID = C.ContestID AND C.MatchID = '.$this->MatchID.' AND E.`StatusID` = 1');
        }
        /* Update Match Status */
        $this->Entity_model->updateEntityInfo($this->MatchID, array("StatusID" => $this->StatusID));
        $this->Return['Data'] = $this->Sports_model->getMatches('MatchClosedInMinutes,Status', array('MatchID' => $this->MatchID), FALSE, 0);
        $this->Return['Message'] = "Success.";
    }

    /*
      Description: 	Use to update user profile info.
      URL: 			/admin/matches/getFilterData/
     */
    public function getFilterData_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('SeriesGUID', 'Series', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $SeriesData = $this->Sports_model->getSeries(@$this->Post['Params'], @$this->Post, true, 0);
        if (!empty($SeriesData)) {
            $Return['SeiresData'] = $SeriesData['Data']['Records'];
        }
        $this->Return['Data'] = empty($Return) ? array() : $Return;
    }

    /* Description:  Use to update user profile info.
      URL:          /admin/matches/getFilterData/
     */

    public function getTeamData_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('SeriesGUID', 'Series', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('LocalTeamGUID', 'TeamGUID', 'trim|callback_validateEntityGUID[Teams,TeamID]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $TeamData = $this->Sports_model->getTeams(@$this->Post['Params'], array_merge(@$this->Post, array('SeriesID' => @$this->SeriesID, 'LocalTeamGUID' => @$this->TeamID)), true, 0);
        if (!empty($TeamData)) {
            $Return['TeamData'] = $TeamData['Data']['Records'];
        }
        $this->Return['Data'] = empty($Return) ? array() : $Return;
    }

    /*
      Description: 	Use to update player role.
      URL: 			/admin/matches/updatePlayerInfo/
     */

    public function updatePlayerInfo_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('PlayerGUID', 'PlayerGUID', 'trim|required|callback_validateEntityGUID[Players,PlayerID]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim' . (empty($this->Post['MatchGUID']) ? '|required' : '') . '|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim' . (empty($this->Post['SeriesGUID']) ? '|required' : '') . '|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('PlayerRole', 'PlayerRole', 'trim|required|in_list[Batsman,Bowler,WicketKeeper,AllRounder]');
        $this->form_validation->set_rules('MediaGUIDs', 'MediaGUIDs', 'trim'); /* Media GUIDs */
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Sports_model->updatePlayerRole($this->PlayerID,$this->MatchID,array("PlayerRole"=>$this->Post['PlayerRole'],"PlayerSalary"=>$this->Post['PlayerSalary']));

        /* check for media present - associate media with this Post */
        if (!empty($this->Post['MediaGUIDs'])) {
            $MediaGUIDsArray = explode(",", $this->Post['MediaGUIDs']);
            foreach ($MediaGUIDsArray as $MediaGUID) {
                $EntityData = $this->Entity_model->getEntity('E.EntityID MediaID', array('EntityGUID' => $MediaGUID, 'EntityTypeID' => 4));
                if ($EntityData) {
                    $this->Media_model->addMediaToEntity($EntityData['MediaID'], $this->SessionUserID, $this->PlayerID);

                    /* Get Media */
                    $MediaData = $this->Media_model->getMedia(
                        'MediaGUID,M.MediaName',
                        array("SectionID" => "PlayerPic", "MediaID" => $EntityData['MediaID']),
                        FALSE
                    );

                    /* Update Player Pic Media Name */
                    $this->db->query('UPDATE sports_players AS P, tbl_media AS M SET P.PlayerPic = M.MediaName WHERE M.EntityID = P.PlayerID AND M.MediaID = ' . $EntityData['MediaID']);

                    /* Edit Into MongoDB */
                    mongoDBConnection();
                    $this->fantasydb->sports_players->updateOne(
                        ['_id' => $this->Post['PlayerGUID']],
                        ['$set'   => array('PlayerID' => (int) $this->PlayerID,'PlayerPic' => $MediaData['MediaName'])],
                        ['upsert' => true]
                    );
                }
            }
        }
        $this->Return['Data'] = $this->Sports_model->getPlayers('PlayerSalaryCredit,TeamGUID,TeamName,TeamNameShort,TeamFlag,PlayerID,PlayerIDLive,PlayerRole,IsPlaying,PlayerSalary,SeriesID,MatchID,PlayerPic,PlayerCountry,PlayerBattingStyle,PlayerBowlingStyle,PlayerBattingStats,PlayerBowlingStats', array('PlayerID' => $this->PlayerID, 'MatchID' => $this->MatchID), FALSE, 0);
        $this->Return['Message'] = "Success.";
    }

    /*
      Description: 	Use to update player salary.
      URL: 			/admin/matches/updatePlayerSalary/
     */

    public function updatePlayerSalary_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('PlayerGUID', 'PlayerGUID', 'trim|required|callback_validateEntityGUID[Players,PlayerID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('PlayerSalaryCredit', 'PlayerSalaryCredit', 'trim|required|numeric');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        $this->Sports_model->updatePlayerSalaryMatch($this->Post, $this->PlayerID, $this->MatchID);
        $this->Return['Message'] = "Player salary has been changed.";
    }

    /*
	Description: 	Use to download csv player salary sample.
	URL: 			/admin/matches/downloadPlayerSalarySample_post/	
	*/
 	public function downloadPlayerSalarySample_post()
 	{
 		/* Validation section */
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
		$this->form_validation->validation($this);  /* Run validation */		
		/* Validation - ends */

        /* Get Player Data */
        $PlayerData = $this->Sports_model->getPlayers(@$this->Post['Params'],array('MatchID' => $this->MatchID), TRUE, 0);
		if ($PlayerData['Data']['TotalRecords'] > 0) {
            foreach ($PlayerData['Data']['Records'] as $Key => $Value) {
                $DataArr[] = array(
                            'PlayerID'      => $Value['PlayerID'],
                            'TeamName'      => $Value['TeamName'],
                            'PlayerName'    => $Value['PlayerName'],
                            'PlayerRole'    => $Value['PlayerRole'],
                            'PlayerSalary'  => $Value['PlayerSalary'],
                        );
            }
            $FileName = 'match-players-salary-'.$this->Post['MatchGUID'].'.csv';
            $FP = fopen($FileName, 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$FileName);
            fputcsv($FP, array('PlayerID','TeamName','PlayerName', 'PlayerRole', 'PlayerSalary'));
            foreach ($DataArr as $Row) {
                fputcsv($FP, $Row);
            }
            $this->Return['ResponseCode'] = 200;
            $this->Return['Data'] = $FileName;
        } else {
            $this->Return['Message'] = "Player data not found.";
        }
    }

    /*
    Description:    Use to delete csv player salary.
    URL:            /admin/matches/deleteFile/ 
    */
    public function deleteFile_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('File', 'File', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */ 

        @unlink(getcwd().'/'.$this->Post['File']);
    }
     
    /*
	Description: 	Use to import csv player salary.
	URL: 			/admin/matches/importPlayerSalary_post/	
	*/
	public function importPlayerSalary_post()
	{
		/* Validation section */
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
		$this->form_validation->validation($this);  /* Run validation */		
		/* Validation - ends */
	
		if(empty($_FILES))
		{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "File array can`t be empty.";
			exit;
		}
		if($_FILES["CSVFile"]["error"] > 0)
		{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "Uploaded file has error.";
			exit;
		}
		if($_FILES["CSVFile"]["size"] < 0 || $_FILES["CSVFile"]["size"] == 0)
		{
			$this->Return['ResponseCode'] = 500;
			$this->Return['Message'] = "File can`t empty.";
			exit;
		}
		$FileExtension = strtolower(end(explode('.',$_FILES["CSVFile"]["name"])));
		$FileTempName = $_FILES["CSVFile"]["tmp_name"];
		$File = fopen($FileTempName, "r");
		$Row  = array();
		while (($EmapData = fgetcsv($File, 10000, ",")) !== FALSE)
		{
		    array_push($Row, $EmapData);
		}
		fclose($File);
		for($i=1; $i<=count($Row); $i++){
			$this->Sports_model->updatePlayerSalaryMatch(array('PlayerSalary' => $Row[$i][4]),$Row[$i][0],$this->MatchID);
		}
		$this->Return['Message'] = "Player salary has been updated.";

	}
};