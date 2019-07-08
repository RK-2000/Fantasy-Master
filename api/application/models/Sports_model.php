<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('AuctionDrafts_model');

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
                'SportsType' => 'S.SportsType',
                'AuctionDraftIsPlayed' => 'S.AuctionDraftIsPlayed',
                'DraftUserLimit' => 'S.DraftUserLimit',
                'DraftTeamPlayerLimit' => 'S.DraftTeamPlayerLimit',
                'DraftPlayerSelectionCriteria' => 'S.DraftPlayerSelectionCriteria',
                'SeriesStartDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesStartDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesStartDate',
                'SeriesEndDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesEndDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesEndDate',
                'TotalMatches' => '(SELECT COUNT(MatchIDLive) FROM sports_matches WHERE sports_matches.SeriesID =  S.SeriesID ) TotalMatches',
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
        if (!empty($Where['DraftAuctionPlay']) && $Where['DraftAuctionPlay'] == "Yes") {
            $this->db->where("S.AuctionDraftIsPlayed", "Yes");
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("S.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['SportsType'])) {
            $this->db->where("S.SportsType", $Where['SportsType']);
        }
        if (!empty($Where['AuctionDraftIsPlayed'])) {
            $this->db->where("S.AuctionDraftIsPlayed", $Where['AuctionDraftIsPlayed']);
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
        } else if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
            $this->db->order_by('DATE(S.SeriesEndDate)="' . date('Y-m-d') . '" ASC', null, FALSE);
            $this->db->order_by('E.StatusID=2 DESC', null, FALSE);
        } else {
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

    function getMatchTypes($MatchTypeID = '')
    {
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
      Description : To Update Match Details
     */
    function updateMatchDetails($MatchID, $Input = array())
    {
        $UpdateArray = array_filter(array(
            'MatchClosedInMinutes' => @$Input['MatchClosedInMinutes']
        ));
        if (!empty($UpdateArray)) {
            $this->db->where('MatchID', $MatchID);
            $this->db->limit(1);
            $this->db->update('sports_matches', $UpdateArray);
        }
        return TRUE;
    }

    /*
      Description: To get all matches
     */
    function getMatches($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
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
                'SportsType' => 'M.SportsType',
                'MatchLocation' => 'M.MatchLocation',
                'IsPreSquad' => 'M.IsPreSquad',
                'IsPlayerPointsUpdated' => 'M.IsPlayerPointsUpdated',
                'MatchScoreDetails' => 'M.MatchScoreDetails',
                'MatchClosedInMinutes' => 'M.MatchClosedInMinutes',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") MatchStartDateTime',
                'CurrentDateTime' => 'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") CurrentDateTime',
                'MatchDate' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%Y-%m-%d") MatchDate',
                'MatchTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%H:%i:%s") MatchTime',
                'MatchStartDateTimeUTC' => 'M.MatchStartDateTime AS MatchStartDateTimeUTC',
                'ServerDateTimeUTC' => 'UTC_TIMESTAMP() AS ServerDateTimeUTC',
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
                'TeamFlagLocal' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag) TeamFlagLocal',
                'TeamFlagVisitor' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag) TeamFlagVisitor',
                'MyTotalJoinedContest' => '(SELECT COUNT(DISTINCT sports_contest_join.ContestID)
                                                FROM sports_contest_join
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND UserID= ' . @$Where['SessionUserID'] . ') AS MyTotalJoinedContest',
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
                'TotalUserWinning' => '(SELECT IFNULL(SUM(UserWinningAmount),0) FROM sports_contest_join WHERE MatchID = M.MatchID AND UserID=' . @$Where['SessionUserID'] . ') TotalUserWinning',
                'isJoinedContest' => '(SELECT COUNT(EntryDate) FROM sports_contest_join WHERE MatchID = M.MatchID AND UserID = "' . @$Where['SessionUserID'] . '" AND E.StatusID=' . @$Where['StatusID'] . ') JoinedContests'
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
        if (array_keys_exist($Params, array('SeriesID', 'SeriesGUID', 'SeriesIDLive', 'SeriesName', 'SeriesStartDate', 'SeriesEndDate'))) {
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
        if (!empty($Where['MatchCompleteDateTime'])) {
            $this->db->where("M.MatchCompleteDateTime <", $Where['MatchCompleteDateTime']);
        }
        if (!empty($Where['MatchTypeID'])) {
            $this->db->where("M.MatchTypeID", $Where['MatchTypeID']);
        }
        if (!empty($Where['SportsType'])) {
            $this->db->where("M.SportsType", $Where['SportsType']);
        }
        if (!empty($Where['TeamIDLocal'])) {
            $this->db->where("M.TeamIDLocal", $Where['TeamIDLocal']);
        }
        if (!empty($Where['IsPreSquad'])) {
            $this->db->where("M.IsPreSquad", $Where['IsPreSquad']);
        }
        if (!empty($Where['TeamIDVisitor'])) {
            $this->db->where("M.TeamIDVisitor", $Where['TeamIDVisitor']);
        }
        if (!empty($Where['IsPlayerPointsUpdated'])) {
            $this->db->where("M.IsPlayerPointsUpdated", $Where['IsPlayerPointsUpdated']);
        }
        if (!empty($Where['MatchStartDateTime'])) {
            $this->db->where("M.MatchStartDateTime <=", $Where['MatchStartDateTime']);
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Today') {
            $this->db->where("DATE(M.MatchStartDateTime)", date('Y-m-d'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Yesterday') {
            $this->db->where("M.MatchStartDateTime <=", date('Y-m-d H:i:s'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'MyJoinedMatch') {
            $this->db->where('EXISTS (select 1 from sports_contest_join J where J.MatchID = M.MatchID AND J.UserID=' . $Where['SessionUserID'] . ')');
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where_in("E.StatusID", ($Where['StatusID'] == 2) ? array(2, 10) : $Where['StatusID']);
        }
        if (!empty($Where['CronFilter']) && $Where['CronFilter'] == 'OneDayDiff') {
            $this->db->having("LastUpdateDiff", 0);
            $this->db->or_having("LastUpdateDiff >=", 86400); // 1 Day
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
            $this->db->order_by('DATE(M.MatchStartDateTime)="' . date('Y-m-d') . '" DESC', null, FALSE);
            $this->db->order_by('E.StatusID=1 DESC', null, FALSE);
        } else {
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
        if ($multiRecords) {
            if ($Query->num_rows() > 0) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('MatchScoreDetails', $Params)) {
                        $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                    }
                }
                $Return['Data']['Records'] = $Records;
            }
            if (!empty($Where['MyJoinedMatchesCount']) && $Where['MyJoinedMatchesCount'] == 'Yes') {
                $Return['Data']['Statics'] = $this->db->query(
                    'SELECT (
                            SELECT COUNT(DISTINCT J.MatchID) FROM `sports_contest_join` J,`tbl_entity` E  WHERE J.MatchID = E.EntityID AND E.StatusID = 1 AND J.UserID ="' . @$Where['UserID'] . '" 
                        ) UpcomingJoinedContest,
                        ( SELECT COUNT(DISTINCT J.MatchID) FROM `sports_contest_join` J,`tbl_entity` E  WHERE J.MatchID = E.EntityID AND E.StatusID IN (2,10) AND J.UserID ="' . @$Where['UserID'] . '" 
                        ) LiveJoinedContest,
                        ( SELECT COUNT(DISTINCT J.MatchID) FROM `sports_contest_join` J,`tbl_entity` E  WHERE J.MatchID = E.EntityID AND E.StatusID = 5 AND J.UserID ="' . @$Where['UserID'] . '" 
                    ) CompletedJoinedContest'
                )->row();
            }
            return $Return;
        } else {
            if ($Query->num_rows() > 0) {
                $Record = $Query->row_array();
                if (in_array('MatchScoreDetails', $Params)) {
                    $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description : To Update Team Details
     */
    function updateTeamDetails($TeamID, $Input = array())
    {
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
      Description: To get all teams
    */
    function getTeams($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
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
                'SportsType' => 'T.SportsType',
                'TeamFlag' => 'IF(T.TeamFlag IS NULL,CONCAT("' . BASE_URL . '","uploads/TeamFlag/","team.png"), CONCAT("' . BASE_URL . '","uploads/TeamFlag/",T.TeamFlag)) TeamFlag',
                'Status' => 'CASE E.StatusID
                                when "2" then "Active"
                                when "6" then "Inactive"
                                END as Status',
                'SeriesName' => '(SELECT GROUP_CONCAT(`SeriesName`) FROM sports_series WHERE SeriesID IN(SELECT DISTINCT SeriesID FROM `sports_team_players` WHERE `TeamID` = T.TeamID)) SeriesName'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('T.TeamName,T.TeamGUID');
        if (!empty($Field)) {
            $this->db->select($Field, FALSE);
        }
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
        if (!empty($Where['TeamIDLive'])) {
            $this->db->where("T.TeamIDLive", $Where['TeamIDLive']);
        }
        if (!empty($Where['SportsType'])) {
            $this->db->where("T.SportsType", $Where['SportsType']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where('T.TeamID IN(SELECT DISTINCT TeamID FROM `sports_team_players` WHERE `SeriesID` = ' . $Where['SeriesID'] . ')', NULL, FALSE);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('T.TeamName', 'ASC');
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
      Description: Use to update player role.
     */
    function updatePlayerRole($PlayerID, $MatchID, $Input = array())
    {
        if (!empty($Input)) {
            $this->db->where(array('MatchID' => $MatchID, 'PlayerID' => $PlayerID));
            $this->db->limit(1);
            $this->db->update('sports_team_players', $Input);
        }
    }

    /*
      Description: Use to update player salary (Match Wise).
     */
    function updatePlayerSalaryMatch($Input = array(), $PlayerID, $MatchID)
    {
        if (!empty($Input)) {
            $this->db->where(array('MatchID' => $MatchID, 'PlayerID' => $PlayerID));
            $this->db->limit(1);
            $this->db->update('sports_team_players', array('PlayerSalary' => $Input['PlayerSalaryCredit'], 'IsAdminUpdate' => 'Yes'));
        }
    }

    /*
      Description: To get all players
    */
    function getPlayers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
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
                'PlayerSalary' => 'FORMAT(TP.PlayerSalary,1) as PlayerSalary',
                'PlayerSalaryCredit' => 'FORMAT(TP.PlayerSalary,1) PlayerSalaryCredit',
                'LastUpdateDiff' => 'IF(P.LastUpdatedOn IS NULL, 0, TIME_TO_SEC(TIMEDIFF("' . date('Y-m-d H:i:s') . '", P.LastUpdatedOn))) LastUpdateDiff',
                'MatchTypeID' => 'SSM.MatchTypeID',
                'MatchType' => 'SSM.MatchTypeName MatchType',
                'TotalPointCredits' => '(SELECT IFNULL(SUM(`TotalPoints`),0) FROM `sports_team_players` WHERE `PlayerID` = TP.PlayerID AND `SeriesID` = TP.SeriesID) TotalPointCredits',
                'MyTeamPlayer' => '(SELECT IF( EXISTS(SELECT UTP.PlayerID FROM sports_contest_join JC,sports_users_team_players UTP WHERE JC.UserTeamID = UTP.UserTeamID AND JC.MatchID = ' . $Where['MatchID'] . ' AND JC.UserID = ' . $Where['SessionUserID'] . ' AND UTP.PlayerID = P.PlayerID LIMIT 1), "Yes", "No")) MyPlayer',
                'PlayerSelectedPercent' => '(SELECT IF((SELECT COUNT(UserTeamName) FROM sports_users_teams WHERE MatchID= ' . $Where['MatchID'] . ') > 0,ROUND((((SELECT COUNT(UTP.PlayerID) FROM sports_users_teams UT,sports_users_team_players UTP WHERE UT.UserTeamID = UTP.UserTeamID AND UTP.PlayerID = P.PlayerID AND UT.MatchID = ' . $Where['MatchID'] . ')*100)/(SELECT COUNT(UserTeamName) FROM sports_users_teams WHERE MatchID= ' . $Where['MatchID'] . ')),2),0)) PlayerSelectedPercent'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('P.PlayerGUID,P.PlayerName');
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
            $this->db->or_like("TP.PlayerRole", $Where['Keyword']);
            $this->db->or_like("P.PlayerCountry", $Where['Keyword']);
            $this->db->or_like("P.PlayerBattingStyle", $Where['Keyword']);
            $this->db->or_like("P.PlayerBowlingStyle", $Where['Keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("TP.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("TP.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['PlayerGUID'])) {
            $this->db->where("P.PlayerGUID", $Where['PlayerGUID']);
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
        if (!empty($Where['PlayerRole'])) {
            $this->db->where("TP.PlayerRole", $Where['PlayerRole']);
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
        if (!empty($Where['PlayerSalary']) && $Where['PlayerSalary'] == 'Yes') {
            $this->db->where("TP.PlayerSalary >", 0);
        }

        /* Order By */
        if (!empty($Where['RandData'])) {
            $this->db->order_by($Where['RandData']);
        } else if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
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

        $Query = $this->db->get();
        $MatchStatus = 0;
        if (!empty($Where['MatchID'])) {
            /* Get Match Status */
            $MatchQuery = $this->db->query('SELECT E.StatusID FROM `sports_matches` `M`,`tbl_entity` `E` WHERE M.`MatchID` = "' . $Where['MatchID'] . '" AND M.MatchID = E.EntityID LIMIT 1');
            $MatchStatus = ($MatchQuery->num_rows() > 0) ? $MatchQuery->row()->StatusID : 0;
        }
        if ($Query->num_rows() > 0) {
            if (in_array('TopPlayer', $Params)) {
                $BestPlayers = $this->getMatchBestPlayers(array('MatchID' => $Where['MatchID']));
                if (!empty($BestPlayers)) {
                    $BestXIPlayers = array_column($BestPlayers['Data']['Records'], 'PlayerGUID');
                }
            }
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('TopPlayer', $Params)) {
                        $Records[$key]['TopPlayer'] = (in_array($Record['PlayerGUID'], $BestXIPlayers)) ? 'Yes' : 'No';
                    }
                    if (in_array('PlayerBattingStats', $Params)) {
                        $Records[$key]['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                    }
                    if (in_array('PlayerBowlingStats', $Params)) {
                        $Records[$key]['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                    }
                    if (in_array('PointsData', $Params)) {
                        $Records[$key]['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                    }
                    if (in_array('PointCredits', $Params)) {
                        $Records[$key]['PointCredits'] = (in_array($MatchStatus, array(2, 5, 10))) ? @$Record['TotalPoints'] : @$Record['TotalPointCredits'];
                    }
                }

                /* Custom Sorting */
                if (!empty($Where['CustomOrderBy']) && !empty($Where['Sequence'])) {
                    $SortArr = array();
                    foreach ($Records as $Value) {
                        $SortArr[] = $Value[$Where['CustomOrderBy']]; // In Object
                    }
                    if ($Where['Sequence'] == 'ASC') {
                        array_multisort($SortArr, SORT_ASC, $Records);
                    } else {
                        array_multisort($SortArr, SORT_DESC, $Records);
                    }
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('TopPlayer', $Params)) {
                    $Record['TopPlayer'] = (in_array($Record['PlayerGUID'], $BestXIPlayers)) ? 'Yes' : 'No';
                }
                if (in_array('PlayerBattingStats', $Params)) {
                    $Record['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                }
                if (in_array('PlayerBowlingStats', $Params)) {
                    $Record['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                }
                if (in_array('PointsData', $Params)) {
                    $Record['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                }
                if (in_array('PointCredits', $Params)) {
                    $Record['PointCredits'] = (in_array($MatchStatus, array(2, 5, 10))) ? @$Record['TotalPoints'] : @$Record['TotalPointCredits'];
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To get sports best played players of the match
    */
    function getMatchBestPlayers($Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {

        /* Get Match Players */
        $PlayersData = $this->Sports_model->getPlayers('PlayerID,PlayerRole,PointsData,PlayerPic,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,IsPlaying,PlayerSalary,TeamNameShort,PlayerPosition,TotalPoints,TotalPointCredits,MyTeamPlayer,PlayerSelectedPercent,TopPlayer', array('MatchID' => $Where['MatchID'], 'UserID' => $Where['SessionUserID'], 'OrderBy' => 'TotalPoints', 'Sequence' => 'DESC', 'IsPlaying' => 'Yes'), TRUE, 0);
        if (!$PlayersData) {
            return false;
        }
        $FinalXIPlayers = array();
        foreach ($PlayersData['Data']['Records'] as $Key => $Value) {
            $Row = $Value;
            $Row['PlayerPosition'] = ($Key == 0) ? 'Captain' : (($Key == 1) ? 'ViceCaptain' : 'Player');
            $Row['TotalPoints'] = strval(($Key == 0) ? 2 * $Row['TotalPoints'] : (($Key == 1) ? 1.5 * $Row['TotalPoints'] : $Row['TotalPoints']));
            array_push($FinalXIPlayers, $Row);
        }

        $Batsman = $this->findSubArray($FinalXIPlayers, "PlayerRole", "Batsman");
        $Bowler = $this->findSubArray($FinalXIPlayers, "PlayerRole", "Bowler");
        $Wicketkipper = $this->findSubArray($FinalXIPlayers, "PlayerRole", "WicketKeeper");
        $Allrounder = $this->findSubArray($FinalXIPlayers, "PlayerRole", "AllRounder");

        $TopBatsman = array_slice($Batsman, 0, 4);
        $TopBowler = array_slice($Bowler, 0, 3);
        $TopWicketkipper = array_slice($Wicketkipper, 0, 1);
        $TopAllrounder = array_slice($Allrounder, 0, 3);

        $BatsmanSort = $BowlerSort = $WicketKipperSort = $AllRounderSort = array();
        foreach ($TopBatsman as $BatsmanValue) {
            $BatsmanSort[] = $BatsmanValue['TotalPoints'];
        }
        array_multisort($BatsmanSort, SORT_DESC, $TopBatsman);

        foreach ($TopBowler as $BowlerValue) {
            $BowlerSort[] = $BowlerValue['TotalPoints'];
        }
        array_multisort($BowlerSort, SORT_DESC, $TopBowler);

        foreach ($TopWicketkipper as $WicketKipperValue) {
            $WicketKipperSort[] = $WicketKipperValue['TotalPoints'];
        }
        array_multisort($WicketKipperSort, SORT_DESC, $TopWicketkipper);

        foreach ($TopAllrounder as $AllrounderValue) {
            $AllRounderSort[] = $AllrounderValue['TotalPoints'];
        }
        array_multisort($AllRounderSort, SORT_DESC, $TopAllrounder);

        $AllPlayers = array();
        $AllPlayers = array_merge($TopBatsman, $TopBowler);
        $AllPlayers = array_merge($AllPlayers, $TopAllrounder);
        $AllPlayers = array_merge($AllPlayers, $TopWicketkipper);

        $Records['Data']['Records'] = $AllPlayers;
        $Records['Data']['TotalPoints'] = strval(array_sum(array_column($AllPlayers, 'TotalPoints')));
        $Records['Data']['TotalRecords'] = count($AllPlayers);
        if ($AllPlayers) {
            return $Records;
        } else {
            return FALSE;
        }
    }

    /*
      Description: To get sports player fantasy stats series wise
     */
    function getPlayerFantasyStats($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'MatchNo' => 'M.MatchNo',
                'SeriesGUID' => 'M.SeriesGUID',
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
        } else {
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
                        $Records[$key]['PlayerSelectedPercent'] = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100) / $Record['TotalTeams']), 2)) : "0";
                    }
                    unset($Records[$key]['PlayerID'], $Records[$key]['MatchID']);
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
                    $Record['PlayerSelectedPercent'] = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100) / $Record['TotalTeams']), 2)) : "0";
                }
                unset($Record['PlayerID'], $Record['MatchID']);
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: Use to update points.
    */
    function updatePoints($Input = array())
    {
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
      Description: Use to get sports points.
    */
    function getPoints($Where = array())
    {
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
      Description: To calculate points according to keys
     */
    function calculatePoints($Points = array(), $MatchType, $BattingMinimumRuns, $ScoreValue, $BallsFaced = 0, $Overs = 0, $Runs = 0, $MinimumOverEconomyRate = 0, $PlayerRole, $HowOut)
    {
        /* Match Types */
        $MatchTypes = array('ODI' => 'PointsODI', 'List A' => 'PointsODI', 'T20' => 'PointsT20', 'T20I' => 'PointsT20', 'Test' => 'PointsTEST', 'Woman ODI' => 'PointsODI', 'Woman T20' => 'PointsT20');
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
                if ($ScoreValue <= 0 && $PlayerRole != 'Bowler' && $HowOut != "Not out") {
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
      Description: To get cricket player points
    */
    function getPlayerPointsCricket($CronID, $MatchID = "")
    {
        /* Get Live Matches Data */
        $LiveMatches = $this->getMatches('MatchID,MatchType,MatchScoreDetails,StatusID,IsPlayerPointsUpdated', array('Filter' => 'Yesterday', 'StatusID' => array(2, 5, 10), 'IsPlayerPointsUpdated' => 'No', 'OrderBy' => 'M.MatchStartDateTime', 'Sequence' => 'DESC', 'MatchID' => $MatchID, 'SportsType' => 'Cricket'), true, 1, 10);
        if (!empty($LiveMatches)) {

            /* Get Points Data */
            $PointsDataArr             = $this->getPoints(array("StatusID" => 1));
            $StatringXIArr             = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'StatringXI');
            $CaptainPointMPArr         = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'CaptainPointMP');
            $ViceCaptainPointMPArr     = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'ViceCaptainPointMP');
            $BattingMinimumRunsArr     = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'BattingMinimumRuns');
            $MinimumRunScoreStrikeRate = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'MinimumRunScoreStrikeRate');
            $MinimumOverEconomyRate    = $this->findSubArray($PointsDataArr['Data']['Records'], 'PointsTypeGUID', 'MinimumOverEconomyRate');
            $MatchTypes = array('ODI' => 'PointsODI', 'List A' => 'PointsODI', 'T20' => 'PointsT20', 'T20I' => 'PointsT20', 'Test' => 'PointsTEST', 'Woman ODI' => 'PointsODI', 'Woman T20' => 'PointsT20');

            /* Sorting Keys */
            $PointsSortingKeys = array('SB', 'RUNS', '4s', '6s', 'STB', 'BTB', 'DUCK', 'WK', 'MD', 'EB', 'BWB', 'RO', 'ST', 'CT');
            foreach ($LiveMatches['Data']['Records'] as $Value) {
                if (empty((array)$Value['MatchScoreDetails'])) {
                    continue;
                }

                /* Delete Cache Key */
                $this->cache->memcached->delete('getJoinedContestPlayerPoints_' . $Value['MatchID']);
                $StatringXIPoints          = (isset($StatringXIArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($StatringXIArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $CaptainPointMPPoints      = (isset($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $ViceCaptainPointMPPoints  = (isset($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "1.5";
                $BattingMinimumRunsPoints  = (isset($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]]) : "15";
                $MinimumRunScoreStrikeRate = (isset($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]]) : "10";
                $MinimumOverEconomyRate    = (isset($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]]) : "1";

                /* Get Match Players */
                $MatchPlayers = $this->cache->memcached->get('PlayerPoints_' . $Value['MatchID']);
                if (empty($MatchPlayers)) {
                    $MatchPlayers = $this->db->query('SELECT P.`PlayerID`,P.`PlayerIDLive`,TP.`PlayerRole` FROM `sports_players` P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = ' . $Value['MatchID'] . ' AND TP.IsPlaying = "Yes" LIMIT 50');
                    if ($MatchPlayers->num_rows() == 0) {
                        continue;
                    } else {
                        $this->cache->memcached->save('PlayerPoints_' . $Value['MatchID'], $MatchPlayers->result_array(), 3600 * 10); // Expire in every 10 hours
                    }
                }

                /* Get Match Live Score Data */
                $BatsmanPlayers = $BowlingPlayers = $FielderPlayers = $AllPalyers = $AllPlayeRoleData = array();
                foreach ($Value['MatchScoreDetails']['Innings'] as $PlayerID) {
                    foreach ($PlayerID['AllPlayingData'] as $PlayerKey => $PlayerSubValue) {
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
                if (empty($AllPalyers)) {
                    continue;
                }

                $AllPlayersLiveIds = array_keys($AllPalyers);
                foreach ($MatchPlayers as $PlayerValue) {

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
                            $PlayersKeys = array_keys($ScoreData);
                            if (($DeleteKey = array_search('Name', $PlayersKeys)) !== false) {
                                unset($PlayersKeys[$DeleteKey]);
                            }
                            if (($DeleteKey = array_search('PlayerIDLive', $PlayersKeys)) !== false) {
                                unset($PlayersKeys[$DeleteKey]);
                            }

                            /** calculate points * */
                            foreach ($PlayersKeys as $ScoreValue) {
                                $calculatePoints = $this->calculatePoints($PointValue, $Value['MatchType'], $MinimumRunScoreStrikeRate, @$ScoreData[$PointValue['PointsScoringField']], @$ScoreData['BallsFaced'], @$ScoreData['Overs'], @$ScoreData['Runs'], $MinimumOverEconomyRate, $PlayerValue['PlayerRole'], $ScoreData['HowOut']);
                                if (is_array($calculatePoints)) {
                                    $PointsData[$calculatePoints['PointsTypeShortDescription']] = array('PointsTypeGUID' => $calculatePoints['PointsTypeGUID'], 'PointsTypeShortDescription' => $calculatePoints['PointsTypeShortDescription'], 'DefinedPoints' => strval($calculatePoints['DefinedPoints']), 'ScoreValue' => strval($calculatePoints['ScoreValue']), 'CalculatedPoints' => strval($calculatePoints['CalculatedPoints']));
                                }
                            }
                        }

                        /* Manage Single Strike Rate & Economy Rate & Bowling & Batting State */
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
                            if (in_array($PointValue['PointsTypeGUID'], array('StrikeRate50N74.99', 'StrikeRate75N99.99', 'StrikeRate100N149.99', 'StrikeRate150N199.99', 'StrikeRate200NMore', 'EconomyRate5.01N7.00Balls', 'EconomyRate5.01N8.00Balls', 'EconomyRate7.01N10.00Balls', 'EconomyRate8.01N10.00Balls', 'EconomyRate10.01N12.00Balls', 'EconomyRateAbove12.1Balls', 'FourWickets', 'FiveWickets', 'SixWickets', 'SevenWicketsMore', 'EightWicketsMore', 'For50runs', 'For100runs', 'For150runs', 'For200runs', 'For300runs', 'MinimumRunScoreStrikeRate', 'MinimumOverEconomyRate')))
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
                    $this->db->where(array('MatchID' => $Value['MatchID'], 'PlayerID' => $PlayerValue['PlayerID']));
                    $this->db->limit(1);
                    $this->db->update('sports_team_players', array('TotalPoints' => $PlayerTotalPoints, 'PointsData' => (!empty($PointsData)) ? json_encode($PointsData) : null));
                }

                /* Update Final player points before complete match */
                $CronID = $this->Common_model->insertCronLogs('getJoinedContestPlayerPoints');
                $this->getJoinedContestPlayerPointsCricket($CronID, array(2), $Value['MatchID']);
                $this->Common_model->updateCronLogs($CronID);

                /* Update Match Player Points Status */
                if ($Value['StatusID'] == 5) {
                    $this->db->where('MatchID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('sports_matches', array('IsPlayerPointsUpdated' => 'Yes'));

                    /* Update Final player points before complete match */
                    $CronID = $this->Common_model->insertCronLogs('getJoinedContestPlayerPoints');
                    $this->getJoinedContestPlayerPointsCricket($CronID, array(2, 5));
                    $this->Common_model->updateCronLogs($CronID);
                }
            }
        }
    }

    /*
      Description: To get joined contest player points
    */
    function getJoinedContestPlayerPointsCricket($CronID, $StatusArr = array(2), $MatchID = "")
    {

        ini_set('memory_limit', '512M');

        /* Get Live Contests */
        $Query = "SELECT M.MatchTypeID, M.MatchID, JC.ContestID, JC.UserID, JC.UserTeamID,U.UserGUID,UT.UserTeamName,U.Username,CONCAT_WS(' ',U.FirstName,U.LastName) FullName,IF(U.ProfilePic IS NULL,CONCAT('" . BASE_URL . "','uploads/profile/picture/','default.jpg'),CONCAT('" . BASE_URL . "','uploads/profile/picture/',U.ProfilePic)) ProfilePic, ( SELECT CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'PlayerGUID', P.PlayerGUID, 'PlayerName', P.PlayerName,'PlayerRole',TP.PlayerRole,'TeamGUID', T.TeamGUID,'PlayerPic',IF(P.PlayerPic IS NULL,CONCAT('" . BASE_URL . "','uploads/PlayerPic/','player.png'),CONCAT('" . BASE_URL . "','uploads/PlayerPic/',P.PlayerPic)),'PlayerPosition', UTP.PlayerPosition ) ), ']' ) FROM sports_players P,sports_team_players TP,sports_teams T, sports_users_team_players UTP WHERE P.PlayerID = UTP.PlayerID AND UTP.MatchID = M.MatchID AND UTP.UserTeamID = JC.UserTeamID AND P.PlayerID = TP.PlayerID AND TP.MatchID = M.MatchID AND TP.TeamID = T.TeamID ) AS UserPlayersJSON FROM `sports_contest_join` JC, sports_matches M, tbl_entity E,tbl_users U,sports_users_teams UT WHERE JC.MatchID = M.MatchID AND E.EntityID = JC.ContestID AND UT.UserTeamID = JC.UserTeamID AND U.UserID = JC.UserID AND E.StatusID = 2 AND EXISTS( SELECT C.ContestID FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND E.StatusID IN(" . implode(',', $StatusArr) . ")";
        if (!empty($MatchID)) {
            $Query .= " AND C.MatchID = " . $MatchID;
        }
        $Query .= " AND C.LeagueType = 'Dfs' AND DATE(M.MatchStartDateTime) <= '" . date('Y-m-d') . "' AND C.ContestID = JC.ContestID LIMIT 1) ORDER BY JC.ContestID";
        $Data = $this->db->query($Query);
        if ($Data->num_rows() > 0) {

            /* Contest Rank Array */
            $ContestIdArr = array();

            /* Get Vice Captain Points */
            $ViceCaptainPointsData = $this->db->query('SELECT PointsODI,PointsT20,PointsTEST FROM sports_setting_points WHERE PointsTypeGUID = "ViceCaptainPointMP" LIMIT 1')->row_array();

            /* Get Captain Points */
            $CaptainPointsData = $this->db->query('SELECT PointsODI,PointsT20,PointsTEST FROM sports_setting_points WHERE PointsTypeGUID = "CaptainPointMP" LIMIT 1')->row_array();

            /* Match Types */
            $MatchTypesArr = array('1' => 'PointsODI', '3' => 'PointsT20', '4' => 'PointsT20', '5' => 'PointsTEST', '7' => 'PointsT20', '9' => 'PointsODI', '8' => 'PointsODI');

            /* Joined Users Teams Data */
            foreach ($Data->result_array() as $Key => $Value) {

                $ContestIdArr[] = $Value['ContestID'];

                /* To Get Match Players */
                $MatchPlayers = $this->cache->memcached->get('getJoinedContestPlayerPoints_' . $Value['MatchID']);
                if (empty($MatchPlayers)) {
                    $MatchPlayers = $this->db->query('SELECT P.PlayerGUID,TP.PlayerID,TP.TotalPoints FROM sports_players P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = ' . $Value['MatchID'])->result_array();
                    $this->cache->memcached->save('getJoinedContestPlayerPoints_' . $Value['MatchID'], $MatchPlayers, 3600);
                }
                $PlayersPointsArr = array_column($MatchPlayers, 'TotalPoints', 'PlayerGUID');
                $PlayersIdsArr    = array_column($MatchPlayers, 'PlayerID', 'PlayerGUID');

                /* Player Points Multiplier */
                $PositionPointsMultiplier = (IS_VICECAPTAIN) ? array('ViceCaptain' => $ViceCaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Captain' => $CaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Player' => 1) : array('Captain' => $CaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Player' => 1);
                $UserTotalPoints = 0;
                $UserPlayersArr  = array();

                /* To Get User Team Players */
                foreach (json_decode($Value['UserPlayersJSON'], TRUE) as $UserTeamValue) {
                    if (!isset($PlayersPointsArr[$UserTeamValue['PlayerGUID']]))
                        continue;

                    $Points = ($PlayersPointsArr[$UserTeamValue['PlayerGUID']] != 0) ? $PlayersPointsArr[$UserTeamValue['PlayerGUID']] * $PositionPointsMultiplier[$UserTeamValue['PlayerPosition']] : 0;
                    $UserTotalPoints = ($Points > 0) ? $UserTotalPoints + $Points : $UserTotalPoints - abs($Points);
                    $UserPlayersArr[] = array('PlayerGUID' => $UserTeamValue['PlayerGUID'], 'PlayerID' => $PlayersIdsArr[$UserTeamValue['PlayerGUID']], 'PlayerName' => $UserTeamValue['PlayerName'], 'PlayerPic' => $UserTeamValue['PlayerPic'], 'PlayerPosition' => $UserTeamValue['PlayerPosition'], 'PlayerRole' => $UserTeamValue['PlayerRole'], 'TeamGUID' => $UserTeamValue['TeamGUID'], 'Points' => (float)$Points);
                }

                /* Add/Edit Joined Contest Data (MongoDB) */
                $ContestCollection = $this->fantasydb->{'Contest_' . $Value['ContestID']};
                $ContestCollection->updateOne(
                    ['_id'    => (int)$Value['ContestID'] . $Value['UserID'] . $Value['UserTeamID']],
                    ['$set'   => ['ContestID' => $Value['ContestID'], 'UserID' =>  $Value['UserID'], 'UserTeamID' => $Value['UserTeamID'], 'UserGUID' => $Value['UserGUID'], 'UserTeamName' => $Value['UserTeamName'], 'Username' => $Value['Username'], 'FullName' => $Value['FullName'], 'ProfilePic' => $Value['ProfilePic'], 'TotalPoints' => $UserTotalPoints, 'UserTeamPlayers' => $UserPlayersArr, 'IsWinningAssigned' => 'No']],
                    ['upsert' => true]
                );
            }

            /* Update User Rank (MongoDB) */
            foreach (array_unique($ContestIdArr) as $ContestID) {
                $ContestCollection = $this->fantasydb->{'Contest_' . $ContestID};
                $ContestData  = $ContestCollection->find([], ['projection' => ['TotalPoints' => 1], 'sort' => ['TotalPoints' => -1]]);
                $PrevPoint    = $PrevRank = 0;
                $SkippedCount = 1;
                foreach ($ContestData as $ContestValue) {
                    if ($PrevPoint != $ContestValue['TotalPoints']) {
                        $PrevRank  = $PrevRank + $SkippedCount;
                        $PrevPoint = $ContestValue['TotalPoints'];
                        $SkippedCount = 1;
                    } else {
                        $SkippedCount++;
                    }
                    $ContestCollection->updateOne(
                        ['_id'    => $ContestValue['_id']],
                        ['$set'   => ['UserRank' => $PrevRank]],
                        ['upsert' => false]
                    );
                }
            }
        }
    }

    /*
      Description: To set contest winners
    */
    function setContestWinners($CronID)
    {
        /* Get Completed Contests */
        $Contests = $this->db->query('SELECT C.WinningAmount,C.ContestID,C.CustomizeWinning FROM tbl_entity E,sports_contest C WHERE E.EntityID = C.ContestID AND E.StatusID = 5 AND C.IsWinningAssigned = "No" AND C.LeagueType = "Dfs"');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {

                /* Get Joined Contests */
                $ContestCollection   = $this->fantasydb->{'Contest_' . $Value['ContestID']};
                $JoinedContestsUsers = iterator_to_array($ContestCollection->find(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "No", "TotalPoints" => ['$gt' => 0]], ['projection' => ['UserRank' => 1, 'UserTeamID' => 1, 'TotalPoints' => 1, 'UserID' => 1], 'sort' => ['UserRank' => -1]]));
                $AllUsersRank   = array_column($JoinedContestsUsers, 'UserRank');
                $AllRankWinners = array_count_values($AllUsersRank);
                if (count($AllRankWinners) == 0) {

                    /* Update Contest Winning Assigned Status */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningAssigned' => "Yes"));
                    continue;
                }
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
                    $userWinnersData[] = $this->findKeyValueArray($JoinedContestsUsers, $Rank, $AmountPerUser);
                }
                foreach ($userWinnersData as $WinnerArray) {
                    foreach ($WinnerArray as $WinnerRow) {
                        $OptionWinner[] = $WinnerRow;
                    }
                }
                if (!empty($OptionWinner)) {
                    foreach ($OptionWinner as $WinnerValue) {

                        /* Update User Winning Amount (MongoDB) */
                        $ContestCollection->updateOne(
                            ['_id'    => $Value['ContestID'] . $WinnerValue['UserID'] . $WinnerValue['UserTeamID']],
                            ['$set'   => ['UserWinningAmount' => round($WinnerValue['UserWinningAmount'], 2), 'IsWinningAssigned' => 'Yes']],
                            ['upsert' => false]
                        );
                    }
                }
            }
        }
    }

    /*
      Description: To transfer joined contest data (MongoDB To MySQL).
     */
    function tranferJoinedContestData($CronID)
    {
        /* Get Contests Data */
        $Contests = $this->db->query('SELECT C.ContestID FROM sports_contest C WHERE C.IsWinningAssigned = "Yes" AND C.LeagueType = "Dfs" LIMIT 10');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {

                /* Get Joined Contests */
                $ContestCollection   = $this->fantasydb->{'Contest_' . $Value['ContestID']};
                $JoinedContestsUsers = $ContestCollection->find(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "Yes"], ['projection' => ['ContestID' => 1, 'UserID' => 1, 'UserTeamID' => 1, 'UserTeamPlayers' => 1, 'TotalPoints' => 1, 'UserRank' => 1, 'UserWinningAmount' => 1]]);
                if ($ContestCollection->count(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "Yes"]) == 0) {

                    /* Update Contest Winning Assigned Status */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningAssigned' => "Moved"));
                    continue;
                }

                foreach ($JoinedContestsUsers as $JC) {

                    /* Update User Team Player Points */
                    // $Query = 'UPDATE sports_users_team_players SET Points = CASE';
                    // foreach($JC['UserTeamPlayers'] as $Player){
                    //     $Query .= ' WHEN UserTeamID = '.$JC['UserTeamID'].' AND PlayerID = '.$Player['PlayerID'].' THEN '.$Player['Points'];
                    // }
                    // $Query .= ' ELSE Points END;';
                    // $this->db->query($Query);

                    /* Update MySQL Row */
                    $this->db->where(array('UserID' => $JC['UserID'], 'ContestID' => $JC['ContestID'], 'UserTeamID' => $JC['UserTeamID']));
                    $this->db->limit(1);
                    $this->db->update('sports_contest_join', array('TotalPoints' => $JC['TotalPoints'], 'UserRank' => $JC['UserRank'], 'UserWinningAmount' => $JC['UserWinningAmount'], 'IsWinningAssigned' => 'Yes', 'ModifiedDate' => date('Y-m-d H:i:s')));

                    /* Update MongoDB Row */
                    $ContestCollection->updateOne(
                        ['_id'    => $JC['_id']],
                        ['$set'   => ['IsWinningAssigned' => 'Moved']],
                        ['upsert' => false]
                    );
                }
            }
        }
    }

    /*
      Description: To set contest winners amount distribute
     */
    function amountDistributeContestWinner($CronID)
    {
        /* Get Contests Data */
        $Contests = $this->db->query('SELECT C.ContestID FROM sports_contest C,tbl_entity E WHERE C.ContestID = E.EntityID AND E.StatusID = 5 AND C.IsWinningDistributed = "No" AND C.IsWinningAssigned = "Moved" AND C.LeagueType = "Dfs" LIMIT 10');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {

                /* Get Joined Contest Users */
                $JoinedContests = $this->db->query('SELECT JC.UserID,JC.UserTeamID,JC.UserWinningAmount FROM sports_contest_join JC WHERE JC.IsWinningAssigned = "Yes" AND JC.IsWinningDistributed = "No" AND JC.UserWinningAmount > 0 AND JC.ContestID = ' . $Value['ContestID']);
                if ($JoinedContests->num_rows() == 0) {

                    /* Update Contest Winning Distribute Status */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningDistributed' => "Yes"));
                    continue;
                }

                foreach ($JoinedContests->result_array() as $WinnerValue) {

                    $this->db->trans_start();

                    /* Update user wallet */
                    $WalletData = array(
                        "Amount"          => $WinnerValue['UserWinningAmount'],
                        "WinningAmount"   => $WinnerValue['UserWinningAmount'],
                        "EntityID"        => $Value['ContestID'],
                        "UserTeamID"      => $WinnerValue['UserTeamID'],
                        "TransactionType" => 'Cr',
                        "Narration"       => 'Join Contest Winning',
                        "EntryDate"       => date("Y-m-d H:i:s")
                    );
                    $this->Users_model->addToWallet($WalletData, $WinnerValue['UserID'], 5);

                    /* Update Joined Contest Winning Distribute Status */
                    $this->db->where(array('UserID' => $WinnerValue['UserID'], 'ContestID' => $Value['ContestID'], 'UserTeamID' => $WinnerValue['UserTeamID']));
                    $this->db->limit(1);
                    $this->db->update('sports_contest_join', array('IsWinningDistributed' => "Yes", 'ModifiedDate' => date('Y-m-d H:i:s')));

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    }
                }
            }
        }
    }

    /*
      Description: To Auto Cancel Contest
    */
    function autoCancelContest($CronID, $CancelType = "Cancelled", $MatchID = "")
    {
        ini_set('max_execution_time', 300);

        /* Get Contest Data */
        if (!empty($MatchID)) {
            $ContestsUsers = $this->db->query('SELECT C.ContestID,C.Privacy,C.EntryFee,C.ContestFormat,C.ContestSize,C.IsConfirm,M.MatchStartDateTime,(SELECT COUNT(TotalPoints) FROM sports_contest_join WHERE ContestID =  C.ContestID ) TotalJoined FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND C.MatchID = ' . $MatchID . ' AND E.StatusID IN(1,2) AND LeagueType = "Dfs" AND DATE(M.MatchStartDateTime) <= "' . date('Y-m-d') . '" ORDER BY M.MatchStartDateTime ASC');
        } else {
            $ContestsUsers = $this->db->query('SELECT C.ContestID,C.Privacy,C.EntryFee,C.ContestFormat,C.ContestSize,C.IsConfirm,M.MatchStartDateTime,(SELECT COUNT(TotalPoints) FROM sports_contest_join WHERE ContestID =  C.ContestID ) TotalJoined FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND E.StatusID IN(1,2) AND LeagueType = "Dfs" AND DATE(M.MatchStartDateTime) <= "' . date('Y-m-d') . '" ORDER BY M.MatchStartDateTime ASC');
        }
        if ($ContestsUsers->num_rows() == 0) {
            return FALSE;
        }

        foreach ($ContestsUsers->result_array() as $Value) {
            if ($CancelType == "Cancelled") {
                if (((strtotime($Value['MatchStartDateTime']) - 19800) - strtotime(date('Y-m-d H:i:s'))) > 0) {
                    continue;
                }

                /* To check contest cancel condition */
                $IsCancelled = 0;
                if ($Value['Privacy'] == 'Yes') { // Should be 100% filled
                    $IsCancelled = ($Value['ContestSize'] != $Value['TotalJoined']) ? 1 : 0;
                } else {
                    if ($Value['ContestFormat'] == 'Head to Head') {
                        $IsCancelled = ($Value['TotalJoined'] == 2) ? 0 : 1;
                    } else {
                        $IsCancelled = ($Value['IsConfirm'] == 'Yes') ? 0 : (((($Value['TotalJoined'] * 100) / $Value['ContestSize']) >= CONTEST_FILL_PERCENT_LIMIT) ? 0 : 1);
                    }
                }
                if ($IsCancelled == 0) {
                    continue;
                }
            }

            /* Update Contest Status */
            $this->db->where('EntityID', $Value['ContestID']);
            $this->db->limit(1);
            $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));
        }
    }

    /*
      Description: To set refund contest amount
     */
    function refundAmountCancelContest($CronID)
    {
        /* Get Contest Data */
        $ContestData = $this->db->query('SELECT C.ContestID,C.EntryFee FROM sports_contest C, tbl_entity E WHERE E.EntityID = C.ContestID AND C.IsRefund = "No" AND C.LeagueType = "Dfs" AND E.StatusID = 3 AND C.EntryFee > 0');
        if ($ContestData->num_rows() > 0) {
            foreach ($ContestData->result_array() as $Value) {

                /* Get Joined Contest Users */
                $JoinedContestsUsers = $this->db->query('SELECT UserID,UserTeamID FROM sports_contest_join WHERE ContestID = ' . $Value['ContestID'] . ' AND IsRefund = "No" ');
                if ($JoinedContestsUsers->num_rows() == 0) {

                    /* Update Contest Refund Status Yes */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsRefund' => "Yes"));
                    continue;
                }

                foreach ($JoinedContestsUsers->result_array() as $JoinValue) {

                    /* Get Wallet Details */
                    $WalletQuery = $this->db->query('SELECT WalletAmount,WinningAmount,CashBonus FROM tbl_users_wallet WHERE UserID = ' . $JoinValue['UserID'] . ' AND EntityID = ' . $Value['ContestID'] . ' AND UserTeamID = ' . $JoinValue['UserTeamID'] . ' AND Narration = "Cancel Contest" LIMIT 1');
                    if ($WalletQuery->num_rows() > 0) {

                        /* Update Join Contest Refund Status Yes */
                        $this->db->where(array('ContestID' => $Value['ContestID'], 'UserTeamID' => $JoinValue['UserTeamID'], 'UserID' => $JoinValue['UserID']));
                        $this->db->limit(1);
                        $this->db->update('sports_contest_join', array('IsRefund' => "Yes", 'ModifiedDate' => date('Y-m-d H:i:s')));
                        continue;
                    }

                    /* Get Join Contest Wallet Details */
                    $WalletDetails = $this->db->query('SELECT WalletAmount,WinningAmount,CashBonus FROM tbl_users_wallet WHERE Narration = "Join Contest" AND UserTeamID = ' . $JoinValue['UserTeamID'] . ' AND EntityID = ' . $Value['ContestID'] . ' AND UserID = ' . $JoinValue['UserID'] . ' LIMIT 1');

                    /* Refund User Amount */
                    $InsertData = array(
                        "Amount"          => $WalletDetails['WalletAmount'] + $WalletDetails['WinningAmount'] + $WalletDetails['CashBonus'],
                        "WalletAmount"    => $WalletDetails['WalletAmount'],
                        "WinningAmount"   => $WalletDetails['WinningAmount'],
                        "CashBonus"       => $WalletDetails['CashBonus'],
                        "TransactionType" => 'Cr',
                        "EntityID"        => $Value['ContestID'],
                        "UserTeamID"      => $JoinValue['UserTeamID'],
                        "Narration"       => 'Cancel Contest',
                        "EntryDate"       => date("Y-m-d H:i:s")
                    );
                    $this->Users_model->addToWallet($InsertData, $JoinValue['UserID'], 5);

                    /* Update Join Contest Refund Status Yes */
                    $this->db->where(array('ContestID' => $Value['ContestID'], 'UserTeamID' => $JoinValue['UserTeamID'], 'UserID' => $JoinValue['UserID']));
                    $this->db->limit(1);
                    $this->db->update('sports_contest_join', array('IsRefund' => "Yes", 'ModifiedDate' => date('Y-m-d H:i:s')));

                    /* Send Refund Notification */
                    $this->Notification_model->addNotification('refund', 'Contest Cancelled Refund', $JoinValue['UserID'], $JoinValue['UserID'], $Value['ContestID'], 'Your ' . DEFAULT_CURRENCY . $InsertData['Amount'] . ' has been refunded.');
                }
            }
        }
    }

    /*
      Description: Use to auction draft play.
     */
    function updateAuctionPlayStatus($SeriesID, $Input = array())
    {
        if (!empty($Input)) {
            $this->db->where('SeriesID', $SeriesID);
            $this->db->limit(1);
            $this->db->update('sports_series', $Input);
        }
    }

    /*
      Description: 	Cron jobs to get auction joined player points.
     */
    function getAuctionJoinedUserTeamsPlayerPoints($CronID)
    {
        /** get series play in auction * */
        $SeriesData = $this->getSeries('SeriesIDLive,SeriesID', array('StatusID' => 2, 'SeriesEndDate' => date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))), "AuctionDraftIsPlayed" => "Yes"), true, 0);
        if ($SeriesData['Data']['TotalRecords'] == 0) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }

        foreach ($SeriesData['Data']['Records'] as $Rows) {
            /* Get Matches Live */
            $LiveMatcheContest = $this->AuctionDrafts_model->getContests('ContestID,SeriesID', array('StatusID' => 1, "AuctionStatusID" => 5, "LeagueType" => "Auction", "SeriesID" => $Rows['SeriesID']), true, 0);

            foreach ($LiveMatcheContest['Data']['Records'] as $Value) {
                $MatchIDLive = $Value['MatchIDLive'];
                $SeriesID = $Value['SeriesID'];
                $ContestID = $Value['ContestID'];
                $Contests = $this->AuctionDrafts_model->getJoinedContests('ContestID,UserID,UserTeamID', array('StatusID' => 1, 'ContestID' => $ContestID, "SeriesID" => $SeriesID), true, 0);

                if (!empty($Contests['Data']['Records'])) {
                    /* Get Vice Captain Points */
                    $ViceCaptainPointsData = $this->db->query('SELECT * FROM sports_setting_points WHERE PointsTypeGUID = "ViceCaptainPointMP" LIMIT 1')->row_array();

                    /* Get Captain Points */
                    $CaptainPointsData = $this->db->query('SELECT * FROM sports_setting_points WHERE PointsTypeGUID = "CaptainPointMP" LIMIT 1')->row_array();

                    /* Match Types */
                    $MatchTypesArr = array('1' => 'PointsODI', '3' => 'PointsT20', '4' => 'PointsT20', '5' => 'PointsTEST', '7' => 'PointsT20', '9' => 'PointsODI');
                    $ContestIDArray = array();
                    foreach ($Contests['Data']['Records'] as $Value) {
                        $ContestIDArray[] = $Value['ContestID'];
                        /* Player Points Multiplier */
                        $UserID = $Value["UserID"];
                        $PositionPointsMultiplier = array('ViceCaptain' => 1.5, 'Captain' => 2, 'Player' => 1);
                        $UserTotalPoints = 0;

                        /* To Get Match Players */
                        $MatchPlayers = $this->AuctionDrafts_model->getAuctionPlayersPoints('SeriesID,PlayerID,TotalPoints', array('SeriesID' => $SeriesID), true, 0);

                        $PlayersPointsArr = array_column($MatchPlayers['Data']['Records'], 'TotalPoints', 'PlayerGUID');
                        $PlayersIdsArr = array_column($MatchPlayers['Data']['Records'], 'PlayerID', 'PlayerGUID');

                        /* To Get User Team Players */
                        $UserTeamPlayers = $this->AuctionDrafts_model->getUserTeams('PlayerID,PlayerPosition,UserTeamPlayers', array('UserTeamID' => $Value['UserTeamID']), 0);
                        foreach ($UserTeamPlayers['UserTeamPlayers'] as $UserTeamValue) {
                            if (!isset($PlayersPointsArr[$UserTeamValue['PlayerGUID']]))
                                continue;

                            $Points = ($PlayersPointsArr[$UserTeamValue['PlayerGUID']] != 0) ? $PlayersPointsArr[$UserTeamValue['PlayerGUID']] * $PositionPointsMultiplier[$UserTeamValue['PlayerPosition']] : 0;
                            $UserTotalPoints = ($Points > 0) ? $UserTotalPoints + $Points : $UserTotalPoints - abs($Points);

                            /* Update Player Points */
                            $this->db->where('UserTeamID', $Value['UserTeamID']);
                            $this->db->where('PlayerID', $PlayersIdsArr[$UserTeamValue['PlayerGUID']]);
                            $this->db->limit(1);
                            $this->db->update('sports_users_team_players', array('Points' => $Points));
                        }

                        $UserTeamPlayersTotal = $this->AuctionDrafts_model->getUserTeamPlayersAuction('TotalPoints', array('UserTeamID' => $Value['UserTeamID']));

                        if (!empty($UserTeamPlayersTotal)) {
                            $UserTotalPoints = $UserTeamPlayersTotal[0]['TotalPoints'];
                        }
                        /* Update Player Total Points */
                        $this->db->where('UserID', $UserID);
                        $this->db->where('ContestID', $Value['ContestID']);
                        $this->db->limit(1);
                        $this->db->update('sports_contest_join', array('TotalPoints' => $UserTotalPoints, 'ModifiedDate' => date('Y-m-d H:i:s')));
                        $AB[] = $this->db->last_query();
                    }
                    $ContestIDArray = array_unique($ContestIDArray);
                }
                $this->updateRankByContest($ContestID);
            }
        }
    }

    /* -----Custom Array Functions----- */
    /* ------------------------------ */

    /*
      Description: To common funtion find key value
    */
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
      Description: Find sub arrays from multidimensional array
    */
    function findSubArray($DataArray, $keyName, $Value)
    {
        if (is_array($DataArray)) {
            $Data = array();
            foreach ($DataArray as $Row) {
                if ($Row[$keyName] == $Value)
                    $Data[] = $Row;
            }
            return $Data;
        }
        return FALSE;
    }
}
