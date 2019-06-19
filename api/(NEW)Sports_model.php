<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sports_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Utility_model');

        $this->IsStrikeRate = $this->IsEconomyRate = $this->IsBattingState = $this->IsBowlingState = "0";
        $this->defaultStrikeRatePoints = $this->defaultEconomyRatePoints = $this->defaultBattingPoints = $this->defaultBowlingPoints = array();
    }

    /*
      Description: To get all series
     */

    function getSeries($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) 
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'SeriesID' => 'S.SeriesID',
                'StatusID' => 'E.StatusID',
                'SeriesIDLive' => 'S.SeriesIDLive',
                'SeriesStartDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesStartDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesStartDate',
                'SeriesEndDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesEndDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesEndDate',
                'TotalMatches' => '(SELECT COUNT(*) AS TotalMatches FROM sports_matches WHERE sports_matches.SeriesID =  S.SeriesID ) AS TotalMatches',
                'Status' => 'CASE E.StatusID
                            when "2" then "Active"
                            when "6" then "Inactive"
                            END as Status',
                );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('S.SeriesGUID,S.SeriesName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_series S');
        $this->db->where("S.SeriesID", "E.EntityID", FALSE);
        if (!empty($Where['Keyword'])) {
            $this->db->like("S.SeriesName", $Where['Keyword']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("S.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['SeriesStartDate'])) {
            $this->db->where("S.SeriesStartDate >=", $Where['SeriesStartDate']);
        }
        if (!empty($Where['SeriesEndDate'])) {
            $this->db->where("S.SeriesEndDate >=", $Where['SeriesEndDate']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            $this->db->order_by('E.StatusID', 'ASC');
            $this->db->order_by('S.SeriesStartDate', 'DESC');
            $this->db->order_by('S.SeriesName', 'ASC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Return['Data']['Records'] = $Query->result_array();
                return $Return;
            } else {
                return $Query->row_array();
            }
        }
        return FALSE;
    }

    /*
      Description: Use to match type data.
     */

    function getMatchTypes($MatchTypeID = '') {
        $this->db->select('*');
        $this->db->from('sports_set_match_types');
        if ($MatchTypeID) {
            $this->db->where("MatchTypeID", $MatchTypeID);
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        }
        return FALSE;
    }

    /*
      Description: To get all matches
     */

    function getMatches($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'SeriesID' => 'S.SeriesID',
                'SeriesGUID' => 'S.SeriesGUID',
                'StatusID' => 'E.StatusID',
                'SeriesIDLive' => 'S.SeriesIDLive',
                'SeriesName' => 'S.SeriesName',
                'SeriesStartDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesStartDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesStartDate',
                'SeriesEndDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesEndDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesEndDate',
                'MatchID' => 'M.MatchID',
                'MatchIDLive' => 'M.MatchIDLive',
                'MatchTypeID' => 'M.MatchTypeID',
                'MatchNo' => 'M.MatchNo',
                'MatchLocation' => 'M.MatchLocation',
                'IsPlayerPointsUpdated' => 'M.IsPlayerPointsUpdated',
                'MatchScoreDetails' => 'M.MatchScoreDetails', 
                'IsPlayingXINotificationSent' => 'M.IsPlayingXINotificationSent',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") MatchStartDateTime',
                'CurrentDateTime'       =>  'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","'.DEFAULT_TIMEZONE.'"), "'.DATE_FORMAT.' ") CurrentDateTime',
                'MatchDate'             =>  'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","'.DEFAULT_TIMEZONE.'"), "%Y-%m-%d") MatchDate',
                'MatchTime'             =>  'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","'.DEFAULT_TIMEZONE.'"), "%H:%i:%s") MatchTime',
                'MatchStartDateTimeUTC' => 'M.MatchStartDateTime as MatchStartDateTimeUTC',
                'TeamIDLocal' => 'TL.TeamID AS TeamIDLocal',
                'TeamIDVisitor' => 'TV.TeamID AS TeamIDVisitor',
                'TeamGUIDLocal' => 'TL.TeamGUID AS TeamGUIDLocal',
                'TeamGUIDVisitor' => 'TV.TeamGUID AS TeamGUIDVisitor',
                'TeamIDLiveLocal' => 'TL.TeamIDLive AS TeamIDLiveLocal',
                'TeamIDLiveVisitor' => 'TV.TeamIDLive AS TeamIDLiveVisitor',
                'TeamNameLocal' => 'TL.TeamName AS TeamNameLocal',
                'TeamNameVisitor' => 'TV.TeamName AS TeamNameVisitor',
                'TeamNameShortLocal' => 'TL.TeamNameShort AS TeamNameShortLocal',
                'TeamNameShortVisitor' => 'TV.TeamNameShort AS TeamNameShortVisitor',
                'TeamFlagLocal' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag) as TeamFlagLocal',
                'TeamFlagVisitor' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag) as TeamFlagVisitor',
                'MyTotalJoinedContest' => '(SELECT COUNT(DISTINCT sports_contest_join.ContestID)
                                                FROM sports_contest_join
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND UserID= ' . @$Where['SessionUserID'] . ') MyTotalJoinedContest',
                'Status' => 'CASE E.StatusID
                                when "1" then "Pending"
                                when "2" then "Running"
                                when "3" then "Cancelled"
                                when "5" then "Completed"  
                                when "8" then "Abandoned"  
                                when "9" then "No Result"  
                                when "10" then "Reviewing"  
                            END as Status',
                'MatchType' => 'MT.MatchTypeName AS MatchType',
                'LastUpdateDiff' => 'IF(M.LastUpdatedOn IS NULL, 0, TIME_TO_SEC(TIMEDIFF("' . date('Y-m-d H:i:s') . '", M.LastUpdatedOn))) LastUpdateDiff',
                'isJoinedContest' => '(select count(*) from sports_contest_join where MatchID = M.MatchID AND UserID = "'.@$Where['SessionUserID'].'" AND E.StatusID=' . $Where['StatusID'] . ') as JoinedContests'
                );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('M.MatchGUID,TL.TeamName AS TeamNameLocal,TV.TeamName AS TeamNameVisitor,TL.TeamNameShort AS TeamNameShortLocal,TV.TeamNameShort AS TeamNameShortVisitor');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
            $this->db->from('tbl_entity E, sports_matches M, sports_teams TL, sports_teams TV');
        if (array_keys_exist($Params, array('SeriesID','SeriesGUID','SeriesIDLive','SeriesName','SeriesStartDate','SeriesEndDate'))) {
            $this->db->from('sports_series S');
            $this->db->where("M.SeriesID", "S.SeriesID", FALSE);
        }
        if (array_keys_exist($Params, array('MatchType'))) {
            $this->db->from('sports_set_match_types MT');
            $this->db->where("M.MatchTypeID", "MT.MatchTypeID", FALSE);
        }
        $this->db->where("M.MatchID", "E.EntityID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        if (!empty($Where['Keyword'])) {
            $this->db->group_start();
            $this->db->or_like("M.MatchNo", $Where['Keyword']);
            $this->db->or_like("M.MatchLocation", $Where['Keyword']);
            $this->db->or_like("TL.TeamName", $Where['Keyword']);
            $this->db->or_like("TV.TeamName", $Where['Keyword']);
            $this->db->or_like("TL.TeamNameShort", $Where['Keyword']);
            $this->db->or_like("TV.TeamNameShort", $Where['Keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("S.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['SeriesEndDate'])) {
            $this->db->where("S.SeriesEndDate", $Where['SeriesEndDate']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("M.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['PlayerStatsUpdate'])) {
            $this->db->where("M.PlayerStatsUpdate", $Where['PlayerStatsUpdate']);
        }
        if (!empty($Where['MatchTypeID'])) {
            $this->db->where("M.MatchTypeID", $Where['MatchTypeID']);
        }
        if (!empty($Where['IsPlayerPointsUpdated'])) {
            $this->db->where("M.IsPlayerPointsUpdated", $Where['IsPlayerPointsUpdated']);
        }
        if (!empty($Where['TeamIDLocal'])) {
            $this->db->where("M.TeamIDLocal", $Where['TeamIDLocal']);
        }
        if (!empty($Where['TeamIDVisitor'])) {
            $this->db->where("M.TeamIDVisitor", $Where['TeamIDVisitor']);
        }
        if (!empty($Where['MatchStartDateTime'])) {
            $this->db->where("M.MatchStartDateTime <=", $Where['MatchStartDateTime']);
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Today') {
            $this->db->where("DATE(M.MatchStartDateTime)", date('Y-m-d'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Yesterday') {
            $this->db->where("DATE(M.MatchStartDateTime) <=", date('Y-m-d'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'YesterdayToday') {
            $this->db->where_in("DATE(M.MatchStartDateTime)", array(date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))), date('Y-m-d')));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'MyJoinedMatch') {
            $this->db->where('EXISTS (select 1 from sports_contest_join J where J.MatchID = M.MatchID AND J.UserID=' . $Where['SessionUserID'] . ')');
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'LiveMatches') {
            $this->db->where_in("E.StatusID", array(2,10));
        }
        if (!empty($Where['StatusID']) && @$Where['Filter'] != 'LiveMatches') {
            $this->db->where_in("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['CronFilter']) && $Where['CronFilter'] == 'OneDayDiff') {
            $this->db->having("LastUpdateDiff", 0);
            $this->db->or_having("LastUpdateDiff >=", 86400); // 1 Day
        }
        if (!empty($Where['ExistingContests']) && !empty($Where['StatusID'])) {
            $this->db->where('EXISTS (select MatchID from sports_contest where MatchID = M.MatchID AND E.StatusID IN (' . $Where['StatusID'] . '))');
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            $this->db->order_by('E.StatusID', 'ASC');
            $this->db->order_by('M.MatchStartDateTime', 'ASC');
        }
        

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if(in_array('MatchScoreDetails',$Params)){
                        $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                    }
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if(in_array('MatchScoreDetails',$Params)){
                    $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To get all teams
     */

    function getTeams($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            
            $Field = '';
            $FieldArray = array(
                'TeamID' => 'T.TeamID',
                'StatusID' => 'E.StatusID',
                'TeamIDLive' => 'T.TeamIDLive',
                'TeamName' => 'T.TeamName',
                'TeamNameShort' => 'T.TeamNameShort',
                'TeamFlag' => 'T.TeamFlag',
                'TeamFlag' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",T.TeamFlag) as TeamFlag',
                'Status' => 'CASE E.StatusID
                                when "2" then "Active"
                                when "6" then "Inactive"
                                END as Status',
                'IsInternational' => 'T.IsInternational'
                );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('T.TeamName,T.TeamGUID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_teams T');
        $this->db->where("T.TeamID", "E.EntityID", FALSE);

        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = trim($Where['Keyword']);
            $this->db->group_start();
            $this->db->like("T.TeamName", $Where['Keyword']);
            $this->db->or_like("T.TeamNameShort", $Where['Keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['TeamID'])) {
            $this->db->where("T.TeamID", $Where['TeamID']);
        }
        if (!empty($Where['IsInternational'])) {
            $this->db->where("T.IsInternational", $Where['IsInternational']);
        }
        if (!empty($Where['TeamIDLive'])) {
            $this->db->where("T.TeamIDLive", $Where['TeamIDLive']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('T.TeamName', 'ASC');

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Return['Data']['Records'] = $Query->result_array();
                return $Return;
            } else {
                return $Query->row_array();
            }
        }
        return FALSE;
    }

    /*
      Description : To Update Team Flag
     */

    function updateTeamFlag($TeamID, $Input = array()) {
        $UpdateArray = array_filter(array(
                        'TeamFlag' => @$Input['TeamFlag'],
                        'TeamName' => @$Input['TeamName'],
                        'TeamNameShort' => @$Input['TeamNameShort']
                    ));
        if (!empty($UpdateArray)) {
            $this->db->where('TeamID', $TeamID);
            $this->db->limit(1);
            $this->db->update('sports_teams', $UpdateArray);
        }
        return TRUE;
    }

    /*
      Description: To get all players
     */

    function getPlayers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = $BestXIPlayers = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));

            $Field = '';
            $FieldArray = array(
                'SeriesGUID' => 'S.SeriesGUID',
                'TeamGUID' => 'T.TeamGUID',
                'TeamName' => 'T.TeamName',
                'TeamNameShort' => 'T.TeamNameShort',
                'TeamFlag' => 'T.TeamFlag',
                'PlayerID' => 'P.PlayerID',
                'PlayerIDLive' => 'P.PlayerIDLive',
                'PlayerRole' => 'TP.PlayerRole',
                'IsPlaying' => 'TP.IsPlaying',
                'TotalPoints' => 'TP.TotalPoints',
                'PointsData' => 'TP.PointsData',
                'SeriesID' => 'TP.SeriesID',
                'MatchID' => 'TP.MatchID',
                'TeamID' => 'TP.TeamID',
                'PlayerPic' => 'IF(P.PlayerPic IS NULL,CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png"),CONCAT("' . BASE_URL . '","uploads/PlayerPic/",P.PlayerPic)) AS PlayerPic',
                'PlayerCountry' => 'P.PlayerCountry',
                'PlayerBattingStyle' => 'P.PlayerBattingStyle',
                'PlayerBowlingStyle' => 'P.PlayerBowlingStyle',
                'PlayerBattingStats' => 'P.PlayerBattingStats',
                'PlayerBowlingStats' => 'P.PlayerBowlingStats',
                'PlayerSalary' => 'TP.PlayerSalary',
                'LastUpdateDiff' => 'IF(P.LastUpdatedOn IS NULL, 0, TIME_TO_SEC(TIMEDIFF("' . date('Y-m-d H:i:s') . '", P.LastUpdatedOn))) LastUpdateDiff',
                'MatchTypeID' => 'SSM.MatchTypeID',
                'Points' => 'UTP.Points',
                'MatchType' => 'SSM.MatchTypeName as MatchType',
                'TotalPointCredits' => '(SELECT IFNULL(SUM(`TotalPoints`),0) FROM `sports_team_players` WHERE `PlayerID` = TP.PlayerID AND `SeriesID` = TP.SeriesID) TotalPointCredits'
                );
            
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('P.PlayerGUID,P.PlayerName,P.PlayerID PlayerIDAsUse');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_players P');
        if (array_keys_exist($Params, array('TeamGUID', 'TeamName', 'TeamNameShort', 'TeamFlag', 'PlayerRole', 'IsPlaying', 'TotalPoints', 'PointsData', 'SeriesID', 'MatchID'))) {
            $this->db->from('sports_teams T,sports_matches M, sports_team_players TP,sports_set_match_types SSM');
            $this->db->where("P.PlayerID", "TP.PlayerID", FALSE);
            $this->db->where("TP.TeamID", "T.TeamID", FALSE);
            $this->db->where("TP.MatchID", "M.MatchID", FALSE);
            $this->db->where("M.MatchTypeID", "SSM.MatchTypeID", FALSE);
        }
        if (array_keys_exist($Params, array('SeriesGUID'))) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "TP.SeriesID", FALSE);
        }
        $this->db->where("P.PlayerID", "E.EntityID", FALSE);
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = trim($Where['Keyword']);
            $this->db->group_start();
            $this->db->like("P.PlayerName", $Where['Keyword']);
            $this->db->or_like("P.PlayerRole", $Where['Keyword']);
            $this->db->or_like("P.PlayerCountry", $Where['Keyword']);
            $this->db->or_like("P.PlayerBattingStyle", $Where['Keyword']);
            $this->db->or_like("P.PlayerBowlingStyle", $Where['Keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("TP.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['TeamID'])) {
            $this->db->where("TP.TeamID", $Where['TeamID']);
        }
        if (!empty($Where['IsPlaying'])) {
            $this->db->where("TP.IsPlaying", $Where['IsPlaying']);
        }
        if (!empty($Where['PlayerID'])) {
            $this->db->where("P.PlayerID", $Where['PlayerID']);
        }
        if (!empty($Where['PlayerGUID'])) {
            $this->db->where("P.PlayerGUID", $Where['PlayerGUID']);
        }
        if (!empty($Where['IsAdminSalaryUpdated'])) {
            $this->db->where("P.IsAdminSalaryUpdated", $Where['IsAdminSalaryUpdated']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['CronFilter']) && $Where['CronFilter'] == 'OneDayDiff') {
            $this->db->having("LastUpdateDiff", 0);
            $this->db->or_having("LastUpdateDiff >=", 86400); // 1 Day
        }

        /* Order By */
        if (!empty($Where['RandData'])) {
            $this->db->order_by($Where['RandData']);
        }else if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('P.PlayerName', 'ASC');
        }


        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }

        // $this->db->cache_on(); //Turn caching on
        $Query = $this->db->get();
        $MatchStatus = 0;
        if (!empty($Where['MatchID'])) {

            /* Get Match Status */
            $MatchQuery = $this->db->query('SELECT E.StatusID FROM `sports_matches` `M`,`tbl_entity` `E` WHERE M.`MatchID` = "'.$Where['MatchID'].'" AND M.MatchID = E.EntityID LIMIT 1');
            $MatchStatus = ($MatchQuery->num_rows() > 0) ? $MatchQuery->row()->StatusID : 0;
        }
        if ($Query->num_rows() > 0) {
            if(in_array('TopPlayer',$Params)){
                $BestPlayers = $this->getMatchBestPlayers(array('MatchID' => $Where['MatchID']));
                if(!empty($BestPlayers)){
                    $BestXIPlayers = array_column($BestPlayers['Data']['Records'], 'PlayerGUID');               
                }
            }
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if(in_array('TopPlayer',$Params)){
                        $Records[$key]['TopPlayer'] = (in_array($Record['PlayerGUID'],$BestXIPlayers)) ? 'Yes' : 'No';
                    }
                    if(in_array('PlayerBattingStats',$Params)){
                        $Records[$key]['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                    }
                    if(in_array('PlayerBowlingStats',$Params)){
                        $Records[$key]['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                    }
                    if(in_array('PointsData',$Params)){
                        $Records[$key]['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                    }
                    if(in_array('PointCredits',$Params)){
                        $Records[$key]['PointCredits'] = (in_array($MatchStatus,array(2,5,10))) ? @$Record['TotalPoints'] : @$Record['TotalPointCredits'];
                    }
                    if(in_array('MyTeamPlayer',$Params)){
                        $this->db->select('DISTINCT(SUTP.PlayerID)');
                        $this->db->where("JC.UserTeamID", "SUTP.UserTeamID", FALSE);
                        $this->db->where("SUT.UserTeamID", "SUTP.UserTeamID", FALSE);
                        $this->db->where('SUT.MatchID',$Where['MatchID']);
                        $this->db->where('SUT.UserID',$Where['UserID']);
                        $this->db->from('sports_contest_join JC,sports_users_teams SUT,sports_users_team_players SUTP');
                        $MyPlayers = $this->db->get()->result_array();
                        $MyPlayersIds = (!empty($MyPlayers)) ? array_column($MyPlayers,'PlayerID') : array();
                        $Records[$key]['MyPlayer'] = (in_array($Record['PlayerIDAsUse'],$MyPlayersIds)) ? 'Yes' : 'No';
                    }
                    if (in_array('PlayerSelectedPercent', $Params)) {
                        $TotalTeams = $this->db->query('Select count(UserTeamID) TotalTeams from sports_users_teams WHERE MatchID="' . $Where['MatchID'] . '"')->row()->TotalTeams;
                        $this->db->select('count(SUTP.PlayerID) TotalPlayer');
                        $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                        $this->db->where("SUTP.PlayerID", $Record['PlayerID']);
                        $this->db->where("SUTP.MatchID", $Where['MatchID']);
                        $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                        $Players = $this->db->get()->row();
                        $Records[$key]['PlayerSelectedPercent']  = ($TotalTeams > 0) ? strval(round((($Players->TotalPlayer * 100 ) / $TotalTeams), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $TotalTeams), 2)) : "0";
                    }
                    unset($Records[$key]['PlayerIDAsUse']);
                }

                /* Custom Sorting */
                if (!empty($Where['CustomOrderBy']) && !empty($Where['Sequence'])) {
                    $SortArr =array();
                    foreach ($Records as $Value) {
                      $SortArr[] = $Value[$Where['CustomOrderBy']]; // In Object
                    }
                    if($Where['Sequence'] == 'ASC'){
                      array_multisort($SortArr,SORT_ASC,$Records);
                    }else{
                      array_multisort($SortArr,SORT_DESC,$Records);
                    }
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if(in_array('TopPlayer',$Params)){
                    $Record['TopPlayer'] = (in_array($Record['PlayerGUID'],$BestXIPlayers)) ? 'Yes' : 'No'; 
                }
                if(in_array('PlayerBattingStats',$Params)){
                    $Record['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                }
                if(in_array('PlayerBowlingStats',$Params)){
                    $Record['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                }
                if(in_array('PointsData',$Params)){
                    $Record['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                }
                if(in_array('PointCredits',$Params)){
                    $Record['PointCredits'] = (in_array($MatchStatus,array(2,5,10))) ? @$Record['TotalPoints'] : @$Record['TotalPointCredits'];
                }
                if(in_array('MyTeamPlayer',$Params)){
                    $this->db->select('DISTINCT(SUTP.PlayerID)');
                    $this->db->where("JC.UserTeamID", "SUTP.UserTeamID", FALSE);
                    $this->db->where("SUT.UserTeamID", "SUTP.UserTeamID", FALSE);
                    $this->db->where('SUT.MatchID',$Where['MatchID']);
                    $this->db->where('SUT.UserID',$Where['UserID']);
                    $this->db->from('sports_contest_join JC,sports_users_teams SUT,sports_users_team_players SUTP');
                    $MyPlayers = $this->db->get()->result_array();
                    $MyPlayersIds = (!empty($MyPlayers)) ? array_column($MyPlayers,'PlayerID') : array();
                    $Record['MyPlayer'] = (in_array($Record['PlayerIDAsUse'],$MyPlayersIds)) ? 'Yes' : 'No';
                }
                unset($Record['PlayerIDAsUse']);
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: Use to get sports points.
     */

    function getPoints($Where = array()) {
        switch (@$Where['PointsCategory']) {
            case 'InPlay':
            $this->db->select('PointsT20InPlay PointsT20, PointsODIInPlay PointsODI, PointsTESTInPlay PointsTEST');
            break;
            case 'Reverse':
            $this->db->select('PointsT20Reverse PointsT20, PointsODIReverse PointsODI, PointsTESTReverse PointsTEST');
            break;
            default:
            $this->db->select('PointsT20,PointsODI,PointsTEST');
            break;
        }
        
        $this->db->select('PointsTypeGUID,PointsTypeDescprition,PointsTypeShortDescription,PointsType,PointsInningType,PointsScoringField,StatusID');
        $this->db->from('sports_setting_points');
        if (!empty($Where['StatusID'])) {
            $this->db->where("StatusID", $Where['StatusID']);
        }
        $this->db->order_by("PointsType", 'ASC');
        $TempOBJ = clone $this->db;
        $TempQ = $TempOBJ->get();
        $Return['Data']['TotalRecords'] = $TempQ->num_rows();
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            $Return['Data']['Records'] = $Query->result_array();
            return $Return;
        }
        return FALSE;
    }

    /*
      Description: Use to update points.
     */

    function updatePoints($Input = array()) {
        if (!empty($Input)) {
            $PointsCategory = ($Input['PointsCategory'] != 'Normal') ? $Input['PointsCategory'] : '';
            for ($i = 0; $i < count($Input['PointsT20']); $i++) {
                $updateArray[] = array(
                        'PointsTypeGUID' => $Input['PointsTypeGUID'][$i],
                        'PointsT20' . $PointsCategory => $Input['PointsT20'][$i],
                        'PointsTEST' . $PointsCategory => $Input['PointsTEST'][$i],
                        'PointsODI' . $PointsCategory => $Input['PointsODI'][$i]
                    );
            }

            /* Update points details to sports_setting_points table. */
            $this->db->update_batch('sports_setting_points', $updateArray, 'PointsTypeGUID');
        }
    }

    /*
      Description: Use to update player role.
     */

    function updatePlayerRole($PlayerID, $MatchID, $Input = array()) {
        if (!empty($Input)) {
            $this->db->where('PlayerID', $PlayerID);
            $this->db->where('MatchID', $MatchID);
            $this->db->limit(1);
            $this->db->update('sports_team_players', $Input);
        }
    }

    /*
      Description: Use to update player salary.
     */

    function updatePlayerSalary($Input = array(), $PlayerID) {
        if (!empty($Input)) {
            $UpdateData = array(
                'PlayerSalary' => json_encode(array(
                    'T20Credits' => @$Input['T20Credits'],
                    'T20iCredits' => @$Input['T20iCredits'],
                    'ODICredits' => @$Input['ODICredits'],
                    'TestCredits' => @$Input['TestCredits']
                    )),
                'IsAdminSalaryUpdated' => 'Yes'
                );

            $this->db->where('PlayerID', $PlayerID);
            $this->db->limit(1);
            $this->db->update('sports_players', $UpdateData);
        }
    }

/*
      Description: To Excecute curl request
     */

    function ExecuteCurl($Url, $Params = '')
    {
        $Curl = curl_init($Url);
        if (!empty($Params)) {
            curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);
        }
        curl_setopt($Curl, CURLOPT_HEADER, 0);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        $Response = curl_exec($Curl);
        curl_close($Curl);
        $Result = json_decode($Response);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $Response;
        } else {
            return gzdecode($Response);
        }
    }

    /*
      Description: To get access token
     */

    function getAccessToken()
    {
        $this->load->helper('file');
        return trim(preg_replace("/\r|\n/", "", (file_exists(SPORTS_FILE_PATH)) ? read_file(SPORTS_FILE_PATH) : $this->generateAccessToken()));
    }

    /*
      Description: To generate access token
     */

    function generateAccessToken()
    {
        /* For Sports Entity Api */
        if (SPORTS_API_NAME == 'ENTITY') {
            $Response = json_decode($this->ExecuteCurl(SPORTS_API_URL_ENTITY . '/v2/auth/', array('access_key' => SPORTS_API_ACCESS_KEY_ENTITY, 'secret_key' => SPORTS_API_SECRET_KEY_ENTITY, 'extend' => 1)), true);
            if ($Response['status'] == 'ok')
                $AccessToken = $Response['response']['token'];
        }

        /* For Sports Cricket Api */
        if (SPORTS_API_NAME == 'CRICKETAPI') {
            $Response = json_decode($this->ExecuteCurl(SPORTS_API_URL_CRICKETAPI . '/rest/v2/auth/', array('access_key' => SPORTS_API_ACCESS_KEY_CRICKETAPI, 'secret_key' => SPORTS_API_SECRET_KEY_CRICKETAPI, 'app_id' => SPORTS_API_APP_ID_CRICKETAPI, 'device_id' => SPORTS_API_DEVICE_ID_CRICKETAPI)), true);
            if ($Response['status'])
                $AccessToken = $Response['auth']['access_token'];
        }

        /* Update Access Token */
        $this->load->helper('file');
        write_file(SPORTS_FILE_PATH, $AccessToken, 'w');
        return trim(preg_replace("/\r|\n/", "", $AccessToken));
    }

    /*
      Description: To fetch sports api data
     */

    function callSportsAPI($ApiUrl)
    {
        $Response = json_decode($this->ExecuteCurl($ApiUrl . $this->getAccessToken()), true);
        if (@$Response['status'] == 'unauthorized' || @$Response['status_code'] == 403) {
            if(@$Response['status_msg'] == 'RequestLimitExceeds'){ // API Calling Limit Exceeds
                
                /* Request Limit Exceeds Respone */
                log_message('ERROR',"Request Limit Exceeds");
                return TRUE;
            }else {

                /* Re-generate token */
                $Response = json_decode($this->ExecuteCurl($ApiUrl . $this->generateAccessToken()), true);
            }
        }
        return $Response;
    }

    /*
      Description: To set series data (Entity API)
     */
    function getSeriesLiveEntity($CronID) {
        ini_set('max_execution_time', 120);

        /* Update Existing Series Status */
        $this->db->query('UPDATE sports_series AS S, tbl_entity AS E SET E.StatusID = 6 WHERE E.EntityID = S.SeriesID AND E.StatusID != 6 AND SeriesEndDate < "' . date('Y-m-d') . '"');

        $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/seasons/?token=');
        if (empty($Response['response']['items'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }

        $SeriesData = array();
        foreach ($Response['response']['items'] as $Value) {
            if (!in_array($Value['sid'], array(date('Y'), date('Y') . date('y') + 1, date('Y') - 1 . date('y'))))
                continue;

            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/' . $Value['competitions_url'] . '?per_page=50&token=');
            /* To get All Series Data */
            $SeriesIdsData = $this->db->query('SELECT GROUP_CONCAT(SeriesIDLive) AS SeriesIDsLive FROM sports_series')->row()->SeriesIDsLive;
            $SeriesIDsLive = array();
            if ($SeriesIdsData) {
                $SeriesIDsLive = explode(",", $SeriesIdsData);
            }

            foreach ($Response['response']['items'] as $Value) {
                if (in_array($Value['cid'], $SeriesIDsLive))
                    continue;

                /* Add series to entity table and get EntityID. */
                $SeriesGUID = get_guid();
                $SeriesData[] = array_filter(array(
                    'SeriesID' => $this->Entity_model->addEntity($SeriesGUID, array("EntityTypeID" => 7, "StatusID" => 2)),
                    'SeriesGUID' => $SeriesGUID,
                    'SeriesIDLive' => $Value['cid'],
                    'SeriesName' => $Value['title'],
                    'SeriesStartDate' => $Value['datestart'],
                    'SeriesEndDate' => $Value['dateend']
                ));
            }
        }
        if (!empty($SeriesData)) {
            $this->db->insert_batch('sports_series', $SeriesData);
        }
    }

    /*
      Description: To set matches data (Entity API)
     */

    function getMatchesLiveEntity($CronID) {
        ini_set('max_execution_time', 300);

        /* Get series data */
        $SeriesData = $this->getSeries('SeriesIDLive,SeriesID', array('StatusID' => 2, 'SeriesEndDate' => date('Y-m-d')), true, 0);
        if (!$SeriesData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        /* To get All Match Types */
        $MatchTypesData = $this->getMatchTypes();
        $MatchTypeIdsData = array_column($MatchTypesData, 'MatchTypeID', 'MatchTypeName');
        
        /* Get Live Matches Data */
        foreach ($SeriesData['Data']['Records'] as $SeriesValue) {
            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/competitions/' . $SeriesValue['SeriesIDLive'] . '/matches/?per_page=150&token=');

            if (empty($Response['response']['items']))
                continue;
            foreach ($Response['response']['items'] as $key => $Value) {

                // $this->db->trans_start();

                /* Managae Teams */
                $LocalTeam = $Value['teama'];
                $VisitorTeam = $Value['teamb'];
                $LocalTeamData = $VisitorTeamData = array();

                if ($VisitorTeam['name'] == "TBA")
                    continue;

                /* To check if local team is already exist */
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = ' . $LocalTeam['team_id'] . ' LIMIT 1');
                $TeamIDLocal = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamIDLocal) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamIDLocal = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $LocalTeamData[] = array(
                        'TeamID' => $TeamIDLocal,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $LocalTeam['team_id'],
                        'TeamName' => $LocalTeam['name'],
                        'TeamNameShort' => (!empty($LocalTeam['short_name'])) ? $LocalTeam['short_name'] : null,
                        'TeamFlag' => (!empty($LocalTeam['logo_url'])) ? $LocalTeam['logo_url'] : null
                    );
                }

                /* To check if visitor team is already exist */
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = ' . $VisitorTeam['team_id'] . ' LIMIT 1');
                $TeamIDVisitor = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamIDVisitor) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamIDVisitor = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $VisitorTeamData[] = array(
                        'TeamID' => $TeamIDVisitor,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $VisitorTeam['team_id'],
                        'TeamName' => $VisitorTeam['name'],
                        'TeamNameShort' => (!empty($VisitorTeam['short_name'])) ? $VisitorTeam['short_name'] : null,
                        'TeamFlag' => (!empty($VisitorTeam['logo_url'])) ? $VisitorTeam['logo_url'] : null
                    );
                }
                $TeamsData = array_merge($VisitorTeamData, $LocalTeamData);
                if (!empty($TeamsData)) {
                    $this->db->insert_batch('sports_teams', $TeamsData);
                }

                /* To check if match is already exist */
                $Query = $this->db->query('SELECT M.MatchID,E.StatusID FROM sports_matches M,tbl_entity E WHERE M.MatchID = E.EntityID AND M.MatchIDLive = ' . $Value['match_id'] . ' LIMIT 1');
                $MatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                if (!$MatchID) {

                    if(strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i', strtotime($Value['date_start'])))){
                        continue;
                    }

                    /* Add matches to entity table and get EntityID. */
                    $MatchGUID = get_guid();
                    $MatchesAPIData = array(
                        'MatchID' => $this->Entity_model->addEntity($MatchGUID, array("EntityTypeID" => 8, "StatusID" => 1)),
                        'MatchGUID' => $MatchGUID,
                        'MatchIDLive' => $Value['match_id'],
                        'SeriesID' => $SeriesValue['SeriesID'],
                        'MatchTypeID' => $MatchTypeIdsData[$Value['format_str']],
                        'MatchNo' => $Value['subtitle'],
                        'MatchLocation' => $Value['venue']['location'],
                        'TeamIDLocal' => $TeamIDLocal,
                        'TeamIDVisitor' => $TeamIDVisitor,
                        'MatchStartDateTime' => date('Y-m-d H:i', strtotime($Value['date_start']))
                    );
                    $this->db->insert('sports_matches', $MatchesAPIData);
                } else {

                    if ($Query->row()->StatusID != 1)
                        continue; // Pending Match

                    /* Update Match Data */
                    $MatchesAPIData = array(
                        'MatchNo' => $Value['subtitle'],
                        'MatchLocation' => $Value['venue']['location'],
                        'TeamIDLocal' => $TeamIDLocal,
                        'TeamIDVisitor' => $TeamIDVisitor,
                        'MatchStartDateTime' => date('Y-m-d H:i', strtotime($Value['date_start'])),
                        'LastUpdatedOn' => date('Y-m-d H:i:s')
                    );
                    $this->db->where('MatchID', $MatchID);
                    $this->db->limit(1);
                    $this->db->update('sports_matches', $MatchesAPIData);
                }

                // $this->db->trans_complete();
                // if ($this->db->trans_status() === false) {
                //     return false;
                // }
            }
        }
    }


    /*
      Description: To set players data match wise (Entity API)
     */

    function getPlayersLiveEntity($CronID) {
        ini_set('max_execution_time', 300);

        /* Get series data */
        $MatchData = $this->getMatches('MatchStartDateTime,MatchIDLive,MatchID,MatchType,SeriesIDLive,SeriesID,TeamIDLiveLocal,TeamIDLiveVisitor,LastUpdateDiff', array('StatusID' => array(1),'CronFilter' => 'OneDayDiff'), true, 1, 10);
        if (!$MatchData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        /* Player Roles */
        $PlayerRolesArr = array('bowl' => 'Bowler', 'bat' => 'Batsman', 'wkbat' => 'WicketKeeper', 'wk' => 'WicketKeeper', 'all' => 'AllRounder');
        foreach ($MatchData['Data']['Records'] as $Value) {

            $MatchID = $Value['MatchID'];
            $SeriesID = $Value['SeriesID'];
            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/competitions/' . $Value['SeriesIDLive'] . '/squads/' . $Value['MatchIDLive'] . '?token=');

            if (empty($Response['response']['squads']))
                continue;

            /* To check if any team is created */
            $TotalJoinedTeams = $this->db->query("SELECT COUNT(*) TotalJoinedTeams FROM `sports_users_teams` WHERE `MatchID` = ".$MatchID)->row()->TotalJoinedTeams;

            foreach ($Response['response']['squads'] as $SquadsValue) {
                $TeamID = $SquadsValue['team_id'];
                $Players = $SquadsValue['players'];
                $TeamPlayersData = array();
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $TeamID . '" LIMIT 1');
                $TeamID = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;

                if (!empty($Players)) {
                    foreach ($Players as $Player) {
                        if (isset($Player['pid']) && !empty($Player['pid'])) {

                            /* To check if player is already exist */
                            $Query = $this->db->query('SELECT PlayerID FROM sports_players WHERE PlayerIDLive = ' . $Player['pid'] . ' LIMIT 1');
                            $PlayerID = ($Query->num_rows() > 0) ? $Query->row()->PlayerID : false;
                            if (!$PlayerID) {
                                /* Add players to entity table and get EntityID. */
                                $PlayerGUID = get_guid();
                                $PlayerID = $this->Entity_model->addEntity($PlayerGUID, array("EntityTypeID" => 10, "StatusID" => 2));
                                $PlayersAPIData = array(
                                    'PlayerID' => $PlayerID,
                                    'PlayerGUID' => $PlayerGUID,
                                    'PlayerIDLive' => $Player['pid'],
                                    'PlayerName' => $Player['title'],
                                    'PlayerSalary' => $Player['fantasy_player_rating'],
                                    'PlayerCountry' => ($Player['country']) ? strtoupper($Player['country']) : null,
                                    'PlayerBattingStyle' => ($Player['batting_style']) ? $Player['batting_style'] : null,
                                    'PlayerBowlingStyle' => ($Player['bowling_style']) ? $Player['bowling_style'] : null
                                );
                                $this->db->insert('sports_players', $PlayersAPIData);
                            }
                            $Query = $this->db->query('SELECT MatchID FROM sports_team_players WHERE PlayerID = ' . $PlayerID . ' AND SeriesID = ' . $SeriesID . ' AND TeamID = ' . $TeamID . ' AND MatchID =' . $MatchID . ' LIMIT 1');
                            $IsMatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                            if (!$IsMatchID) {
                                $TeamPlayersData[] = array(
                                    'SeriesID' => $SeriesID,
                                    'MatchID' => $MatchID,
                                    'TeamID' => $TeamID,
                                    'PlayerID' => $PlayerID,
                                    'PlayerSalary' => $Player['fantasy_player_rating'],
                                    'IsPlaying' => "No",
                                    'PlayerRole' => $PlayerRolesArr[strtolower($Player['playing_role'])]
                                );
                            }else{
                                if($TotalJoinedTeams > 0){
                                    continue;
                                }

                                /* Update Fantasy Points */
                                $this->db->where('SeriesID', $SeriesID);
                                $this->db->where('MatchID', $MatchID);
                                $this->db->where('TeamID', $TeamID);
                                $this->db->where('PlayerID', $PlayerID);
                                $this->db->limit(1);
                                $this->db->update('sports_team_players', array('PlayerSalary' => $Player['fantasy_player_rating'], 'PlayerRole' => $PlayerRolesArr[strtolower($Player['playing_role'])]));
                            }
                        }
                    }
                }
                if (!empty($TeamPlayersData)) {
                    $this->db->insert_batch('sports_team_players', $TeamPlayersData);
                }
            }

            /* Update Last Updated Status */
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('LastUpdatedOn' => date('Y-m-d H:i:s')));
        }
    }

    /*
      Description: To set matches data (Cricket API)
     */

    function getMatchesLiveCricketApi($CronID)
    {
        ini_set('max_execution_time', 300);

        /* Get Live Matches Data */
        $DatesArr = array(date('Y-m'), date('Y-m', strtotime('+1 month')));
        foreach ($DatesArr as $DateValue) {
            $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/schedule/?date=' . $DateValue . '&access_token=');
            if (!$Response['status']) {
                $this->db->where('CronID', $CronID);
                $this->db->limit(1);
                $this->db->update('log_cron', array('CronStatus' => 'Exit'));
                exit;
            }else{
                $this->db->where('CronID', $CronID);
                $this->db->limit(1);
                $this->db->update('log_cron', array('CronResponse' => json_encode($Response, JSON_UNESCAPED_UNICODE)));
            }

            $LiveMatchesData = @$Response['data']['months'][0]['days'];
            if (empty($LiveMatchesData))
                continue;

            /* To get All Series Data */
            $SeriesIdsData = array();
            $SeriesData = $this->db->query('SELECT S.SeriesID,S.SeriesIDLive FROM tbl_entity E, sports_series S WHERE E.EntityID = S.SeriesID AND E.StatusID = 2');
            if ($SeriesData->num_rows() > 0) {
                $SeriesIdsData = array_column($SeriesIdsData->result_array(), 'SeriesID', 'SeriesIDLive');
            }

            /* To get All Match Types */
            $MatchTypesData  = $this->db->query('SELECT * FROM sports_set_match_types');
            $MatchTypeIdsData = array_column($MatchTypesData->result_array(), 'MatchTypeID', 'MatchTypeNameCricketAPI');

            foreach ($LiveMatchesData as $Value) {
                if (empty($Value['matches']))
                    continue;
                    
                foreach ($Value['matches'] as $MatchValue) {
                        
                    if(strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso'])))){
                        continue;
                    }
                        
                    $this->db->trans_start();
                        
                    /* Manage Series Data */
                    if (!isset($SeriesIdsData[$MatchValue['season']['key']])) {

                        /* Add series to entity table and get EntityID. */
                        $SeriesGUID = get_guid();
                        $SeriesID = $this->Entity_model->addEntity($SeriesGUID, array("EntityTypeID" => 7, "StatusID" => 2));
                        $SeriesData = array_filter(array(
                            'SeriesID' => $SeriesID,
                            'SeriesGUID' => $SeriesGUID,
                            'SeriesIDLive' => $MatchValue['season']['key'],
                            'SeriesName' => $MatchValue['season']['name']
                        ));

                        $this->db->insert('sports_series', $SeriesData);
                        $SeriesIdsData[$MatchValue['season']['key']] = $SeriesID;
                    } else {
                        $SeriesID = $SeriesIdsData[$MatchValue['season']['key']];
                    }

                    /* Manage Teams */
                    $LocalTeam = $MatchValue['teams']['a'];
                    $VisitorTeam = $MatchValue['teams']['b'];
                    $LocalTeamData = $VisitorTeamData = $TeamLiveIds = array();
                    if ($LocalTeam['key'] == 'tbc' || $VisitorTeam['key'] == 'tbc')
                        continue;

                    /* To check if local team is already exist */
                    $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $LocalTeam['key'] . '" LIMIT 1');
                    $TeamIDLocal = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                    if (!$TeamIDLocal) {

                        /* Add team to entity table and get EntityID. */
                        $TeamGUID = get_guid();
                        $TeamIDLocal = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                        $LocalTeamData[] = array(
                            'TeamID' => $TeamIDLocal,
                            'TeamGUID' => $TeamGUID,
                            'TeamIDLive' => $LocalTeam['key'],
                            'TeamName' => $LocalTeam['name'],
                            'TeamNameShort' => strtoupper($LocalTeam['key'])
                        );
                    }

                    /* To check if visitor team is already exist */
                    $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $VisitorTeam['key'] . '" LIMIT 1');
                    $TeamIDVisitor = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                    if (!$TeamIDVisitor) {

                        /* Add team to entity table and get EntityID. */
                        $TeamGUID = get_guid();
                        $TeamIDVisitor = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                        $VisitorTeamData[] = array(
                            'TeamID' => $TeamIDVisitor,
                            'TeamGUID' => $TeamGUID,
                            'TeamIDLive' => $VisitorTeam['key'],
                            'TeamName' => $VisitorTeam['name'],
                            'TeamNameShort' => strtoupper($VisitorTeam['key'])
                        );
                    }
                    $TeamsData = array_merge($VisitorTeamData, $LocalTeamData);
                    if (!empty($TeamsData)) {
                        $this->db->insert_batch('sports_teams', $TeamsData);
                    }

                    /* To check if match is already exist */
                    $Query = $this->db->query('SELECT M.MatchID,E.StatusID FROM sports_matches M,tbl_entity E WHERE M.MatchID = E.EntityID AND M.MatchIDLive = "' . $MatchValue['key'] . '" LIMIT 1');
                    $MatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                    if (!$MatchID) {

                        /* Add matches to entity table and get EntityID. */
                        $MatchGUID = get_guid();
                        $MatchStatusArr = array('completed' => 5, 'notstarted' => 1, 'started' => 2);
                        $MatchesAPIData = array(
                            'MatchID' => $this->Entity_model->addEntity($MatchGUID, array("EntityTypeID" => 8, "StatusID" => $MatchStatusArr[$MatchValue['status']])),
                            'MatchGUID' => $MatchGUID,
                            'MatchIDLive' => $MatchValue['key'],
                            'SeriesID' => $SeriesID,
                            'MatchTypeID' => $MatchTypeIdsData[$MatchValue['format']],
                            'MatchNo' => $MatchValue['related_name'],
                            'MatchLocation' => $MatchValue['venue'],
                            'TeamIDLocal' => $TeamIDLocal,
                            'TeamIDVisitor' => $TeamIDVisitor,
                            'MatchStartDateTime' => date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso']))
                        );
                        $this->db->insert('sports_matches', $MatchesAPIData);
                    } else {

                        if ($Query->row()->StatusID != 1)
                            continue; // Pending Match

                            /* Update Match Data */
                        $MatchesAPIData = array(
                            'MatchNo' => $MatchValue['related_name'],
                            'MatchLocation' => $MatchValue['venue'],
                            'TeamIDLocal' => $TeamIDLocal,
                            'TeamIDVisitor' => $TeamIDVisitor,
                            'MatchStartDateTime' => date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso'])),
                            'LastUpdatedOn' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('MatchID', $MatchID);
                        $this->db->limit(1);
                        $this->db->update('sports_matches', $MatchesAPIData);
                    }

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    }
                }
            }
        }
    }

    /*
      Description: To set players data (Cricket API)
     */

    function getPlayersLiveCricketApi($CronID)
    {
        ini_set('max_execution_time', 300);

        /* Get matches data */
        $MatchesData = $this->getMatches('MatchStartDateTime,MatchIDLive,MatchID,MatchType,SeriesIDLive,SeriesID,TeamIDLiveLocal,TeamIDLiveVisitor,LastUpdateDiff', array('StatusID' => array(1)), true, 1, 10);
        if (!$MatchesData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }else {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronResponse' => @json_encode(array('Query' => $this->db->last_query(), 'MatchesData' => $MatchesData), JSON_UNESCAPED_UNICODE)));
        }

        foreach ($MatchesData['Data']['Records'] as $Value) {

            /* Manage All Player Id's */
            $PlayersData = array();

            /* Get Both Teams */
            $TeamsArr = array($Value['TeamIDLiveLocal'] => $Value['SeriesIDLive'] . "_" . $Value['TeamIDLiveLocal'], $Value['TeamIDLiveVisitor'] => $Value['SeriesIDLive'] . "_" . $Value['TeamIDLiveVisitor']);
            foreach ($TeamsArr as $TeamKey => $TeamValue) {
                $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/season/' . $Value['SeriesIDLive'] . '/team/' . $TeamValue . '/?access_token=');

                /* Manage CRON API Response */
                $this->Utility_model->insertCronAPILogs($CronID, $Response);

                if (empty($Response['data']['players_key']))
                    continue;

                $this->db->trans_start();

                /* To check if visitor team is already exist */
                $IsNewTeam = false;
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $TeamKey . '" LIMIT 1');
                $TeamID = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamID) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamID = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $TeamData = array_filter(array(
                        'TeamID' => $TeamID,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $TeamKey,
                        'TeamName' => $Response['data']['name'],
                        'TeamNameShort' => strtoupper($TeamKey)
                    ));
                    $IsNewTeam = true;
                    $this->db->insert('sports_teams', $TeamData);
                }
                if (!$IsNewTeam) {

                    /* To get all match ids */
                    $Query = $this->db->query('SELECT MatchID FROM `sports_matches` WHERE `SeriesID` = ' . $Value['SeriesID'] . ' AND (`TeamIDLocal` = ' . $TeamID . ' OR `TeamIDVisitor` = ' . $TeamID . ')');
                    $MatchIds = ($Query->num_rows() > 0) ? array_column($Query->result_array(), 'MatchID') : array();
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    return false;
                }

                /* Insert All Players */
                foreach ($Response['data']['players_key'] as $PlayerIDLive) {

                    $this->db->trans_start();

                    /* To check if player is already exist */
                    $Query = $this->db->query('SELECT PlayerID FROM sports_players WHERE PlayerIDLive = "' . $PlayerIDLive . '" LIMIT 1');
                    $PlayerID = ($Query->num_rows() > 0) ? $Query->row()->PlayerID : false;
                    if (!$PlayerID) {

                        /* Add players to entity table and get EntityID. */
                        $PlayerGUID = get_guid();
                        $PlayerID = $this->Entity_model->addEntity($PlayerGUID, array("EntityTypeID" => 10, "StatusID" => 2));
                        $PlayersAPIData = array(
                            'PlayerID' => $PlayerID,
                            'PlayerGUID' => $PlayerGUID,
                            'PlayerIDLive' => $PlayerIDLive,
                            'PlayerName' => $Response['data']['players'][$PlayerIDLive]['name'],
                            'PlayerBattingStyle' => @$Response['data']['players'][$PlayerIDLive]['batting_style'][0],
                            'PlayerBowlingStyle' => @$Response['data']['players'][$PlayerIDLive]['bowling_style'][0],
                        );
                        $this->db->insert('sports_players', $PlayersAPIData);
                    }
                    $PlayersData[$PlayerIDLive] = $PlayerID;

                    /* To check If match player is already exist */
                    if (!$IsNewTeam && !empty($MatchIds)) {
                        $TeamPlayersData = array();
                        foreach ($MatchIds as $MatchID) {
                            $Query = $this->db->query('SELECT MatchID FROM sports_team_players WHERE PlayerID = ' . $PlayerID . ' AND SeriesID = ' . $Value['SeriesID'] . ' AND TeamID = ' . $TeamID . ' AND MatchID =' . $MatchID . ' LIMIT 1');
                            $IsMatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                            if (!$IsMatchID) {

                                /* Get Player Role */
                                $Keeper = $Response['data']['players'][$PlayerIDLive]['identified_roles']['keeper'];
                                $Batsman = $Response['data']['players'][$PlayerIDLive]['identified_roles']['batsman'];
                                $Bowler = $Response['data']['players'][$PlayerIDLive]['identified_roles']['bowler'];
                                $PlayerRole = ($Keeper == 1) ? 'WicketKeeper' : (($Batsman == 1 && $Bowler == 1) ? 'AllRounder' : ((empty($Batsman) && $Bowler == 1) ? 'Bowler' : ((empty($Bowler) && $Batsman == 1) ? 'Batsman' : '')));
                                $TeamPlayersData[] = array(
                                    'SeriesID' => $Value['SeriesID'],
                                    'MatchID' => $MatchID,
                                    'TeamID' => $TeamID,
                                    'PlayerID' => $PlayerID,
                                    'PlayerRole' => $PlayerRole
                                );
                            }
                        }
                        if (!empty($TeamPlayersData)) {
                            $this->db->insert_batch('sports_team_players', $TeamPlayersData);
                        }
                    }

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    }
                }
            }

            /* To check if any team is created */
            $TotalJoinedTeams = $this->db->query("SELECT COUNT(UserTeamType) TotalJoinedTeams FROM `sports_users_teams` WHERE `MatchID` = ".$Value['MatchID'])->row()->TotalJoinedTeams;
            if($TotalJoinedTeams == 0){

                /* Get Player Credit Points */
                $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v3/fantasy-match-credits/' . $Value['MatchIDLive'] . '/?access_token=');
                if (!empty($Response['data']['fantasy_points'])) {
                    foreach ($Response['data']['fantasy_points'] as $PlayerValue) {
                        $this->db->update('sports_team_players', array('PlayerSalary' => $PlayerValue['credit_value']), array('MatchID' => $Value['MatchID'],'PlayerID' => $PlayersData[$PlayerValue['player']]));
                    }
                }
            }

            /* Update Last Updated Status */
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('LastUpdatedOn' => date('Y-m-d H:i:s')));
        }
    }

    /*
      Description: To set player stats (Entity API)
     */

    function getPlayerStatsLiveEntity($CronID) {
        ini_set('max_execution_time', 120);

        /* To get All Player Stats Data */
        $MatchData = $this->getMatches('MatchID,MatchIDLive,SeriesIDLive,SeriesID', array('StatusID' => 5, 'PlayerStatsUpdate' => 'No'), true, 0);
        if (!$MatchData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        foreach ($MatchData['Data']['Records'] as $Value) {
            $PlayerData = $this->getPlayers('PlayerIDLive,PlayerID,MatchID', array('MatchID' => $Value['MatchID']), true, 0);
            if (empty($PlayerData))
                continue;

            foreach ($PlayerData['Data']['Records'] as $Player) {
                $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/players/' . $Player['PlayerIDLive'] . '/stats/?token=');
                $BattingStats = (!empty($Response['response']['batting'])) ? str_replace(array('odi', 't20', 't20i', 'lista', 'firstclass', 'test', 'others', 'womant20', 'womanodi', 'run4', 'run6', 'runs', 'balls', 'run50', 'notout', 'run100', 'strike', 'average', 'catches', 'highest', 'innings', 'matches', 'stumpings', 'match_id', 'inning_id'), array('ODI', 'T20', 'T20I', 'ListA', 'FirstClass', 'Test', 'Others', 'WomanT20', 'WomanODI', 'Fours', 'Sixes', 'Runs', 'Balls', 'Fifties', 'NotOut', 'Hundreds', 'StrikeRate', 'Average', 'Catches', 'HighestScore', 'Innings', 'Matches', 'Stumpings', 'MatchID', 'InningID'), json_encode($Response['response']['batting'])) : null;
                $BowlingStats = (!empty($Response['response']['bowling'])) ? str_replace(array('odi', 't20', 't20i', 'lista', 'firstclass', 'test', 'others', 'womant20', 'womanodi', 'match_id', 'inning_id', 'innings', 'matches', 'balls', 'overs', 'runs', 'wickets', 'bestinning', 'bestmatch', 'econ', 'average', 'strike', 'wicket4i', 'wicket5i', 'wicket10m'), array('ODI', 'T20', 'T20I', 'ListA', 'FirstClass', 'Test', 'Others', 'WomanT20', 'WomanODI', 'MatchID', 'InningID', 'Innings', 'Matches', 'Balls', 'Overs', 'Runs', 'Wickets', 'BestInning', 'BestMatch', 'Economy', 'Average', 'StrikeRate', 'FourPlusWicketsInSingleInning', 'FivePlusWicketsInSingleInning', 'TenPlusWicketsInSingleInning'), json_encode($Response['response']['bowling'])) : null;
                /* Update Player Stats */
                $PlayerStats = array(
                    'PlayerBattingStats' => $BattingStats,
                    'PlayerBowlingStats' => $BowlingStats,
                    'LastUpdatedOn' => date('Y-m-d H:i:s')
                );
                $this->db->where('PlayerID', $Player['PlayerID']);
                $this->db->limit(1);
                $this->db->update('sports_players', $PlayerStats);
            }

            $MatchUpdate = array(
                'PlayerStatsUpdate' => "Yes",
            );
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', $MatchUpdate);
        }
    }

    /*
      Description: To set player stats (Cricket API)
     */

    function getPlayerStatsLiveCricketApi($CronID)
    {

        ini_set('max_execution_time', 300);

        /* To get All Player Stats Data */
        $PlayersData = $this->getPlayers('PlayerIDLive,PlayerID,LastUpdateDiff', array('IsAdminSalaryUpdated' => 'No', 'CronFilter' => 'OneDayDiff'), true, 1, 25);

        if (!$PlayersData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }else {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronResponse' => @json_encode(array('Query' => $this->db->last_query(), 'PlayersData' => $PlayersData), JSON_UNESCAPED_UNICODE)));
        }

        foreach ($PlayersData['Data']['Records'] as $Value) {

            /* Call Player Stats API */
            $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/player/' . $Value['PlayerIDLive'] . '/league/icc/stats/?access_token=');


            /* Manage CRON API Response */
            $this->Utility_model->insertCronAPILogs($CronID, $Response);

            /* Manage Batting Stats */
            $BattingStats = new stdClass();
            $BowlingStats = new stdClass();

            /* Test Batting Stats */
            $BattingStats->Test = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['test']['batting']['matches'],
                'Innings' => @$Response['data']['player']['stats']['test']['batting']['innings'],
                'NotOut' => @$Response['data']['player']['stats']['test']['batting']['not_outs'],
                'Runs' => @$Response['data']['player']['stats']['test']['batting']['runs'],
                'Balls' => @$Response['data']['player']['stats']['test']['batting']['balls'],
                'HighestScore' => @$Response['data']['player']['stats']['test']['batting']['high_score'],
                'Hundreds' => @$Response['data']['player']['stats']['test']['batting']['hundreds'],
                'Fifties' => @$Response['data']['player']['stats']['test']['batting']['fifties'],
                'Fours' => @$Response['data']['player']['stats']['test']['batting']['fours'],
                'Sixes' => @$Response['data']['player']['stats']['test']['batting']['sixes'],
                'Average' => @$Response['data']['player']['stats']['test']['batting']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['test']['batting']['strike_rate'],
                'Catches' => @$Response['data']['player']['stats']['test']['fielding']['catches'],
                'Stumpings' => @$Response['data']['player']['stats']['test']['fielding']['stumpings']
            );
            /* ODI Batting Stats */
            $BattingStats->ODI = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['one-day']['batting']['matches'],
                'Innings' => @$Response['data']['player']['stats']['one-day']['batting']['innings'],
                'NotOut' => @$Response['data']['player']['stats']['one-day']['batting']['not_outs'],
                'Runs' => @$Response['data']['player']['stats']['one-day']['batting']['runs'],
                'Balls' => @$Response['data']['player']['stats']['one-day']['batting']['balls'],
                'HighestScore' => @$Response['data']['player']['stats']['one-day']['batting']['high_score'],
                'Hundreds' => @$Response['data']['player']['stats']['one-day']['batting']['hundreds'],
                'Fifties' => @$Response['data']['player']['stats']['one-day']['batting']['fifties'],
                'Fours' => @$Response['data']['player']['stats']['one-day']['batting']['fours'],
                'Sixes' => @$Response['data']['player']['stats']['one-day']['batting']['sixes'],
                'Average' => @$Response['data']['player']['stats']['one-day']['batting']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['one-day']['batting']['strike_rate'],
                'Catches' => @$Response['data']['player']['stats']['one-day']['fielding']['catches'],
                'Stumpings' => @$Response['data']['player']['stats']['one-day']['fielding']['stumpings']
            );
            /* T20 Batting Stats */
            $BattingStats->T20 = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['t20']['batting']['matches'],
                'Innings' => @$Response['data']['player']['stats']['t20']['batting']['innings'],
                'NotOut' => @$Response['data']['player']['stats']['t20']['batting']['not_outs'],
                'Runs' => @$Response['data']['player']['stats']['t20']['batting']['runs'],
                'Balls' => @$Response['data']['player']['stats']['t20']['batting']['balls'],
                'HighestScore' => @$Response['data']['player']['stats']['t20']['batting']['high_score'],
                'Hundreds' => @$Response['data']['player']['stats']['t20']['batting']['hundreds'],
                'Fifties' => @$Response['data']['player']['stats']['t20']['batting']['fifties'],
                'Fours' => @$Response['data']['player']['stats']['t20']['batting']['fours'],
                'Sixes' => @$Response['data']['player']['stats']['t20']['batting']['sixes'],
                'Average' => @$Response['data']['player']['stats']['t20']['batting']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['t20']['batting']['strike_rate'],
                'Catches' => @$Response['data']['player']['stats']['t20']['fielding']['catches'],
                'Stumpings' => @$Response['data']['player']['stats']['t20']['fielding']['stumpings']
            );

            /* Test Bowling Stats */
            $BowlingStats->Test = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['test']['bowling']['matches'],
                'Innings' => @$Response['data']['player']['stats']['test']['bowling']['innings'],
                'Balls' => @$Response['data']['player']['stats']['test']['bowling']['balls'],
                'Overs' => "",
                'Runs' => @$Response['data']['player']['stats']['test']['bowling']['runs'],
                'Wickets' => @$Response['data']['player']['stats']['test']['bowling']['wickets'],
                'BestInning' => @$Response['data']['player']['stats']['test']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['test']['bowling']['best_innings']['runs'],
                'BestMatch' => "",
                'Economy' => @$Response['data']['player']['stats']['test']['bowling']['economy'],
                'Average' => @$Response['data']['player']['stats']['test']['bowling']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['test']['bowling']['strike_rate'],
                'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['four_wickets'],
                'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['five_wickets'],
                'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['ten_wickets']
            );

            /* ODI Bowling Stats */
            $BowlingStats->ODI = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['one-day']['bowling']['matches'],
                'Innings' => @$Response['data']['player']['stats']['one-day']['bowling']['innings'],
                'Balls' => @$Response['data']['player']['stats']['one-day']['bowling']['balls'],
                'Overs' => "",
                'Runs' => @$Response['data']['player']['stats']['one-day']['bowling']['runs'],
                'Wickets' => @$Response['data']['player']['stats']['one-day']['bowling']['wickets'],
                'BestInning' => @$Response['data']['player']['stats']['one-day']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['one-day']['bowling']['best_innings']['runs'],
                'BestMatch' => "",
                'Economy' => @$Response['data']['player']['stats']['one-day']['bowling']['economy'],
                'Average' => @$Response['data']['player']['stats']['one-day']['bowling']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['one-day']['bowling']['strike_rate'],
                'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['four_wickets'],
                'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['five_wickets'],
                'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['ten_wickets']
            );

            /* T20 Bowling Stats */
            $BowlingStats->T20 = (object)array(
                'MatchID' => 0,
                'InningID' => 0,
                'Matches' => @$Response['data']['player']['stats']['t20']['bowling']['matches'],
                'Innings' => @$Response['data']['player']['stats']['t20']['bowling']['innings'],
                'Balls' => @$Response['data']['player']['stats']['t20']['bowling']['balls'],
                'Overs' => "",
                'Runs' => @$Response['data']['player']['stats']['t20']['bowling']['runs'],
                'Wickets' => @$Response['data']['player']['stats']['t20']['bowling']['wickets'],
                'BestInning' => @$Response['data']['player']['stats']['t20']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['t20']['bowling']['best_innings']['runs'],
                'BestMatch' => "",
                'Economy' => @$Response['data']['player']['stats']['t20']['bowling']['economy'],
                'Average' => @$Response['data']['player']['stats']['t20']['bowling']['average'],
                'StrikeRate' => @$Response['data']['player']['stats']['t20']['bowling']['strike_rate'],
                'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['four_wickets'],
                'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['five_wickets'],
                'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['ten_wickets']
            );

            /* Update Player Stats */
            $this->db->where('PlayerID', $Value['PlayerID']);
            $this->db->limit(1);
            $this->db->update('sports_players', array(
                                            'PlayerBattingStats' => json_encode($BattingStats),
                                            'PlayerBowlingStats' => json_encode($BowlingStats),
                                            'LastUpdatedOn'      => date('Y-m-d H:i:s')
                                        )
                            );
        }
    }

    /*
      Description: To get match live score (Entity API)
     */

    function getMatchScoreLiveEntity($CronID) {
        ini_set('max_execution_time', 120);
        

        /* Get Live Matches Data */
        $LiveMatches = $this->getMatches('MatchIDLive,MatchID,StatusID,IsPlayingXINotificationSent,MatchStartDateTime,Status', array('Filter' => 'YesterdayToday', 'StatusID' => array(1,2,10)), true, 1, 10);
        if (!$LiveMatches) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        $MatchStatus = array("live" => 2, "abandoned" => 8, "cancelled" => 3, "no result" => 9);
        $ContestStatus = array("live" => 2, "abandoned" => 5, "cancelled" => 3, "no result" => 5);
        $InningsStatus = array(1 => 'scheduled', 2 => 'completed', 3 => 'live', 4 => 'abandoned');

        foreach ($LiveMatches['Data']['Records'] as $Value) {

            if ($Value['Status'] == 'Pending' && (strtotime(date('Y-m-d H:i:s')) + 19800 >= strtotime($Value['MatchStartDateTime']))) { // +05:30

                /* Update Match Status */
                $this->db->where('EntityID', $Value['MatchID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 2));

                /* Update Contest Status */
                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 2 WHERE E.StatusID = 1 AND C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
            }

            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/matches/' . $Value['MatchIDLive'] . '/scorecard/?token=');

            /* Manage CRON API Response */
            $this->Utility_model->insertCronAPILogs($CronID, $Response);

            if (!empty($Response)) {
                if ($Response['status'] == "ok" && !empty($Response['response'])) {
                    $MatchStatusLive = strtolower($Response['response']['status_str']);
                    $MatchStatusLiveCheck = $Response['response']['status'];
                    $GameState = $Response['response']['game_state'];
                    $Verified = $Response['response']['verified'];
                    $StatusNote = strtolower($Response['response']['status_note']);
                    if ($GameState != 7 || $GameState != 6) {
                        if ($MatchStatusLiveCheck == 2 || $MatchStatusLiveCheck == 3) {
                            if($Response['response']['pre_squad'] && empty($Response['response']['innings'])){
                                
                                /** set is playing player 22 * */
                                $ResponsePlayerSquad = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/matches/' . $Value['MatchIDLive'] . '/squads/?token=');

                                if (!empty($ResponsePlayerSquad) && $ResponsePlayerSquad['status'] == 'ok') {
                                    $SquadTeamA = $ResponsePlayerSquad['response']['teama']['squads'];
                                    $SquadTeamB = $ResponsePlayerSquad['response']['teamb']['squads'];
                                    $PlayingPlayerIDs = array();
                                    foreach ($SquadTeamA as $TeamA) {
                                        if ($TeamA['playing11'] == 'true') {
                                            $PlayingPlayerIDs[] = $TeamA['player_id'];
                                        }
                                    }
                                    foreach ($SquadTeamB as $TeamB) {
                                        if ($TeamB['playing11'] == 'true') {
                                            $PlayingPlayerIDs[] = $TeamB['player_id'];
                                        }
                                    }
                                    
                                    $PlayersIdsData = array();
                                    $PlayersData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID', array('MatchID' => $Value['MatchID']), true, 0);
                                    if ($PlayersData) {
                                        $PlayersIdsData = array_column($PlayersData['Data']['Records'], 'PlayerID', 'PlayerIDLive');
                                    }
                                    foreach ($PlayingPlayerIDs as $IsPlayer) {
                                        $this->db->where('MatchID', $Value['MatchID']);
                                        $this->db->where('PlayerID', $PlayersIdsData[$IsPlayer]);
                                        $this->db->limit(1);
                                        $this->db->update('sports_team_players', array('IsPlaying' => "Yes"));
                                    }

                                    /* Update Playing XI Notification Status */
                                    $this->db->where('MatchID', $Value['MatchID']);
                                    $this->db->limit(1);
                                    $this->db->update('sports_matches', array('IsPlayingXINotificationSent' => "Yes"));
                                }
                            }

                            if ($MatchStatusLive == 'scheduled' || $MatchStatusLive == 'rescheduled')
                                continue;

                            if (empty($Response['response']['players']))
                                continue;

                            $LivePlayersData = array_column($Response['response']['players'], 'title', 'pid');
                            $MatchScoreDetails = $InningsData = array();
                            $MatchScoreDetails['StatusLive'] = $Response['response']['status_str'];
                            $MatchScoreDetails['StatusNote'] = $Response['response']['status_note'];
                            $MatchScoreDetails['TeamScoreLocal'] = array('Name' => $Response['response']['teama']['name'], 'ShortName' => $Response['response']['teama']['short_name'], 'LogoURL' => $Response['response']['teama']['logo_url'], 'Scores' => @$Response['response']['teama']['scores'], 'Overs' => @$Response['response']['teama']['overs']);
                            $MatchScoreDetails['TeamScoreVisitor'] = array('Name' => $Response['response']['teamb']['name'], 'ShortName' => $Response['response']['teamb']['short_name'], 'LogoURL' => $Response['response']['teamb']['logo_url'], 'Scores' => @$Response['response']['teamb']['scores'], 'Overs' => @$Response['response']['teamb']['overs']);
                            $MatchScoreDetails['MatchVenue'] = @$Response['response']['venue']['name'] . ", " . $Response['response']['venue']['location'];
                            $MatchScoreDetails['Result'] = @$Response['response']['result'];
                            $MatchScoreDetails['Toss'] = @$Response['response']['toss']['text'];
                            $MatchScoreDetails['ManOfTheMatchPlayer'] = @$Response['response']['man_of_the_match']['name'];
                            
                            foreach ($Response['response']['innings'] as $InningsValue) {
                                $BatsmanData = $BowlersData = $FielderData = $AllPlayingXI = array();

                                /* Manage Batsman Data */
                                foreach ($InningsValue['batsmen'] as $BatsmenValue) {
                                    $BatsmanData[] = array(
                                        'Name' => @$LivePlayersData[$BatsmenValue['batsman_id']],
                                        'PlayerIDLive' => $BatsmenValue['batsman_id'],
                                        'Role' => $BatsmenValue['role'],
                                        'Runs' => $BatsmenValue['runs'],
                                        'BallsFaced' => $BatsmenValue['balls_faced'],
                                        'Fours' => $BatsmenValue['fours'],
                                        'Sixes' => $BatsmenValue['sixes'],
                                        'HowOut' => $BatsmenValue['how_out'],
                                        'IsPlaying' => (!empty($BatsmenValue['dismissal'])) ? 'No' : (($BatsmenValue['balls_faced'] > 0) ? 'Yes' : ''),
                                        'StrikeRate' => ($BatsmenValue['strike_rate'] == "-") ? "" : $BatsmenValue['strike_rate']
                                    );
                                    $AllPlayingXI[$BatsmenValue['batsman_id']]['batting'] = array(
                                        'Name' => @$LivePlayersData[$BatsmenValue['batsman_id']],
                                        'PlayerIDLive' => $BatsmenValue['batsman_id'],
                                        'Role' => $BatsmenValue['role'],
                                        'Runs' => $BatsmenValue['runs'],
                                        'BallsFaced' => $BatsmenValue['balls_faced'],
                                        'Fours' => $BatsmenValue['fours'],
                                        'Sixes' => $BatsmenValue['sixes'],
                                        'HowOut' => $BatsmenValue['how_out'],
                                        'IsPlaying' => (!empty($BatsmenValue['dismissal'])) ? 'No' : (($BatsmenValue['balls_faced'] > 0) ? 'Yes' : ''),
                                        'StrikeRate' => ($BatsmenValue['strike_rate'] == "-") ? "" : $BatsmenValue['strike_rate']
                                    );
                                }

                                /* Manage Bowler Data */
                                foreach ($InningsValue['bowlers'] as $BowlersValue) {
                                    $BowlersData[] = array(
                                        'Name' => @$LivePlayersData[$BowlersValue['bowler_id']],
                                        'PlayerIDLive' => $BowlersValue['bowler_id'],
                                        'Overs' => $BowlersValue['overs'],
                                        'Maidens' => $BowlersValue['maidens'],
                                        'RunsConceded' => $BowlersValue['runs_conceded'],
                                        'Wickets' => $BowlersValue['wickets'],
                                        'NoBalls' => $BowlersValue['noballs'],
                                        'Wides' => $BowlersValue['wides'],
                                        'Economy' => $BowlersValue['econ']
                                    );
                                    $AllPlayingXI[$BowlersValue['bowler_id']]['bowling'] = array(
                                        'Name' => @$LivePlayersData[$BowlersValue['bowler_id']],
                                        'PlayerIDLive' => $BowlersValue['bowler_id'],
                                        'Overs' => $BowlersValue['overs'],
                                        'Maidens' => $BowlersValue['maidens'],
                                        'RunsConceded' => $BowlersValue['runs_conceded'],
                                        'Wickets' => $BowlersValue['wickets'],
                                        'NoBalls' => $BowlersValue['noballs'],
                                        'Wides' => $BowlersValue['wides'],
                                        'Economy' => $BowlersValue['econ']
                                    );
                                }

                                /* Manage Fielder Data */
                                foreach ($InningsValue['fielder'] as $FielderValue) {
                                    $FielderData[] = array(
                                        'Name' => $FielderValue['fielder_name'],
                                        'PlayerIDLive' => $FielderValue['fielder_id'],
                                        'Catches' => $FielderValue['catches'],
                                        'RunOutThrower' => $FielderValue['runout_thrower'],
                                        'RunOutCatcher' => $FielderValue['runout_catcher'],
                                        'RunOutDirectHit' => $FielderValue['runout_direct_hit'],
                                        'Stumping' => $FielderValue['stumping']
                                    );
                                    $AllPlayingXI[$FielderValue['fielder_id']]['fielding'] = array(
                                        'Name' => $FielderValue['fielder_name'],
                                        'PlayerIDLive' => $FielderValue['fielder_id'],
                                        'Catches' => $FielderValue['catches'],
                                        'RunOutThrower' => $FielderValue['runout_thrower'],
                                        'RunOutCatcher' => $FielderValue['runout_catcher'],
                                        'RunOutDirectHit' => $FielderValue['runout_direct_hit'],
                                        'Stumping' => $FielderValue['stumping']
                                    );
                                }

                                $InningsData[] = array(
                                    'Name' => $InningsValue['name'],
                                    'ShortName' => $InningsValue['short_name'],
                                    'Scores' => $InningsValue['scores'],
                                    'Status' => $InningsStatus[$InningsValue['status']],
                                    'ScoresFull' => $InningsValue['scores_full'],
                                    'BatsmanData' => $BatsmanData,
                                    'BowlersData' => $BowlersData,
                                    'FielderData' => $FielderData,
                                    'AllPlayingData' => $AllPlayingXI,
                                    'ExtraRuns' => array('Byes' => $InningsValue['extra_runs']['byes'], 'LegByes' => $InningsValue['extra_runs']['legbyes'], 'Wides' => $InningsValue['extra_runs']['wides'], 'NoBalls' => $InningsValue['extra_runs']['noballs'])
                                );
                            }
                            $MatchScoreDetails['Innings'] = $InningsData;
                            $MatchCompleteDateTime = date('Y-m-d H:i:s', strtotime("+2 hours"));

                            /* Update Match Data */
                            // $this->db->trans_start();

                            $this->db->where('MatchID', $Value['MatchID']);
                            $this->db->limit(1);
                            $this->db->update('sports_matches', array('MatchScoreDetails' => json_encode($MatchScoreDetails), 'MatchCompleteDateTime' => $MatchCompleteDateTime));
                            
                            if ($Value['StatusID'] != 2 && (strtotime(date('Y-m-d H:i:s')) + 19800 >= strtotime($Value['MatchStartDateTime']))) {

                                /* Update Contest Status */
                                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 2 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . '  AND E.StatusID != 3');

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 2));

                                $IsMatchLive = TRUE;
                            }
                            
                            if (strtolower($MatchStatusLive) == "completed" && $StatusNote != "abandoned") {

                                /* Update Final points before complete match */
				                $CronID = $this->Utility_model->insertCronLogs('getPlayerPoints');
				                $this->getPlayerPoints($CronID);
				                $this->Utility_model->updateCronLogs($CronID);

				                /* Update Final player points before complete match */
				                $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
				                $this->getJoinedContestPlayerPoints($CronID);
				                $this->Utility_model->updateCronLogs($CronID);

                                /* Update Contest Status */
                                if($Verified){
                                    $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 5 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . ' AND E.StatusID != 3');
                                }

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => ($Verified) ? 5 : 10));
                            }

                            /* Fire Auto Cancel Cron (Instant - Once Match Live) */
                            if(isset($IsMatchLive) && $IsMatchLive == TRUE){
                                $CronID = $this->Utility_model->insertCronLogs('autoCancelContest');
                                $this->autoCancelContest($CronID);
                                $this->Utility_model->updateCronLogs($CronID);
                            }

                            // $this->db->trans_complete();
                            // if ($this->db->trans_status() === false) {
                            //     return false;
                            // }
                        } else {
                            if ($StatusNote == 'no result') {
                                $MatchStatusLive = 'no result';
                            }
                            if ($MatchStatusLiveCheck == 4) {
                                $MatchStatusLive = 'abandoned';
                            }
                            if (strpos($StatusNote, 'abandoned') !== false) {
                                $MatchStatusLive = 'abandoned';
                            }
                            if (strpos($StatusNote, 'scheduled') !== false) {
                                $MatchStatusLive = 'scheduled';
                            }
                            // $this->db->trans_start();
                            if ($MatchStatusLiveCheck == 4) {
                                /* Update Contest Status */
                                //$this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 3 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . ' AND E.StatusID != 3');
                                $CronID = $this->Utility_model->insertCronLogs('autoCancelContest');
                                $this->Sports_model->autoCancelContest($CronID,'Abonded',$Value['MatchID']);
                                $this->Utility_model->updateCronLogs($CronID);

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 8));
                            }
                            // $this->db->trans_complete();
                            // if ($this->db->trans_status() === false) {
                            //     return false;
                            // }
                        }
                    }
                }
            }
        }
    }

    /*
      Description: To get match live score (Cricket API)
     */
    function getMatchScoreLiveCricketApi($CronID)
    {
        ini_set('max_execution_time', 300);
       
        /* Get Live Matches Data */
        $LiveMatches = $this->getMatches('MatchIDLive,MatchID,MatchStartDateTime,Status,IsPlayingXINotificationSent,TeamNameShortLocal,TeamNameShortVisitor', array('Filter'=> 'Yesterday','StatusID' => array(1,2,10)), true, 1, 10);
        if (!$LiveMatches) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }

        $MatchStatus   = array('completed' => 5, "started" => 2, "notstarted" => 9);
        $ContestStatus = array('completed' => 5, "started" => 2, "notstarted" => 9, "Abandoned" => 5, "Cancelled" => 3, "No Result" => 5);
        $InningsStatus = array(1 => 'Scheduled', 2 => 'Completed', 3 => 'Live', 4 => 'Abandoned');
        foreach ($LiveMatches['Data']['Records'] as $Value) {

            if ($Value['Status'] == 'Pending' && (strtotime(date('Y-m-d H:i:s')) + 19800 >= strtotime($Value['MatchStartDateTime']))) { // +05:30

                /* Update Match Status */
                $this->db->where('EntityID', $Value['MatchID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 2));

                /* Update Contest Status */
                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 2 WHERE E.StatusID = 1 AND C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
            }

            $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/match/' . $Value['MatchIDLive'] . '/?access_token=');
            
            /* Manage CRON API Response */
            $this->Utility_model->insertCronAPILogs($CronID, $Response);

            $MatchStatusLive     = @$Response['data']['card']['status'];
            $MatchStatusOverView = @$Response['data']['card']['status_overview'];

            /* Get Match Review Check Point */
            $MatchReviewCheckPoint = @$Response['data']['card']['data_review_checkpoint'];
           
            if ($Value['Status'] == 'Running' && $MatchStatusLive != 'notstarted') {

                /* Update Match Status */
                $this->db->where('EntityID', $Value['MatchID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => ($MatchStatusLive != 'completed') ? $MatchStatus[$MatchStatusLive] : (($MatchReviewCheckPoint == 'post-match-validated') ? 5 : 10)));

                /* Update Contest Status */
                if($MatchStatusLive != 'completed'){
                    $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = ' . $ContestStatus[$MatchStatusLive] . ' WHERE  E.StatusID = 2 AND C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
                }

            }
            
            /* Get Match Players Live */
            if (empty($Response['data']['card']['players']))
                continue;
            foreach ($Response['data']['card']['players'] as $PlayerIdLive => $Player) {
                $LivePlayersData[$PlayerIdLive] = $Player['name'];
            }

            /* Get Playing XI */
            $PlayingXIArr = array_merge(empty($Response['data']['card']['teams']['a']['match']['playing_xi']) ? array() : $Response['data']['card']['teams']['a']['match']['playing_xi'], empty($Response['data']['card']['teams']['b']['match']['playing_xi']) ? array() : $Response['data']['card']['teams']['b']['match']['playing_xi']);

            /* Get Match Players */
            $IsPlayingPlayers = 0;
            $PlayersData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID,IsPlaying', array('MatchID' => $Value['MatchID']), true, 0);
            if ($PlayersData) {
                $IsPlayingData = array_count_values(array_values(array_column($PlayersData['Data']['Records'], 'IsPlaying', 'PlayerID')));
                $IsPlayingPlayers = (isset($IsPlayingData['Yes'])) ? (int) $IsPlayingData['Yes'] : 0;
            }
            if (!empty($PlayingXIArr) && $IsPlayingPlayers < 22) {

                /* Get Match Players */
                $PlayersIdsData = array();
                if ($PlayersData) {
                    $PlayersIdsData = array_column($PlayersData['Data']['Records'], 'PlayerID', 'PlayerIDLive');
                }
                foreach ($PlayingXIArr as $PlayerIdLiveNew => $PlayerValue) {

                    /* Update Playing XI Status */
                    $this->db->where('MatchID', $Value['MatchID']);
                    $this->db->where('PlayerID', $PlayersIdsData[$PlayerValue]);
                    $this->db->limit(1);
                    $this->db->update('sports_team_players', array('IsPlaying' => "Yes"));
                }
            }

            if(!in_array($MatchStatusLive,array('started','completed'))){
                continue;
            }
            
            $MatchScoreDetails = $InningsData = array();
            $MatchScoreDetails['StatusLive'] = ($MatchStatusLive == 'started') ? 'Live' : (($MatchStatusLive == 'notstarted') ? 'Not Started' : 'Completed');
            $MatchScoreDetails['StatusNote'] = (!empty($Response['data']['card']['msgs']['result'])) ? $Response['data']['card']['msgs']['result'] : '';
            $MatchScoreDetails['TeamScoreLocal'] = array('Name' => $Response['data']['card']['teams']['a']['name'], 'ShortName' => $Response['data']['card']['teams']['a']['short_name'], 'LogoURL' => '', 'Scores' => @$Response['data']['card']['innings']['a_1']['runs'] . '/' . @$Response['data']['card']['innings']['a_1']['wickets'], 'Overs' => @$Response['data']['card']['innings']['a_1']['overs']);
            $MatchScoreDetails['TeamScoreVisitor'] = array('Name' => $Response['data']['card']['teams']['b']['name'], 'ShortName' => $Response['data']['card']['teams']['b']['short_name'], 'LogoURL' => '', 'Scores' => @$Response['data']['card']['innings']['b_1']['runs'] . '/' . @$Response['data']['card']['innings']['b_1']['wickets'], 'Overs' => @$Response['data']['card']['innings']['b_1']['overs']);
            $MatchScoreDetails['MatchVenue'] = @$Response['data']['card']['venue'];
            $MatchScoreDetails['Result'] = (!empty($Response['data']['cards']['msgs']['result'])) ? $Response['data']['cards']['msgs']['result'] : '';
            $MatchScoreDetails['Toss'] = @$Response['data']['card']['toss']['str'];
            $MatchScoreDetails['ManOfTheMatchPlayer'] = (!empty($LivePlayersData[@$Response['data']['card']['man_of_match']])) ? $LivePlayersData[@$Response['data']['card']['man_of_match']] : '';
            
            foreach ($Response['data']['card']['teams'] as $TeamKey => $TeamValue) {
                $AllPlayingXI = array();

                /* Manage Team Players Details */
                foreach ($Response['data']['card']['teams'][$TeamKey]['match']['playing_xi'] as $InningPlayer) {

                    /* Get Player Details */
                    $PlayerDetails = @$Response['data']['card']['players'][$InningPlayer];

                    /* Get Player Role */
                    $Keeper = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['keeper'];
                    $Batsman = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['batsman'];
                    $Bowler = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['bowler'];
                    $PlayerRole = ($Keeper == 1) ? 'WicketKeeper' : (($Batsman == 1 && $Bowler == 1) ? 'AllRounder' : ((empty($Batsman) && $Bowler == 1) ? 'Bowler' : ((empty($Bowler) && $Batsman == 1) ? 'Batsman' : '')));

                    /* Batting */
                    if (isset($PlayerDetails['match']['innings'][1]['batting']['balls'])) {

                        $AllPlayingXI[$InningPlayer]['batting'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => @$InningPlayer,
                            'Role' => @$PlayerRole,
                            'Runs' => (!empty($PlayerDetails['match']['innings'][1]['batting']['runs'])) ? $PlayerDetails['match']['innings'][1]['batting']['runs'] : "",
                            'BallsFaced' => (!empty($PlayerDetails['match']['innings'][1]['batting']['balls'])) ? $PlayerDetails['match']['innings'][1]['batting']['balls'] : "",
                            'Fours' => (!empty($PlayerDetails['match']['innings'][1]['batting']['fours'])) ? $PlayerDetails['match']['innings'][1]['batting']['fours'] : "",
                            'Sixes' => (!empty($PlayerDetails['match']['innings'][1]['batting']['sixes'])) ? $PlayerDetails['match']['innings'][1]['batting']['sixes'] : "",
                            'HowOut' => (!empty($PlayerDetails['match']['innings'][1]['batting']['out_str'])) ? $PlayerDetails['match']['innings'][1]['batting']['out_str'] : "",
                            'IsPlaying' => (@$PlayerDetails['match']['innings'][1]['batting']['dismissed'] == 1) ? 'No' : ((isset($PlayerDetails['match']['innings'][1]['batting']['balls'])) ? 'Yes' : ''),
                            'StrikeRate' => (!empty($PlayerDetails['match']['innings'][1]['batting']['strike_rate'])) ? $PlayerDetails['match']['innings'][1]['batting']['strike_rate'] : ""
                        );
                    }

                    /* Bowling */
                    if (!empty(@$PlayerDetails['match']['innings'][1]['bowling'])) {

                        $AllPlayingXI[$InningPlayer]['bowling'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => $InningPlayer,
                            'Overs' => (!empty($PlayerDetails['match']['innings'][1]['bowling']['overs'])) ? $PlayerDetails['match']['innings'][1]['bowling']['overs'] : '',
                            'Maidens' => (!empty($PlayerDetails['match']['innings'][1]['bowling']['maiden_overs'])) ? $PlayerDetails['match']['innings'][1]['bowling']['maiden_overs'] : '',
                            'RunsConceded' => (!empty($PlayerDetails['match']['innings'][1]['bowling']['runs'])) ? $PlayerDetails['match']['innings'][1]['bowling']['runs'] : '',
                            'Wickets' => (!empty($PlayerDetails['match']['innings'][1]['bowling']['wickets'])) ? $PlayerDetails['match']['innings'][1]['bowling']['wickets'] : '',
                            'NoBalls' => '',
                            'Wides' => '',
                            'Economy' => (!empty($PlayerDetails['match']['innings'][1]['bowling']['economy'])) ? $PlayerDetails['match']['innings'][1]['bowling']['economy'] : ''
                        );
                    }

                    /* Fielding */
                    if (!empty(@$PlayerDetails['match']['innings'][1]['fielding'])) {

                        $AllPlayingXI[$InningPlayer]['fielding'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => $InningPlayer,
                            'Catches' => (!empty($PlayerDetails['match']['innings'][1]['fielding']['catches'])) ? $PlayerDetails['match']['innings'][1]['fielding']['catches'] : '',
                            'RunOutThrower' => (!empty($PlayerDetails['match']['innings'][1]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][1]['fielding']['runouts'] : '',
                            'RunOutCatcher' => (!empty($PlayerDetails['match']['innings'][1]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][1]['fielding']['runouts'] : '',
                            'RunOutDirectHit' => (!empty($PlayerDetails['match']['innings'][1]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][1]['fielding']['runouts'] : '',
                            'Stumping' => (!empty($PlayerDetails['match']['innings'][1]['fielding']['stumbeds'])) ? $PlayerDetails['match']['innings'][1]['fielding']['stumbeds'] : ''
                        );
                    }
                }

                /* Get Team Details */
                $InningsData[] = array(
                    'Name' => $Response['data']['card']['teams'][$TeamKey]['name'] . ' inning',
                    'ShortName' => $Response['data']['card']['teams'][$TeamKey]['short_name'] . ' inn.',
                    'Scores' => $Response['data']['card']['innings'][$TeamKey.'_1']['runs'] . "/" . $Response['data']['card']['innings'][$TeamKey.'_1']['wickets'],
                    'Status' => '',
                    'ScoresFull' => $Response['data']['card']['innings'][$TeamKey.'_1']['runs'] . "/" . $Response['data']['card']['innings'][$TeamKey.'_1']['wickets'] . " (" . $Response['data']['card']['innings'][$TeamKey.'_1']['overs'] . " ov)",
                    'AllPlayingData' => $AllPlayingXI,
                    'ExtraRuns' => array('Byes' => @$Response['data']['card']['innings'][$TeamKey.'_1']['extras'], 'LegByes' => @$Response['data']['card']['innings'][$TeamKey.'_1']['extras'], 'Wides' => @$Response['data']['card']['innings'][$TeamKey.'_1']['wide'], 'NoBalls' => @$Response['data']['card']['innings'][$TeamKey.'_1']['noball'])
                );

            }
            $MatchScoreDetails['Innings'] = $InningsData;

            /* Update Match Data */
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('MatchScoreDetails' => json_encode($MatchScoreDetails)));

            if ($MatchStatusLive == 'completed') {

                if (strtolower($MatchStatusOverView) == "abandoned" || strtolower($MatchStatusOverView) == "canceled" || strtolower($MatchStatusOverView) == "play_suspended_unknown"){

                    /* Cancel Contest */
                    $CronID = $this->Utility_model->insertCronLogs('autoCancelContest');
                    $this->Sports_model->autoCancelContest($CronID,'Abonded',$Value['MatchID']);
                    $this->Utility_model->updateCronLogs($CronID);
                    
                    /* Update Match Status */
                    $this->db->where('EntityID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 8));

                }else{

                    /* Update Final points before complete match */
                    $CronID = $this->Utility_model->insertCronLogs('getPlayerPoints');
                    $this->getPlayerPoints($CronID);
                    $this->Utility_model->updateCronLogs($CronID);

                    /* Update Final player points before complete match */
                    $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
                    $this->getJoinedContestPlayerPoints($CronID);
                    $this->Utility_model->updateCronLogs($CronID);

                    /* Update Match Status */
                    $this->db->where('EntityID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => ($MatchReviewCheckPoint == 'post-match-validated') ? 5 : 10));

                    /* Update Contest Status */
                    if($MatchReviewCheckPoint == 'post-match-validated'){
                        $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 5 WHERE  E.StatusID = 2 AND  C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
                    }
                }
            }
        }
    }
    
    /*
      Description: To get player points
     */

    function getPlayerPoints($CronID,$MatchID = "")
    {

        ini_set('max_execution_time', 300);

        $LiveMatches = $this->getMatches('MatchID,MatchType,MatchScoreDetails,StatusID,IsPlayerPointsUpdated', array('Filter' => 'Yesterday', 'StatusID' => array(1,2,5,10),'IsPlayerPointsUpdated' => 'No','OrderBy' => 'M.MatchStartDateTime','Sequence' => 'DESC','MatchID' => $MatchID), true, 1, 10);

        /* Get Live Matches Data */
        if (!empty($LiveMatches)) {

            /* Get Points Data */
            $PointsDataArr = $this->getPoints();
            $StatringXIArr = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'StatringXI');
            $CaptainPointMPArr = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'CaptainPointMP');
            $ViceCaptainPointMPArr = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'ViceCaptainPointMP');
            $BattingMinimumRunsArr = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'BattingMinimumRuns');
            $MinimumRunScoreStrikeRate = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'MinimumRunScoreStrikeRate');
            $MinimumOverEconomyRate = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'MinimumOverEconomyRate');
            $MatchTypes = array('ODI' => 'PointsODI','List A' => 'PointsODI', 'T20' => 'PointsT20', 'T20I' => 'PointsT20', 'Test' => 'PointsTEST', 'Woman ODI' => 'PointsODI', 'Woman T20' => 'PointsT20');

            /* Sorting Keys */
            $PointsSortingKeys = array('SB', 'RUNS', '4s', '6s', 'STB', 'BTB', 'DUCK', 'WK', 'MD', 'EB', 'BWB', 'RO', 'ST', 'CT');
            foreach ($LiveMatches['Data']['Records'] as $Value) {
                if (empty((array)$Value['MatchScoreDetails']))
                    continue;

                $StatringXIPoints = (isset($StatringXIArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($StatringXIArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $CaptainPointMPPoints = (isset($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $ViceCaptainPointMPPoints = (isset($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "1.5";
                $BattingMinimumRunsPoints = (isset($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]]) : "15";
                $MinimumRunScoreStrikeRate = (isset($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]]) : "10";
                $MinimumOverEconomyRate = (isset($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]]) : "1";

                /* Get Match Players */
                $MatchPlayers = $this->getPlayers('PlayerIDLive,PlayerID,MatchID,PlayerRole', array('MatchID' => $Value['MatchID'], 'IsPlaying' => 'Yes'), true, 0);
                if (!$MatchPlayers){
                    continue;
                }

                /* Get Match Live Score Data */
                $AllPalyers = array();
                foreach ($Value['MatchScoreDetails']['Innings'] as $PlayerID) {
                    foreach($PlayerID['AllPlayingData'] as $PlayerKey => $PlayerSubValue){
                        if (isset($PlayerSubValue['batting'])) {
                            $AllPalyers[$PlayerKey]['Name'] = $PlayerSubValue['batting']['Name'];
                            $AllPalyers[$PlayerKey]['PlayerIDLive'] = $PlayerSubValue['batting']['PlayerIDLive'];
                            $AllPalyers[$PlayerKey]['Role'] = $PlayerSubValue['batting']['Role'];
                            $AllPalyers[$PlayerKey]['Runs'] = $PlayerSubValue['batting']['Runs'];
                            $AllPalyers[$PlayerKey]['BallsFaced'] = $PlayerSubValue['batting']['BallsFaced'];
                            $AllPalyers[$PlayerKey]['Fours'] = $PlayerSubValue['batting']['Fours'];
                            $AllPalyers[$PlayerKey]['Sixes'] = $PlayerSubValue['batting']['Sixes'];
                            $AllPalyers[$PlayerKey]['HowOut'] = $PlayerSubValue['batting']['HowOut'];
                            $AllPalyers[$PlayerKey]['IsPlaying'] = $PlayerSubValue['batting']['IsPlaying'];
                            $AllPalyers[$PlayerKey]['StrikeRate'] = $PlayerSubValue['batting']['StrikeRate'];
                        }
                        if (isset($PlayerSubValue['bowling'])) {
                            $AllPalyers[$PlayerKey]['Name'] = $PlayerSubValue['bowling']['Name'];
                            $AllPalyers[$PlayerKey]['PlayerIDLive'] = $PlayerSubValue['bowling']['PlayerIDLive'];
                            $AllPalyers[$PlayerKey]['Overs'] = $PlayerSubValue['bowling']['Overs'];
                            $AllPalyers[$PlayerKey]['Maidens'] = $PlayerSubValue['bowling']['Maidens'];
                            $AllPalyers[$PlayerKey]['RunsConceded'] = $PlayerSubValue['bowling']['RunsConceded'];
                            $AllPalyers[$PlayerKey]['Wickets'] = $PlayerSubValue['bowling']['Wickets'];
                            $AllPalyers[$PlayerKey]['NoBalls'] = $PlayerSubValue['bowling']['NoBalls'];
                            $AllPalyers[$PlayerKey]['Wides'] = $PlayerSubValue['bowling']['Wides'];
                            $AllPalyers[$PlayerKey]['Economy'] = $PlayerSubValue['bowling']['Economy'];
                        }
                        if (isset($PlayerSubValue['fielding'])) {
                            $AllPalyers[$PlayerKey]['Name'] = $PlayerSubValue['fielding']['Name'];
                            $AllPalyers[$PlayerKey]['PlayerIDLive'] = $PlayerSubValue['fielding']['PlayerIDLive'];
                            $AllPalyers[$PlayerKey]['Catches'] = $PlayerSubValue['fielding']['Catches'];
                            $AllPalyers[$PlayerKey]['RunOutThrower'] = $PlayerSubValue['fielding']['RunOutThrower'];
                            $AllPalyers[$PlayerKey]['RunOutCatcher'] = $PlayerSubValue['fielding']['RunOutCatcher'];
                            $AllPalyers[$PlayerKey]['RunOutDirectHit'] = $PlayerSubValue['fielding']['RunOutDirectHit'];
                            $AllPalyers[$PlayerKey]['Stumping'] = $PlayerSubValue['fielding']['Stumping'];
                        }
                    }
                }
                if(empty($AllPalyers)){
                    continue;
                }

                $AllPlayersLiveIds = array_keys($AllPalyers);
                foreach ($MatchPlayers['Data']['Records'] as $PlayerValue) {

                    $this->IsStrikeRate = $this->IsEconomyRate = $this->IsBattingState = $this->IsBowlingState = $PlayerTotalPoints = "0";
                    $this->defaultStrikeRatePoints = $this->defaultEconomyRatePoints = $this->defaultBattingPoints = $this->defaultBowlingPoints = $PointsData = array();
                    $PointsData['SB'] = array('PointsTypeGUID' => 'StatringXI', 'PointsTypeShortDescription' => 'SB', 'DefinedPoints' => $StatringXIPoints, 'ScoreValue' => "1", 'CalculatedPoints' => $StatringXIPoints);
                    $ScoreData = $AllPalyers[$PlayerValue['PlayerIDLive']];

                    /* To Check Player Is Played Or Not */
                    if (in_array($PlayerValue['PlayerIDLive'], $AllPlayersLiveIds) && !empty($ScoreData)) {
                        foreach ($PointsDataArr['Data']['Records'] as $PointValue) {
                            if (IS_VICECAPTAIN) {
                                if (in_array($PointValue['PointsTypeGUID'], array('BattingMinimumRuns', 'CaptainPointMP', 'StatringXI', 'ViceCaptainPointMP')))
                                    continue;
                            } else {
                                if (in_array($PointValue['PointsTypeGUID'], array('BattingMinimumRuns', 'CaptainPointMP', 'StatringXI')))
                                    continue;
                            }
                            $allKeys = array_keys($ScoreData);
                            if (($DeleteKey = array_search('Name', $allKeys)) !== false) {
                                unset($allKeys[$DeleteKey]);
                            }
                            if (($DeleteKey = array_search('PlayerIDLive', $allKeys)) !== false) {
                                unset($allKeys[$DeleteKey]);
                            }

                            /** calculate points * */
                            foreach ($allKeys as $ScoreValue) {
                                $calculatePoints = $this->calculatePoints($PointValue, $Value['MatchType'], $MinimumRunScoreStrikeRate, @$ScoreData[$PointValue['PointsScoringField']], @$ScoreData['BallsFaced'], @$ScoreData['Overs'], @$ScoreData['Runs'], $MinimumOverEconomyRate,$PlayerValue['PlayerRole']);
                                if (is_array($calculatePoints)) {
                                    $PointsData[$calculatePoints['PointsTypeShortDescription']] = array('PointsTypeGUID' => $calculatePoints['PointsTypeGUID'], 'PointsTypeShortDescription' => $calculatePoints['PointsTypeShortDescription'], 'DefinedPoints' => strval($calculatePoints['DefinedPoints']), 'ScoreValue' => strval($calculatePoints['ScoreValue']), 'CalculatedPoints' => strval($calculatePoints['CalculatedPoints']));
                                }
                            }
                        }

                        /* Manage Single Strike Rate & Economy Rate & Bowling & Batting State*/
                        if ($this->IsStrikeRate == 0) {
                            $PointsData['STB'] = $this->defaultStrikeRatePoints;
                        }
                        if ($this->IsEconomyRate == 0) {
                            $PointsData['EB'] = $this->defaultEconomyRatePoints;
                        }
                        if ($this->IsBattingState == 0) {
                            $PointsData['BTB'] = $this->defaultBattingPoints;
                        }
                        if ($this->IsBowlingState == 0) {
                            $PointsData['BWB'] = $this->defaultBowlingPoints;
                        }
                    } else {
                        $PointsData['SB'] = array('PointsTypeGUID' => 'StatringXI', 'PointsTypeShortDescription' => 'SB', 'DefinedPoints' => $StatringXIPoints, 'ScoreValue' => "1", 'CalculatedPoints' => $StatringXIPoints);
                        foreach ($PointsDataArr['Data']['Records'] as $PointValue) {
                            if (IS_VICECAPTAIN) {
                                if (in_array($PointValue['PointsTypeGUID'], array('BattingMinimumRuns', 'CaptainPointMP', 'StatringXI', 'ViceCaptainPointMP')))
                                    continue;
                            } else {
                                if (in_array($PointValue['PointsTypeGUID'], array('BattingMinimumRuns', 'CaptainPointMP', 'StatringXI')))
                                    continue;
                            }
                            if (in_array($PointValue['PointsTypeGUID'], array('StrikeRate50N74.99', 'StrikeRate75N99.99', 'StrikeRate100N149.99', 'StrikeRate150N199.99', 'StrikeRate200NMore', 'EconomyRate5.01N7.00Balls', 'EconomyRate5.01N8.00Balls', 'EconomyRate7.01N10.00Balls', 'EconomyRate8.01N10.00Balls', 'EconomyRate10.01N12.00Balls', 'EconomyRateAbove12.1Balls', 'FourWickets', 'FiveWickets', 'SixWickets', 'SevenWicketsMore', 'EightWicketsMore', 'For50runs', 'For100runs', 'For150runs', 'For200runs', 'For300runs','MinimumRunScoreStrikeRate','MinimumOverEconomyRate')))
                                continue;
                            $PointsData[$PointValue['PointsTypeShortDescription']] = array('PointsTypeGUID' => $PointValue['PointsTypeGUID'], 'PointsTypeShortDescription' => $PointValue['PointsTypeShortDescription'], 'DefinedPoints' => "0", 'ScoreValue' => "0", 'CalculatedPoints' => "0");
                        }
                    }

                    /* Sort Points Keys Data */
                    $OrderedArray = array();
                    foreach ($PointsSortingKeys as $SortValue) {
                        unset($PointsData[$SortValue]['PointsTypeShortDescription']);
                        $OrderedArray[] = $PointsData[$SortValue];
                    }
                    $PointsData = $OrderedArray;

                    /* Calculate Total Points */
                    if (!empty($PointsData)) {
                        foreach ($PointsData as $PointValue) {
                            if ($PointValue['CalculatedPoints'] > 0) {
                                $PlayerTotalPoints += $PointValue['CalculatedPoints'];
                            } else {
                                $PlayerTotalPoints = $PlayerTotalPoints - abs($PointValue['CalculatedPoints']);
                            }
                        }
                    }

                    /* Update Player Points Data */
                    $this->db->where('MatchID', $Value['MatchID']);
                    $this->db->where('PlayerID', $PlayerValue['PlayerID']);
                    $this->db->limit(1);
                    $this->db->update('sports_team_players',  array_filter(array(
                                                                'TotalPoints' => $PlayerTotalPoints,
                                                                'PointsData' => (!empty($PointsData)) ? json_encode($PointsData) : null
                                                            ))
                                    );

                }

                /* Update Final player points before complete match */
                $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
                $this->getJoinedContestPlayerPoints($CronID,array(2),$Value['MatchID']);
                $this->Utility_model->updateCronLogs($CronID);

                /* Update Match Player Points Status */
                if($Value['StatusID'] == 5){
                    $this->db->where('MatchID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('sports_matches', array('IsPlayerPointsUpdated' => 'Yes'));

                    /* Update Final player points before complete match */
	                $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
	                $this->getJoinedContestPlayerPoints($CronID,array(2,5));
	                $this->Utility_model->updateCronLogs($CronID);
                }
            }
        }
    }

    /*
      Description: To calculate points according to keys
     */

    function calculatePoints($Points = array(), $MatchType, $BattingMinimumRuns, $ScoreValue, $BallsFaced = 0, $Overs = 0, $Runs = 0, $MinimumOverEconomyRate = 0,$PlayerRole)
    {
        /* Match Types */
        $MatchTypes = array('ODI' => 'PointsODI','List A' => 'PointsODI', 'T20' => 'PointsT20', 'T20I' => 'PointsT20', 'Test' => 'PointsTEST', 'Woman ODI' => 'PointsODI', 'Woman T20' => 'PointsT20');
        $MatchTypeField = $MatchTypes[$MatchType];
        $PlayerPoints = array('PointsTypeGUID' => $Points['PointsTypeGUID'], 'PointsTypeShortDescription' => $Points['PointsTypeShortDescription'], 'DefinedPoints' => strval($Points[$MatchTypeField]), 'ScoreValue' => (!empty($ScoreValue)) ? strval($ScoreValue) : "0");
        switch ($Points['PointsTypeGUID']) {
            case 'ThreeWickets':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue == 3) ? strval($Points[$MatchTypeField]) : "0";
                $this->defaultBowlingPoints = $PlayerPoints;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'FourWickets':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue == 4) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'FiveWickets':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue == 5) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'SixWickets':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue == 6) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'SevenWicketsMore':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 7) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'EightWicketsMore':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 8) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBowlingState == 0) {
                    $this->IsBowlingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'RunOUT':
            case 'Stumping':
            case 'Four':
            case 'Six':
            case 'EveryRunScored':
            case 'Catch':
            case 'Wicket':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue > 0) ? $Points[$MatchTypeField] * $ScoreValue : 0;
                return $PlayerPoints;
                break;
            case 'Maiden':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue > 0) ? $Points[$MatchTypeField] * $ScoreValue : 0;
                return $PlayerPoints;
                break;
            case 'Duck':
                if ($ScoreValue <= 0 && $PlayerRole != 'Bowler') {
                    $PlayerPoints['CalculatedPoints'] = ($BallsFaced >= 1) ? $Points[$MatchTypeField] : 0;
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                return $PlayerPoints;
                break;
            case 'StrikeRate0N49.99':
                $PlayerPoints['CalculatedPoints'] = "0";
                $this->defaultStrikeRatePoints = $PlayerPoints;
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 0.1 && $ScoreValue <= 49.99) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'StrikeRate50N74.99':
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 50 && $ScoreValue <= 74.99) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'StrikeRate75N99.99':
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 75 && $ScoreValue <= 99.99) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'StrikeRate100N149.99':
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 100 && $ScoreValue <= 149.99) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'StrikeRate150N199.99':
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 150 && $ScoreValue <= 199.99) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'StrikeRate200NMore':
                if ($Runs >= $BattingMinimumRuns) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 200) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsStrikeRate == 0) {
                        $this->IsStrikeRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate0N5Balls':
                $PlayerPoints['CalculatedPoints'] = "0";
                $this->defaultEconomyRatePoints = $PlayerPoints;
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 0.1 && $ScoreValue <= 5) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate5.01N7.00Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 5.01 && $ScoreValue <= 7) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate5.01N8.00Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 5.01 && $ScoreValue <= 8) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate7.01N10.00Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 7.01 && $ScoreValue <= 10) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate8.01N10.00Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 8.01 && $ScoreValue <= 10) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRate10.01N12.00Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 10.01 && $ScoreValue <= 12) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'EconomyRateAbove12.1Balls':
                if ($Overs >= $MinimumOverEconomyRate) {
                    $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 12.1) ? $Points[$MatchTypeField] : 0;
                    if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsEconomyRate == 0) {
                        $this->IsEconomyRate = 1;
                        return $PlayerPoints;
                    }
                } else {
                    $PlayerPoints['CalculatedPoints'] = 0;
                }
                break;
            case 'For30runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 30 && $ScoreValue < 50) ? strval($Points[$MatchTypeField]) : "0";
                $this->defaultBattingPoints = $PlayerPoints;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'For50runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 50 && $ScoreValue < 100) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'For100runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 100 && $ScoreValue < 150) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'For150runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 150 && $ScoreValue < 200) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'For200runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 200 && $ScoreValue < 300) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            case 'For300runs':
                $PlayerPoints['CalculatedPoints'] = ($ScoreValue >= 300) ? $Points[$MatchTypeField] : 0;
                if ($PlayerPoints['CalculatedPoints'] != 0 && $this->IsBattingState == 0) {
                    $this->IsBattingState = 1;
                    return $PlayerPoints;
                }
                break;
            default:
                return false;
                break;
        }
    }

    /*
      Description: Find sub arrays from multidimensional array
     */
    function findSubArray($DataArray, $keyName, $Value)
    {
        $Data = array();
        foreach ($DataArray as $Row) {
            if ($Row[$keyName] == $Value)
                $Data[] = $Row;
        }
        return $Data;
    }

    /*
      Description: To get joined contest player points
     */
    function getJoinedContestPlayerPoints($CronID,$StatusArr = array(2),$MatchID = "") {

       ini_set('max_execution_time', 300);

        /* Get Matches Live */
        if($MatchID){
            $LiveMatcheContest = $this->db->query('SELECT C.MatchID,C.ContestID FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND C.MatchID = '.$MatchID.' AND E.StatusID IN('.implode(",",$StatusArr).') AND C.LeagueType = "Dfs" AND DATE(M.MatchStartDateTime) <= "'.date('Y-m-d').'" ORDER BY M.MatchStartDateTime ASC');
        }else{
            $LiveMatcheContest = $this->db->query('SELECT C.MatchID,C.ContestID FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND E.StatusID IN('.implode(",",$StatusArr).') AND C.LeagueType = "Dfs" AND DATE(M.MatchStartDateTime) <= "'.date('Y-m-d').'" ORDER BY M.MatchStartDateTime ASC');
        }
        if ($LiveMatcheContest->num_rows() == 0) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }

        /* Get Vice Captain Points */
        $ViceCaptainPointsData = $this->db->query('SELECT PointsODI,PointsT20,PointsTEST FROM sports_setting_points WHERE PointsTypeGUID = "ViceCaptainPointMP" LIMIT 1')->row_array();

        /* Get Captain Points */
        $CaptainPointsData = $this->db->query('SELECT PointsODI,PointsT20,PointsTEST FROM sports_setting_points WHERE PointsTypeGUID = "CaptainPointMP" LIMIT 1')->row_array();

        /* Match Types */
        $MatchTypesArr = array('1' => 'PointsODI', '3' => 'PointsT20', '4' => 'PointsT20', '5' => 'PointsTEST', '7' => 'PointsT20', '9' => 'PointsODI', '8' => 'PointsODI');

        foreach ($LiveMatcheContest->result_array() as $Value) {
            $Contests = $this->db->query('SELECT M.MatchTypeID,M.MatchID,JC.ContestID,JC.UserID,JC.UserTeamID FROM tbl_entity E, sports_matches M,sports_contest_join JC WHERE E.EntityID = JC.ContestID AND JC.MatchID = M.MatchID AND E.StatusID = 2 AND JC.ContestID = '.$Value['ContestID'].' ORDER BY M.MatchStartDateTime ASC');
            if ($Contests->num_rows() == 0) {
                continue;
            }

             /* To Get Match Players */
             $MatchPlayers     = $this->db->query('SELECT P.PlayerGUID,TP.PlayerID,TP.TotalPoints FROM sports_players P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = '.$Value['MatchID']);
             $PlayersPointsArr = array_column($MatchPlayers->result_array(), 'TotalPoints', 'PlayerGUID');
             $PlayersIdsArr    = array_column($MatchPlayers->result_array(), 'PlayerID', 'PlayerGUID');

            foreach ($Contests->result_array() as $ContestValue) {

                /* Player Points Multiplier */
                $PositionPointsMultiplier = (IS_VICECAPTAIN) ? array('ViceCaptain' => $ViceCaptainPointsData[$MatchTypesArr[$ContestValue['MatchTypeID']]], 'Captain' => $CaptainPointsData[$MatchTypesArr[$ContestValue['MatchTypeID']]], 'Player' => 1) : array('Captain' => $CaptainPointsData[$MatchTypesArr[$ContestValue['MatchTypeID']]], 'Player' => 1);
                $UserTotalPoints = 0;

                /* To Get User Team Players */
                $UserTeamPlayers = $this->db->query('SELECT P.PlayerGUID,UTP.PlayerPosition FROM sports_players P,sports_users_team_players UTP WHERE P.PlayerID = UTP.PlayerID AND UTP.UserTeamID = '.$ContestValue['UserTeamID'].' LIMIT 11');
                foreach ($UserTeamPlayers->result_array() as $UserTeamValue) {
                    if (!isset($PlayersPointsArr[$UserTeamValue['PlayerGUID']]))
                        continue;

                    $Points = ($PlayersPointsArr[$UserTeamValue['PlayerGUID']] != 0) ? $PlayersPointsArr[$UserTeamValue['PlayerGUID']] * $PositionPointsMultiplier[$UserTeamValue['PlayerPosition']] : 0;
                    $UserTotalPoints = ($Points > 0) ? $UserTotalPoints + $Points : $UserTotalPoints - abs($Points);

                    /* Update User Player Points */
                    /*                   
                    $this->db->where(array("UserTeamID"=>$ContestValue['UserTeamID'], "PlayerID"=>$PlayersIdsArr[$UserTeamValue['PlayerGUID']]));
                    $this->db->limit(1);
                    $this->db->update('sports_users_team_players', array('Points' => $Points));
                    */
                     $this->db->query("UPDATE sports_users_team_players SET Points=$Points WHERE UserTeamID=".$ContestValue['UserTeamID']." AND PlayerID=".$PlayersIdsArr[$UserTeamValue['PlayerGUID']]." LIMIT 1");
                }

                /* Update Player Total Points */
                $this->db->where('UserTeamID', $ContestValue['UserTeamID']);
                $this->db->update('sports_contest_join', array('TotalPoints' => $UserTotalPoints, 'ModifiedDate' => date('Y-m-d H:i:s')));
            }
            $this->updateRankByContest($Value['ContestID']);
        }
    }

    /*
      Description: To update rank
     */

    function updateRankByContest($ContestID) {
        $Query = $this->db->query("SELECT FIND_IN_SET( TotalPoints, 
                        ( SELECT GROUP_CONCAT( TotalPoints ORDER BY TotalPoints DESC)
                        FROM sports_contest_join WHERE sports_contest_join.ContestID = '" . $ContestID . "')) AS UserRank,ContestID,UserTeamID
                        FROM sports_contest_join,tbl_users 
                        WHERE sports_contest_join.ContestID = '" . $ContestID . "' AND tbl_users.UserID = sports_contest_join.UserID
                    ");
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $Value) {
                $this->db->where('ContestID', $Value['ContestID']);
                $this->db->where('UserTeamID', $Value['UserTeamID']);
                $this->db->limit(1);
                $this->db->update('sports_contest_join', array('UserRank' => $Value['UserRank']));
            }
        }
    }

    /*
      Description: To set contest winners
     */
    
    function setContestWinners($CronID) {

        ini_set('max_execution_time', 300);

        /* Get Completed Contests */
        $Contests = $this->db->query('SELECT C.WinningAmount,C.NoOfWinners,C.ContestID,C.CustomizeWinning FROM tbl_entity E,sports_contest C WHERE E.EntityID = C.ContestID AND E.StatusID = 5 AND C.IsWinningDistributed = "No"');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {
                $JoinedContestsUsers = $this->db->query('SELECT UserRank,UserTeamID,TotalPoints,UserID FROM sports_contest_join WHERE ContestID = '.$Value['ContestID'].' AND TotalPoints > 0 ORDER BY UserRank DESC');
                if ($JoinedContestsUsers->num_rows() == 0){
                    continue;
                }

                $AllRankWinners   = array_count_values(array_column($JoinedContestsUsers->result_array(), 'UserRank'));
                $userWinnersData  = $OptionWinner = array();
                $CustomizeWinning = (!empty($Value['CustomizeWinning'])) ? json_decode($Value['CustomizeWinning'], true) : array();
                foreach ($AllRankWinners as $Rank => $WinnerValue) {
                    $Flag = $TotalAmount = $AmountPerUser = 0;
                    for ($J = 0; $J < count($CustomizeWinning); $J++) {
                        if ($Rank >= $CustomizeWinning[$J]['From'] && $Rank <= $CustomizeWinning[$J]['To']) {
                            $TotalAmount = $CustomizeWinning[$J]['WinningAmount'];
                            if ($WinnerValue > 1) {
                                $L = 0;
                                for ($k = 1; $k < $WinnerValue; $k++) {
                                    if (!empty($CustomizeWinning[$J + $L]['From']) && !empty($CustomizeWinning[$J + $L]['To'])) {
                                        if ($Rank + $k >= $CustomizeWinning[$J + $L]['From'] && $Rank + $k <= $CustomizeWinning[$J + $L]['To']) {
                                            $TotalAmount += $CustomizeWinning[$J + $L]['WinningAmount'];
                                            $Flag = 1;
                                        } else {
                                            $L = $L + 1;
                                            if (!empty($CustomizeWinning[$J + $L]['From']) && !empty($CustomizeWinning[$J + $L]['To'])) {
                                                if ($Rank + $k >= $CustomizeWinning[$J + $L]['From'] && $Rank + $k <= $CustomizeWinning[$J + $L]['To']) {
                                                    $TotalAmount += $CustomizeWinning[$J + $L]['WinningAmount'];
                                                    $Flag = 1;
                                                }
                                            }
                                        }
                                    }
                                    if ($Flag == 0) {
                                        if ($Rank + $k >= $CustomizeWinning[$J]['From'] && $Rank + $k <= $CustomizeWinning[$J]['To']) {
                                            $TotalAmount += $CustomizeWinning[$J]['WinningAmount'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $AmountPerUser = $TotalAmount / $WinnerValue;
                    $userWinnersData[] = $this->findKeyValueArray($JoinedContestsUsers->result_array(), $Rank, $AmountPerUser);
                }
                foreach ($userWinnersData as $WinnerArray) {
                    foreach ($WinnerArray as $WinnerRow) {
                        $OptionWinner[] = $WinnerRow;
                    }
                }

                if (!empty($OptionWinner)) {
                    foreach ($OptionWinner as $WinnerValue) {

                        $this->db->trans_start();

                        /* Update Winning Amount */
                        $this->db->where('UserID', $WinnerValue['UserID']);
                        $this->db->where('ContestID', $Value['ContestID']);
                        $this->db->where('UserTeamID', $WinnerValue['UserTeamID']);
                        $this->db->limit(1);
                        $this->db->update('sports_contest_join', array('UserWinningAmount' => $WinnerValue['UserWinningAmount'], 'ModifiedDate' => date('Y-m-d H:i:s')));

                        /* Add winning into user wallet */
                        if ($WinnerValue['UserWinningAmount'] > 0) {
                            $WalletData = array(
                                "Amount" => $WinnerValue['UserWinningAmount'],
                                "WinningAmount" => $WinnerValue['UserWinningAmount'],
                                "TransactionType" => 'Cr',
                                "Narration" => 'Join Contest Winning',
                                "EntryDate" => date("Y-m-d H:i:s")
                            );
                            $this->Users_model->addToWallet($WalletData, $WinnerValue['UserID'], 5);
                        }
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === false) {
                            return false;
                        }
                    }
                }

                /* update contest winner amount distribute flag set YES */
                $this->db->where('ContestID', $Value['ContestID']);
                $this->db->limit(1);
                $this->db->update('sports_contest', array('IsWinningDistributed' => 'Yes'));
            }
        }
    }

    function findKeyValueArray($JoinedContestsUsers, $Rank, $AmountPerUser)
    {
        $WinnerUsers = array();
        foreach ($JoinedContestsUsers as $Rows) {
            if ($Rows['UserRank'] == $Rank) {
                $Temp['UserID'] = $Rows['UserID'];
                $Temp['FirstName'] = $Rows['FirstName'];
                $Temp['Email'] = $Rows['Email'];
                $Temp['UserWinningAmount'] = $AmountPerUser;
                $Temp['UserRank'] = $Rows['UserRank'];
                $Temp['TotalPoints'] = $Rows['TotalPoints'];
                $Temp['UserTeamID'] = $Rows['UserTeamID'];
                $WinnerUsers[] = $Temp;
            }
        }
        return $WinnerUsers;
    }

    /*
      Description: To get sports best played players of the match
     */
    function getMatchBestPlayers($Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        
        /* Get Match Players */
        $PlayersData = $this->getPlayers('PlayerRole,PlayerCountry,PlayerPic,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,IsPlaying,PlayerSalary,TeamNameShort,PlayerPosition,TotalPoints,TotalPointCredits', array('MatchID' => $Where['MatchID'],'OrderBy' => 'TotalPoints','Sequence' => 'DESC','IsPlaying' => 'Yes'), TRUE,0);
        if(!$PlayersData){
            return false;
        }

        $FinalXIPlayers = array();
        foreach($PlayersData['Data']['Records'] as $Key => $Value){
            $Row = $Value;
            $Row['PlayerPosition']  = ($Key == 0) ? 'Captain' : (($Key == 1) ? 'ViceCaptain' : 'Player');
            $Row['TotalPoints'] = strval(($Key == 0) ? 2 * $Row['TotalPoints'] : (($Key == 1) ? 1.5 * $Row['TotalPoints'] : $Row['TotalPoints']));
            array_push($FinalXIPlayers,$Row);
        }

        $Batsman      = $this->findKeyValuePlayers($FinalXIPlayers, "Batsman");
        $Bowler       = $this->findKeyValuePlayers($FinalXIPlayers, "Bowler");
        $Wicketkipper = $this->findKeyValuePlayers($FinalXIPlayers, "WicketKeeper");
        $Allrounder   = $this->findKeyValuePlayers($FinalXIPlayers, "AllRounder");

        $TopBatsman         = array_slice($Batsman, 0, 4);
        $TopBowler          = array_slice($Bowler, 0, 3);
        $TopWicketkipper    = array_slice($Wicketkipper, 0, 1);
        $TopAllrounder      = array_slice($Allrounder, 0, 3);

        $BatsmanSort = $BowlerSort = $WicketKipperSort = $AllRounderSort = array();
        foreach ($TopBatsman as $BatsmanValue) {
            $BatsmanSort[] = $BatsmanValue['TotalPoints'];
        }
        array_multisort($BatsmanSort,SORT_DESC,$TopBatsman);

        foreach ($TopBowler as $BowlerValue) {
            $BowlerSort[] = $BowlerValue['TotalPoints'];
        }
        array_multisort($BowlerSort,SORT_DESC,$TopBowler);

        foreach ($TopWicketkipper as $WicketKipperValue) {
            $WicketKipperSort[] = $WicketKipperValue['TotalPoints'];
        }
        array_multisort($WicketKipperSort,SORT_DESC,$TopWicketkipper);

        foreach ($TopAllrounder as $AllrounderValue) {
            $AllRounderSort[] = $AllrounderValue['TotalPoints'];
        }
        array_multisort($AllRounderSort,SORT_DESC,$TopAllrounder);
      
        $AllPlayers = array();
        $AllPlayers = array_merge($TopBatsman, $TopBowler);
        $AllPlayers = array_merge($AllPlayers, $TopAllrounder);
        $AllPlayers = array_merge($AllPlayers, $TopWicketkipper);

        $TotalCalculatedPoints = 0;
        foreach ($AllPlayers as $Value) {
            $TotalCalculatedPoints += $Value['TotalPoints'];
        }
        
        $Records['Data']['Records'] = $AllPlayers; 
        $Records['Data']['TotalPoints'] = strval($TotalCalculatedPoints); 
        $Records['Data']['TotalRecords'] = count($AllPlayers); 
        if($AllPlayers){
            return $Records;
        }else{
            return FALSE;
        }
    }

     /*
      Description: To get sports player fantasy stats series wise
     */

    function getPlayerFantasyStats($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'MatchNo' => 'M.MatchNo',
                'MatchLocation' => 'M.MatchLocation',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") MatchStartDateTime',
                'TeamNameLocal' => 'TL.TeamName AS TeamNameLocal',
                'TeamNameVisitor' => 'TV.TeamName AS TeamNameVisitor',
                'TeamNameShortLocal' => 'TL.TeamNameShort AS TeamNameShortLocal',
                'TeamNameShortVisitor' => 'TV.TeamNameShort AS TeamNameShortVisitor',
                'TeamFlagLocal' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag) as TeamFlagLocal',
                'TeamFlagVisitor' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag) as TeamFlagVisitor',
                'TotalPoints' => 'TP.TotalPoints',
                'TotalTeams' => '(SELECT COUNT(UserTeamID) FROM `sports_users_teams` WHERE `MatchID` = M.MatchID) TotalTeams'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('M.MatchGUID,M.MatchID,TP.PlayerID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_matches M, sports_teams TL, sports_teams TV, sports_team_players TP');
        $this->db->where("E.EntityID", "M.MatchID", FALSE);
        $this->db->where("M.MatchID", "TP.MatchID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        if (!empty($Where['SeriesID'])) {
            $this->db->where("TP.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("TP.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['PlayerID'])) {
            $this->db->where("TP.PlayerID", $Where['PlayerID']);
        }
        if (!empty($Where['PlayerID'])) {
            $this->db->where("TP.PlayerID", $Where['PlayerID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']); 
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            $this->db->order_by('M.MatchStartDateTime', 'DESC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['Data']['TotalRecords'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        // $this->db->cache_on(); //Turn caching on
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('PlayerSelectedPercent', $Params)) {
                        $this->db->select('COUNT(SUTP.PlayerID) TotalPlayer');
                        $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                        $this->db->where("SUTP.PlayerID", $Record['PlayerID']);
                        $this->db->where("SUTP.MatchID", $Record['MatchID']);
                        $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                        $Players = $this->db->get()->row();
                        $Records[$key]['PlayerSelectedPercent']  = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2)) : "0";
                    }
                    unset($Records[$key]['PlayerID'],$Records[$key]['MatchID']);
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('PlayerSelectedPercent', $Params)) {
                    $this->db->select('COUNT(SUTP.PlayerID) TotalPlayer');
                    $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                    $this->db->where("SUTP.PlayerID", $Record['PlayerID']);
                    $this->db->where("SUTP.MatchID", $Record['MatchID']);
                    $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                    $Players = $this->db->get()->row();
                    $Record['PlayerSelectedPercent']  = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2)) : "0";
                }
                unset($Record['PlayerID'],$Record['MatchID']);
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To Auto Cancel Contest
     */

    function autoCancelContest($CronID,$CancelType="Cancelled",$MatchID="")
    {
        ini_set('max_execution_time', 300);

        /* Get Contest Data */
        if(!empty($MatchID)){
            $ContestsUsers = $this->db->query('SELECT C.ContestID,C.Privacy,C.EntryFee,C.ContestFormat,C.ContestSize,C.IsConfirm,M.MatchStartDateTime,(SELECT COUNT(TotalPoints) FROM sports_contest_join WHERE ContestID =  C.ContestID ) TotalJoined FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND C.MatchID = '.$MatchID.' AND E.StatusID IN(1,2) AND DATE(M.MatchStartDateTime) <= "'.date('Y-m-d').'" ORDER BY M.MatchStartDateTime ASC');
        }else{
            $ContestsUsers = $this->db->query('SELECT C.ContestID,C.Privacy,C.EntryFee,C.ContestFormat,C.ContestSize,C.IsConfirm,M.MatchStartDateTime,(SELECT COUNT(TotalPoints) FROM sports_contest_join WHERE ContestID =  C.ContestID ) TotalJoined FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND E.StatusID IN(1,2) AND DATE(M.MatchStartDateTime) <= "'.date('Y-m-d').'" ORDER BY M.MatchStartDateTime ASC');
        }
        if ($ContestsUsers->num_rows() == 0) {
            return FALSE;
        }

        foreach ($ContestsUsers->result_array() as $Value) {

            if($CancelType == "Cancelled"){
                if (((strtotime($Value['MatchStartDateTime']) - 19800) - strtotime(date('Y-m-d H:i:s'))) > 0){
                    continue;
                } 

                /* To check contest cancel condition */
                $IsCancelled = 0;
                if($Value['Privacy'] == 'Yes'){ // Should be 100% filled
                    $IsCancelled = ($Value['ContestSize'] != $Value['TotalJoined']) ? 1 : 0;
                }else{
                    if($Value['ContestFormat'] == 'Head to Head'){
                        $IsCancelled = ($Value['TotalJoined'] == 2) ? 0 : 1;
                    }else{
                        $IsCancelled = ($Value['IsConfirm'] == 'Yes') ? 0 : (((($Value['TotalJoined'] * 100 ) / $Value['ContestSize']) >= CONTEST_FILL_PERCENT_LIMIT) ? 0 : 1);
                    }
                }
                if ($IsCancelled == 0){
                    continue;
                }
            }

            /* Update Contest Status */
            $this->db->where('EntityID', $Value['ContestID']);
            $this->db->limit(1);
            $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));

            /* Get Joined Contest */
            $JoinedContestsUsers = $this->db->query('SELECT UserID,UserTeamID FROM sports_contest_join WHERE ContestID = '.$Value['ContestID']);
            if ($JoinedContestsUsers->num_rows() == 0){
                continue;
            }

            foreach ($JoinedContestsUsers->result_array() as $JoinValue) {

                /* Refund Wallet Money */
                if (!empty($Value['EntryFee'])) {

                    /* Get Wallet Details */
                    $WalletDetails = $this->db->query('SELECT WalletAmount,WinningAmount,CashBonus FROM tbl_users_wallet WHERE Narration = "Cancel Contest" AND UserTeamID = '.$JoinValue['UserTeamID'].' AND EntityID = '.$Value['ContestID'].' AND UserID = '.$JoinValue['UserID'].' LIMIT 1');
                    if($WalletDetails->num_rows() > 0){
                        continue;
                    }

                    /* Get Wallet Details */
                    $WalletDetails = $this->db->query('SELECT WalletAmount,WinningAmount,CashBonus FROM tbl_users_wallet WHERE Narration = "Join Contest" AND UserTeamID = '.$JoinValue['UserTeamID'].' AND EntityID = '.$Value['ContestID'].' AND UserID = '.$JoinValue['UserID'].' LIMIT 1')->result_array();
                    $InsertData = array(
                        "Amount" => $WalletDetails['WalletAmount'] + $WalletDetails['WinningAmount'] + $WalletDetails['CashBonus'],
                        "WalletAmount" => $WalletDetails['WalletAmount'],
                        "WinningAmount" => $WalletDetails['WinningAmount'],
                        "CashBonus" => $WalletDetails['CashBonus'],
                        "TransactionType" => 'Cr',
                        "EntityID" => $Value['ContestID'],
                        "UserTeamID" => $JoinValue['UserTeamID'],
                        "Narration" => 'Cancel Contest',
                        "EntryDate" => date("Y-m-d H:i:s")
                    );
                    $this->Users_model->addToWallet($InsertData, $JoinValue['UserID'], 5);
                }
            }
        }
    }


    function findKeyValuePlayers($array, $value) {
        if (is_array($array)) {
            $players = array();
            foreach ($array as $key => $rows) {
                if ($rows['PlayerRole'] == $value) {
                    $players[] = $array[$key];
                }
            }
            return $players;
        }
        return false;
    }

    function findKeyArrayDiff($array, $value) {
        if (is_array($array)) {
            $players = array();
            foreach ($array as $key => $rows) {
                if ($rows['PlayerID'] == $value) {
                    return false;
                }
            }
            return true;
        }
        return true;
    }

}

?>
