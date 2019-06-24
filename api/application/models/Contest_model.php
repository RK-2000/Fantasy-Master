<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contest_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sports_model');
    }

    /*
      Description:    ADD contest to system.
     */

    function addContest($Input = array(), $SessionUserID, $MatchID, $SeriesID, $StatusID = 1) {

        $defaultCustomizeWinningObj = new stdClass();
        $defaultCustomizeWinningObj->From = 1;
        $defaultCustomizeWinningObj->To = 1;
        $defaultCustomizeWinningObj->Percent = 100;
        $defaultCustomizeWinningObj->WinningAmount = @$Input['WinningAmount'];

        $this->db->trans_start();
        $EntityGUID = get_guid();

        /* Add contest to entity table and get EntityID. */
        $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 11, "UserID" => $SessionUserID, "StatusID" => $StatusID));
        $ContestName = $Input['ContestName'];
        if (empty($Input['ContestName'])) {
            if (($Input['IsPaid'] == 'Yes')) {
                $ContestName = "Win " . @$Input['WinningAmount'];
            } else {
                $ContestName = "Win Skill";
            }
        }
        /* Add contest to contest table . */
        $InsertData = array_filter(array(
            "ContestID" => $EntityID,
            "ContestGUID" => $EntityGUID,
            "UserID" => $SessionUserID,
            "GameTimeLive" => @$Input['GameTimeLive'],
            "GameType" => @$Input['GameType'],
            "PreContestID" => @$Input['PreContestID'],
            "ContestName" => $ContestName,
            "ContestFormat" => @$Input['ContestFormat'],
            "ContestType" => @$Input['ContestType'],
            "AdminPercent" => @$Input['AdminPercent'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => (@$Input['Privacy'] == 'Yes') ? 'No' : @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "ShowJoinedContest" => @$Input['ShowJoinedContest'],
            "WinningAmount" => @$Input['WinningAmount'],
            "ContestSize" => (@$Input['ContestFormat'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => (@$Input['IsPaid'] == 'Yes') ? @$Input['NoOfWinners'] : 1,
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "IsPrivacyNameDisplay" => @$Input['IsPrivacyNameDisplay'],
            "CustomizeWinning" => (@$Input['ContestFormat'] == 'League' && !empty($Input['CustomizeWinning'])) ? $Input['CustomizeWinning'] : json_encode(array($defaultCustomizeWinningObj)),
            "SeriesID" => @$SeriesID,
            "MatchID" => @$MatchID,
            "UserInvitationCode" => random_string('alnum', 6)
        ));
        $this->db->insert('sports_contest', $InsertData);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $EntityID;
    }

    /*
      Description: Update contest to system.
     */

    function updateContest($Input = array(), $SessionUserID, $ContestID, $StatusID = 1) {
        $defaultCustomizeWinningObj = new stdClass();
        $defaultCustomizeWinningObj->From = 1;
        $defaultCustomizeWinningObj->To = 1;
        $defaultCustomizeWinningObj->Percent = "100";
        $defaultCustomizeWinningObj->WinningAmount = @$Input['WinningAmount'];
        /* Add contest to contest table . */
        $UpdateData = array_filter(array(
            "GameType" => @$Input['GameType'],
            "GameTimeLive" => @$Input['GameTimeLive'],
            "ContestName" => @$Input['ContestName'],
            "ContestFormat" => @$Input['ContestFormat'],
            "ContestType" => @$Input['ContestType'],
            "Privacy" => @$Input['Privacy'],
            "AdminPercent" => @$Input['AdminPercent'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "ShowJoinedContest" => @$Input['ShowJoinedContest'],
            "WinningAmount" => @$Input['WinningAmount'],
            "ContestSize" => (@$Input['ContestFormat'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => (@$Input['IsPaid'] == 'Yes') ? @$Input['NoOfWinners'] : 1,
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "IsPrivacyNameDisplay" => @$Input['IsPrivacyNameDisplay'],
            // "CustomizeWinning" => (@$Input['IsPaid'] == 'Yes') ? @$Input['CustomizeWinning'] : NULL,
            "CustomizeWinning" => (@$Input['IsPaid'] == 'Yes') ? @$Input['CustomizeWinning'] : json_encode(array($defaultCustomizeWinningObj)),
        ));
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', $UpdateData);
    }

    /*
      Description: Delete contest to system.
     */

    function deleteContest($SessionUserID, $ContestID) {
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->delete('sports_contest');
    }

    /*
      Description: To get contest
     */

    function getContests($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'MatchID' => 'M.MatchID',
                'MatchGUID' => 'M.MatchGUID',
                'StatusID' => 'E.StatusID',
                'MatchIDLive' => 'M.MatchIDLive',
                'MatchTypeID' => 'M.MatchTypeID',
                'MatchNo' => 'M.MatchNo',
                'MatchLocation' => 'M.MatchLocation',
                'MatchStartDateTime' => 'CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '") AS MatchStartDateTime',
                'MatchStartDateTimeUTC' => 'M.MatchStartDateTime as MatchStartDateTimeUTC',
                'MatchScoreDetails' => 'M.MatchScoreDetails',
                'AdminPercent' => 'C.AdminPercent',
                'ContestID' => 'C.ContestID',
                'GameTimeLive' => 'C.GameTimeLive',
                'LeagueType' => 'C.LeagueType',
                'GameType' => 'C.GameType',
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                "IsAutoCreate" => 'C.IsAutoCreate',
                'ShowJoinedContest' => 'C.ShowJoinedContest',
                'WinningAmount' => 'C.WinningAmount',
                'ContestSize' => 'C.ContestSize',
                'ContestFormat' => 'C.ContestFormat',
                'ContestType' => 'C.ContestType',
                'CustomizeWinning' => 'C.CustomizeWinning',
                'EntryFee' => 'C.EntryFee',
                'NoOfWinners' => 'C.NoOfWinners',
                'EntryType' => 'C.EntryType',
                'UserJoinLimit' => 'C.UserJoinLimit',
                'CashBonusContribution' => 'C.CashBonusContribution',
                'EntryType' => 'C.EntryType',
                'IsWinningDistributed' => 'C.IsWinningDistributed',
                'UserInvitationCode' => 'C.UserInvitationCode',
                'IsPrivacyNameDisplay' => 'C.IsPrivacyNameDisplay',
                'SeriesID' => 'M.SeriesID',
                'TeamNameLocal' => 'TL.TeamName AS TeamNameLocal',
                'TeamGUIDLocal' => 'TL.TeamGUID AS TeamGUIDLocal',
                'TeamGUIDVisitor' => 'TV.TeamGUID AS TeamGUIDVisitor',
                'TeamNameVisitor' => 'TV.TeamName AS TeamNameVisitor',
                'TeamNameShortLocal' => 'TL.TeamNameShort AS TeamNameShortLocal',
                'TeamNameShortVisitor' => 'TV.TeamNameShort AS TeamNameShortVisitor',
                'TeamFlagLocal' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag) as TeamFlagLocal',
                'TeamFlagVisitor' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag) as TeamFlagVisitor',
                'StatusID' => 'E.StatusID',
                'SeriesName' => 'S.SeriesName',
                'IsJoined' => '(SELECT IF( EXISTS(SELECT EntryDate FROM sports_contest_join
                                                        WHERE sports_contest_join.ContestID =  C.ContestID AND UserID = ' . @$Where['SessionUserID'] . ' LIMIT 1), "Yes", "No")) AS IsJoined',
                'TotalJoined' => '(SELECT COUNT(*)
                                                        FROM sports_contest_join
                                                        WHERE ContestID =  C.ContestID ) AS TotalJoined',
                'UserTotalJoinedInMatch' => '(SELECT COUNT(*)
                                                FROM sports_contest_join,tbl_entity
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND sports_contest_join.ContestID = tbl_entity.EntityID AND tbl_entity.StatusID != 3 AND sports_contest_join.UserID= ' . @$Where['SessionUserID'] . ') AS UserTotalJoinedInMatch',
                'Status' => 'CASE E.StatusID
                                                    when "1" then "Pending"
                                                    when "2" then "Running"
                                                    when "3" then "Cancelled"
                                                    when "5" then "Completed"
                                                    END as Status',
                'MatchType' => 'MT.MatchTypeName AS MatchType',
                'CurrentDateTime' => 'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") CurrentDateTime',
                'MatchDate' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%Y-%m-%d") MatchDate',
                'MatchTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%H:%i:%s") MatchTime',
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('C.ContestGUID,C.ContestName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_contest C, sports_matches M, sports_teams TL, sports_teams TV,sports_series S,sports_set_match_types MT');
        $this->db->where("C.ContestID", "E.EntityID", FALSE);
        $this->db->where("M.MatchID", "C.MatchID", FALSE);
        $this->db->where("S.SeriesID", "C.SeriesID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        $this->db->where("M.MatchTypeID", "MT.MatchTypeID", FALSE);
        if (!empty($Where['Keyword'])) {
            if (is_array(json_decode($Where['Keyword'], true))) {
                $Where['Keyword'] = json_decode($Where['Keyword'], true);

                if (isset($Where['Keyword']['ContestName'])) {
                    $this->db->like("C.ContestName", @$Where['Keyword']['ContestName']);
                }
                if (isset($Where['Keyword']['ContestType'])) {
                    $this->db->where("C.ContestType", @$Where['Keyword']['ContestType']);
                }
                if (isset($Where['Keyword']['GameType'])) {
                    $this->db->where("C.GameType", @$Where['Keyword']['GameType']);
                }
                if (isset($Where['Keyword']['ContestSize'])) {
                    $ContestSize = explode("-", $Where['Keyword']['ContestSize']);

                    if (count($ContestSize) > 1) {
                        $this->db->where("C.ContestSize >=", @$ContestSize[0]);
                        $this->db->where("C.ContestSize <=", @$ContestSize[1]);
                    } else {
                        $this->db->where("C.ContestSize >=", @$ContestSize[0]);
                    }
                }
                if (isset($Where['Keyword']['EntryFee'])) {

                    $EntryFee = explode("-", $Where['Keyword']['EntryFee']);
                    if (count($EntryFee) > 1) {
                        $this->db->where("C.EntryFee >=", $EntryFee[0]);
                        $this->db->where("C.EntryFee <=", $EntryFee[1]);
                    } else {
                        $this->db->where("C.EntryFee >=", $EntryFee[0]);
                    }
                }
            } else {
                $this->db->group_start();
                $this->db->like("C.ContestName", $Where['Keyword']);
                $this->db->or_like("C.GameType", $Where['Keyword']);
                $this->db->or_like("C.WinningAmount", $Where['Keyword']);
                $this->db->or_like("C.ContestSize", $Where['Keyword']);
                $this->db->or_like("C.EntryFee", $Where['Keyword']);
                $this->db->or_like("M.MatchLocation", $Where['Keyword']);
                $this->db->or_like("M.MatchNo", $Where['Keyword']);
                $this->db->group_end();
            }
        }

        if (!empty($Where['AdvanceSafeValidate'])) {
            $this->db->where("M.MatchStartDateTime >= (UTC_TIMESTAMP() + INTERVAL C.GameTimeLive MINUTE)");
        }

        if (!empty($Where['ContestID'])) {
            $this->db->where("C.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['IsVirtualUserJoined'])) {
            $this->db->where("C.IsVirtualUserJoined", $Where['IsVirtualUserJoined']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("C.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['UserID'])) {
            $this->db->where("C.UserID", $Where['UserID']);
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Today') {
            $this->db->where("DATE(M.MatchStartDateTime)", date('Y-m-d'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'MatchLive') {
            $this->db->where("M.MatchStartDateTime <=", date('Y-m-d H:i:s'));
        }
        if (!empty($Where['Filter']) && $Where['Filter'] == 'Yesterday') {
            $this->db->where("DATE(M.MatchStartDateTime) <=", date('Y-m-d'));
        }
        if (!empty($Where['GameType'])) {
            $this->db->where("C.GameType", $Where['GameType']);
        }
        if (!empty($Where['LeagueType'])) {
            $this->db->where("C.LeagueType", $Where['LeagueType']);
        }
        if (!empty($Where['Privacy']) && $Where['Privacy'] != 'All') {
            $this->db->where("C.Privacy", $Where['Privacy']);
        }
        if (!empty($Where['ContestType'])) {
            $this->db->where("C.ContestType", $Where['ContestType']);
        }
        if (!empty($Where['EntryStartFrom'])) {
            $this->db->where("C.EntryFee >=", $Where['EntryStartFrom']);
        }
        if (!empty($Where['EntryEndTo'])) {
            $this->db->where("C.EntryFee <=", $Where['EntryEndTo']);
        }
        if (!empty($Where['WinningStartFrom'])) {
            $this->db->where("C.WinningAmount >=", $Where['WinningStartFrom']);
        }
        if (!empty($Where['WinningEndTo'])) {
            $this->db->where("C.WinningAmount <=", $Where['WinningEndTo']);
        }
        if (!empty($Where['ContestSizeStartFrom'])) {
            $this->db->where("C.ContestSize >=", $Where['ContestSizeStartFrom']);
        }
        if (!empty($Where['ContestSizeEndTo'])) {
            $this->db->where("C.ContestSize <=", $Where['ContestSizeEndTo']);
        }
        if (!empty($Where['IsRefund'])) {
            $this->db->where("C.IsRefund", $Where['IsRefund']);
        }
        if (!empty($Where['IsWinningDistributeAmount'])) {
            $this->db->where("C.IsWinningDistributeAmount", $Where['IsWinningDistributeAmount']);
        }
        if (!empty($Where['ContestFormat'])) {
            $this->db->where("C.ContestFormat", $Where['ContestFormat']);
        }
        if (!empty($Where['IsPaid'])) {
            $this->db->where("C.IsPaid", $Where['IsPaid']);
        }
        if (!empty($Where['IsConfirm'])) {
            $this->db->where("C.IsConfirm", $Where['IsConfirm']);
        }
        if (!empty($Where['WinningAmount'])) {
            $this->db->where("C.WinningAmount >=", $Where['WinningAmount']);
        }
        if (!empty($Where['ContestSize'])) {
            $this->db->where("C.ContestSize", $Where['ContestSize']);
        }
        if (!empty($Where['FromDate'])) {
            $this->db->where("DATE(M.MatchStartDateTime) >=", $Where['FromDate']);
        }
        if (!empty($Where['ToDate'])) {
            $this->db->where("DATE(M.MatchStartDateTime) <=", $Where['ToDate']);
        }
        if (!empty($Where['EntryFee'])) {
            $this->db->where("C.EntryFee", $Where['EntryFee']);
        }
        if (!empty($Where['NoOfWinners'])) {
            $this->db->where("C.NoOfWinners", $Where['NoOfWinners']);
        }
        if (!empty($Where['EntryType'])) {
            $this->db->where("C.EntryType", $Where['EntryType']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("C.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['SeriesID'])) {
            $this->db->where("M.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where_in("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['MyJoinedContest']) && $Where['MyJoinedContest'] == "Yes") {
            $this->db->where('EXISTS (select ContestID from sports_contest_join JE where JE.ContestID = C.ContestID AND JE.UserID=' . @$Where['SessionUserID'] . ')');
        }
        if (!empty($Where['UserInvitationCode'])) {
            $this->db->where("C.UserInvitationCode", $Where['UserInvitationCode']);
        }
        if (!empty($Where['IsWinningDistributed'])) {
            $this->db->where("C.IsWinningDistributed", $Where['IsWinningDistributed']);
        }
        if (!empty($Where['ContestFull']) && $Where['ContestFull'] == 'No') {
            $this->db->having("TotalJoined !=", 'C.ContestSize', FALSE);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
                $this->db->order_by('DATE(M.MatchStartDateTime)="' . date('Y-m-d') . '" DESC', null, FALSE);
                $this->db->order_by('E.StatusID=1 DESC', null, FALSE);
            }else{
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
        //$this->db->group_by('C.ContestID'); // Will manage later
        $Query = $this->db->get();
        // echo $this->db->last_query(); die();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {

                    $Records[] = $Record;
                    $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                    $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                    $TotalAmountReceived = $this->getTotalContestCollections($Record['ContestGUID']);
                    $Records[$key]['TotalAmountReceived'] = ($TotalAmountReceived) ? (int)$TotalAmountReceived : 0;
                    $TotalWinningAmount = $this->getTotalWinningAmount($Record['ContestGUID']);
                    $Records[$key]['TotalWinningAmount'] = ($TotalWinningAmount) ? (int)$TotalWinningAmount : 0;
                    $Records[$key]['NoOfWinners'] = ($Record['NoOfWinners'] == 0 ) ? 1 : $Record['NoOfWinners'];
                    if (in_array('IsJoined', $Params)) {
                        if ($Record['IsJoined'] == 'Yes') {
                            $UserTeamDetails = $this->getUserTeams('TotalPoints', array('ContestID' => $Record['ContestID']), true, 0);
                            $Records[$key]['UserTeamDetails'] = $UserTeamDetails['Data']['Records'];
                        } else {
                            $Records[$key]['UserTeamDetails'] = array();
                        }
                        unset($Records[$key]['ContestID']);
                    }

                    if (in_array('UserWinningAmount', $Params)) {
                        /* update user time break . */
                        $Query = $this->db->query('SELECT JC.UserWinningAmount,JC.TotalPoints,JC.UserRank,UT.UserTeamName FROM sports_contest_join JC,sports_users_teams UT WHERE UT.UserTeamID = JC.UserTeamID AND JC.ContestID = "' . $Record['ContestID'] . '" AND JC.UserID = "' . $Where['SessionUserID'] . '"');
                        $UserWinningAmount = $Query->row_array();
                        if (!empty($UserWinningAmount)) {
                            $Records[$key]['UserWinningAmount'] = $UserWinningAmount['UserWinningAmount'];
                            $Records[$key]['TotalPoints'] = $UserWinningAmount['TotalPoints'];
                            $Records[$key]['UserRank'] = $UserWinningAmount['UserRank'];
                            $Records[$key]['UserTeamName'] = $UserWinningAmount['UserTeamName'];
                        }
                    }
                }
                $Return['Data']['Records'] = $Records;
            } else {
                $Record = $Query->row_array();
                $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], TRUE) : array();
                $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                $TotalAmountReceived = $this->getTotalContestCollections($Record['ContestGUID']);
                $Record['TotalAmountReceived'] = ($TotalAmountReceived) ? $TotalAmountReceived : 0;
                $TotalWinningAmount = $this->getTotalWinningAmount($Record['ContestGUID']);
                $Record['TotalWinningAmount'] = ($TotalWinningAmount) ? $TotalWinningAmount : 0;
                if (in_array('IsJoined', $Params)) {
                    if ($Record['IsJoined'] == 'Yes') {
                        $UserTeamDetails = $this->getUserTeams('TotalPoints', array('ContestID' => $Record['ContestID']), true, 0);
                        $Record['UserTeamDetails'] = $UserTeamDetails['Data']['Records'];
                    } else {
                        $Record['UserTeamDetails'] = array();
                    }
                    unset($Record['ContestID']);
                }

                if (!empty($Where['MatchID'])) {
                    $Record['Statics'] = $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                            )as NormalContest,
                            ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                            )as ReverseContest,(
                            SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '" 
                            )as JoinedContest,( 
                            SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '"
                        ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
                }

                return $Record;
            }
        } else {
            if (!$multiRecords) {
                return array();
            }
        }
        if (!empty($Where['MatchID'])) {
            $Return['Data']['Statics'] = $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                    )as NormalContest,
                    ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                    )as ReverseContest,(
                    SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '" 
                    )as JoinedContest,( 
                    SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '"
                ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
        }

        $Return['Data']['Records'] = empty($Records) ? array() : $Records;
        return $Return;
    }

    function getTotalContestCollections($ContestGUID) {
        return $this->db->query('SELECT SUM(C.EntryFee) as TotalAmountReceived FROM sports_contest C join sports_contest_join J on C.ContestID = J.ContestID WHERE C.ContestGUID = "' . $ContestGUID . '"')->row()->TotalAmountReceived;
    }

    function getTotalWinningAmount($ContestGUID) {
        return $this->db->query('SELECT SUM(J.UserWinningAmount) as TotalWinningAmount FROM sports_contest C join sports_contest_join J on C.ContestID = J.ContestID WHERE C.ContestGUID = "' . $ContestGUID . '"')->row()->TotalWinningAmount;
    }

    /*
      Description: Join contest
     */

    function joinContest($Input = array(), $SessionUserID, $ContestID, $MatchID, $UserTeamID) {

        $this->db->trans_start();

        /* Add entry to join contest table . */
        $InsertData = array(
            "UserID" => $SessionUserID,
            "ContestID" => $ContestID,
            "MatchID" => $MatchID,
            "UserTeamID" => $UserTeamID,
            "EntryDate" => date('Y-m-d H:i:s')
        );
        $this->db->insert('sports_contest_join', $InsertData);
        /* Manage User Wallet */
        if (@$Input['IsPaid'] == 'Yes') {
            $ContestEntryRemainingFees = @$Input['EntryFee'];
            $CashBonusContribution = @$Input['CashBonusContribution'];
            $WalletAmountDeduction = 0;
            $WinningAmountDeduction = 0;
            $CashBonusDeduction = 0;
            if (!empty($CashBonusContribution) && @$Input['CashBonus'] > 0) {
                $CashBonusContributionAmount = $ContestEntryRemainingFees * ($CashBonusContribution / 100);
                if (@$Input['CashBonus'] >= $CashBonusContributionAmount) {
                    $CashBonusDeduction = $CashBonusContributionAmount;
                } else {
                    $CashBonusDeduction = @$Input['CashBonus'];
                }
                $ContestEntryRemainingFees = $ContestEntryRemainingFees - $CashBonusDeduction;
            }
            if ($ContestEntryRemainingFees > 0 && @$Input['WalletAmount'] > 0) {
                if (@$Input['WalletAmount'] >= $ContestEntryRemainingFees) {
                    $WalletAmountDeduction = $ContestEntryRemainingFees;
                } else {
                    $WalletAmountDeduction = @$Input['WalletAmount'];
                }
                $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WalletAmountDeduction;
            }
            if ($ContestEntryRemainingFees > 0 && @$Input['WinningAmount'] > 0) {
                if (@$Input['WinningAmount'] >= $ContestEntryRemainingFees) {
                    $WinningAmountDeduction = $ContestEntryRemainingFees;
                } else {
                    $WinningAmountDeduction = @$Input['WinningAmount'];
                }
                $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WinningAmountDeduction;
            }
            $TransactionID = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            $InsertData = array(
                "Amount" => @$Input['EntryFee'],
                "WalletAmount" => $WalletAmountDeduction,
                "WinningAmount" => $WinningAmountDeduction,
                "CashBonus" => $CashBonusDeduction,
                "TransactionType" => 'Dr',
                "TransactionID" => $TransactionID,
                "EntityID" => $ContestID,
                "UserTeamID" => $UserTeamID,
                "Narration" => 'Join Contest',
                "EntryDate" => date("Y-m-d H:i:s")
            );
            $WalletID = $this->Users_model->addToWallet($InsertData, $SessionUserID, 5);
            if (!$WalletID)
                return FALSE;
            $ContestsData = $this->getContests('ContestSize,TotalJoined,IsAutoCreate', array('ContestID' => $ContestID));
            /* To Check If Contest Is Auto Create (Yes) */
            if ($ContestsData['IsAutoCreate'] == 'Yes' && ($ContestsData['ContestSize'] - $ContestsData['TotalJoined']) == 0) {
                /* Get Contests Details */
                $ContestData = $this->db->query('SELECT * FROM sports_contest WHERE ContestID = ' . $ContestID . ' LIMIT 1')->result_array()[0];

                /* Create Contest */
                $Contest = array();
                $Contest['ContestName'] = $ContestData['ContestName'];
                $Contest['ContestFormat'] = $ContestData['ContestFormat'];
                $Contest['ContestType'] = $ContestData['ContestType'];
                $Contest['Privacy'] = $ContestData['Privacy'];
                $Contest['IsPaid'] = $ContestData['IsPaid'];
                $Contest['IsConfirm'] = $ContestData['IsConfirm'];
                $Contest['IsAutoCreate'] = $ContestData['IsAutoCreate'];
                $Contest['ShowJoinedContest'] = $ContestData['ShowJoinedContest'];
                $Contest['WinningAmount'] = $ContestData['WinningAmount'];
                $Contest['ContestSize'] = $ContestData['ContestSize'];
                $Contest['EntryFee'] = $ContestData['EntryFee'];
                $Contest['NoOfWinners'] = $ContestData['NoOfWinners'];
                $Contest['EntryType'] = $ContestData['EntryType'];
                $Contest['UserJoinLimit'] = $ContestData['UserJoinLimit'];
                $Contest['CashBonusContribution'] = $ContestData['CashBonusContribution'];
                $Contest['CustomizeWinning'] = $ContestData['CustomizeWinning'];
                $Contest['IsWinnerSocialFeed'] = $ContestData['IsWinnerSocialFeed'];
                $this->addContest($Contest, $ContestData['UserID'], $ContestData['MatchID'], $ContestData['SeriesID']);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $this->Users_model->getWalletDetails($SessionUserID);
    }

    /*
      Description: To get joined contest
     */

    function getJoinedContests($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {

        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'MatchID' => 'M.MatchID',
                'MatchGUID' => 'M.MatchGUID',
                'StatusID' => 'E.StatusID',
                'MatchIDLive' => 'M.MatchIDLive',
                'MatchTypeID' => 'M.MatchTypeID',
                'MatchNo' => 'M.MatchNo',
                'MatchLocation' => 'M.MatchLocation',
                'MatchStartDateTime' => 'CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '") AS MatchStartDateTime',
                'MatchStartDateTimeUTC' => 'M.MatchStartDateTime MatchStartDateTimeUTC',
                'ContestID' => 'C.ContestID',
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                'ShowJoinedContest' => 'C.ShowJoinedContest',
                'CashBonusContribution' => 'C.CashBonusContribution',
                'UserInvitationCode' => 'C.UserInvitationCode',
                'WinningAmount' => 'C.WinningAmount',
                'GameType' => 'C.GameType',
                'ContestSize' => 'C.ContestSize',
                'ContestFormat' => 'C.ContestFormat',
                'ContestType' => 'C.ContestType',
                'GameTimeLive' => 'C.GameTimeLive',
                'EntryFee' => 'C.EntryFee',
                'NoOfWinners' => 'C.NoOfWinners',
                'EntryType' => 'C.EntryType',
                'CustomizeWinning' => 'C.CustomizeWinning',
                'UserID' => 'JC.UserID',
                'UserTeamID' => 'JC.UserTeamID',
                'JoinInning' => 'JC.JoinInning',
                'EntryDate' => 'JC.EntryDate',
                'TotalPoints' => 'JC.TotalPoints',
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'SeriesID' => 'M.SeriesID',
                'TeamNameLocal' => 'TL.TeamName AS TeamNameLocal',
                'TeamNameVisitor' => 'TV.TeamName AS TeamNameVisitor',
                'TeamNameShortLocal' => 'TL.TeamNameShort AS TeamNameShortLocal',
                'TeamNameShortVisitor' => 'TV.TeamNameShort AS TeamNameShortVisitor',
                'TeamFlagLocal' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag) as TeamFlagLocal',
                'TeamFlagVisitor' => 'CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag) as TeamFlagVisitor',
                'SeriesName' => 'S.SeriesName AS SeriesName',
                'TotalJoined' => '(SELECT COUNT(*) AS TotalJoined
                                                FROM sports_contest_join
                                                WHERE sports_contest_join.ContestID =  C.ContestID ) AS TotalJoined',
                'UserTotalJoinedInMatch' => '(SELECT COUNT(*)
                                                FROM sports_contest_join
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND UserID= ' . $Where['SessionUserID'] . ') AS UserTotalJoinedInMatch',
                'UserRank' => 'JC.UserRank',
                'StatusID' => 'E.StatusID',
                'Status' => 'CASE E.StatusID
                when "1" then "Pending"
                when "2" then "Running"
                when "3" then "Cancelled"
                when "5" then "Completed"
                END as Status',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' %h:%i %p") as MatchStartDateTime',
                'CurrentDateTime' => 'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") as CurrentDateTime',
                'MatchDate' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%Y-%m-%d") MatchDate',
                'MatchTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "%H:%i:%s") MatchTime',
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }

        $this->db->select('C.ContestGUID,C.ContestName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_contest C, sports_matches M, sports_teams TL, sports_teams TV,sports_series S,sports_contest_join JC');
        $this->db->where("C.ContestID", "JC.ContestID", FALSE);
        $this->db->where("C.ContestID", "E.EntityID", FALSE);
        $this->db->where("M.MatchID", "C.MatchID", FALSE);
        $this->db->where("S.SeriesID", "C.SeriesID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = $Where['Keyword'];
            $this->db->group_start();
            $this->db->like("C.ContestName", $Where['Keyword']);
            $this->db->or_like("C.GameType", $Where['Keyword']);
            $this->db->or_like("C.ContestSize", $Where['Keyword']);
            $this->db->or_like("C.EntryFee", $Where['Keyword']);
            $this->db->or_like("C.WinningAmount", $Where['Keyword']);
            $this->db->or_like("M.MatchLocation", $Where['Keyword']);
            $this->db->or_like("M.MatchNo", $Where['Keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['ContestID'])) {
            $this->db->where("C.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['SessionUserID'])) {
            $this->db->where("JC.UserID", $Where['SessionUserID']);
        }
        if (!empty($Where['UserTeamID'])) {
            $this->db->where("JC.UserTeamID", $Where['UserTeamID']);
        }
        if (!empty($Where['Privacy'])) {
            $this->db->where("C.Privacy", $Where['Privacy']);
        }
        if (!empty($Where['GameType'])) {
            $this->db->where("C.GameType", $Where['GameType']);
        }
        if (!empty($Where['IsPaid'])) {
            $this->db->where("C.IsPaid", $Where['IsPaid']);
        }
        if (!empty($Where['LeagueType'])) {
            $this->db->where("C.LeagueType", $Where['LeagueType']);
        }
        if (!empty($Where['WinningAmount'])) {
            $this->db->where("C.WinningAmount >=", $Where['WinningAmount']);
        }
        if (!empty($Where['ContestSize'])) {
            $this->db->where("C.ContestSize", $Where['ContestSize']);
        }
        if (!empty($Where['EntryFee'])) {
            $this->db->where("C.EntryFee", $Where['EntryFee']);
        }
        if (!empty($Where['NoOfWinners'])) {
            $this->db->where("C.NoOfWinners", $Where['NoOfWinners']);
        }
        if (!empty($Where['EntryType'])) {
            $this->db->where("C.EntryType", $Where['EntryType']);
        }
        // print_r($Where['MatchID']);die;
        if (!empty($Where['MatchID'])) {
            $this->db->where("C.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['StatusIDIn'])) {
            $this->db->where_in("E.StatusID", $Where['StatusIDIn']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('M.MatchStartDateTime', 'ASC');
        //$this->db->group_by('C.ContestGUID');

        if (!empty($Where['getJoinedMatches']) && $Where['getJoinedMatches'] == 'Yes') {
            // $this->db->group_by('C.MatchID');
        }
        //$this->db->group_by('C.ContestID');
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
        //echo $this->db->last_query();exit;
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], TRUE) : array();
                }
                $Return['Data']['Records'] = $Records;
            } else {
                $Record = $Query->row_array();
                $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], TRUE) : array();
                return $Record;
            }
        } else {
            $Return['Data']['Records'] = array();
        }

        if (!empty($Where['MatchID'])) {
            $Return['Data']['Statics'] = $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                    )as NormalContest,
                    ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                    )as ReverseContest,(
                    SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '" 
                    )as JoinedContest,( 
                    SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '"
                ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
        } else {
            $Return['Data']['Statics'] = $this->db->query('SELECT (
                SELECT COUNT(DISTINCT J.MatchID) AS `UpcomingJoinedContest` FROM `sports_contest_join` J, `tbl_entity` E , `sports_matches` M WHERE E.EntityID = J.ContestID AND J.MatchID=M.MatchID AND E.StatusID = 1 AND J.UserID = "' . @$Where['SessionUserID'] . '" 
                )as UpcomingJoinedContest,
                (
                SELECT COUNT(DISTINCT J.MatchID) AS `LiveJoinedContest` FROM `sports_contest_join` J, `tbl_entity` E , `sports_matches` M WHERE E.EntityID = J.ContestID AND J.MatchID=M.MatchID AND E.StatusID = 2 AND J.UserID = "' . @$Where['SessionUserID'] . '" 
                )as LiveJoinedContest,
                (
                SELECT COUNT(DISTINCT J.MatchID) AS `CompletedJoinedContest` FROM `sports_contest_join` J, `tbl_entity` E, `sports_matches` M WHERE E.EntityID = J.ContestID AND J.MatchID=M.MatchID AND E.StatusID = 5 AND J.UserID = "' . @$Where['SessionUserID'] . '"
            )as CompletedJoinedContest'
                    )->row();
        }

        return $Return;
    }

    /*
      Description: ADD user team
     */

    function addUserTeam($Input = array(), $SessionUserID, $MatchID, $StatusID = 2) {

        $this->db->trans_start();

        $EntityGUID = get_guid();

        /* Add user team to entity table and get EntityID. */
        $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 12, "UserID" => $SessionUserID, "StatusID" => $StatusID));

        $UserTeamCount = $this->db->query('SELECT count(T.UserTeamID) as UserTeamsCount,U.Username from `sports_users_teams` T join tbl_users U on U.UserID = T.UserID WHERE T.MatchID = "' . $MatchID . '" AND T.UserID = "' . $SessionUserID . '" ')->row();
        /* Add user team to user team table . */
        $teamName = " Team " . ($UserTeamCount->UserTeamsCount + 1);
        $InsertData = array(
            "UserTeamID" => $EntityID,
            "UserTeamGUID" => $EntityGUID,
            "UserID" => $SessionUserID,
            // "UserTeamName"  =>   @$Input['UserTeamName'],
            "UserTeamName" => $teamName,
            "UserTeamType" => @$Input['UserTeamType'],
            "MatchID" => $MatchID
        );
        $this->db->insert('sports_users_teams', $InsertData);

        /* Add User Team Players */
        if (!empty($Input['UserTeamPlayers'])) {

            /* Get Players */
            $PlayersIdsData = array();
            $PlayersData = $this->Sports_model->getPlayers('PlayerID,MatchID', array('MatchID' => $MatchID), TRUE, 0);
            if ($PlayersData) {
                foreach ($PlayersData['Data']['Records'] as $PlayerValue) {
                    $PlayersIdsData[$PlayerValue['PlayerGUID']] = $PlayerValue['PlayerID'];
                }
            }

            /* Manage User Team Players */
            $Input['UserTeamPlayers'] = (!is_array($Input['UserTeamPlayers'])) ? json_decode($Input['UserTeamPlayers'], TRUE) : $Input['UserTeamPlayers'];
            $UserTeamPlayers = array();
            foreach ($Input['UserTeamPlayers'] as $Value) {
                if (isset($PlayersIdsData[$Value['PlayerGUID']])) {
                    $UserTeamPlayers[] = array(
                        'UserTeamID' => $EntityID,
                        'MatchID' => $MatchID,
                        'PlayerID' => $PlayersIdsData[$Value['PlayerGUID']],
                        'PlayerPosition' => $Value['PlayerPosition']
                    );
                }
            }
            if ($UserTeamPlayers)
                $this->db->insert_batch('sports_users_team_players', $UserTeamPlayers);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        return $EntityGUID;
    }

    /*
      Description: EDIT user team
     */

    function editUserTeam($Input = array(), $UserTeamID) {

        $this->db->trans_start();

        /* Delete Team Players */
        $this->db->delete('sports_users_team_players', array('UserTeamID' => $UserTeamID));

        /* Edit user team to user team table . */
        $this->db->where('UserTeamID', $UserTeamID);
        $this->db->limit(1);
        $this->db->update('sports_users_teams', array('UserTeamName' => $Input['UserTeamName'], 'UserTeamType' => $Input['UserTeamType']));

        /* Add User Team Players */
        if (!empty($Input['UserTeamPlayers'])) {

            /* Get Match ID */
            $MatchID = $this->db->query('SELECT MatchID FROM sports_users_teams WHERE UserTeamID = ' . $UserTeamID . ' LIMIT 1')->row()->MatchID;
            /* Get Players */
            $PlayersIdsData = array();
            $PlayersData = $this->Sports_model->getPlayers('PlayerID,MatchID', array('MatchID' => $MatchID), TRUE, 0);
            if ($PlayersData) {
                foreach ($PlayersData['Data']['Records'] as $PlayerValue) {
                    $PlayersIdsData[$PlayerValue['PlayerGUID']] = $PlayerValue['PlayerID'];
                }
            }

            /* Manage User Team Players */
            $Input['UserTeamPlayers'] = (!is_array($Input['UserTeamPlayers'])) ? json_decode($Input['UserTeamPlayers'], TRUE) : $Input['UserTeamPlayers'];
            $UserTeamPlayers = array();
            foreach ($Input['UserTeamPlayers'] as $Value) {
                if (isset($PlayersIdsData[$Value['PlayerGUID']])) {
                    $UserTeamPlayers[] = array(
                        'UserTeamID' => $UserTeamID,
                        'MatchID' => $MatchID,
                        'PlayerID' => $PlayersIdsData[$Value['PlayerGUID']],
                        'PlayerPosition' => $Value['PlayerPosition']
                    );
                }
            }
            if ($UserTeamPlayers)
                $this->db->insert_batch('sports_users_team_players', $UserTeamPlayers);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /*
      Description: To get user teams
     */

    function getUserTeams($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserTeamID' => 'UT.UserTeamID',
                'MatchID' => 'UT.MatchID',
                'MatchInning' => 'UT.MatchInning',
                'TotalPoints' => 'JC.TotalPoints',
                'TotalJoinedContests' => '(SELECT COUNT(ContestID) FROM sports_contest_join WHERE UserTeamID = UT.UserTeamID) TotalJoinedContests',
                'IsTeamJoined' => '(SELECT IF( EXISTS(
                                    SELECT sports_contest_join.ContestID FROM sports_contest_join
                                    WHERE sports_contest_join.UserTeamID =  UT.UserTeamID AND sports_contest_join.ContestID = ' . @$Where['TeamsContestID'] . ' LIMIT 1), "Yes", "No")) AS IsTeamJoined'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('UT.UserTeamGUID,UT.UserTeamName,UT.UserTeamType,UT.UserTeamID,UT.MatchID,UT.UserID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        if (in_array('TotalPoints', $Params)) {
            $this->db->from('tbl_entity E, sports_users_teams UT,sports_contest_join JC');
            $this->db->where("UT.UserTeamID", "E.EntityID", false);
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", false);
        } else {
            $this->db->from('tbl_entity E, sports_users_teams UT');
            $this->db->where("UT.UserTeamID", "E.EntityID", false);
        }

        if (!empty($Where['Keyword'])) {
            $this->db->like("UT.UserTeamName", $Where['Keyword']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("UT.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['UserTeamType']) && $Where['UserTeamType'] != 'All') {
            $this->db->where("UT.UserTeamType", $Where['UserTeamType']);
        }
        if (!empty($Where['UserTeamID'])) {
            $this->db->where("UT.UserTeamID", $Where['UserTeamID']);
        }
        if (!empty($Where['MatchInning'])) {
            $this->db->where("UT.MatchInning", $Where['MatchInning']);
        }
        if (!empty($Where['UserID']) && empty($Where['UserTeamID'])) { // UserTeamID used to manage other user team details (On live score page)
            $this->db->where("UT.UserID", $Where['UserID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('UT.UserTeamID', 'DESC');
        if (!empty($Where['MatchID'])) {
            $Return['Data']['Statics'] = $this->db->query('SELECT (
                SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal"
                )as NormalContest,
                (
                SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse"
                )as ReverseContest,
                (
                SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '"
                )as JoinedContest,
                ( 
                SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '" 
            ) as TotalTeams'
                    )->row();
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
                if (in_array('UserTeamPlayers', $Params)) {
                    foreach ($Return['Data']['Records'] as $key => $value) {
                        $UserTeamPlayers = $this->getUserTeamPlayers('PlayerID,PlayerSalary,PlayerSalaryCredit,PlayerPosition,PlayerBattingStyle,PlayerBowlingStyle,PlayerName,PlayerPic,PlayerCountry,PlayerRole,Points,TeamGUID,TotalPoints,TotalPointCredits', array('UserTeamID' => $value['UserTeamID']));
                        $Return['Data']['Records'][$key]['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                    }
                }
                if ($Where['ValidateAdvanceSafe'] == "Yes") {
                    foreach ($Return['Data']['Records'] as $key => $value) {
                        $Return['Data']['Records'][$key]['IsEditUserTeam'] = "Yes";
                        $isvalidate = $this->ValidateAdvanceSafePlay($value['MatchID'], $value['UserID'], $value['UserTeamID']);
                        if (!$isvalidate) {
                            $Return['Data']['Records'][$key]['IsEditUserTeam'] = "No";
                        }
                    }
                }
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('UserTeamPlayers', $Params)) {
                    $UserTeamPlayers = $this->getUserTeamPlayers('PlayerID,PlayerSalary,PlayerSalaryCredit,TeamGUID,PlayerPosition,PlayerBattingStyle,PlayerBowlingStyle,PlayerName,PlayerPic,PlayerCountry,PlayerRole,Points,TotalPoints,TotalPointCredits', array('UserTeamID' => $Where['UserTeamID']));
                    $Record['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                }
                return $Record;
            }
        }

        return FALSE;
    }

    /*
      Description: To get user team players
     */

    function getUserTeamPlayers($Field = '', $Where = array()) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'SeriesGUID' => 'S.SeriesGUID',
                'PlayerPosition' => 'UTP.PlayerPosition',
                'Points' => 'UTP.Points',
                'PlayerID' => 'UTP.PlayerID',
                'PlayerName' => 'P.PlayerName',
                'PlayerPic' => 'IF(P.PlayerPic IS NULL,CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png"),CONCAT("' . BASE_URL . '","uploads/PlayerPic/",P.PlayerPic)) AS PlayerPic',
                'PlayerCountry' => 'P.PlayerCountry',
                'PlayerSalary' => 'TP.PlayerSalary',
                'PlayerBattingStyle' => 'P.PlayerBattingStyle',
                'PlayerBowlingStyle' => 'P.PlayerBowlingStyle',
                'PlayerRole' => 'TP.PlayerRole',
                'PointsData' => 'TP.PointsData',
                'TeamGUID' => 'T.TeamGUID',
                'MatchType' => 'SM.MatchTypeName as MatchType',
                'TotalPointCredits' => '(SELECT IFNULL(SUM(`TotalPoints`),0) FROM `sports_team_players` WHERE `PlayerID` = TP.PlayerID AND `SeriesID` = TP.SeriesID) TotalPointCredits'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('P.PlayerGUID,P.PlayerID,M.MatchGUID,UTP.Points');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_users_team_players UTP, sports_players P, sports_team_players TP,sports_teams T,sports_matches M,sports_set_match_types SM');
        if (array_keys_exist($Params, array('SeriesGUID'))) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "TP.SeriesID", FALSE);
        }
        $this->db->where("UTP.PlayerID", "P.PlayerID", FALSE);
        $this->db->where("UTP.PlayerID", "TP.PlayerID", FALSE);
        $this->db->where("UTP.MatchID", "TP.MatchID", FALSE);
        $this->db->where("T.TeamID", "TP.TeamID", FALSE);
        $this->db->where("M.MatchID", "TP.MatchID", FALSE);
        $this->db->where("M.MatchTypeID", "SM.MatchTypeID", FALSE);
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = $Where['Keyword'];
            $this->db->like("P.PlayerName", $Where['Keyword']);
        }
        if (!empty($Where['UserTeamID'])) {
            $this->db->where("UTP.UserTeamID", $Where['UserTeamID']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("UTP.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['PlayerID'])) {
            $this->db->where("UTP.PlayerID", $Where['PlayerID']);
        }
        if (!empty($Where['PlayerRole'])) {
            $this->db->where("TP.PlayerRole", $Where['PlayerRole']);
        }
        if (!empty($Where['PlayerPosition'])) {
            $this->db->where("UTP.PlayerPosition", $Where['PlayerPosition']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('P.PlayerName', 'ASC');
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            $Records = array();
            $MatchStatus = 0;
            foreach ($Query->result_array() as $key => $Record) {
                if ($key == 0) {
                    /* Get Match Status */
                    $Query = $this->db->query('SELECT E.StatusID FROM `sports_matches` `M`,`tbl_entity` `E` WHERE M.`MatchGUID` = "' . $Record['MatchGUID'] . '" AND M.MatchID = E.EntityID LIMIT 1');
                    $MatchStatus = ($Query->num_rows() > 0) ? $Query->row()->StatusID : 0;
                }
                $Records[] = $Record;
                $Records[$key]['PointCredits'] = ($MatchStatus == 2 || $MatchStatus == 5) ? $Record['Points'] : $Record['PlayerSalary'];
                if (in_array('MyTeamPlayer', $Params)) {
                        $this->db->select('DISTINCT(SUTP.PlayerID)');
                        $this->db->where("JC.UserTeamID", "SUTP.UserTeamID", FALSE);
                        $this->db->where("SUT.UserTeamID", "SUTP.UserTeamID", FALSE);
                        $this->db->where('SUT.MatchID', $Where['MatchID']);
                        $this->db->where('SUT.UserID', $Where['UserID']);
                        $this->db->from('sports_contest_join JC,sports_users_teams SUT,sports_users_team_players SUTP');
                        $MyPlayers = $this->db->get()->result_array();
                        $MyPlayersIds = (!empty($MyPlayers)) ? array_column($MyPlayers, 'PlayerID') : array();
                        $Records[$key]['MyPlayer'] = (in_array($Record['PlayerID'], $MyPlayersIds)) ? 'Yes' : 'No';
                    }

                    if (in_array('PlayerSelectedPercent', $Params)) {
                        $TotalTeams = $this->db->query('Select count(*) as TotalTeams from sports_users_teams WHERE MatchID="' . $Where['MatchID'] . '"')->row()->TotalTeams;

                        $this->db->select('count(SUTP.PlayerID) as TotalPlayer');
                        $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                        $this->db->where("SUTP.PlayerID", $Record['PlayerID']);
                        $this->db->where("SUTP.MatchID", $Where['MatchID']);
                        $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                        $Players = $this->db->get()->row();
                        $Records[$key]['PlayerSelectedPercent'] = ($TotalTeams > 0 ) ? round((($Players->TotalPlayer * 100 ) / $TotalTeams), 2) > 100 ? 100 : round((($Players->TotalPlayer * 100 ) / $TotalTeams), 2) : 0;
                    }

                    if (in_array('TopPlayer', $Params)) {
                        $Wicketkipper = $this->findKeyValuePlayers($Records, "WicketKeeper");
                        $Batsman = $this->findKeyValuePlayers($Records, "Batsman");
                        $Bowler = $this->findKeyValuePlayers($Records, "Bowler");
                        $Allrounder = $this->findKeyValuePlayers($Records, "AllRounder");
                        usort($Batsman, function ($a, $b) {
                            return $b['TotalPoints'] - $a['TotalPoints'];
                        });
                        usort($Bowler, function ($a, $b) {
                            return $b['TotalPoints'] - $a['TotalPoints'];
                        });
                        usort($Wicketkipper, function ($a, $b) {
                            return $b['TotalPoints'] - $a['TotalPoints'];
                        });
                        usort($Allrounder, function ($a, $b) {
                            return $b['TotalPoints'] - $a['TotalPoints'];
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
                            return $b['TotalPoints'] - $a['TotalPoints'];
                        });
                    }

                    if (in_array($Record['PlayerID'], array_column($AllPlayers, 'PlayerID'))) {
                        $Records[$key]['TopPlayer'] = 'Yes';
                    } else {
                        $Records[$key]['TopPlayer'] = 'No';
                    }
                    $Records[$key]['PointsData'] = ($Record['PointsData'] != '' ? json_decode($Record['PointsData']) : array());
            }
            return $Records;
        }
        return FALSE;
    }

    /*
      Description: To get contest winning users
     */

    function getContestWinningUsers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'TotalPoints' => 'JC.TotalPoints',
                'EntryFee' => 'C.EntryFee',
                'ContestSize' => 'C.ContestSize',
                'NoOfWinners' => 'C.NoOfWinners',
                'UserTeamName' => 'UT.UserTeamName',
                'FullName' => 'CONCAT_WS(" ",U.FirstName,U.LastName) FullName',
                'UserRank' => 'JC.UserRank'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('C.ContestName');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_contest_join JC, sports_contest C, sports_users_teams UT, tbl_users U');
        $this->db->where("C.ContestID", "JC.ContestID", FALSE);
        $this->db->where("JC.UserTeamID", "UT.UserTeamID", FALSE);
        $this->db->where("JC.UserID", "U.UserID", FALSE);
        $this->db->where("JC.UserWinningAmount >", 0);
        if (!empty($Where['Keyword'])) {
            $this->db->like("C.ContestName", $Where['ContestName']);
        }
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('UserRank', 'ASC');

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
      Description: To get joined contest users
     */

    function getJoinedContestsUsers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'TotalPoints' => 'JC.TotalPoints',
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'FirstName' => 'U.FirstName',
                'MiddleName' => 'U.MiddleName',
                'LastName' => 'U.LastName',
                'Username' => 'U.Username',
                'Email' => 'U.Email',
                'PhoneNumber' => 'U.PhoneNumber',
                'UserID' => 'U.UserID',
                'UserRank' => 'JC.UserRank',
                'UserTeamName' => 'UT.UserTeamName',
                'UserTeamID' => 'UT.UserTeamID',
                'ProfilePic' => 'IF(U.ProfilePic IS NULL,CONCAT("' . BASE_URL . '","uploads/profile/picture/","default.jpg"),CONCAT("' . BASE_URL . '","uploads/profile/picture/",U.ProfilePic)) AS ProfilePic',
                'UserRank' => 'JC.UserRank'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('U.UserGUID,UT.UserTeamGUID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_contest_join JC, tbl_users U, sports_users_teams UT');
        $this->db->where("JC.UserTeamID", "UT.UserTeamID", FALSE);
        $this->db->where("JC.UserID", "U.UserID", FALSE);
        if (!empty($Where['UserID'])) {
            //$this->db->where("JC.UserID", $Where['UserID']);
        }
        if (!empty($Where['NotInUser'])) {
            // $this->db->where("JC.UserID !=", $Where['NotInUser']);
        }

        if (!empty($Where['PointFilter']) && $Where['PointFilter'] == 'TotalPoints') {
            $this->db->where("JC.TotalPoints >", 0);
        }
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            if (!empty($Where['UserID'])) {
                $this->db->order_by('JC.UserID=' . $Where['UserID'] . ' DESC', null, FALSE);
            }

            $this->db->order_by('JC.UserRank', 'ASC');
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
                foreach ($Return['Data']['Records'] as $key => $record) {
                    $UserTeamPlayers = $this->getUserTeamPlayers('PointsData,PlayerSelectedPercent,TopPlayer,MyTeamPlayer,PlayerPosition,SeriesGUID,PlayerName,PlayerRole,PlayerBattingStyle,PlayerBowlingStyle,PlayerPic,TeamGUID,PlayerSalary,MatchType,PointCredits', array('UserTeamID' => $record['UserTeamID'],'UserID' => $Where['UserID'],'MatchID' => $Where['MatchID']));
                    $Return['Data']['Records'][$key]['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                }
                return $Return;
            } else {
                $result = $Query->row_array();

                foreach ($result as $key => $record) {
                    $UserTeamPlayers = $this->getUserTeamPlayers('PointsData,PlayerSelectedPercent,TopPlayer,MyTeamPlayer,PlayerPosition,SeriesGUID,PlayerName,PlayerRole,PlayerPic,PlayerBattingStyle,PlayerBowlingStyle,TeamGUID,PlayerSalary,MatchType,PointCredits', array('UserTeamID' => $record['UserTeamGUID'],'UserID' => $Where['UserID'],'MatchID' => $Where['MatchID']));
                    $Return['Data']['Records'][$key]['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                }
                return $Return;
            }
        }
        return FALSE;
    }

    /*
      Description: To get joined contest users (MongoDB)
     */
    function getJoinedContestsUsersMongoDB($Where = array(), $PageNo = 1, $PageSize = 15) {

        /* Get Joined Contest Users */
        $ContestCollection   = $this->fantasydb->{'Contest_'.$Where['ContestID']};
        $JoinedContestsUsers = iterator_to_array($ContestCollection->find([],['projection' => ['UserTeamName' => 1,'Username' => 1,'FullName' => 1,'ProfilePic' => 1,'TotalPoints' => 1,'UserTeamPlayers' => 1,'UserRank' => 1,'UserWinningAmount' => 1],'skip' => paginationOffset($PageNo, $PageSize) ,'limit' => $PageSize,'sort' => ['UserRank' => 1]]));
        if(count($JoinedContestsUsers) > 0){
            $Return['Data']['TotalRecords'] = $ContestCollection->count();
            $Return['Data']['Records'] = $JoinedContestsUsers;
            return $Return;
        }
        return FALSE;
    }

    /*
      Description: To Cancel Contest
     */

    function cancelContest($Input = array(), $SessionUserID, $ContestID) {

        /* Update Contest Status */
        $this->db->where('EntityID', $ContestID);
        $this->db->limit(1);
        $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));
    }

    /*
      Description: To Download Contest Teams
     */

    function downloadTeams($Input = array()) {

        error_reporting(1);
        /* Teams File Name */
        $FileName = 'contest-teams-' . $Input['ContestGUID'] . '.pdf';
        if (file_exists(getcwd() . '/uploads/Contests/' . $FileName)) {
            return array('TeamsPdfFileURL' => BASE_URL . 'uploads/Contests/' . $FileName);
        } else {

            /* Create PDF file using MPDF Library */
            ob_start();
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 300);
            require_once getcwd() . '/vendor/autoload.php';

            /* Get Matches Details */
            $ContestsData = $this->getContests('TeamNameLocal,TeamNameVisitor,EntryFee,ContestSize,UserInvitationCode', array('ContestID' => $Input['ContestID']));


            /* Get Contest User Teams */
            $UserTeams = $this->getUserTeams('TotalPoints,UserTeamPlayers', array('ContestID' => $Input['ContestID']), TRUE, 0);

            /* Player Positions */
            $PlayerPositions = array('Captain' => '(C)', 'ViceCaptain' => '(VC)', 'Player' => '');

            /* Create PDF HTML */
            $PDFHtml = '<html lang="en" data-ng-app="fxi"><body style ="font-family: Montserrat, sans-serif;">';
            $PDFHtml .= '<div style="width:100%; max-width:1500px;">';
            $PDFHtml .= '<table style="background:#ffa100; width:100%;" width="100%" cellpadding="0"  cellspacing="0">';
            $PDFHtml .= '<tr>';
            $PDFHtml .= '<td style="padding:10px 0;">';
            $PDFHtml .= '<span>' . SITE_NAME . '</span>';
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0;font-size:15px; color:#fff;">';
            $PDFHtml .= $ContestsData['TeamNameLocal'] . ' V/S ' . $ContestsData['TeamNameVisitor'];
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0; font-size:15px; color:#fff;">';
            $PDFHtml .= 'Entry Fee: ' . DEFAULT_CURRENCY . $ContestsData['EntryFee'];
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0; font-size:15px; color:#fff;">';
            $PDFHtml .= 'Contest Size: ' . $ContestsData['ContestSize'];
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0; font-size:15px; color:#fff;">';
            $PDFHtml .= 'Invite Code: ' . $ContestsData['UserInvitationCode'];
            $PDFHtml .= '</td>';
            $PDFHtml .= '</tr>';
            $PDFHtml .= '</table>';
            $PDFHtml .= '<table style="width:100%; border:1px solid #000" cellpadding="0"  cellspacing="0">';
            $PDFHtml .= '<thead>';
            $PDFHtml .= '<tr>';
            $PDFHtml .= '<th style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">User Team Name</th>';
            for ($I = 1; $I <= 11; $I++) {
                $PDFHtml .= '<th style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">Player' . ' ' . $I . '</th>';
            }
            $PDFHtml .= '</tr>';
            $PDFHtml .= '</thead>';
            $PDFHtml .= '<tbody>';
            foreach ($UserTeams['Data']['Records'] as $TeamValue) {
                $PDFHtml .= '<tr>';
                $PDFHtml .= '<td style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">' . $TeamValue['UserTeamName'] . '</td>';
                foreach ($TeamValue['UserTeamPlayers'] as $PlayerValue) {
                    $PDFHtml .= '<td style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">' . $PlayerValue['PlayerName'] . ' ' . $PlayerPositions[$PlayerValue['PlayerPosition']] . '</td>';
                }
                $PDFHtml .= '</tr>';
            }
            $PDFHtml .= '</tbody>';
            $PDFHtml .= '</table>';
            $PDFHtml .= '</div></body></html>';

            /* MPDF Object */
            // $MPDF = new mPDF();
            $MPDF = new \Mpdf\Mpdf();
            ini_set("pcre.backtrack_limit", "500000000");
            $PDFFilePath = getcwd() . '/uploads/Contests/' . $FileName;
            $MPDF->WriteHTML($PDFHtml);
            $output = $MPDF->output($PDFFilePath, \Mpdf\Output\Destination::FILE);
            // $output = $MPDF->output($PDFFilePath, 'F');
            return array('TeamsPdfFileURL' => BASE_URL . 'uploads/Contests/' . $FileName);
        }
    }

    public function getWinningBreakup($Field = '', $Input = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $dataArr = array();
        $EntryFee = $Input['EntryFee'];
        $WinningAmount = $Input['WinningAmount'];
        $MatchID = $Input['MatchID'];
        $UserID = $Input['UserID'];
        $ContestSize = $Input['ContestSize'];

        $IsMultiEntry = $Input['EntryType'];

        $TotalFee = (abs($WinningAmount) * 20) / 100;

        if ($Input['IsPaid'] == 'Yes') {
            $MatchID = $Input['MatchID'];
            $UserID = $Input['UserID'];
            $WinningAmount = $Input['WinningAmount'];

            if ($ContestSize > 0 && $ContestSize < 11) {
                $result = array();
                $data = [];
                if ($ContestSize > 5) {
                    $ContestSize = 5;
                }
                if ($ContestSize == 5) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => "1",
                        'To' => "1",
                        'Percent' => "40",
                        'WinningAmount' => (string) (($WinningAmount * 40) / 100));

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => "2",
                        'To' => "2",
                        'Percent' => "25",
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize--;
                }

                if ($ContestSize == 4) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '40',
                        'WinningAmount' => (string) (($WinningAmount * 40) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string) (($WinningAmount * 30) / 100));

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 3) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '50',
                        'WinningAmount' => (string) (($WinningAmount * 50) / 100));

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string) (($WinningAmount * 30) / 100));

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result1 = array();
                    $result1[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '70',
                        'WinningAmount' => (string) (($WinningAmount * 70) / 100));

                    $result1[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string) (($WinningAmount * 30) / 100));

                    $result2 = array();
                    $result2[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '100',
                        'WinningAmount' => (string) (($WinningAmount * 100) / 100));

                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result);
                    $data[] = array('NoOfWinners' => $ContestSize - 1, 'Winners' => $result1);
                    $data[] = array('NoOfWinners' => $ContestSize - 2, 'Winners' => $result2);
                }

                if ($ContestSize == 2) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '100',
                        'WinningAmount' => (string) (($WinningAmount * 100) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 1, 'Winners' => $result);
                }
                $Return['Data'] = $data;
            }


            if ($ContestSize > 10 && $ContestSize < 17) {

                $result = array();
                $data = [];
                if ($ContestSize > 10) {
                    $ContestSize = 7;
                }
                if ($ContestSize == 7) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result5[] = array(
                        'Rank' => '2',
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result5[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));
                    $result5[] = array(
                        'Rank' => '6',
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));
                    $result5[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize--;
                }

                if ($ContestSize == 6) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '30',
                        'WinningAmount' => (string) (($WinningAmount * 30) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));
                    $result4[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 5) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '40',
                        'WinningAmount' => (string) (($WinningAmount * 40) / 100));

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result[] = array(
                        'Rank' => "4",
                        'From' => 4,
                        'To' => 4,
                        'Percent' => 12.5,
                        'WinningAmount' => ($WinningAmount * 12.5) / 100);

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));



                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result);
                }

                $Return['Data'] = $data;
            }


            if ($ContestSize > 16 && $ContestSize < 21) {

                $result = array();
                $data = [];
                if ($ContestSize > 16) {
                    $ContestSize = 10;
                }
                if ($ContestSize == 10) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result5[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 3;
                }

                if ($ContestSize == 7) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result4[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));
                    $result4[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));
                    $result4[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 6) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '30',
                        'WinningAmount' => (string) (($WinningAmount * 30) / 100));

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));

                    $result[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));

                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result);
                }

                $Return['Data'] = $data;
            }
            if ($ContestSize > 20 && $ContestSize < 25) {
                $result = array();
                $data = [];
                if ($ContestSize > 20) {
                    $ContestSize = 15;
                }
                if ($ContestSize == 15) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result5[] = array(
                        'Rank' => "4-6",
                        'From' => '4',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));
                    $result5[] = array(
                        'Rank' => "7-10",
                        'From' => '7',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));
                    $result5[] = array(
                        'Rank' => "11-15",
                        'From' => '11',
                        'To' => '15',
                        'Percent' => '2.5',
                        'WinningAmount' => (string) (($WinningAmount * 2.5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 5;
                }

                if ($ContestSize == 10) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result4[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 3;
                }

                if ($ContestSize == 7) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string) (($WinningAmount * 12.5) / 100));

                    $result[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));

                    $result[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));

                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result);
                }

                $Return['Data'] = $data;
            }

            if ($ContestSize > 24 && $ContestSize < 50) {
                $result = array();
                $data = [];
                $size = $ContestSize;
                if ($ContestSize > 24) {
                    $ContestSize = 25;
                }
                if ($ContestSize == 25) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string) (($WinningAmount * 6) / 100));
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));
                    $result5[] = array(
                        'Rank' => "6-8",
                        'From' => '6',
                        'To' => '8',
                        'Percent' => '4',
                        'WinningAmount' => (string) (($WinningAmount * 4) / 100));
                    $result5[] = array(
                        'Rank' => "9-11",
                        'From' => '9',
                        'To' => '11',
                        'Percent' => '3',
                        'WinningAmount' => (string) (($WinningAmount * 3) / 100));
                    $result5[] = array(
                        'Rank' => "12-15",
                        'From' => '12',
                        'To' => '15',
                        'Percent' => '2',
                        'WinningAmount' => (string) (($WinningAmount * 2) / 100));
                    $result5[] = array(
                        'Rank' => "16-25",
                        'From' => '16',
                        'To' => '25',
                        'Percent' => '1',
                        'WinningAmount' => (string) (($WinningAmount * 1) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 10;
                }

                if ($ContestSize == 15) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result4[] = array(
                        'Rank' => "4-6",
                        'From' => '4',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string) (($WinningAmount * 7.5) / 100));
                    $result4[] = array(
                        'Rank' => "7-10",
                        'From' => '7',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));
                    $result4[] = array(
                        'Rank' => "11-15",
                        'From' => '11',
                        'To' => '15',
                        'Percent' => '2.5',
                        'WinningAmount' => (string) (($WinningAmount * 2.5) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 5;
                }

                if ($ContestSize == 10 && $size < 31) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string) (($WinningAmount * 20) / 100));

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));
                    $result[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));

                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result);
                }

                $Return['Data'] = $data;
            }

            if ($ContestSize > 49 && $ContestSize < 1000000000) {
                $result = array();
                $data = [];
                if ($ContestSize > 50) {
                    $ContestSize = 50;
                }
                if ($ContestSize == 50) {

                    $result5[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '8',
                        'WinningAmount' => (string) (($WinningAmount * 8) / 100));
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string) (($WinningAmount * 6) / 100));
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));
                    $result5[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '4',
                        'WinningAmount' => (string) (($WinningAmount * 4) / 100));
                    $result5[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '3.5',
                        'WinningAmount' => (string) (($WinningAmount * 3.5) / 100));
                    $result5[] = array(
                        'Rank' => "8",
                        'From' => '8',
                        'To' => '8',
                        'Percent' => '3',
                        'WinningAmount' => (string) (($WinningAmount * 3) / 100));
                    $result5[] = array(
                        'Rank' => "9",
                        'From' => '9',
                        'To' => '9',
                        'Percent' => '2.5',
                        'WinningAmount' => (string) (($WinningAmount * 2.5) / 100));

                    $result5[] = array(
                        'Rank' => "10",
                        'From' => '10',
                        'To' => '10',
                        'Percent' => '2',
                        'WinningAmount' => (string) (($WinningAmount * 2) / 100));
                    $result5[] = array(
                        'Rank' => "11-25",
                        'From' => '11',
                        'To' => '25',
                        'Percent' => '1.5',
                        'WinningAmount' => (string) (($WinningAmount * 1.5) / 100));
                    $result5[] = array(
                        'Rank' => "26-37",
                        'From' => '26',
                        'To' => '37',
                        'Percent' => '1',
                        'WinningAmount' => (string) (($WinningAmount * 1) / 100));
                    $result5[] = array(
                        'Rank' => "38-50",
                        'From' => '38',
                        'To' => '50',
                        'Percent' => '.5',
                        'WinningAmount' => (string) (($WinningAmount * .5) / 100));



                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 25;
                }

                if ($ContestSize == 25) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string) (($WinningAmount * 25) / 100));

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string) (($WinningAmount * 15) / 100));

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string) (($WinningAmount * 10) / 100));
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string) (($WinningAmount * 6) / 100));
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string) (($WinningAmount * 5) / 100));
                    $result4[] = array(
                        'Rank' => "6-8",
                        'From' => '6',
                        'To' => '8',
                        'Percent' => '4',
                        'WinningAmount' => (string) (($WinningAmount * 4) / 100));
                    $result4[] = array(
                        'Rank' => "9-11",
                        'From' => '9',
                        'To' => '11',
                        'Percent' => '3',
                        'WinningAmount' => (string) (($WinningAmount * 3) / 100));
                    $result4[] = array(
                        'Rank' => "12-15",
                        'From' => '12',
                        'To' => '15',
                        'Percent' => '2',
                        'WinningAmount' => (string) (($WinningAmount * 2) / 100));
                    $result4[] = array(
                        'Rank' => "16-25",
                        'From' => '16',
                        'To' => '25',
                        'Percent' => '1',
                        'WinningAmount' => (string) (($WinningAmount * 1) / 100));


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 10;
                }



                $Return['Data'] = $data;
            }
        }
        return $Return;
    }

    /*
      Description: Switch user team
     */

    function switchUserTeam($UserID, $ContestID, $UserTeamID, $OldUserTeamGUID) {
        /* Update Joined Contest Team Status */
        $this->db->where('UserID', $UserID);
        $this->db->where('ContestID', $ContestID);
        $this->db->where('UserTeamID', $OldUserTeamGUID);
        $this->db->limit(1);
        $this->db->update('sports_contest_join', array('UserTeamID' => $UserTeamID));
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

    /*
      Description: validate Advance or safe Play.
     */

    function ValidateAdvanceSafePlay($MatchID, $UserID, $UserTeamID) {
        $JoinedContest = $this->getJoinedContests("ContestID,GameType,GameTimeLive,MatchStartDateTimeUTC", array("UserTeamID" => $UserTeamID, "MatchID" => $MatchID, "SessionUserID" => $UserID, "GameType" => "Advance", "OrderBy" => "GameTimeLive", "Sequence" => "DESC"), TRUE);
        if ($JoinedContest['Data']['TotalRecords'] > 0) {
            $CurrentDateTime = strtotime(date('Y-m-d H:i:s')); // UTC 
            if ($JoinedContest['Data']['Records'][0]["GameTimeLive"] > 0) {
                $MatchStartDateTime = strtotime($JoinedContest['Data']['Records'][0]['MatchStartDateTimeUTC']) - $JoinedContest['Data']['Records'][0]["GameTimeLive"] * 60;
                if ($MatchStartDateTime > $CurrentDateTime) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    function getTotalDummyJoinedContest($ContestID) {
        return $this->db->query("SELECT count(JC.ContestID) as DummyJoinedContest FROM sports_contest_join as JC JOIN tbl_users ON tbl_users.UserID = JC.UserID WHERE JC.ContestID = $ContestID AND tbl_users.UserTypeID = 3")->row()->DummyJoinedContest;
    }

    function UpdateVirtualJoinContest($ContestID) {
        /* Edit user team to user team table . */
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', array('IsVirtualUserJoined' => "Completed"));
        return true;
    }

    function GetVirtualTeamPlayerMatchWise($MatchID, $DummyUserPercentage) {
        $Sql = "SELECT SUT.UserTeamID, SUT.UserID, CONCAT('[',GROUP_CONCAT(distinct CONCAT('{\"PlayerID\":\"',PlayerID,'\",\"PlayerPosition\":\"',PlayerPosition,'\"}')),']') as Players "
                . "FROM `sports_users_teams` SUT JOIN tbl_users U ON U.UserID = SUT.UserID "
                . "JOIN sports_users_team_players UTP ON UTP.UserTeamID = SUT.UserTeamID WHERE SUT.MatchID = $MatchID "
                . "AND U.UserTypeID = 3 GROUP BY SUT.UserTeamID ORDER BY RAND() limit $DummyUserPercentage";
        return $this->db->query($Sql)->result_array();
    }

    function ContestUpdateVirtualTeam($ContestID, $IsDummyJoined) {
        /* Edit user team to user team table . */
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', array('IsDummyJoined' => $IsDummyJoined + 1));
        return true;
    }

}

?>