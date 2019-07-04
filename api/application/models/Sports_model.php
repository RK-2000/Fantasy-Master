<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sports_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('AuctionDrafts_model');

        $this->IsStrikeRate = $this->IsEconomyRate = $this->IsBattingState = $this->IsBowlingState = "0";
        $this->defaultStrikeRatePoints = $this->defaultEconomyRatePoints = $this->defaultBattingPoints = $this->defaultBowlingPoints = array();
    }

    /*
      Description: To get all series
     */

    function getSeries($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'SeriesID' => 'S.SeriesID',
                'StatusID' => 'E.StatusID',
                'SeriesIDLive' => 'S.SeriesIDLive',
                'AuctionDraftIsPlayed' => 'S.AuctionDraftIsPlayed',
                'DraftUserLimit' => 'S.DraftUserLimit',
                'DraftTeamPlayerLimit' => 'S.DraftTeamPlayerLimit',
                'DraftPlayerSelectionCriteria' => 'S.DraftPlayerSelectionCriteria',
                'SeriesStartDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesStartDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesStartDate',
                'SeriesEndDate' => 'DATE_FORMAT(CONVERT_TZ(S.SeriesEndDate,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") SeriesEndDate',
                'TotalMatches' => '(SELECT COUNT(*) AS TotalMatches
                FROM sports_matches
                WHERE sports_matches.SeriesID =  S.SeriesID ) AS TotalMatches',
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
        } else {
            if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
                $this->db->order_by('DATE(S.SeriesEndDate)="' . date('Y-m-d') . '" ASC', null, FALSE);
                $this->db->order_by('E.StatusID=2 DESC', null, FALSE);
            } else {
                $this->db->order_by('E.StatusID', 'ASC');
                $this->db->order_by('S.SeriesStartDate', 'DESC');
                $this->db->order_by('S.SeriesName', 'ASC');
            }
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
                $Return['Data']['Records'] = $Query->result_array();
                return $Return;
            } else {
                return $Query->row_array();
            }
        }
        return FALSE;
    }

    /*
      Description: Custom query set
     */

    public function customQuery($Sql, $Single = false, $UpdDelete = false, $NoReturn = false) {
        $Query = $this->db->query($Sql);
        if ($Single) {
            return $Query->row_array();
        } elseif ($UpdDelete) {
            return $this->db->affected_rows();
        } elseif (!$NoReturn) {
            return $Query->result_array();
        } else {
            return true;
        }
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
        // $this->db->cache_on(); //Turn caching on
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
                'IsPreSquad' => 'M.IsPreSquad',
                'IsPlayerPointsUpdated' => 'M.IsPlayerPointsUpdated',
                'MatchScoreDetails' => 'M.MatchScoreDetails',
                'MatchClosedInMinutes' => 'M.MatchClosedInMinutes',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . '") MatchStartDateTime',
                'CurrentDateTime' => 'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") CurrentDateTime',
                'MatchDate' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%Y-%m-%d") MatchDate',
                'MatchTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%H:%i:%s") MatchTime',
                'MatchStartDateTimeUTC' => 'M.MatchStartDateTime as MatchStartDateTimeUTC',
                'ServerDateTimeUTC' => 'UTC_TIMESTAMP() as ServerDateTimeUTC',
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
                'TotalUserWinning' => '(select SUM(UserWinningAmount) from sports_contest_join where MatchID = M.MatchID AND UserID=' . @$Where['SessionUserID'] . ') as TotalUserWinning',
                'isJoinedContest' => '(select count(*) from sports_contest_join where MatchID = M.MatchID AND UserID = "' . @$Where['SessionUserID'] . '" AND E.StatusID=' . @$Where['StatusID'] . ') as JoinedContests'
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
        $this->db->from('tbl_entity E, sports_series S, sports_matches M, sports_teams TL, sports_teams TV, sports_set_match_types MT');
        $this->db->where("M.SeriesID", "S.SeriesID", FALSE);
        $this->db->where("M.MatchID", "E.EntityID", FALSE);
        $this->db->where("M.MatchTypeID", "MT.MatchTypeID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        if (!empty($Where['Keyword'])) {
            $this->db->group_start();
            $this->db->like("S.SeriesName", $Where['Keyword']);
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
            $this->db->where('EXISTS (select 1 from sports_contest_join J where J.MatchID = M.MatchID AND J.UserID=' . $Where['UserID'] . ')');
        }
        if (!empty($Where['StatusID'])) {
            if ($Where['StatusID'] == 2) {
                $Where['StatusID'] = array(2, 10);
                $this->db->where_in("E.StatusID", $Where['StatusID']);
            } else {
                $this->db->where_in("E.StatusID", $Where['StatusID']);
            }
        }
        if (!empty($Where['CronFilter']) && $Where['CronFilter'] == 'OneDayDiff') {
            $this->db->having("LastUpdateDiff", 0);
            $this->db->or_having("LastUpdateDiff >=", 86400); // 1 Day
        }
        /* if (!empty($Where['existingContests'])) {
          $StatusID = $Where['StatusID'];
          print_r($StatusID);
          $this->db->where('EXISTS (select MatchID from sports_contest where MatchID = M.MatchID AND E.StatusID IN(2,10)');
          } */

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
                $this->db->order_by('DATE(M.MatchStartDateTime)="' . date('Y-m-d') . '" DESC', null, FALSE);
                $this->db->order_by('E.StatusID=1 DESC', null, FALSE);
            } else {
                $this->db->order_by('E.StatusID', 'ASC');
                $this->db->order_by('M.MatchStartDateTime', 'ASC');
            }
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
        // echo $this->db->last_query();exit;
        // if ($Query->num_rows() > 0) {
        if ($multiRecords) {
            if ($Query->num_rows() > 0) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                }
                $Return['Data']['Records'] = $Records;
            }
            if (!empty($Where['MyJoinedMatchesCount']) && $Where['MyJoinedMatchesCount'] == 1) {
                $Return['Data']['Statics'] = $this->db->query('SELECT (
                            SELECT COUNT(DISTINCT M.MatchID) AS `UpcomingJoinedContest` FROM `sports_matches` M
                            JOIN `sports_contest_join` J ON M.MatchID = J.MatchID JOIN `tbl_entity` E ON E.EntityID = J.ContestID WHERE E.StatusID = 1 AND J.UserID ="' . @$Where['UserID'] . '" 
                        )as UpcomingJoinedContest,
                        ( SELECT COUNT(DISTINCT M.MatchID) AS `LiveJoinedContest` FROM `sports_matches` M JOIN `sports_contest_join` J ON M.MatchID = J.MatchID JOIN `tbl_entity` E ON E.EntityID = J.ContestID WHERE  E.StatusID IN (2,10) AND J.UserID = "' . @$Where['UserID'] . '" 
                        )as LiveJoinedContest,
                        ( SELECT COUNT(DISTINCT J.MatchID) AS `CompletedJoinedContest` FROM `sports_contest_join` J, `tbl_entity` E, `sports_matches` M WHERE E.EntityID = M.MatchID AND J.MatchID=M.MatchID AND E.StatusID IN (5,10) AND J.UserID = "' . @$Where['UserID'] . '" 
                    )as CompletedJoinedContest'
                        )->row();
            }
            // echo $this->db->last_query(); die();
            return $Return;
        } else {
            if ($Query->num_rows() > 0) {
                $Record = $Query->row_array();
                $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                return $Record;
            }
        }
        // }
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
                'SeriesName' => '(SELECT SeriesName FROM `sports_series` WHERE `SeriesID` = M.SeriesID) SeriesName',
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
        if (!empty($Where['SeriesName']) && $Where['SeriesName'] == 'Yes' || !empty($Where['SeriesID'])) {
            $this->db->from('sports_matches M, sports_matches V');
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->from('sports_team_players TP');
        }
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
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['SeriesName']) && $Where['SeriesName'] == 'Yes') {
            $this->db->where("M.TeamIDLocal", "T.TeamID", false);
            $this->db->where("V.TeamIDVisitor", "T.TeamID", false);
            $this->db->group_by("T.TeamGUID");
        }
        if (!empty($Where['SeriesID']) && empty($Where['LocalTeamGUID'])) {
            $this->db->where("TP.SeriesID", $Where['SeriesID']);
            $this->db->where("TP.TeamID", 'T.TeamID', false);
            $this->db->where("M.TeamIDLocal", "T.TeamID", false);
            $this->db->group_by("T.TeamGUID");
        }
        if (!empty($Where['LocalTeamGUID'])) {
            $this->db->where("M.TeamIDLocal", $Where['LocalTeamGUID']);
            $this->db->where("TP.SeriesID", $Where['SeriesID']);
            $this->db->where("TP.TeamID", 'T.TeamID', false);
            $this->db->where("M.TeamIDVisitor", "T.TeamID", false);
            $this->db->group_by("T.TeamGUID");
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
        // $this->db->cache_on(); //Turn caching on
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
      Description : To Update Team Details
     */

    function updateTeamDetails($TeamID, $Input = array()) {
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
      Description : To Update Match Details
     */

    function updateMatchDetails($MatchID, $Input = array()) {
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
      Description: To get all players
     */

    function getPlayers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
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
                // 'PlayerPic' => 'IF(P.PlayerPic IS NULL,CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png"),CONCAT("' . BASE_URL . '","uploads/PlayerPic/",P.PlayerPic)) AS PlayerPic',
                'PlayerPic' => 'IF(P.PlayerPic IS NULL,CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png"),CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png")) AS PlayerPic',
                'PlayerCountry' => 'P.PlayerCountry',
                'PlayerBattingStyle' => 'P.PlayerBattingStyle',
                'PlayerBowlingStyle' => 'P.PlayerBowlingStyle',
                'PlayerBattingStats' => 'P.PlayerBattingStats',
                'PlayerBowlingStats' => 'P.PlayerBowlingStats',
                'PlayerSalary' => 'FORMAT(TP.PlayerSalary,1) as PlayerSalary',
                'PlayerSalaryCredit' => 'FORMAT(TP.PlayerSalary,1) PlayerSalaryCredit',
                'LastUpdateDiff' => 'IF(P.LastUpdatedOn IS NULL, 0, TIME_TO_SEC(TIMEDIFF("' . date('Y-m-d H:i:s') . '", P.LastUpdatedOn))) LastUpdateDiff',
                'MatchTypeID' => 'SSM.MatchTypeID',
                'MatchType' => 'SSM.MatchTypeName as MatchType',
                'TotalPointCredits' => '(SELECT SUM(`TotalPoints`) FROM `sports_team_players` WHERE `PlayerID` = TP.PlayerID AND `SeriesID` = TP.SeriesID) TotalPointCredits'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('DISTINCT(P.PlayerGUID),P.PlayerName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_players P');
        if (array_keys_exist($Params, array('TeamGUID', 'TeamName', 'TeamNameShort', 'TeamFlag', 'PlayerRole', 'IsPlaying', 'TotalPoints', 'PointsData', 'SeriesID', 'MatchID','PlayerSalary'))) {
            $this->db->from('sports_teams T,sports_matches M, sports_team_players TP,sports_set_match_types SSM');
            $this->db->where("P.PlayerID", "TP.PlayerID", FALSE);
            $this->db->where("TP.TeamID", "T.TeamID", FALSE);
            $this->db->where("TP.MatchID", "M.MatchID", FALSE);
            $this->db->where("M.MatchTypeID", "SSM.MatchTypeID", FALSE);
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
        if (array_keys_exist($Params, array('SeriesGUID'))) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "TP.SeriesID", FALSE);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("TP.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("TP.SeriesID", $Where['SeriesID']);
            $this->db->group_by("P.PlayerGUID");
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
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }

        if (!empty($Where['RandData'])) {
            $this->db->order_by($Where['RandData']);
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
                        $this->db->where('SUT.UserID',$Where['SessionUserID']);
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
                    $this->db->where('SUT.UserID',$Where['SessionUserID']);
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
      Description: Use to auction draft play.
     */

    function updateAuctionPlayStatus($SeriesID, $Input = array()) {
        if (!empty($Input)) {
            $this->db->where('SeriesID', $SeriesID);
            $this->db->limit(1);
            $this->db->update('sports_series', $Input);
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

    function updatePlayerSalaryMatch($Input = array(), $PlayerID, $MatchID) {
        if (!empty($Input)) {
            $UpdateData = array(
                'PlayerSalary' => $Input['PlayerSalaryCredit'],
                'IsAdminUpdate' => 'Yes'
            );
            $this->db->where('PlayerID', $PlayerID);
            $this->db->where('MatchID', $MatchID);
            $this->db->limit(1);
            $this->db->update('sports_team_players', $UpdateData);
        }
    }

    

    

    


    /*
      Description: To get player points
     */

    function getPlayerPoints($CronID,$MatchID = "") {

        /* Get Live Matches Data */
        $LiveMatches = $this->getMatches('MatchID,MatchType,MatchScoreDetails,StatusID,IsPlayerPointsUpdated', array('Filter' => 'Yesterday', 'StatusID' => array(2, 5, 10), 'IsPlayerPointsUpdated' => 'No', 'OrderBy' => 'M.MatchStartDateTime', 'Sequence' => 'DESC','MatchID' => $MatchID), true, 1, 10);
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
                if (empty((array) $Value['MatchScoreDetails'])){
                    continue;
                }

                /* Delete Cache Key */
                $this->cache->memcached->delete('getJoinedContestPlayerPoints_'.$Value['MatchID']);
                $StatringXIPoints          = (isset($StatringXIArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($StatringXIArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $CaptainPointMPPoints      = (isset($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($CaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "2";
                $ViceCaptainPointMPPoints  = (isset($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($ViceCaptainPointMPArr[0][$MatchTypes[$Value['MatchType']]]) : "1.5";
                $BattingMinimumRunsPoints  = (isset($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]])) ? strval($BattingMinimumRunsArr[0][$MatchTypes[$Value['MatchType']]]) : "15";
                $MinimumRunScoreStrikeRate = (isset($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumRunScoreStrikeRate[0][$MatchTypes[$Value['MatchType']]]) : "10";
                $MinimumOverEconomyRate    = (isset($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]])) ? strval($MinimumOverEconomyRate[0][$MatchTypes[$Value['MatchType']]]) : "1";

                /* Get Match Players */
                $MatchPlayers = $this->cache->memcached->get('PlayerPoints_'.$Value['MatchID']);
                if(empty($MatchPlayers)){
                    $MatchPlayers = $this->db->query('SELECT P.`PlayerID`,P.`PlayerIDLive`,TP.`PlayerRole` FROM `sports_players` P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = '.$Value['MatchID'].' AND TP.IsPlaying = "Yes" LIMIT 50'); 
                    if ($MatchPlayers->num_rows() == 0) {
                        continue;
                    }else{
                        $this->cache->memcached->save('PlayerPoints_'.$Value['MatchID'],$MatchPlayers->result_array(),3600*10); // Expire in every 10 hours
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
                    $this->db->where(array('MatchID' => $Value['MatchID'],'PlayerID' => $PlayerValue['PlayerID']));
                    $this->db->limit(1);
                    $this->db->update('sports_team_players', array('TotalPoints' => $PlayerTotalPoints,'PointsData' => (!empty($PointsData)) ? json_encode($PointsData) : null));
                }

                /* Update Final player points before complete match */
                $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
                $this->getJoinedContestPlayerPoints($CronID,array(2),$Value['MatchID']);
                $this->Utility_model->updateCronLogs($CronID);

                /* Update Match Player Points Status */
                if ($Value['StatusID'] == 5) {
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
      Description: To get joined contest player points
     */

    function getJoinedContestPlayerPoints($CronID, $StatusArr = array(2),$MatchID = "") {

        ini_set('memory_limit','512M');

        /* Get Live Contests */
        $Query = "SELECT M.MatchTypeID, M.MatchID, JC.ContestID, JC.UserID, JC.UserTeamID,U.UserGUID,UT.UserTeamName,U.Username,CONCAT_WS(' ',U.FirstName,U.LastName) FullName,IF(U.ProfilePic IS NULL,CONCAT('" . BASE_URL . "','uploads/profile/picture/','default.jpg'),CONCAT('" . BASE_URL . "','uploads/profile/picture/',U.ProfilePic)) ProfilePic, ( SELECT CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'PlayerGUID', P.PlayerGUID, 'PlayerName', P.PlayerName,'PlayerRole',TP.PlayerRole,'TeamGUID', T.TeamGUID,'PlayerPic',IF(P.PlayerPic IS NULL,CONCAT('" . BASE_URL . "','uploads/PlayerPic/','player.png'),CONCAT('" . BASE_URL . "','uploads/PlayerPic/',P.PlayerPic)),'PlayerPosition', UTP.PlayerPosition ) ), ']' ) FROM sports_players P,sports_team_players TP,sports_teams T, sports_users_team_players UTP WHERE P.PlayerID = UTP.PlayerID AND UTP.MatchID = M.MatchID AND UTP.UserTeamID = JC.UserTeamID AND P.PlayerID = TP.PlayerID AND TP.MatchID = M.MatchID AND TP.TeamID = T.TeamID ) AS UserPlayersJSON FROM `sports_contest_join` JC, sports_matches M, tbl_entity E,tbl_users U,sports_users_teams UT WHERE JC.MatchID = M.MatchID AND E.EntityID = JC.ContestID AND UT.UserTeamID = JC.UserTeamID AND U.UserID = JC.UserID AND E.StatusID = 2 AND EXISTS( SELECT C.ContestID FROM tbl_entity E, sports_contest C, sports_matches M WHERE E.EntityID = C.ContestID AND C.MatchID = M.MatchID AND E.StatusID IN(".implode(',',$StatusArr).")";
        if(!empty($MatchID)){
            $Query .= " AND C.MatchID = ".$MatchID;
        }
        $Query .= " AND C.LeagueType = 'Dfs' AND DATE(M.MatchStartDateTime) <= '".date('Y-m-d')."' AND C.ContestID = JC.ContestID LIMIT 1) ORDER BY JC.ContestID";
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
                $MatchPlayers = $this->cache->memcached->get('getJoinedContestPlayerPoints_'.$Value['MatchID']);
                if(empty($MatchPlayers)){
                    $MatchPlayers = $this->db->query('SELECT P.PlayerGUID,TP.PlayerID,TP.TotalPoints FROM sports_players P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = '.$Value['MatchID'])->result_array();
                    $this->cache->memcached->save('getJoinedContestPlayerPoints_'.$Value['MatchID'],$MatchPlayers,3600);
                }
                $PlayersPointsArr = array_column($MatchPlayers, 'TotalPoints', 'PlayerGUID');
                $PlayersIdsArr    = array_column($MatchPlayers, 'PlayerID', 'PlayerGUID');

                /* Player Points Multiplier */
                $PositionPointsMultiplier = (IS_VICECAPTAIN) ? array('ViceCaptain' => $ViceCaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Captain' => $CaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Player' => 1) : array('Captain' => $CaptainPointsData[$MatchTypesArr[$Value['MatchTypeID']]], 'Player' => 1);
                $UserTotalPoints = 0;
                $UserPlayersArr  = array();

                /* To Get User Team Players */
                foreach (json_decode($Value['UserPlayersJSON'],TRUE) as $UserTeamValue) {
                    if (!isset($PlayersPointsArr[$UserTeamValue['PlayerGUID']]))
                        continue;

                    $Points = ($PlayersPointsArr[$UserTeamValue['PlayerGUID']] != 0) ? $PlayersPointsArr[$UserTeamValue['PlayerGUID']] * $PositionPointsMultiplier[$UserTeamValue['PlayerPosition']] : 0;
                    $UserTotalPoints = ($Points > 0) ? $UserTotalPoints + $Points : $UserTotalPoints - abs($Points);
                    $UserPlayersArr[] = array('PlayerGUID' => $UserTeamValue['PlayerGUID'],'PlayerID' => $PlayersIdsArr[$UserTeamValue['PlayerGUID']],'PlayerName' => $UserTeamValue['PlayerName'],'PlayerPic' => $UserTeamValue['PlayerPic'],'PlayerPosition' => $UserTeamValue['PlayerPosition'],'PlayerRole' => $UserTeamValue['PlayerRole'],'TeamGUID' => $UserTeamValue['TeamGUID'],'Points' => (float) $Points);
                }

                /* Add/Edit Joined Contest Data (MongoDB) */
                $ContestCollection = $this->fantasydb->{'Contest_'.$Value['ContestID']};
                $ContestCollection->updateOne(
                                ['_id'    => (int) $Value['ContestID'].$Value['UserID'].$Value['UserTeamID']],
                                ['$set'   => ['ContestID' => $Value['ContestID'],'UserID' =>  $Value['UserID'],'UserTeamID' => $Value['UserTeamID'],'UserGUID' => $Value['UserGUID'],'UserTeamName' => $Value['UserTeamName'],'Username' => $Value['Username'],'FullName' => $Value['FullName'],'ProfilePic' => $Value['ProfilePic'],'TotalPoints' => $UserTotalPoints,'UserTeamPlayers' => $UserPlayersArr,'IsWinningAssigned' => 'No']],
                                ['upsert' => true]
                            );
            }

            /* Update User Rank (MongoDB) */
            foreach(array_unique($ContestIdArr) as $ContestID){
                $ContestCollection = $this->fantasydb->{'Contest_'.$ContestID};
                $ContestData  = $ContestCollection->find([],['projection' => ['TotalPoints' => 1],'sort' => ['TotalPoints' => -1]]);
                $PrevPoint    = $PrevRank = 0;
                $SkippedCount = 1;
                foreach($ContestData as $ContestValue) {
                   if($PrevPoint != $ContestValue['TotalPoints']){
                        $PrevRank  = $PrevRank + $SkippedCount;
                        $PrevPoint = $ContestValue['TotalPoints'];
                        $SkippedCount = 1;
                   }else{
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
      Description: To update rank
     */

    function updateRankByContest($ContestID) {
        if (!empty($ContestID)) {
            $query = $this->db->query("SELECT FIND_IN_SET( TotalPoints, 
                         ( SELECT GROUP_CONCAT( TotalPoints ORDER BY TotalPoints DESC)
                         FROM sports_contest_join WHERE sports_contest_join.ContestID = '" . $ContestID . "')) AS UserRank,ContestID,UserTeamID
                         FROM sports_contest_join,tbl_users 
                         WHERE sports_contest_join.ContestID = '" . $ContestID . "' AND tbl_users.UserID = sports_contest_join.UserID
                     ");
            $results = $query->result_array();
            if (!empty($results)) {
                foreach ($results as $rows) {
                    $this->db->where(array('ContestID' => $rows['ContestID'],'UserTeamID' => $rows['UserTeamID']));
                    $this->db->limit(1);
                    $this->db->update('sports_contest_join', array('UserRank' => $rows['UserRank']));
                }
            }
        }
    }

    /*
      Description: 	Cron jobs to get auction joined player points.

     */

    function getAuctionJoinedUserTeamsPlayerPoints($CronID) {
        ini_set('max_execution_time', 600);
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
                /* else {
                  $this->db->where('CronID', $CronID);
                  $this->db->limit(1);
                  $this->db->update('log_cron', array('CronStatus' => 'Exit'));
                  exit;
                  } */
                $this->updateRankByContest($ContestID);
            }
        }
    }

        function getPlayersByType($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));

            $Field = '';
            $FieldArray = array(
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
                'PlayerSalary' => 'P.PlayerSalary',
                'LastUpdateDiff' => 'IF(P.LastUpdatedOn IS NULL, 0, TIME_TO_SEC(TIMEDIFF("' . date('Y-m-d H:i:s') . '", P.LastUpdatedOn))) LastUpdateDiff',
                'MatchTypeID' => 'SSM.MatchTypeID',
                'MatchType' => 'SSM.MatchTypeName as MatchType',
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

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }

        if (!empty($Where['RandData'])) {
            $this->db->order_by($Where['RandData']);
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
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {

                    $Records[] = $Record;
                    $Records[$key]['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                    $Records[$key]['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                    $Records[$key]['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                    $Records[$key]['PlayerSalary'] = (!empty($Record['PlayerSalary'])) ? json_decode($Record['PlayerSalary']) : new stdClass();
                    if ($Record['MatchType'] == 'T20') {
                        $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20Credits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20Credits'] : 0;
                    } else if ($Record['MatchType'] == 'Test') {
                        $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20iCredits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20iCredits'] : 0;
                    } else if ($Record['MatchType'] == 'T20I') {
                        $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['ODICredits']) ? json_decode($Record['PlayerSalary'], TRUE)['ODICredits'] : 0;
                    } else if ($Record['MatchType'] == 'ODI') {
                        $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['TestCredits']) ? json_decode($Record['PlayerSalary'], TRUE)['TestCredits'] : 0;
                    } else {
                        $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20Credits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20Credits'] : 0;
                    }

                    if (in_array('MyTeamPlayer', $Params)) {
                        $this->db->select('SUTP.PlayerID,SUTP.MatchID');
                        $this->db->where('SUTP.MatchID', $Where['MatchID']);
                        $this->db->where('SUT.UserID', $Where['UserID']);
                        $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                        $MyPlayers = $this->db->get()->result_array();
                        if (!empty($MyPlayers)) {
                            foreach ($MyPlayers as $k => $value) {
                                if ($value['PlayerID'] == $Record['PlayerID']) {
                                    $Records[$key]['MyPlayer'] = 'Yes';
                                } else {
                                    $Records[$key]['MyPlayer'] = 'No';
                                }
                            }
                        } else {
                            $Records[$key]['MyPlayer'] = 'No';
                        }
                    }

                    if (in_array('PlayerSelectedPercent', $Params)) {

                        $TotalTeams = $this->db->query('Select count(*) as TotalTeams from sports_users_teams')->row();
                        $this->db->select('count(SUTP.PlayerID) as TotalPlayer');
                        $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                        $this->db->where("SUTP.PlayerID", $Record['PlayerID']);
                        $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                        $Players = $this->db->get()->row();
                        $Records[$key]['PlayerSelectedPercent'] = strval(round((($Players->TotalPlayer * 100 ) / $TotalTeams), 2));
                    }

                    if (in_array('TopPlayer', $Params)) {
                        $Wicketkipper = $this->findKeyValuePlayers($Records, "WicketKeeper");
                        $Batsman = $this->findKeyValuePlayers($Records, "Batsman");
                        $Bowler = $this->findKeyValuePlayers($Records, "Bowler");
                        $Allrounder = $this->findKeyValuePlayers($Records, "AllRounder");
                        usort($Batsman, function ($a, $b) {
                            return $b->TotalPoints - $a->TotalPoints;
                        });
                        usort($Bowler, function ($a, $b) {
                            return $b->TotalPoints - $a->TotalPoints;
                        });
                        usort($Wicketkipper, function ($a, $b) {
                            return $b->TotalPoints - $a->TotalPoints;
                        });
                        usort($Allrounder, function ($a, $b) {
                            return $b->TotalPoints - $a->TotalPoints;
                        });

                        $TopBatsman = array_slice($Batsman, 0, 4);
                        $TopBowler = array_slice($Bowler, 0, 3);
                        $TopWicketkipper = array_slice($Wicketkipper, 0, 1);
                        $TopAllrounder = array_slice($Allrounder, 0, 3);

                        $AllPlayers = array();
                        $AllPlayers = array_merge($TopBatsman, $TopBowler);
                        $AllPlayers = array_merge($AllPlayers, $TopAllrounder);
                        $AllPlayers = array_merge($AllPlayers, $TopWicketkipper);

                        rsort($AllPlayers, function($a, $b) {
                            return $b->TotalPoints - $a->TotalPoints;
                        });
                        $PlayersAll = array_column($AllPlayers, 'PlayerID');
                        if (!empty($Record['PlayerID']) && !empty($PlayersAll)) {
                            if (in_array($Record['PlayerID'], $PlayersAll)) {
                                $Records[$key]['TopPlayer'] = 'Yes';
                            } else {
                                $Records[$key]['TopPlayer'] = 'No';
                            }
                        } else {
                            $Records[$key]['TopPlayer'] = 'No';
                        }
                    } else {
                        $Records[$key]['TopPlayer'] = 'No';
                    }
                }



                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                $Record['PlayerBattingStats'] = (!empty($Record['PlayerBattingStats'])) ? json_decode($Record['PlayerBattingStats']) : new stdClass();
                $Record['PlayerBowlingStats'] = (!empty($Record['PlayerBowlingStats'])) ? json_decode($Record['PlayerBowlingStats']) : new stdClass();
                $Record['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                $Record['PlayerSalary'] = (!empty($Record['PlayerSalary'])) ? json_decode($Record['PlayerSalary']) : new stdClass();
                if ($Record['MatchType'] == 'T20') {
                    $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20Credits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20Credits'] : 0;
                } else if ($Record['MatchType'] == 'Test') {
                    $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20iCredits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20iCredits'] : 0;
                } else if ($Record['MatchType'] == 'T20I') {
                    $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['ODICredits']) ? json_decode($Record['PlayerSalary'], TRUE)['ODICredits'] : 0;
                } else if ($Record['MatchType'] == 'ODI') {
                    $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['TestCredits']) ? json_decode($Record['PlayerSalary'], TRUE)['TestCredits'] : 0;
                } else {
                    $Records[$key]['PointCredits'] = (json_decode($Record['PlayerSalary'], TRUE)['T20Credits']) ? json_decode($Record['PlayerSalary'], TRUE)['T20Credits'] : 0;
                }

                if (in_array('MyTeamPlayer', $Params)) {

                    $this->db->select('SUTP.PlayerID,SUTP.MatchID');
                    $this->db->where('SUTP.MatchID', $Where['MatchID']);
                    $this->db->where('SUT.UserID', $Where['UserID']);
                    $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                    $MyPlayers = $this->db->get()->result_array();


                    foreach ($MyPlayers as $key => $value) {
                        if ($value['PlayerID'] == $Record['PlayerID']) {
                            $Records['MyPlayer'] = 'Yes';
                        } else {
                            $Records['MyPlayer'] = 'No';
                        }
                    }
                }

                if (in_array('TopPlayer', $Params)) {
                    $Wicketkipper = $this->findKeyValuePlayers($Records, "WicketKeeper");
                    $Batsman = $this->findKeyValuePlayers($Records, "Batsman");
                    $Bowler = $this->findKeyValuePlayers($Records, "Bowler");
                    $Allrounder = $this->findKeyValuePlayers($Records, "AllRounder");
                    usort($Batsman, function ($a, $b) {
                        return $b->TotalPoints - $a->TotalPoints;
                    });
                    usort($Bowler, function ($a, $b) {
                        return $b->TotalPoints - $a->TotalPoints;
                    });
                    usort($Wicketkipper, function ($a, $b) {
                        return $b->TotalPoints - $a->TotalPoints;
                    });
                    usort($Allrounder, function ($a, $b) {
                        return $b->TotalPoints - $a->TotalPoints;
                    });

                    $TopBatsman = array_slice($Batsman, 0, 4);
                    $TopBowler = array_slice($Bowler, 0, 3);
                    $TopWicketkipper = array_slice($Wicketkipper, 0, 1);
                    $TopAllrounder = array_slice($Allrounder, 0, 3);

                    $AllPlayers = array();
                    $AllPlayers = array_merge($TopBatsman, $TopBowler);
                    $AllPlayers = array_merge($AllPlayers, $TopAllrounder);
                    $AllPlayers = array_merge($AllPlayers, $TopWicketkipper);

                    rsort($AllPlayers, function($a, $b) {
                        return $b->TotalPoints - $a->TotalPoints;
                    });
                }

                if (in_array($Record['PlayerID'], array_column($AllPlayers, 'PlayerID'))) {
                    $Records['TopPlayer'] = 'Yes';
                } else {
                    $Records['TopPlayer'] = 'No';
                }

                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To set contest winners
     */

    function setContestWinners_OLD($CronID) {

        ini_set('max_execution_time', 300);


        $Contests = $this->Contest_model->getContests('WinningAmount,NoOfWinners,ContestID,EntryFee,TotalJoined,ContestSize,IsConfirm,SeriesName,ContestName,MatchStartDateTime,MatchNo,TeamNameLocal,TeamNameVisitor,CustomizeWinning', array('StatusID' => 5, 'IsWinningDistributed' => 'No', "LeagueType" => "Dfs"), true, 0);
        if (isset($Contests['Data']['Records'])) {

            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronResponse' => @json_encode(array('Query' => $this->db->last_query(), 'Contests' => $Contests), JSON_UNESCAPED_UNICODE)));

            foreach ($Contests['Data']['Records'] as $Value) {

                $JoinedContestsUsers = $this->Contest_model->getJoinedContestsUsers('UserRank,UserTeamID,TotalPoints,UserRank,UserID,FirstName,Email', array('ContestID' => $Value['ContestID'], 'OrderBy' => 'JC.UserRank', 'Sequence' => 'DESC', 'PointFilter' => 'TotalPoints'), true, 0);
                if (!$JoinedContestsUsers)
                    continue;

                $AllUsersRank = array_column($JoinedContestsUsers['Data']['Records'], 'UserRank');
                $AllRankWinners = array_count_values($AllUsersRank);
                $userWinnersData = $OptionWinner = array();
                $CustomizeWinning = $Value['CustomizeWinning'];
                if (empty($CustomizeWinning)) {
                    $CustomizeWinning[] = array(
                        'From' => 1,
                        'To' => $Value['NoOfWinners'],
                        'Percent' => 100,
                        'WinningAmount' => $Value['WinningAmount']
                    );
                }
                /** calculate amount according to rank or contest winning configuration */
                foreach ($AllRankWinners as $Rank => $WinnerValue) {
                    $Flag = $TotalAmount = $AmountPerUser = 0;
                    for ($J = 0; $J < count($CustomizeWinning); $J++) {
                        $FromWinner = $CustomizeWinning[$J]['From'];
                        $ToWinner = $CustomizeWinning[$J]['To'];
                        if ($Rank >= $FromWinner && $Rank <= $ToWinner) {
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
                    $userWinnersData[] = $this->findKeyValueArray($JoinedContestsUsers['Data']['Records'], $Rank, $AmountPerUser);
                }
                foreach ($userWinnersData as $WinnerArray) {
                    foreach ($WinnerArray as $WinnerRow) {
                        $OptionWinner[] = $WinnerRow;
                    }
                }

                if (!empty($OptionWinner)) {
                    foreach ($OptionWinner as $WinnerValue) {

                        $this->db->trans_start();

                        /** join contest user winning amount update * */
                        $this->db->where('UserID', $WinnerValue['UserID']);
                        $this->db->where('ContestID', $Value['ContestID']);
                        $this->db->where('UserTeamID', $WinnerValue['UserTeamID']);
                        $this->db->limit(1);
                        $this->db->update('sports_contest_join', array('UserWinningAmount' => $WinnerValue['UserWinningAmount'], 'ModifiedDate' => date('Y-m-d H:i:s')));

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
        } else {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }
    }

    function setContestWinners($CronID) {

        ini_set('max_execution_time', 300);

        /* Get Completed Contests */
        $Contests = $this->db->query('SELECT C.WinningAmount,C.ContestID,C.CustomizeWinning FROM tbl_entity E,sports_contest C WHERE E.EntityID = C.ContestID AND E.StatusID = 5 AND C.IsWinningAssigned = "No" AND C.LeagueType = "Dfs"');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {
                
                /* Get Joined Contests */
                $ContestCollection   = $this->fantasydb->{'Contest_'.$Value['ContestID']};
                $JoinedContestsUsers = iterator_to_array($ContestCollection->find(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "No","TotalPoints" => ['$gt' => 0]],['projection' => ['UserRank' => 1,'UserTeamID' => 1,'TotalPoints' => 1,'UserID' => 1],'sort' => ['UserRank' => -1]]));
                $AllUsersRank   = array_column($JoinedContestsUsers,'UserRank');
                $AllRankWinners = array_count_values($AllUsersRank);
                if(count($AllRankWinners) == 0){

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
                                                ['_id'    => $Value['ContestID'].$WinnerValue['UserID'].$WinnerValue['UserTeamID']],
                                                ['$set'   => ['UserWinningAmount' => round($WinnerValue['UserWinningAmount'],2),'IsWinningAssigned' => 'Yes']],
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
    function tranferJoinedContestData($CronID) {

        ini_set('max_execution_time', 1200);

        /* Get Contests Data */
        $Contests = $this->db->query('SELECT C.ContestID FROM sports_contest C WHERE C.IsWinningAssigned = "Yes" AND C.LeagueType = "Dfs" LIMIT 10');
        if ($Contests->num_rows() > 0) {
            foreach ($Contests->result_array() as $Value) {

                /* Get Joined Contests */
                $ContestCollection   = $this->fantasydb->{'Contest_'.$Value['ContestID']};
                $JoinedContestsUsers = $ContestCollection->find(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "Yes"],['projection' => ['ContestID' => 1,'UserID' => 1,'UserTeamID' => 1,'UserTeamPlayers' => 1,'TotalPoints' => 1,'UserRank' => 1,'UserWinningAmount' => 1]]);
                if($ContestCollection->count(["ContestID" => $Value['ContestID'], "IsWinningAssigned" => "Yes"]) == 0){

                    /* Update Contest Winning Assigned Status */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningAssigned' => "Moved"));
                    continue;
                }

                foreach($JoinedContestsUsers as $JC){

                    /* Update User Team Player Points */
                    // $Query = 'UPDATE sports_users_team_players SET Points = CASE';
                    // foreach($JC['UserTeamPlayers'] as $Player){
                    //     $Query .= ' WHEN UserTeamID = '.$JC['UserTeamID'].' AND PlayerID = '.$Player['PlayerID'].' THEN '.$Player['Points'];
                    // }
                    // $Query .= ' ELSE Points END;';
                    // $this->db->query($Query);

                    /* Update MySQL Row */
                    $this->db->where(array('UserID' => $JC['UserID'],'ContestID' => $JC['ContestID'],'UserTeamID' => $JC['UserTeamID']));
                    $this->db->limit(1);
                    $this->db->update('sports_contest_join', array('TotalPoints' => $JC['TotalPoints'],'UserRank' => $JC['UserRank'],'UserWinningAmount' => $JC['UserWinningAmount'], 'IsWinningAssigned' => 'Yes','ModifiedDate' => date('Y-m-d H:i:s')));

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

    function amountDistributeContestWinner_OLD($CronID) {
        ini_set('max_execution_time', 300);
        /* Get Joined Contest Users */
        $this->db->select('C.ContestGUID,C.ContestID,C.EntryFee,E.StatusID');
        $this->db->from('sports_contest C,tbl_entity E');
        $this->db->where("E.EntityID", "C.ContestID", FALSE);
        $this->db->where("C.IsWinningDistributed", "Yes");
        $this->db->where("C.IsWinningDistributeAmount", "No");
        $this->db->where("C.LeagueType", "Dfs");
        $this->db->where("E.StatusID", 5);
        $this->db->limit(15);
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            $Contests = $Query->result_array();
            foreach ($Contests as $Value) {
                /* Get Joined Contest Users */
                $this->db->select('U.UserGUID,U.UserID,U.FirstName,U.Email,JC.ContestID,JC.MatchID,JC.UserTeamID,JC.UserRank,JC.UserWinningAmount,JC.IsWinningDistributeAmount');
                $this->db->from('sports_contest_join JC, tbl_users U');
                $this->db->where("JC.UserID", "U.UserID", FALSE);
                $this->db->where("JC.IsWinningDistributeAmount", "No");
                $this->db->where("JC.ContestID", $Value['ContestID']);
                $Query = $this->db->get();
                if ($Query->num_rows() > 0) {
                    $JoinedContestsUsers = $Query->result_array();
                    foreach ($JoinedContestsUsers as $WinnerValue) {

                        $this->db->trans_start();

                        if ($WinnerValue['UserWinningAmount'] > 0) {
                            /** update user wallet * */
                            $WalletData = array(
                                "Amount" => $WinnerValue['UserWinningAmount'],
                                "WinningAmount" => $WinnerValue['UserWinningAmount'],
                                "EntityID" => $WinnerValue['ContestID'],
                                "UserTeamID" => $WinnerValue['UserTeamID'],
                                "TransactionType" => 'Cr',
                                "Narration" => 'Join Contest Winning',
                                "EntryDate" => date("Y-m-d H:i:s")
                            );
                            $this->Users_model->addToWallet($WalletData, $WinnerValue['UserID'], 5);
                        }

                        /** user join contest winning status update * */
                        $this->db->where('UserID', $WinnerValue['UserID']);
                        $this->db->where('ContestID', $Value['ContestID']);
                        $this->db->where('UserTeamID', $WinnerValue['UserTeamID']);
                        $this->db->limit(1);
                        $this->db->update('sports_contest_join', array('IsWinningDistributeAmount' => "Yes", 'ModifiedDate' => date('Y-m-d H:i:s')));

                        $this->db->trans_complete();
                        if ($this->db->trans_status() === false) {
                            return false;
                        }
                    }

                    /* Update Contest Winning Status Yes */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningDistributeAmount' => "Yes"));
                } else {
                    /* Update Contest Winning Status Yes */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsWinningDistributeAmount' => "Yes"));
                }
            }
        } else {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }
    }

    /*
      Description: To set contest winners amount distribute
     */

    function amountDistributeContestWinner($CronID) {
        ini_set('max_execution_time', 900);
		
		/* Get Contests Data */
        $Contests = $this->db->query('SELECT C.ContestID FROM sports_contest C,tbl_entity E WHERE C.ContestID = E.EntityID AND E.StatusID = 5 AND C.IsWinningDistributed = "No" AND C.IsWinningAssigned = "Moved" AND C.LeagueType = "Dfs" LIMIT 10');
        if ($Contests->num_rows() > 0) {
        	foreach ($Contests->result_array() as $Value) {

        		/* Get Joined Contest Users */
        		$JoinedContests = $this->db->query('SELECT JC.UserID,JC.UserTeamID,JC.UserWinningAmount FROM sports_contest_join JC WHERE JC.IsWinningAssigned = "Yes" AND JC.IsWinningDistributed = "No" AND JC.UserWinningAmount > 0 AND JC.ContestID = '.$Value['ContestID']);
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
                    $this->db->where(array('UserID' => $WinnerValue['UserID'],'ContestID' => $Value['ContestID'],'UserTeamID' => $WinnerValue['UserTeamID']));
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
      Description: To common funtion find key value
     */

    function findKeyValueArray($JoinedContestsUsers, $Rank, $AmountPerUser) {
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
      Description: To Auto Cancel Contest
     */

    function autoCancelContest($CronID) {

        ini_set('max_execution_time', 300);

        /* Get Contest Data */
        $ContestsUsers = $this->Contest_model->getContests('ContestID,EntryFee,TotalJoined,ContestSize,IsConfirm,SeriesName,ContestName,MatchStartDateTime,MatchNo,TeamNameLocal,TeamNameVisitor', array('StatusID' => array(1, 2), 'Filter' => 'MatchLive', 'IsConfirm' => "No", "IsPaid" => "Yes", "LeagueType" => "Dfs"), true, 0);
        if ($ContestsUsers['Data']['TotalRecords'] == 0) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }
        foreach ($ContestsUsers['Data']['Records'] as $Value) {

            $MatchStartDateTime = strtotime($Value['MatchStartDateTime']) - 19800; // -05:30 Hours
            $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
            if (($MatchStartDateTime - $CurrentDateTime) > 300)
                continue;

            $IsCancelled = (($Value['IsConfirm'] == 'No' && $Value['TotalJoined'] != $Value['ContestSize']) ? 1 : 0);
            if ($IsCancelled == 0)
                continue;

            /* Update Contest Status */
            $this->db->where('EntityID', $Value['ContestID']);
            $this->db->limit(1);
            $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));
        }
    }

    function refundAmountCancelContest($CronID) {
        ini_set('max_execution_time', 300);
        /* Get Contest Data */
        $this->db->select('C.ContestGUID,C.ContestID,C.EntryFee,C.ContestSize,C.IsConfirm');
        $this->db->from('sports_contest C,tbl_entity E');
        $this->db->where("E.EntityID", "C.ContestID", FALSE);
        $this->db->where("C.IsRefund", "No");
        $this->db->where("C.LeagueType", "Dfs");
        $this->db->where("E.StatusID", 3);
        $this->db->limit(100);
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            $ContestsUsers = $Query->result_array();
            foreach ($ContestsUsers as $Value) {
                /* Get Joined Contest Users */
                $this->db->select('U.UserGUID,U.UserID,U.FirstName,U.Email,JC.ContestID,JC.MatchID,JC.UserTeamID,JC.IsRefund');
                $this->db->from('sports_contest_join JC, tbl_users U');
                $this->db->where("JC.UserID", "U.UserID", FALSE);
                $this->db->where("JC.IsRefund", "No");
                $this->db->where("JC.ContestID", $Value['ContestID']);
                $Query = $this->db->get();
                $JoinedContestsUsers = $Query->result_array();
                //print_r($JoinedContestsUsers);exit;
                if ($Query->num_rows() > 0) {
                    $JoinedContestsUsers = $Query->result_array();
                    //print_r($JoinedContestsUsers);exit;
                    foreach ($JoinedContestsUsers as $JoinValue) {

                        //$this->db->trans_start();
                        /* Refund Wallet Money */
                        if (!empty($Value['EntryFee'])) {
                            /* Get Wallet Details */
                            $WalletDetails = $this->Users_model->getWallet('WalletAmount,WinningAmount,CashBonus', array(
                                'UserID' => $JoinValue['UserID'],
                                'EntityID' => $Value['ContestID'],
                                'UserTeamID' => $JoinValue['UserTeamID'],
                                'Narration' => 'Cancel Contest'
                            ));
                            if (!empty($WalletDetails)) {
                                /** update user refund status Yes */
                                $this->db->where('ContestID', $JoinValue['ContestID']);
                                $this->db->where('UserTeamID', $JoinValue['UserTeamID']);
                                $this->db->where('UserID', $JoinValue['UserID']);
                                $this->db->limit(1);
                                $this->db->update('sports_contest_join', array('IsRefund' => "Yes"));
                                continue;
                            }

                            /* Get Wallet Details */
                            $WalletDetails = $this->Users_model->getWallet('WalletAmount,WinningAmount,CashBonus', array(
                                'UserID' => $JoinValue['UserID'],
                                'EntityID' => $Value['ContestID'],
                                'UserTeamID' => $JoinValue['UserTeamID'],
                                'Narration' => 'Join Contest'
                            ));
                            if (!empty($WalletDetails)) {
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
                                $TotalRefundAmount = $WalletDetails['WalletAmount'] + $WalletDetails['WinningAmount'] + $WalletDetails['CashBonus'];

                                $this->Users_model->addToWallet($InsertData, $JoinValue['UserID'], 5);

                                $this->Notification_model->addNotification('refund', 'Contest Cancelled Refund', $JoinValue['UserID'], $JoinValue['UserID'], $Value['ContestID'], 'Your Rs.' . $TotalRefundAmount . ' has been refunded.');
                            }

                            /** update user refund status Yes */
                            $this->db->where('ContestID', $JoinValue['ContestID']);
                            $this->db->where('UserTeamID', $JoinValue['UserTeamID']);
                            $this->db->where('UserID', $JoinValue['UserID']);
                            $this->db->limit(1);
                            $this->db->update('sports_contest_join', array('IsRefund' => "Yes"));
                        }

//                        $this->db->trans_complete();
//                        if ($this->db->trans_status() === FALSE) {
//                            return FALSE;
//                        }
                    }
                    /* Update Contest Refund Status Yes */
                    /* $this->db->where('ContestID', $Value['ContestID']);
                      $this->db->limit(1);
                      $this->db->update('sports_contest', array('IsRefund' => "Yes")); */
                } else {
                    /* Update Contest Refund Status Yes */
                    $this->db->where('ContestID', $Value['ContestID']);
                    $this->db->limit(1);
                    $this->db->update('sports_contest', array('IsRefund' => "Yes"));
                }
            }
        }
    }

    

    

    


    /*
      Description: To calculate points according to keys
     */

    function calculatePoints($Points = array(), $MatchType, $BattingMinimumRuns, $ScoreValue, $BallsFaced = 0, $Overs = 0, $Runs = 0, $MinimumOverEconomyRate = 0, $PlayerRole, $HowOut) {
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
      Description: Find sub arrays from multidimensional array
     */

    function findSubArray($DataArray, $keyName, $Value) {
        $Data = array();
        foreach ($DataArray as $Row) {
            if ($Row[$keyName] == $Value)
                $Data[] = $Row;
        }
        return $Data;
    }

    /*
      Description: Use to get player credits (salary)
     */

    function getPlayerCredits($BattingStats = array(), $BowlingStats = array(), $PlayerRole) {

        /* Player Roles */
        $PlayerRolesArr = array('bowl' => 'Bowler', 'bat' => 'Batsman', 'wkbat' => 'WicketKeeper', 'wk' => 'WicketKeeper', 'all' => 'AllRounder');
        $PlayerCredits = array('T20Credits' => 0, 'T20iCredits' => 0, 'ODICredits' => 0, 'TestCredits' => 0);
        $PlayerRole = (SPORTS_API_NAME == 'ENTITY') ? @$PlayerRolesArr[$PlayerRole] : $PlayerRole;
        if (empty($PlayerRole))
            return $PlayerCredits;

        /* Get Player Credits */
        if ($PlayerRole == 'Batsman') {
            if (isset($BattingStats->T20)) {
                $PlayerCredits['T20Credits'] = $this->getT20BattingCredits($BattingStats->T20);
            }
            if (isset($BattingStats->T20i)) {
                $PlayerCredits['T20iCredits'] = $this->getT20iBattingCredits($BattingStats->T20i);
            }
            if (isset($BattingStats->ODI)) {
                $PlayerCredits['ODICredits'] = $this->getODIBattingCredits($BattingStats->ODI);
            }
            if (isset($BattingStats->Test)) {
                $PlayerCredits['TestCredits'] = $this->getTestBattingCredits($BattingStats->Test);
            }
        } else if ($PlayerRole == 'Bowler') {
            if (isset($BowlingStats->T20)) {
                $PlayerCredits['T20Credits'] = $this->getT20BowlingCredits($BowlingStats->T20);
            }
            if (isset($BowlingStats->T20i)) {
                $PlayerCredits['T20iCredits'] = $this->getT20iBowlingCredits($BowlingStats->T20i);
            }
            if (isset($BowlingStats->ODI)) {
                $PlayerCredits['ODICredits'] = $this->getODIBowlingCredits($BowlingStats->ODI);
            }
            if (isset($BowlingStats->Test)) {
                $PlayerCredits['TestCredits'] = $this->getTestBowlingCredits($BowlingStats->Test);
            }
        } else if ($PlayerRole == 'WicketKeeper') {
            if (isset($BattingStats->T20)) {
                $T20Credits = $this->getT20BattingCredits($BattingStats->T20);
                if ($T20Credits < 10.5) {
                    $T20Credits = number_format((float) ($T20Credits + 0.5), 2, '.', '');
                }
                $PlayerCredits['T20Credits'] = $T20Credits;
            }
            if (isset($BattingStats->T20i)) {
                $T20iCredits = $this->getT20iBattingCredits($BattingStats->T20i);
                if ($T20iCredits < 10.5) {
                    $T20iCredits = number_format((float) ($T20iCredits + 0.5), 2, '.', '');
                }
                $PlayerCredits['T20iCredits'] = $T20iCredits;
            }
            if (isset($BattingStats->ODI)) {
                $ODICredits = $this->getODIBattingCredits($BattingStats->ODI);
                if ($ODICredits < 10.5) {
                    $ODICredits = number_format((float) ($ODICredits + 0.5), 2, '.', '');
                }
                $PlayerCredits['ODICredits'] = $ODICredits;
            }
            if (isset($BattingStats->Test)) {
                $TestCredits = $this->getTestBattingCredits($BattingStats->Test);
                if ($TestCredits < 10.5) {
                    $TestCredits = number_format((float) ($TestCredits + 0.5), 2, '.', '');
                }
                $PlayerCredits['TestCredits'] = $TestCredits;
            }
        } else if ($PlayerRole == 'AllRounder') {
            if (isset($BattingStats->T20) && isset($BowlingStats->T20)) {
                $T20BattingCredits = $this->getT20BattingCredits($BattingStats->T20);
                $T20BowlingCredits = $this->getT20BowlingCredits($BowlingStats->T20);
                $T20CreditPoints = number_format((float) ($T20BattingCredits + $T20BowlingCredits) / 2, 2, '.', '');
                if ($T20CreditPoints >= 6 && $T20CreditPoints <= 6.49) {
                    $T20CreditPoints = 6.5;
                } else if ($T20CreditPoints >= 6.5 && $T20CreditPoints <= 6.99) {
                    $T20CreditPoints = 7.5;
                } else if ($T20CreditPoints >= 7 && $T20CreditPoints <= 7.49) {
                    $T20CreditPoints = 8.5;
                } else if ($T20CreditPoints >= 7.5 && $T20CreditPoints <= 7.99) {
                    $T20CreditPoints = 9.5;
                } else if ($T20CreditPoints >= 8 && $T20CreditPoints <= 8.49) {
                    $T20CreditPoints = 10;
                } else if ($T20CreditPoints >= 8.50) {
                    $T20CreditPoints = 10.5;
                }
                $PlayerCredits['T20Credits'] = $T20CreditPoints;
            }
            if (isset($BattingStats->T20i) && isset($BowlingStats->T20i)) {
                $T20iBattingCredits = $this->getT20iBattingCredits($BattingStats->T20i);
                $T20iBowlingCredits = $this->getT20iBowlingCredits($BowlingStats->T20i);
                $T20iCreditPoints = number_format((float) ($T20iBattingCredits + $T20iBowlingCredits) / 2, 2, '.', '');
                if ($T20iCreditPoints >= 6 && $T20iCreditPoints <= 6.49) {
                    $T20iCreditPoints = 6.5;
                } else if ($T20iCreditPoints >= 6.5 && $T20iCreditPoints <= 6.99) {
                    $T20iCreditPoints = 7.5;
                } else if ($T20iCreditPoints >= 7 && $T20iCreditPoints <= 7.49) {
                    $T20iCreditPoints = 8.5;
                } else if ($T20iCreditPoints >= 7.5 && $T20iCreditPoints <= 7.99) {
                    $T20iCreditPoints = 9.5;
                } else if ($T20iCreditPoints >= 8 && $T20iCreditPoints <= 8.49) {
                    $T20iCreditPoints = 10;
                } else if ($T20iCreditPoints >= 8.50) {
                    $T20iCreditPoints = 10.5;
                }
                $PlayerCredits['T20iCredits'] = $T20iCreditPoints;
            }
            if (isset($BattingStats->ODI) && isset($BowlingStats->ODI)) {
                $ODIBattingCredits = $this->getODIBattingCredits($BattingStats->ODI);
                $ODIBowlingCredits = $this->getODIBowlingCredits($BowlingStats->ODI);
                $ODICreditPoints = number_format((float) ($ODIBattingCredits + $ODIBowlingCredits) / 2, 2, '.', '');
                if ($ODICreditPoints >= 6 && $ODICreditPoints <= 6.49) {
                    $ODICreditPoints = 7;
                } else if ($ODICreditPoints >= 6.5 && $ODICreditPoints <= 6.99) {
                    $ODICreditPoints = 7.5;
                } else if ($ODICreditPoints >= 7 && $ODICreditPoints <= 7.24) {
                    $ODICreditPoints = 8;
                } else if ($ODICreditPoints >= 7.25 && $ODICreditPoints <= 7.49) {
                    $ODICreditPoints = 8.5;
                } else if ($ODICreditPoints >= 7.5 && $ODICreditPoints <= 7.74) {
                    $ODICreditPoints = 9;
                } else if ($ODICreditPoints >= 7.75 && $ODICreditPoints <= 8.25) {
                    $ODICreditPoints = 9.5;
                } else if ($ODICreditPoints >= 8.26 && $ODICreditPoints <= 8.75) {
                    $ODICreditPoints = 10;
                } else if ($ODICreditPoints > 8.75) {
                    $ODICreditPoints = 10.5;
                }
                $PlayerCredits['ODICredits'] = $ODICreditPoints;
            }
            if (isset($BattingStats->Test) && isset($BowlingStats->Test)) {
                $TestBattingCredits = $this->getTestBattingCredits($BattingStats->Test);
                $TestBowlingCredits = $this->getTestBowlingCredits($BowlingStats->Test);
                $TestCreditPoints = number_format((float) ($TestBattingCredits + $TestBowlingCredits) / 2, 2, '.', '');
                if ($TestCreditPoints >= 6 && $TestCreditPoints <= 6.49) {
                    $TestCreditPoints = 7;
                } else if ($TestCreditPoints >= 6.5 && $TestCreditPoints <= 6.99) {
                    $TestCreditPoints = 7.5;
                } else if ($TestCreditPoints >= 7 && $TestCreditPoints <= 7.24) {
                    $TestCreditPoints = 8;
                } else if ($TestCreditPoints >= 7.25 && $TestCreditPoints <= 7.49) {
                    $TestCreditPoints = 8.5;
                } else if ($TestCreditPoints >= 7.5 && $TestCreditPoints <= 7.74) {
                    $TestCreditPoints = 9;
                } else if ($TestCreditPoints >= 7.75 && $TestCreditPoints <= 8.25) {
                    $TestCreditPoints = 9.5;
                } else if ($TestCreditPoints >= 8.26 && $TestCreditPoints <= 8.75) {
                    $TestCreditPoints = 10;
                } else if ($TestCreditPoints > 8.75) {
                    $TestCreditPoints = 10.5;
                }
                $PlayerCredits['TestCredits'] = $TestCreditPoints;
            }
        }
        return $PlayerCredits;
    }

    /*
      Description: Use to get T20 batting credits
     */

    function getT20BattingCredits($T20Batting = array()) {
        $T20CreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($T20Batting)) {
            $T20BattingAverage = $T20Batting->Average;
            $StrikeRate = $T20Batting->StrikeRate;
            $InningPlayed = $T20Batting->Innings;
            if (!empty($T20BattingAverage)) {
                $T20CreditPoints = number_format((float) ($StrikeRate / 100) * $T20BattingAverage, 2, '.', '');
                if ($T20CreditPoints >= 20 && $T20CreditPoints <= 25) {
                    $T20CreditPoints = 7;
                } else if ($T20CreditPoints >= 25.01 && $T20CreditPoints <= 30) {
                    $T20CreditPoints = 7.5;
                } else if ($T20CreditPoints >= 30.01 && $T20CreditPoints <= 35) {
                    $T20CreditPoints = 8;
                } else if ($T20CreditPoints >= 35.01 && $T20CreditPoints <= 40) {
                    $T20CreditPoints = 8.5;
                } else if ($T20CreditPoints >= 40.01 && $T20CreditPoints <= 45) {
                    $T20CreditPoints = 9;
                } else if ($T20CreditPoints >= 45.01 && $T20CreditPoints <= 50) {
                    $T20CreditPoints = 9.5;
                } else if ($T20CreditPoints >= 50.01 && $T20CreditPoints <= 55) {
                    $T20CreditPoints = 10;
                } else if ($T20CreditPoints > 55) {
                    $T20CreditPoints = 10.5;
                }
                if ($InningPlayed < 20) {
                    if ($T20CreditPoints > 7 && $T20CreditPoints <= 7.99) {
                        $T20CreditPoints = 7;
                    } else if ($T20CreditPoints >= 8) {
                        $T20CreditPoints = $T20CreditPoints - 1;
                    }
                }
            }
        }
        return $T20CreditPoints;
    }

    /*
      Description: Use to get T20i batting credits
     */

    function getT20iBattingCredits($T20iBatting = array()) {
        $T20iCreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($T20iBatting)) {
            $T20iBattingAverage = $T20iBatting->Average;
            $StrikeRate = $T20iBatting->StrikeRate;
            $InningPlayed = $T20iBatting->Innings;
            if (!empty($T20iBattingAverage)) {
                $T20iCreditPoints = number_format((float) ($StrikeRate / 100) * $T20iBattingAverage, 2, '.', '');
                if ($T20iCreditPoints >= 20 && $T20iCreditPoints <= 25) {
                    $T20iCreditPoints = 7;
                } else if ($T20iCreditPoints >= 25.01 && $T20iCreditPoints <= 30) {
                    $T20iCreditPoints = 7.5;
                } else if ($T20iCreditPoints >= 30.01 && $T20iCreditPoints <= 35) {
                    $T20iCreditPoints = 8;
                } else if ($T20iCreditPoints >= 35.01 && $T20iCreditPoints <= 40) {
                    $T20iCreditPoints = 8.5;
                } else if ($T20iCreditPoints >= 40.01 && $T20iCreditPoints <= 45) {
                    $T20iCreditPoints = 9;
                } else if ($T20iCreditPoints >= 45.01 && $T20iCreditPoints <= 50) {
                    $T20iCreditPoints = 9.5;
                } else if ($T20iCreditPoints >= 50.01 && $T20iCreditPoints <= 55) {
                    $T20iCreditPoints = 10;
                } else if ($T20iCreditPoints > 55) {
                    $T20iCreditPoints = 10.5;
                }
                if ($InningPlayed < 20) {
                    if ($T20iCreditPoints > 7 && $T20iCreditPoints <= 7.99) {
                        $T20iCreditPoints = 7;
                    } else if ($T20iCreditPoints >= 8) {
                        $T20iCreditPoints = $T20iCreditPoints - 1;
                    }
                }
            }
        }
        return $T20iCreditPoints;
    }

    /*
      Description: Use to get ODI batting credits
     */

    function getODIBattingCredits($ODIBatting = array()) {
        $ODICreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($ODIBatting)) {
            $ODIBattingAverage = $ODIBatting->Average;
            $OdiStrikeRate = $ODIBatting->StrikeRate;
            $InningPlayed = $ODIBatting->Innings;
            if (!empty($ODIBattingAverage)) {
                $ODICreditPoints = number_format((float) (sqrt($OdiStrikeRate / 100)) * $ODIBattingAverage, 2, '.', '');
                if ($ODICreditPoints >= 20 && $ODICreditPoints <= 30) {
                    $ODICreditPoints = 6.5;
                } else if ($ODICreditPoints >= 30.01 && $ODICreditPoints <= 35) {
                    $ODICreditPoints = 7;
                } else if ($ODICreditPoints >= 35.01 && $ODICreditPoints <= 40) {
                    $ODICreditPoints = 7.5;
                } else if ($ODICreditPoints >= 40.01 && $ODICreditPoints <= 45) {
                    $ODICreditPoints = 8;
                } else if ($ODICreditPoints >= 45.01 && $ODICreditPoints <= 50) {
                    $ODICreditPoints = 8.5;
                } else if ($ODICreditPoints >= 50.01 && $ODICreditPoints <= 55) {
                    $ODICreditPoints = 9;
                } else if ($ODICreditPoints >= 55.01 && $ODICreditPoints <= 60) {
                    $ODICreditPoints = 9.5;
                } else if ($ODICreditPoints > 60) {
                    $ODICreditPoints = 10;
                }
                if ($InningPlayed < 20) {
                    if ($ODICreditPoints > 7 && $ODICreditPoints <= 7.99) {
                        $ODICreditPoints = 7;
                    } else if ($ODICreditPoints >= 8) {
                        $ODICreditPoints = $ODICreditPoints - 1;
                    }
                }
            }
        }
        return $ODICreditPoints;
    }

    /*
      Description: Use to get Test batting credits
     */

    function getTestBattingCredits($TestBatting = array()) {
        $TestCreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($TestBatting)) {
            $InningPlayed = $TestBatting->Innings;
            $TestBattingAverage = $TestBatting->Average;
            if (!empty($TestBattingAverage)) {
                $TestCreditPoints = number_format((float) $TestBattingAverage, 2, '.', '');
                if ($TestCreditPoints >= 20 && $TestCreditPoints <= 30) {
                    $TestCreditPoints = 6.5;
                } else if ($TestCreditPoints >= 30.01 && $TestCreditPoints <= 35) {
                    $TestCreditPoints = 7;
                } else if ($TestCreditPoints >= 35.01 && $TestCreditPoints <= 40) {
                    $TestCreditPoints = 7.5;
                } else if ($TestCreditPoints >= 40.01 && $TestCreditPoints <= 45) {
                    $TestCreditPoints = 8;
                } else if ($TestCreditPoints >= 45.01 && $TestCreditPoints <= 50) {
                    $TestCreditPoints = 8.5;
                } else if ($TestCreditPoints >= 50.01 && $TestCreditPoints <= 55) {
                    $TestCreditPoints = 9;
                } else if ($TestCreditPoints >= 55.01 && $TestCreditPoints <= 60) {
                    $TestCreditPoints = 9.5;
                } else if ($TestCreditPoints > 60) {
                    $TestCreditPoints = 10;
                }
                if ($InningPlayed < 20) {
                    if ($TestCreditPoints > 7 && $TestCreditPoints <= 7.99) {
                        $TestCreditPoints = 7;
                    } else if ($TestCreditPoints >= 8) {
                        $TestCreditPoints = $TestCreditPoints - 1;
                    }
                }
            }
        }
        return $TestCreditPoints;
    }

    /*
      Description: Use to get T20 bowling credits
     */

    function getT20BowlingCredits($T20Bowling = array()) {
        $T20CreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($T20Bowling)) {
            $T20BowlingAverage = $T20Bowling->Average;
            $T20BowlingWickets = $T20Bowling->Wickets;
            $T20BowlingInnings = $T20Bowling->Innings;
            $T20BowlingEconomyRate = $T20Bowling->Economy;
            $InningPlayed = $T20Bowling->Innings;
            if (!empty($T20BowlingAverage)) {
                $T20CreditPoints = number_format((float) (($T20BowlingEconomyRate * $T20BowlingEconomyRate) * $T20BowlingInnings) / (10 * $T20BowlingWickets), 2, '.', '');
                if ($T20CreditPoints > 8) {
                    $T20CreditPoints = 7;
                } else if ($T20CreditPoints >= 7.01 && $T20CreditPoints <= 8) {
                    $T20CreditPoints = 7.5;
                } else if ($T20CreditPoints >= 6.01 && $T20CreditPoints <= 7) {
                    $T20CreditPoints = 8;
                } else if ($T20CreditPoints >= 5.01 && $T20CreditPoints <= 6) {
                    $T20CreditPoints = 8.5;
                } else if ($T20CreditPoints >= 4.51 && $T20CreditPoints <= 5) {
                    $T20CreditPoints = 9;
                } else if ($T20CreditPoints >= 4.01 && $T20CreditPoints <= 4.5) {
                    $T20CreditPoints = 9.5;
                } else if ($T20CreditPoints >= 3.51 && $T20CreditPoints <= 4) {
                    $T20CreditPoints = 10;
                } else if ($T20CreditPoints <= 3.5) {
                    $T20CreditPoints = 10.5;
                }
                if ($InningPlayed < 20) {
                    if ($T20CreditPoints > 7 && $T20CreditPoints <= 7.99) {
                        $T20CreditPoints = 7;
                    } else if ($T20CreditPoints >= 8) {
                        $T20CreditPoints = $T20CreditPoints - 1;
                    }
                }
            }
        }
        return $T20CreditPoints;
    }

    /*
      Description: Use to get T20i bowling credits
     */

    function getT20iBowlingCredits($T20iBowling = array()) {
        $T20iCreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($T20iBowling)) {
            $T20iBowlingAverage = $T20iBowling->Average;
            $T20iBowlingWickets = $T20iBowling->Wickets;
            $T20iBowlingInnings = $T20iBowling->Innings;
            $T20iBowlingEconomyRate = $T20iBowling->Economy;
            $InningPlayed = $T20iBowling->Innings;
            if (!empty($T20iBowlingAverage)) {
                $T20iCreditPoints = number_format((float) (($T20iBowlingEconomyRate * $T20iBowlingEconomyRate) * $T20iBowlingInnings) / (10 * $T20iBowlingWickets), 2, '.', '');
                if ($T20iCreditPoints > 8) {
                    $T20iCreditPoints = 7;
                } else if ($T20iCreditPoints >= 7.01 && $T20iCreditPoints <= 8) {
                    $T20iCreditPoints = 7.5;
                } else if ($T20iCreditPoints >= 6.01 && $T20iCreditPoints <= 7) {
                    $T20iCreditPoints = 8;
                } else if ($T20iCreditPoints >= 5.01 && $T20iCreditPoints <= 6) {
                    $T20iCreditPoints = 8.5;
                } else if ($T20iCreditPoints >= 4.51 && $T20iCreditPoints <= 5) {
                    $T20iCreditPoints = 9;
                } else if ($T20iCreditPoints >= 4.01 && $T20iCreditPoints <= 4.5) {
                    $T20iCreditPoints = 9.5;
                } else if ($T20iCreditPoints >= 3.51 && $T20iCreditPoints <= 4) {
                    $T20iCreditPoints = 10;
                } else if ($T20iCreditPoints <= 3.5) {
                    $T20iCreditPoints = 10.5;
                }
                if ($InningPlayed < 20) {
                    if ($T20iCreditPoints > 7 && $T20iCreditPoints <= 7.99) {
                        $T20iCreditPoints = 7;
                    } else if ($T20iCreditPoints >= 8) {
                        $T20iCreditPoints = $T20iCreditPoints - 1;
                    }
                }
            }
        }
        return $T20iCreditPoints;
    }

    /*
      Description: Use to get ODI bowling credits
     */

    function getODIBowlingCredits($ODIBowling = array()) {
        $ODICreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($ODIBowling)) {
            $ODIBowlingAverage = $ODIBowling->Average;
            $ODIBowlingWickets = $ODIBowling->Wickets;
            $ODIBowlingInnings = $ODIBowling->Innings;
            $ODIBowlingEconomyRate = $ODIBowling->Economy;
            $InningPlayed = $ODIBowling->Innings;
            if (!empty($ODIBowlingAverage)) {
                $ODICreditPoints = number_format((float) ($ODIBowlingEconomyRate * $ODIBowlingInnings) / $ODIBowlingWickets, 2, '.', '');
                if ($ODICreditPoints > 7) {
                    $ODICreditPoints = 6.5;
                } else if ($ODICreditPoints >= 6.01 && $ODICreditPoints <= 7) {
                    $ODICreditPoints = 7;
                } else if ($ODICreditPoints >= 5.01 && $ODICreditPoints <= 6) {
                    $ODICreditPoints = 7.5;
                } else if ($ODICreditPoints >= 4.01 && $ODICreditPoints <= 5) {
                    $ODICreditPoints = 8;
                } else if ($ODICreditPoints >= 3.01 && $ODICreditPoints <= 4) {
                    $ODICreditPoints = 8.5;
                } else if ($ODICreditPoints >= 2.51 && $ODICreditPoints <= 3) {
                    $ODICreditPoints = 9;
                } else if ($ODICreditPoints >= 2.01 && $ODICreditPoints <= 2.5) {
                    $ODICreditPoints = 9.5;
                } else if ($ODICreditPoints <= 2) {
                    $ODICreditPoints = 10;
                }
                if ($InningPlayed < 20) {
                    if ($ODICreditPoints > 7 && $ODICreditPoints <= 7.99) {
                        $ODICreditPoints = 7;
                    } else if ($ODICreditPoints >= 8) {
                        $ODICreditPoints = $ODICreditPoints - 1;
                    }
                }
            }
        }
        return $ODICreditPoints;
    }

    /*
      Description: Use to get Test bowling credits
     */

    function getTestBowlingCredits($TestBowling = array()) {
        $TestCreditPoints = DEFAULT_PLAYER_CREDITS;
        if (!empty($TestBowling)) {
            $TestBowlingAverage = $TestBowling->Average;
            $TestBowlingWickets = $TestBowling->Wickets;
            $TestBowlingInnings = $TestBowling->Innings;
            $InningPlayed = $TestBowling->Innings;
            if (!empty($TestBowlingAverage)) {
                $TestCreditPoints = number_format((float) ($TestBowlingWickets / $TestBowlingInnings), 2, '.', '');
                if ($TestCreditPoints > 7) {
                    $TestCreditPoints = 6.5;
                } else if ($TestCreditPoints >= 6.01 && $TestCreditPoints <= 7) {
                    $TestCreditPoints = 7;
                } else if ($TestCreditPoints >= 5.01 && $TestCreditPoints <= 6) {
                    $TestCreditPoints = 7.5;
                } else if ($TestCreditPoints >= 4.01 && $TestCreditPoints <= 5) {
                    $TestCreditPoints = 8;
                } else if ($TestCreditPoints >= 3.01 && $TestCreditPoints <= 4) {
                    $TestCreditPoints = 8.5;
                } else if ($TestCreditPoints >= 2.51 && $TestCreditPoints <= 3) {
                    $TestCreditPoints = 9;
                } else if ($TestCreditPoints >= 2.01 && $TestCreditPoints <= 2.5) {
                    $TestCreditPoints = 9.5;
                } else if ($TestCreditPoints <= 2) {
                    $TestCreditPoints = 10;
                }
                if ($InningPlayed < 20) {
                    if ($TestCreditPoints > 7 && $TestCreditPoints <= 7.99) {
                        $TestCreditPoints = 7;
                    } else if ($TestCreditPoints >= 8) {
                        $TestCreditPoints = $TestCreditPoints - 1;
                    }
                }
            }
        }
        return $TestCreditPoints;
    }

    function match_players_best_played($Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $return['code'] = 200;

        $MatchData = $this->Sports_model->getMatches('MatchID,TeamIDLocal,TeamIDVisitor,SeriesID,MatchType,PlayerSalary', array('MatchID' => $Where['MatchID']), TRUE, 0);
        $dataArr = array();

        $playersData = $this->Sports_model->getPlayers('PlayerRole,PlayerPic,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerPosition,TotalPoints', array_merge($this->Post, array('MatchID' => @$this->MatchID)), TRUE, 0);
        $Wicketkipper = $this->findKeyValuePlayers($playersData['Data']['Records'], "WicketKeeper");
        $Batsman = $this->findKeyValuePlayers($playersData['Data']['Records'], "Batsman");
        $Bowler = $this->findKeyValuePlayers($playersData['Data']['Records'], "Bowler");
        $Allrounder = $this->findKeyValuePlayers($playersData['Data']['Records'], "AllRounder");

        usort($Batsman, function ($a, $b) {
            return $b->TotalPoints - $a->TotalPoints;
        });
        usort($Bowler, function ($a, $b) {
            return $b->TotalPoints - $a->TotalPoints;
        });
        usort($Wicketkipper, function ($a, $b) {
            return $b->TotalPoints - $a->TotalPoints;
        });
        usort($Allrounder, function ($a, $b) {
            return $b->TotalPoints - $a->TotalPoints;
        });

        $TopBatsman = array_slice($Batsman, 0, 4);
        $TopBowler = array_slice($Bowler, 0, 3);
        $TopWicketkipper = array_slice($Wicketkipper, 0, 1);
        $TopAllrounder = array_slice($Allrounder, 0, 3);

        $AllPlayers = array();
        $AllPlayers = array_merge($TopBatsman, $TopBowler);
        $AllPlayers = array_merge($AllPlayers, $TopAllrounder);
        $AllPlayers = array_merge($AllPlayers, $TopWicketkipper);

        rsort($AllPlayers, function($a, $b) {
            return $b->TotalPoints - $a->TotalPoints;
        });
        foreach ($AllPlayers as $key => $value) {
            $AllPlayers[$key]['PlayerPosition'] = 'Player';
            $AllPlayers[0]['PlayerPosition'] = 'Captain';
            $AllPlayers[1]['PlayerPosition'] = 'ViceCaptain';
            $TotalCalculatedPoints += $value['TotalPoints'];
        }
        // print_r($TotalCalculatedPoints); die;
        $Records['Data']['Records'] = $AllPlayers;
        $Records['Data']['TotalPoints'] = $TotalCalculatedPoints;
        $Records['Data']['TotalRecords'] = count($AllPlayers);
        if ($AllPlayers) {
            return $Records;
        } else {
            return false;
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

    /*
      Description: To get sports best played players of the match
     */

    function getMatchBestPlayers($Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {

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

        $Batsman = $this->findKeyValuePlayers($FinalXIPlayers, "Batsman");
        $Bowler = $this->findKeyValuePlayers($FinalXIPlayers, "Bowler");
        $Wicketkipper = $this->findKeyValuePlayers($FinalXIPlayers, "WicketKeeper");
        $Allrounder = $this->findKeyValuePlayers($FinalXIPlayers, "AllRounder");

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
      Description: To Auto Cancel Contest
     */

    function autoCancelContestMatchAbonded($MatchID) {

        ini_set('max_execution_time', 300);
        /* Get Contest Data */
        $ContestsUsers = $this->Contest_model->getContests('ContestID,Privacy,EntryFee,TotalJoined,ContestFormat,ContestSize,IsConfirm,SeriesName,ContestName,MatchStartDateTime,MatchNo,TeamNameLocal,TeamNameVisitor', array('StatusID' => array(1, 2, 5), "MatchID" => $MatchID), true, 0);
        if ($ContestsUsers['Data']['TotalRecords'] > 0) {
            foreach ($ContestsUsers['Data']['Records'] as $Value) {
                /* Update Contest Status */
                $this->db->where('EntityID', $Value['ContestID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));
            }
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
                        $Records[$key]['PlayerSelectedPercent'] = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2)) : "0";
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
                    $Record['PlayerSelectedPercent'] = ($Record['TotalTeams'] > 0) ? strval(round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $Record['TotalTeams']), 2)) : "0";
                }
                unset($Record['PlayerID'], $Record['MatchID']);
                return $Record;
            }
        }
        return FALSE;
    }

}
