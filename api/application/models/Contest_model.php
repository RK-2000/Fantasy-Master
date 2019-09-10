<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contest_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sports_model');
    }

    /*
      Description:    ADD contest to system.
     */
    function addContest($Input = array(), $SessionUserID, $MatchID, $SeriesID, $StatusID = 1)
    {

        /* Create Multiple Contests */
        foreach ($MatchID as $Match) {
            $this->db->trans_start();
            $EntityGUID = get_guid();

            /* Add contest to entity table and get EntityID. */
            $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 11, "UserID" => $SessionUserID, "StatusID" => $StatusID));

            /* Add contest to contest table . */
            $InsertData = array_filter(array(
                "ContestID" => $EntityID,
                "ContestGUID" => $EntityGUID,
                "UserID" => $SessionUserID,
                "GameTimeLive" => @$Input['GameTimeLive'],
                "GameType" => @$Input['GameType'],
                "PredraftContestID" => @$Input['PredraftContestID'],
                "ContestName" => (empty($Input['ContestName'])) ? (($Input['IsPaid'] == 'Yes') ? "Win " . @$Input['WinningAmount'] : 'Win Skill') : $Input['ContestName'],
                "ContestFormat" => @$Input['ContestFormat'],
                "ContestType" => (@$Input['ContestFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['ContestType'],
                "AdminPercent" => @$Input['AdminPercent'],
                "Privacy" => @$Input['Privacy'],
                "IsPaid" => @$Input['IsPaid'],
                "IsConfirm" => (@$Input['Privacy'] == 'Yes') ? 'No' : @$Input['IsConfirm'],
                "IsAutoCreate" => @$Input['IsAutoCreate'],
                "ShowJoinedContest" => @$Input['ShowJoinedContest'],
                "WinningAmount" => @$Input['WinningAmount'],
                "UnfilledWinningPercent" => @$Input['UnfilledWinningPercent'],
                "ContestSize" => (@$Input['ContestFormat'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
                "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
                "NoOfWinners" => (@$Input['WinningAmount'] > 0) ? @$Input['NoOfWinners'] : 0,
                "EntryType" => @$Input['EntryType'],
                "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? (!empty($Input['UserJoinLimit']) ? $Input['UserJoinLimit'] : 6) : 1,
                "CashBonusContribution" => @$Input['CashBonusContribution'],
                "IsPrivacyNameDisplay" => @$Input['IsPrivacyNameDisplay'],
                "SeriesID" => @$SeriesID,
                "MatchID" => @$Match,
                "UserInvitationCode" => random_string('alnum', 6)
            ));
            $InsertData['CustomizeWinning'] = ($InsertData['WinningAmount'] > 0) ? (($InsertData['ContestSize'] == 2) ? json_encode(array(array('From' => 1, 'To' => 1, 'Percent' => 100, 'WinningAmount' => $InsertData['WinningAmount']))) : json_encode(@$Input['CustomizeWinning'])) : NULL;
            $this->db->insert('sports_contest', $InsertData);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            }
        }
        return $EntityID;
    }

    /*
      Description: Update contest to system.
    */
    function updateContest($Input = array(), $SessionUserID, $ContestID, $StatusID = 1)
    {
        $UpdateData = array_filter(array(
            "GameTimeLive" => @$Input['GameTimeLive'],
            "GameType" => @$Input['GameType'],
            "ContestName" => (empty($Input['ContestName'])) ? (($Input['IsPaid'] == 'Yes') ? "Win " . @$Input['WinningAmount'] : 'Win Skill') : $Input['ContestName'],
            "ContestFormat" => @$Input['ContestFormat'],
            "ContestType" => (@$Input['ContestFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['ContestType'],
            "AdminPercent" => @$Input['AdminPercent'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => (@$Input['Privacy'] == 'Yes') ? 'No' : @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "ShowJoinedContest" => @$Input['ShowJoinedContest'],
            "WinningAmount" => @$Input['WinningAmount'],
            "UnfilledWinningPercent" => @$Input['UnfilledWinningPercent'],
            "ContestSize" => (@$Input['ContestFormat'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => (@$Input['WinningAmount'] > 0) ? @$Input['NoOfWinners'] : 0,
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? (!empty($Input['UserJoinLimit']) ? $Input['UserJoinLimit'] : 6) : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "IsPrivacyNameDisplay" => @$Input['IsPrivacyNameDisplay']
        ));
        $UpdateData['CustomizeWinning'] = ($UpdateData['WinningAmount'] > 0) ? (($UpdateData['ContestSize'] == 2) ? json_encode(array(array('From' => 1, 'To' => 1, 'Percent' => 100, 'WinningAmount' => $UpdateData['WinningAmount']))) : json_encode(@$Input['CustomizeWinning'])) : NULL;
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', $UpdateData);
    }

    /*
      Description: Delete contest to system.
     */
    function deleteContest($SessionUserID, $ContestID)
    {
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->delete('sports_contest');
    }

    /*
      Description: To Cancel Contest
     */
    function cancelContest($Input = array(), $SessionUserID, $ContestID)
    {
        /* Update Contest Status */
        $this->db->where('EntityID', $ContestID);
        $this->db->limit(1);
        $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 3));
    }

    /*
      Description: To get contest
     */
    function getContests($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
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
                'UnfilledWinningPercent' => 'C.UnfilledWinningPercent',
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
                'TeamFlagLocal' => 'IF(TL.TeamFlag IS NULL,CONCAT("' . BASE_URL . '","uploads/TeamFlag/","team.png"), CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag)) TeamFlagLocal',
                'TeamFlagVisitor' => 'IF(TV.TeamFlag IS NULL,CONCAT("' . BASE_URL . '","uploads/TeamFlag/","team.png"), CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag)) TeamFlagVisitor',
                'StatusID' => 'E.StatusID',
                'SeriesName' => 'S.SeriesName',
                'IsJoined' => '(SELECT IF( EXISTS(SELECT EntryDate FROM sports_contest_join
                                                        WHERE sports_contest_join.ContestID =  C.ContestID AND UserID = ' . @$Where['SessionUserID'] . ' LIMIT 1), "Yes", "No")) AS IsJoined',
                'TotalJoined' => '(SELECT COUNT(TotalPoints)
                                                        FROM sports_contest_join
                                                        WHERE ContestID =  C.ContestID ) TotalJoined',
                'UserTotalJoinedInMatch' => '(SELECT COUNT(TotalPoints)
                                                FROM sports_contest_join,tbl_entity
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND sports_contest_join.ContestID = tbl_entity.EntityID AND tbl_entity.StatusID != 3 AND sports_contest_join.UserID= ' . @$Where['SessionUserID'] . ') UserTotalJoinedInMatch',
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
                'UserTeamName' => 'UT.UserTeamName',
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'TotalPoints' => 'JC.TotalPoints',
                'UserRank' => 'JC.UserRank',
                'TotalAmountReceived' => '(SELECT IFNULL(SUM(SC.EntryFee),0) FROM sports_contest SC, sports_contest_join SJC WHERE SC.ContestID = SJC.ContestID AND SC.ContestID = C.ContestID) TotalAmountReceived',
                'TotalWinningAmount'  => '(SELECT IFNULL(SUM(SJC.UserWinningAmount),0) FROM sports_contest SC, sports_contest_join SJC WHERE SC.ContestID = SJC.ContestID AND SC.ContestID = C.ContestID) TotalWinningAmount',
                'UserTeamDetails' => "( SELECT CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'UserTeamGUID', SUT.UserTeamGUID, 'UserTeamName', SUT.UserTeamName,'UserTeamType',SUT.UserTeamType,'TotalPoints', SJC.TotalPoints) ), ']' ) FROM sports_contest_join SJC, sports_users_teams SUT WHERE SJC.UserTeamID = SUT.UserTeamID AND SJC.ContestID = C.ContestID AND SJC.UserID = " . $Where['SessionUserID'] . " ORDER BY SUT.UserTeamID DESC)  UserTeamDetails"
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('C.ContestGUID,C.ContestName,C.ContestID ContestIDAsUse');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('tbl_entity E, sports_contest C, sports_matches M');
        if (in_array('MatchType', $Params)) {
            $this->db->from('sports_set_match_types MT');
            $this->db->where("M.MatchTypeID", "MT.MatchTypeID", FALSE);
        }
        if (in_array('SeriesName', $Params)) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "C.SeriesID", FALSE);
        }
        if (array_keys_exist($Params, array('TeamNameLocal', 'TeamNameVisitor', 'TeamNameShortLocal', 'TeamNameShortVisitor', 'TeamFlagLocal', 'TeamFlagVisitor'))) {
            $this->db->from('sports_teams TL, sports_teams TV');
            $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
            $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        }
        if (in_array('UserTeamName', $Params)) {
            $this->db->from('sports_users_teams UT');
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", false);
        }
        if (!empty($Where['MyJoinedContest']) && $Where['MyJoinedContest'] == "Yes") {
            $this->db->from('sports_contest_join JC');
            $this->db->where("JC.ContestID", "C.ContestID", FALSE);
            $this->db->where("JC.UserID", $Where['SessionUserID']);
        }
        $this->db->where("C.ContestID", "E.EntityID", FALSE);
        $this->db->where("C.MatchID", "M.MatchID", FALSE);
        if (!empty($Where['Keyword'])) {
            $this->db->group_start();
            $this->db->like("C.ContestName", $Where['Keyword']);
            $this->db->or_like("M.MatchLocation", $Where['Keyword']);
            $this->db->or_like("M.MatchNo", $Where['Keyword']);
            $this->db->group_end();
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
        if (!empty($Where['Filter']) && $Where['Filter'] == 'NonJoined') {
            $this->db->having("TotalJoined", "0");
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
        if (!empty($Where['Filter']) && $Where['Filter'] == 'NonCanceled') {
            $this->db->where_in("E.StatusID", array(1, 2, 5));
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
            $this->db->where_in("E.StatusID", ($Where['StatusID'] == 10) ? 2 : $Where['StatusID']);
        }
        if (!empty($Where['MyJoinedContest']) && $Where['MyJoinedContest'] == "Yes") {
            $this->db->where('EXISTS (select ContestID from sports_contest_join JE where JE.ContestID = C.ContestID AND JE.UserID="' . @$Where['SessionUserID'] . '" LIMIT 1)');
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
        } else if (!empty($Where['OrderByToday']) && $Where['OrderByToday'] == 'Yes') {
            $this->db->order_by('DATE(M.MatchStartDateTime)="' . date('Y-m-d') . '" DESC', null, FALSE);
            $this->db->order_by('E.StatusID=1 DESC', null, FALSE);
        } else {
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
                    if (in_array('CustomizeWinning', $Params)) {
                        $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                    }
                    if (in_array('MatchScoreDetails', $Params)) {
                        $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                    }
                    if (in_array('NoOfWinners', $Params)) {
                        $Records[$key]['NoOfWinners'] = ($Record['NoOfWinners'] == 0) ? 1 : $Record['NoOfWinners'];
                    }
                    if (in_array('UserTeamDetails', $Params)) {
                        $Records[$key]['UserTeamDetails'] = (!empty($Record['UserTeamDetails'])) ? json_decode($Record['UserTeamDetails'], true) : array();
                    }
                    unset($Records[$key]['ContestIDAsUse']);
                }
                $Return['Data']['Records'] = $Records;
            } else {
                $Record = $Query->row_array();
                if (in_array('CustomizeWinning', $Params)) {
                    $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                }
                if (in_array('MatchScoreDetails', $Params)) {
                    $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                }
                if (in_array('NoOfWinners', $Params)) {
                    $Record['NoOfWinners'] = ($Record['NoOfWinners'] == 0) ? 1 : $Record['NoOfWinners'];
                }
                if (in_array('UserTeamDetails', $Params)) {
                    $Record['UserTeamDetails'] = (!empty($Record['UserTeamDetails'])) ? json_decode($Record['UserTeamDetails'], true) : array();
                }
                if (in_array('Statics', $Params)) {
                    $Record['Statics'] = $this->contestStatics(@$Where['SessionUserID'], $Where['MatchID']);
                }
                unset($Record['ContestIDAsUse']);
                return $Record;
            }
        } else {
            if (!$multiRecords) {
                return array();
            }
        }
        if (in_array('Statics', $Params)) {
            $Return['Data']['Statics'] = $this->contestStatics(@$Where['SessionUserID'], $Where['MatchID']);
        }
        $Return['Data']['Records'] = empty($Records) ? array() : $Records;
        return $Return;
    }

    /*
      Description: ADD user team
     */
    function addUserTeam($Input = array(), $SessionUserID, $MatchID, $StatusID = 2)
    {

        $this->db->trans_start();
        $EntityGUID = get_guid();

        /* Add user team to entity table and get EntityID. */
        $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 12, "UserID" => $SessionUserID, "StatusID" => $StatusID));

        /* Get Teams Count */
        $UserTeamCount = $this->db->query('SELECT COUNT(UserTeamName) UserTeamsCount FROM `sports_users_teams` WHERE MatchID = ' . $MatchID . ' AND UserID = ' . $SessionUserID)->row()->UserTeamsCount;

        /* Add user team to user team table . */
        $InsertData = array_filter(array(
            "UserTeamID"   => $EntityID,
            "UserTeamGUID" => $EntityGUID,
            "UserID"       => $SessionUserID,
            "UserTeamName" => "Team " . ($UserTeamCount + 1),
            "UserTeamType" => @$Input['UserTeamType'],
            "MatchID"      => $MatchID
        ));
        $this->db->insert('sports_users_teams', $InsertData);

        /* Get Players */
        $PlayersIdsData = $this->cache->memcached->get('UserTeamPlayers_' . $MatchID);
        if (empty($PlayersIdsData)) {
            $PlayersData = $this->db->query('SELECT P.`PlayerID`,P.`PlayerGUID` FROM `sports_players` P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = ' . $MatchID . ' LIMIT 100'); // Max 100 Players
            if ($PlayersData->num_rows() > 0) {
                $PlayersIdsData = array_column($PlayersData->result_array(), 'PlayerID', 'PlayerGUID');
                $this->cache->memcached->save('UserTeamPlayers_' . $MatchID, $PlayersIdsData, 3600 * 6); // Expire in every 6 hours
            }
        }

        /* Manage User Team Players */
        $UserTeamPlayers = array();
        foreach ($Input['UserTeamPlayers'] as $Value) {
            $UserTeamPlayers[] = array(
                'UserTeamID'     => $EntityID,
                'MatchID'        => $MatchID,
                'PlayerID'       => $PlayersIdsData[$Value['PlayerGUID']],
                'PlayerPosition' => $Value['PlayerPosition']
            );
        }
        if ($UserTeamPlayers) {
            $this->db->insert_batch('sports_users_team_players', $UserTeamPlayers);
        }

        /* Update Player Selection */
        $this->updatePlayerSelectionPercent($MatchID);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $EntityGUID;
    }

    /*
      Description: EDIT user team
     */
    function editUserTeam($Input = array(), $UserTeamID, $MatchID)
    {
        $this->db->trans_start();

        /* Delete User Team Players */
        $this->db->where('UserTeamID', $UserTeamID);
        $this->db->limit(11);
        $this->db->delete('sports_users_team_players');

        /* Add User Team Players */
        if (!empty($Input['UserTeamPlayers'])) {

            /* Get Players */
            $PlayersIdsData = $this->cache->memcached->get('UserTeamPlayers_' . $MatchID);
            if (empty($PlayersIdsData)) {
                $PlayersData = $this->db->query('SELECT P.`PlayerID`,P.`PlayerGUID` FROM `sports_players` P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = ' . $MatchID . ' LIMIT 100'); // Max 100 Players
                if ($PlayersData->num_rows() > 0) {
                    $PlayersIdsData = array_column($PlayersData->result_array(), 'PlayerID', 'PlayerGUID');
                    $this->cache->memcached->save('UserTeamPlayers_' . $MatchID, $PlayersIdsData, 3600 * 6); // Expire in every 6 hours
                }
            }

            /* Manage User Team Players */
            $UserTeamPlayers = array();
            foreach ($Input['UserTeamPlayers'] as $Value) {
                $UserTeamPlayers[] = array(
                    'UserTeamID'     => $UserTeamID,
                    'MatchID'        => $MatchID,
                    'PlayerID'       => $PlayersIdsData[$Value['PlayerGUID']],
                    'PlayerPosition' => $Value['PlayerPosition']
                );
            }
            if ($UserTeamPlayers) {
                $this->db->insert_batch('sports_users_team_players', $UserTeamPlayers);
            }
        }

        /* Update Player Selection */
        $this->updatePlayerSelectionPercent($MatchID);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /*
      Description: Update player selection percentage
     */
    function updatePlayerSelectionPercent($MatchID)
    {
        /* Get Total Teams */
        $TotalTeams = $this->db->query('SELECT COUNT(UserTeamID) TotalTeams FROM sports_users_teams WHERE MatchID='. $MatchID)->row()->TotalTeams;
        if($TotalTeams > 0){

            /* Get Match Players */
            $MatchPlayers = $this->db->query('SELECT P.`PlayerID` FROM `sports_players` P,sports_team_players TP WHERE P.PlayerID = TP.PlayerID AND TP.MatchID = ' . $Value['MatchID'] . ' LIMIT 100');
            if ($MatchPlayers->num_rows() > 0) {
                foreach(array_column($MatchPlayers->result_array(), 'PlayerID') as $PlayerID){

                    /* Get Total Players */
                    $this->db->select('COUNT(SUTP.PlayerID) TotalPlayer');
                    $this->db->from('sports_users_teams SUT,sports_users_team_players SUTP');
                    $this->db->where("SUTP.UserTeamID", "SUT.UserTeamID", FALSE);
                    $this->db->where(array("SUTP.PlayerID" => $PlayerID,"SUTP.MatchID" => $MatchID));
                    $Players = $this->db->get()->row();
                    $PlayerSelectedPercent = (($Players->TotalPlayer * 100 ) / $TotalTeams) > 100 ? 100 : (($Players->TotalPlayer * 100 ) / $TotalTeams);

                    /* Update Player Selection Percent */
                    $this->db->where(array('PlayerID' => $PlayerID,'MatchID' => $MatchID));
                    $this->db->limit(1);
                    $this->db->update('sports_team_players', array('SelectionPercent' => $PlayerSelectedPercent));
                }
            }
        }
    }

    /*
      Description: Switch user team
     */
    function switchUserTeam($UserID, $ContestID, $UserTeamID, $OldUserTeamGUID)
    {

        /* Switch Team */
        $this->db->where(array('UserID' => $UserID, 'ContestID' => $ContestID, 'UserTeamID' => $OldUserTeamGUID));
        $this->db->limit(1);
        $this->db->update('sports_contest_join', array('UserTeamID' => $UserTeamID));
    }

    /*
      Description: To get user teams
    */
    function getUserTeams($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserTeamID' => 'UT.UserTeamID',
                'MatchID' => 'UT.MatchID',
                'MatchInning' => 'UT.MatchInning',
                'TotalPoints' => 'JC.TotalPoints',
                'TotalJoinedContests' => '(SELECT COUNT(EntryDate) FROM sports_contest_join WHERE UserTeamID = UT.UserTeamID) TotalJoinedContests',
                'IsTeamJoined' => '(SELECT IF( EXISTS(
                                    SELECT sports_contest_join.ContestID FROM sports_contest_join
                                    WHERE sports_contest_join.UserTeamID =  UT.UserTeamID AND sports_contest_join.ContestID = ' . @$Where['TeamsContestID'] . ' LIMIT 1), "Yes", "No")) IsTeamJoined',
                'UserTeamPlayers' => "(SELECT CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'MatchGUID', SM.MatchGUID, 'TeamGUID', ST.TeamGUID, 'PlayerGUID', SP.PlayerGUID, 'PlayerName', SP.PlayerName, 'PlayerCountry', SP.PlayerCountry, 'PlayerPic', IF( SP.PlayerPic IS NULL, CONCAT( '" . BASE_URL . "', 'uploads/PlayerPic/', 'player.png' ), CONCAT( '" . BASE_URL . "', 'uploads/PlayerPic/', SP.PlayerPic ) ), 'PlayerBattingStyle', SP.PlayerBattingStyle, 'PlayerBowlingStyle', SP.PlayerBowlingStyle, 'PlayerRole', STP.PlayerRole, 'PlayerSalary', STP.PlayerSalary, 'TotalPoints', STP.TotalPoints, 'PlayerPosition', SUTP.PlayerPosition, 'Points', SUTP.Points, 'TotalPointCredits', ( SELECT IFNULL(SUM(`TotalPoints`), 0) FROM `sports_team_players` WHERE `PlayerID` = STP.PlayerID AND `SeriesID` = STP.SeriesID ) ) ), ']' ) FROM sports_matches SM, sports_teams ST, sports_players SP, sports_team_players STP, sports_users_team_players SUTP WHERE ST.TeamID = STP.TeamID AND SUTP.PlayerID = SP.PlayerID AND SUTP.PlayerID = STP.PlayerID AND SUTP.MatchID = STP.MatchID AND SM.MatchID = STP.MatchID AND SUTP.UserTeamID = UT.UserTeamID ORDER BY SP.PlayerName ASC) UserTeamPlayers"
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('UT.UserTeamGUID,UT.UserTeamName,UT.UserTeamType,UT.UserTeamID UserTeamIDAsUse,UT.MatchID MatchIDAsUse,UT.UserID UserIDAsUse');
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
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
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
        } else {
            $this->db->order_by('UT.UserTeamID', 'DESC');
        }
        if (in_array('Statics', $Params)) {
            $Return['Data']['Statics'] = $this->contestStatics(@$Where['SessionUserID'], $Where['MatchID']);
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
                    if (in_array('UserTeamPlayers', $Params)) {
                        $Records[$key]['UserTeamPlayers'] = (!empty($Record['UserTeamPlayers'])) ? json_decode($Record['UserTeamPlayers'], true) : array();
                    }
                    if ($Where['ValidateAdvanceSafe'] == "Yes") {
                        $Records[$key]['IsEditUserTeam'] = (!$this->validateAdvanceSafePlay($Record['MatchIDAsUse'], $Record['UserIDAsUse'], $Record['UserTeamIDAsUse'])) ? "No" : "Yes";
                    }
                    unset($Records[$key]['MatchIDAsUse'], $Records[$key]['UserIDAsUse'], $Records[$key]['UserTeamIDAsUse']);
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('UserTeamPlayers', $Params)) {
                    $Record['UserTeamPlayers'] = (!empty($Record['UserTeamPlayers'])) ? json_decode($Record['UserTeamPlayers'], true) : array();
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To get user team players
    */
    function getUserTeamPlayers($Field = '', $Where = array())
    {
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
                'PlayerSelectedPercent' => 'TP.SelectionPercent',
                'MatchType' => 'SM.MatchTypeName as MatchType',
                'TotalPointCredits' => '(SELECT IFNULL(SUM(`TotalPoints`),0) FROM `sports_team_players` WHERE `PlayerID` = TP.PlayerID AND `SeriesID` = TP.SeriesID) TotalPointCredits',
                'MyTeamPlayer' => '(SELECT IF( EXISTS(SELECT UTP.PlayerID FROM sports_contest_join JC,sports_users_team_players SUTP WHERE JC.UserTeamID = SUTP.UserTeamID AND JC.MatchID = ' . $Where['MatchID'] . ' AND JC.UserID = ' . (!empty($Where['SessionUserID'])) ? $Where['SessionUserID'] : $Where['UserID'] . ' AND SUTP.PlayerID = P.PlayerID LIMIT 1), "Yes", "No")) MyPlayer'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('P.PlayerGUID,M.MatchGUID,UTP.Points');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_users_team_players UTP, sports_players P, sports_team_players TP,sports_matches M');
        if (array_keys_exist($Params, array('TeamGUID'))) {
            $this->db->from('sports_teams T');
            $this->db->where("T.TeamID", "TP.TeamID", FALSE);
        }
        if (array_keys_exist($Params, array('MatchType'))) {
            $this->db->from('sports_set_match_types SM');
            $this->db->where("M.MatchTypeID", "SM.MatchTypeID", FALSE);
        }
        if (array_keys_exist($Params, array('SeriesGUID'))) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "TP.SeriesID", FALSE);
        }
        $this->db->where("UTP.PlayerID", "P.PlayerID", FALSE);
        $this->db->where("UTP.PlayerID", "TP.PlayerID", FALSE);
        $this->db->where("UTP.MatchID", "TP.MatchID", FALSE);
        $this->db->where("M.MatchID", "TP.MatchID", FALSE);
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
        } else {
            $this->db->order_by('P.PlayerName', 'ASC');
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if (in_array('TopPlayer', $Params)) {
                $BestPlayers = $this->Sports_model->getMatchBestPlayers(array('MatchID' => $Where['MatchID'],'UserID' => (!empty($Where['SessionUserID'])) ? $Where['SessionUserID'] : $Where['UserID']));
                if (!empty($BestPlayers)) {
                    $BestXIPlayers = array_column($BestPlayers['Data']['Records'], 'PlayerGUID');
                }
            }
            $Records = array();
            $MatchStatus = 0;
            foreach ($Query->result_array() as $key => $Record) {
                if ($key == 0) {
                    /* Get Match Status */
                    $Query = $this->db->query('SELECT E.StatusID FROM `sports_matches` `M`,`tbl_entity` `E` WHERE M.`MatchGUID` = "' . $Record['MatchGUID'] . '" AND M.MatchID = E.EntityID LIMIT 1');
                    $MatchStatus = ($Query->num_rows() > 0) ? $Query->row()->StatusID : 0;
                }
                $Records[] = $Record;
                if (in_array('PointCredits', $Params)) {
                    $Records[$key]['PointCredits'] = ($MatchStatus == 2 || $MatchStatus == 5) ? $Record['Points'] : $Record['PlayerSalary'];
                }
                if (in_array('PointsData', $Params)) {
                    $Records[$key]['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
                }
                if (in_array('TopPlayer', $Params)) {
                    $Records[$key]['TopPlayer'] = (in_array($Record['PlayerGUID'], $BestXIPlayers)) ? 'Yes' : 'No';
                }
            }
            return $Records;
        }
        return FALSE;
    }

    /*
      Description: To Download Contest Teams (MPDF)
    */
    function downloadTeams($Input = array())
    {
        /* Teams File Name */
        $FileName = 'contest-teams-' . $Input['ContestGUID'] . '.pdf';
        if (file_exists(getcwd() . '/uploads/Contests/' . $FileName)) {
            return array('TeamsPdfFileURL' => BASE_URL . 'uploads/Contests/' . $FileName);
        } else {
            $this->load->helper('file');

            /* Get Matches Details */
            $ContestsData = $this->getContests('TeamNameLocal,TeamNameVisitor,EntryFee,ContestSize,UserInvitationCode', array('ContestID' => $Input['ContestID']));

            /* Get Contest User Teams */
            $ContestCollection = $this->fantasydb->{'Contest_' . $Input['ContestID']};
            $UserTeams = iterator_to_array($ContestCollection->find([], ['projection' => ['_id' => 0, 'UserTeamName' => 1, 'UserTeamPlayers' => 1], 'sort' => ['UserTeamID' => 1]]));
            if ($ContestCollection->count() == 0) {
                $UserTeams = $this->getUserTeams('TotalPoints,UserTeamPlayers', array('ContestID' => $Input['ContestID']), TRUE, 0)['Data']['Records'];
            }

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
            foreach ($UserTeams as $TeamValue) {
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

            /* Create HTML File */
            $HTMLFilePath = getcwd() . '/uploads/Contests/contest-teams-' . $Input['ContestGUID'] . '.html';
            write_file($HTMLFilePath, $PDFHtml, 'w');
            shell_exec('xvfb-run wkhtmltopdf ' . BASE_URL . 'uploads/Contests/contest-teams-' . $Input['ContestGUID'] . '.html ' . getcwd() . '/uploads/Contests/' . $FileName);

            /* Delete Created HTML File */
            unlink($HTMLFilePath);
            return array('TeamsPdfFileURL' => BASE_URL . 'uploads/Contests/' . $FileName);
        }
    }

    /*
      Description: Join contest
     */
    function joinContest($Input = array(), $SessionUserID, $ContestID, $MatchID, $UserTeamID)
    {
        $this->db->trans_start();

        /* Add entry to join contest table . */
        $InsertData = array(
            "UserID"     => $SessionUserID,
            "ContestID"  => $ContestID,
            "MatchID"    => $MatchID,
            "UserTeamID" => $UserTeamID,
            "EntryDate"  => date('Y-m-d H:i:s')
        );
        $this->db->insert('sports_contest_join', $InsertData);

        /* Manage User Wallet */
        if (@$Input['IsPaid'] == 'Yes' && @$Input['EntryFee'] > 0) {

            /* Deduct Money From User Wallet */
            $InsertData = array(
                "Amount"          => @$Input['EntryFee'],
                "WalletAmount"    => $Input['WalletAmountDeduction'],
                "WinningAmount"   => $Input['WinningAmountDeduction'],
                "CashBonus"       => $Input['CashBonusDeduction'],
                "TransactionType" => 'Dr',
                "TransactionID"   => substr(hash('sha256', mt_rand() . microtime()), 0, 20),
                "EntityID"        => $ContestID,
                "UserTeamID"      => $UserTeamID,
                "Narration"       => 'Join Contest',
                "EntryDate"       => date("Y-m-d H:i:s")
            );
            $WalletID = $this->Users_model->addToWallet($InsertData, $SessionUserID, 5);
            if (!$WalletID) {
                return FALSE;
            }
        }

        /* To Check If Contest Is Auto Create (Yes) */
        if (@$Input['IsAutoCreate'] == 'Yes' && ($Input['ContestSize'] - $Input['TotalJoined']) <= 1) {

            /* Get Contests Details */
            $ContestData = $this->db->query('SELECT * FROM sports_contest WHERE ContestID = ' . $ContestID . ' LIMIT 1')->result_array()[0];

            /* Create Contest */
            $Contest = array();
            $Contest['ContestName']           = $ContestData['ContestName'];
            $Contest['ContestFormat']         = $ContestData['ContestFormat'];
            $Contest['ContestType']           = $ContestData['ContestType'];
            $Contest['GameTimeLive']          = $ContestData['GameTimeLive'];
            $Contest['GameType']              = $ContestData['GameType'];
            $Contest['AdminPercent']          = $ContestData['AdminPercent'];
            $Contest['Privacy']               = $ContestData['Privacy'];
            $Contest['IsPaid']                = $ContestData['IsPaid'];
            $Contest['IsConfirm']             = $ContestData['IsConfirm'];
            $Contest['IsAutoCreate']          = $ContestData['IsAutoCreate'];
            $Contest['ShowJoinedContest']     = $ContestData['ShowJoinedContest'];
            $Contest['WinningAmount']         = $ContestData['WinningAmount'];
            $Contest['UnfilledWinningPercent'] = $ContestData['UnfilledWinningPercent'];
            $Contest['ContestSize']           = $ContestData['ContestSize'];
            $Contest['EntryFee']              = $ContestData['EntryFee'];
            $Contest['NoOfWinners']           = $ContestData['NoOfWinners'];
            $Contest['EntryType']             = $ContestData['EntryType'];
            $Contest['UserJoinLimit']         = $ContestData['UserJoinLimit'];
            $Contest['CashBonusContribution'] = $ContestData['CashBonusContribution'];
            $Contest['IsPrivacyNameDisplay']  = $ContestData['IsPrivacyNameDisplay'];
            $Contest['CustomizeWinning']      = json_decode($ContestData['CustomizeWinning'], TRUE);
            $this->addContest($Contest, $ContestData['UserID'], array($ContestData['MatchID']), $ContestData['SeriesID']);
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
    function getJoinedContests($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
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
                'TaxAmount' => 'JC.TaxAmount',
                'SeriesID' => 'M.SeriesID',
                'TeamNameLocal' => 'TL.TeamName AS TeamNameLocal',
                'TeamNameVisitor' => 'TV.TeamName AS TeamNameVisitor',
                'TeamNameShortLocal' => 'TL.TeamNameShort AS TeamNameShortLocal',
                'TeamNameShortVisitor' => 'TV.TeamNameShort AS TeamNameShortVisitor',
                'TeamFlagLocal' => 'IF(TL.TeamFlag IS NULL,CONCAT("' . BASE_URL . '","uploads/TeamFlag/","team.png"), CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TL.TeamFlag)) TeamFlagLocal',
                'TeamFlagVisitor' => 'IF(TV.TeamFlag IS NULL,CONCAT("' . BASE_URL . '","uploads/TeamFlag/","team.png"), CONCAT("' . BASE_URL . '","uploads/TeamFlag/",TV.TeamFlag)) TeamFlagVisitor',
                'SeriesName' => 'S.SeriesName AS SeriesName',
                'TotalJoined' => '(SELECT COUNT(EntryDate) FROM sports_contest_join
                                                WHERE sports_contest_join.ContestID =  C.ContestID ) TotalJoined',
                'UserTotalJoinedInMatch' => '(SELECT COUNT(EntryDate)
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
        $this->db->from('tbl_entity E, sports_contest C, sports_matches M,sports_contest_join JC');
        $this->db->where("C.ContestID", "JC.ContestID", FALSE);
        if (in_array('SeriesName', $Params)) {
            $this->db->from('sports_series S');
            $this->db->where("S.SeriesID", "C.SeriesID", FALSE);
        }
        if (array_keys_exist($Params, array('TeamNameLocal', 'TeamNameVisitor', 'TeamNameShortLocal', 'TeamNameShortVisitor', 'TeamFlagLocal', 'TeamFlagVisitor'))) {
            $this->db->from('sports_teams TL, sports_teams TV');
            $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
            $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
        }
        if (in_array('UserTeamName', $Params)) {
            $this->db->from('sports_users_teams UT');
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", false);
        }
        $this->db->where("C.ContestID", "E.EntityID", FALSE);
        $this->db->where("M.MatchID", "C.MatchID", FALSE);
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = $Where['Keyword'];
            $this->db->group_start();
            $this->db->like("C.ContestName", $Where['Keyword']);
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
        if (!empty($Where['MatchID'])) {
            $this->db->where("C.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where_in("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
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
                    if (in_array('CustomizeWinning', $Params)) {
                        $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], TRUE) : array();
                    }
                }
                $Return['Data']['Records'] = $Records;
            } else {
                $Record = $Query->row_array();
                if (in_array('CustomizeWinning', $Params)) {
                    $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], TRUE) : array();
                }
                return $Record;
            }
        } else {
            $Return['Data']['Records'] = array();
        }
        if (in_array('Statics', $Params)) {
            $Return['Data']['Statics'] = $this->contestStatics(@$Where['SessionUserID'], $Where['MatchID']);
        }
        return $Return;
    }

    /*
      Description: To get joined contest users
     */

    function getJoinedContestsUsers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'TotalPoints' => 'JC.TotalPoints',
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'TaxAmount' => 'JC.TaxAmount',
                'FirstName' => 'U.FirstName',
                'MiddleName' => 'U.MiddleName',
                'LastName' => 'U.LastName',
                'Username' => 'U.Username',
                'FullName' => 'CONCAT_WS(" ",U.FirstName,U.LastName) FullName',
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
        $this->db->select('U.UserGUID,UT.UserTeamGUID,UT.UserTeamID UserTeamIDAsUse,UT.MatchID MatchIDAsUse,U.UserID UserIDAsUse');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_contest_join JC, tbl_users U, sports_users_teams UT');
        $this->db->where("JC.UserTeamID", "UT.UserTeamID", FALSE);
        $this->db->where("JC.UserID", "U.UserID", FALSE);
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['PointFilter']) && $Where['PointFilter'] == 'TotalPoints') {
            $this->db->where("JC.TotalPoints >", 0);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("JC.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            if (!empty($Where['SessionUserID'])) {
                $this->db->order_by('JC.UserID=' . $Where['SessionUserID'] . ' DESC', null, FALSE);
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
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('UserTeamPlayers', $Params)) {
                        $UserTeamPlayers = $this->getUserTeamPlayers('PlayerSelectedPercent,TopPlayer,MyTeamPlayer,MatchType,PointCredits,PlayerRole,TeamGUID,PlayerName,PlayerPic,SeriesGUID,PlayerPosition,PlayerSalary', array('UserTeamID' => $Record['UserTeamIDAsUse'],'MatchID' => $Record['MatchIDAsUse'],'UserID' => $Record['UserIDAsUse']));
                        $Records[$key]['UserTeamPlayers']  = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                    }
                    unset($Records[$key]['UserTeamIDAsUse'],$Records[$key]['MatchIDAsUse'],$Records[$key]['UserIDAsUse']);
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('UserTeamPlayers', $Params)) {
                    if (in_array('UserTeamPlayers', $Params)) {
                        $UserTeamPlayers = $this->getUserTeamPlayers('PlayerSelectedPercent,TopPlayer,MyTeamPlayer,MatchType,PointCredits,PlayerRole,TeamGUID,PlayerName,PlayerPic,SeriesGUID,PlayerPosition,PlayerSalary', array('UserTeamID' => $Record['UserTeamIDAsUse'],'MatchID' => $Record['MatchIDAsUse'],'UserID' => $Record['UserIDAsUse']));
                        $Record['UserTeamPlayers']  = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                    }
                    unset($Record['UserTeamIDAsUse'],$Record['MatchIDAsUse'],$Record['UserIDAsUse']);
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: To get joined contest users (MongoDB)
     */
    function getJoinedContestsUsersMongoDB($Where = array(), $PageNo = 1, $PageSize = 15)
    {
        /* Get Joined Contest Users */
        $ContestCollection   = $this->fantasydb->{'Contest_' . $Where['ContestID']};
        $JoinedContestsUsers = iterator_to_array($ContestCollection->find([], ['projection' => ['_id' => 0, 'UserGUID' => 1, 'UserTeamName' => 1, 'Username' => 1, 'FullName' => 1, 'ProfilePic' => 1, 'TotalPoints' => 1, 'UserTeamPlayers' => 1, 'UserRank' => 1, 'UserWinningAmount' => 1,'TaxAmount' => 1], 'skip' => paginationOffset($PageNo, $PageSize), 'limit' => (int) $PageSize, 'sort' => ['UserRank' => 1]]));
        if (count($JoinedContestsUsers) > 0) {
            $Return['Data']['TotalRecords'] = $ContestCollection->count();
            $Return['Data']['Records'] = $JoinedContestsUsers;
            return $Return;
        }
        return FALSE;
    }

    /*
      Description: Invite contest
     */
    function inviteContest($Input = array(), $SessionUserID)
    {
        /* Invite Users */
        if ($Input['ReferType'] == 'Email' && !empty($Input['Email'])) {

            /* Send invite contest Email to User with invite contest url */
            send_mail(array(
                'emailTo'         => $Input['Email'],
                'template_id'     => 'd-21c013b7011144ac9ab7315081258881',
                'Subject'         => 'Contest Invitation - ' . SITE_NAME,
                "Name"            => $this->db->query('SELECT FirstName FROM tbl_users WHERE UserID = ' . $SessionUserID . ' LIMIT 1')->row()->FirstName,
                "InviteCode"      => $Input['UserInvitationCode'],
                "TeamNameLocal"   => $Input['TeamNameShortLocal'],
                "TeamNameVisitor" => $Input['TeamNameShortVisitor']
            ));
        } else if ($Input['ReferType'] == 'Phone' && !empty($Input['PhoneNumber'])) {

            /* Send invite contest SMS to User with invite contest url */
            $this->Utility_model->sendSMS(array(
                'PhoneNumber' => $Input['PhoneNumber'],
                'Text' => "Put your cricket knowledge to test and play with me on " . SITE_NAME . ". Click " . SITE_HOST . ROOT_FOLDER . "download-app to download the " . SITE_NAME . " app or login on portal and Use contest code: " . $Input['UserInvitationCode'] . " to join my contest for " . $Input['TeamNameShortLocal'] . " V/S " . $Input['TeamNameShortVisitor'] . " Match."
            ));
        }
    }

    /*
      Description: Get Joined contest statics
     */
    function contestStatics($SessionUserID, $MatchID)
    {
        return $this->db->query('SELECT(
                    SELECT COUNT(J.EntryDate) FROM `sports_contest_join` J, `sports_contest` C WHERE C.ContestID = J.ContestID AND J.UserID = "' . $SessionUserID . '" AND C.MatchID = "' . $MatchID . '" 
                    ) JoinedContest,( 
                    SELECT COUNT(UserTeamName) FROM `sports_users_teams`WHERE UserID = "' . $SessionUserID . '" AND MatchID = "' . $MatchID . '"
                ) TotalTeams')->row();
    }

    /*
      Description: To get contest winning users
    */
    function getContestWinningUsers($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'UserWinningAmount' => 'JC.UserWinningAmount',
                'TaxAmount' => 'JC.TaxAmount',
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
        $this->db->from('sports_contest_join JC, sports_contest C');
        if (array_keys_exist($Params, array('UserTeamName'))) {
            $this->db->from('sports_users_teams UT');
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", FALSE);
        }
        if (array_keys_exist($Params, array('FullName'))) {
            $this->db->from('tbl_users U');
            $this->db->where("JC.UserID", "U.UserID", FALSE);
        }
        $this->db->where("C.ContestID", "JC.ContestID", FALSE);
        $this->db->where("JC.UserWinningAmount >", 0);
        if (!empty($Where['Keyword'])) {
            $this->db->like("C.ContestName", $Where['ContestName']);
        }
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('UserRank', 'ASC');
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
      Description: To get contest winning breakup
     */
    public function getWinningBreakup($Field = '', $Input = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
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
                        'WinningAmount' => (string)(($WinningAmount * 40) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => "2",
                        'To' => "2",
                        'Percent' => "25",
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize--;
                }

                if ($ContestSize == 4) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '40',
                        'WinningAmount' => (string)(($WinningAmount * 40) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string)(($WinningAmount * 30) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 3) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '50',
                        'WinningAmount' => (string)(($WinningAmount * 50) / 100)
                    );

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string)(($WinningAmount * 30) / 100)
                    );

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result1 = array();
                    $result1[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '70',
                        'WinningAmount' => (string)(($WinningAmount * 70) / 100)
                    );

                    $result1[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '30',
                        'WinningAmount' => (string)(($WinningAmount * 30) / 100)
                    );

                    $result2 = array();
                    $result2[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '100',
                        'WinningAmount' => (string)(($WinningAmount * 100) / 100)
                    );

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
                        'WinningAmount' => (string)(($WinningAmount * 100) / 100)
                    );


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
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result5[] = array(
                        'Rank' => '2',
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => '6',
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize--;
                }

                if ($ContestSize == 6) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '30',
                        'WinningAmount' => (string)(($WinningAmount * 30) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 5) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '40',
                        'WinningAmount' => (string)(($WinningAmount * 40) / 100)
                    );

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result[] = array(
                        'Rank' => "4",
                        'From' => 4,
                        'To' => 4,
                        'Percent' => 12.5,
                        'WinningAmount' => ($WinningAmount * 12.5) / 100
                    );

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );



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
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 3;
                }

                if ($ContestSize == 7) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize--;
                }

                if ($ContestSize == 6) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '30',
                        'WinningAmount' => (string)(($WinningAmount * 30) / 100)
                    );

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );

                    $result[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );

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
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "4-6",
                        'From' => '4',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "7-10",
                        'From' => '7',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "11-15",
                        'From' => '11',
                        'To' => '15',
                        'Percent' => '2.5',
                        'WinningAmount' => (string)(($WinningAmount * 2.5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 5;
                }

                if ($ContestSize == 10) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 3;
                }

                if ($ContestSize == 7) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result[] = array(
                        'Rank' => "3-4",
                        'From' => '3',
                        'To' => '4',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '12.5',
                        'WinningAmount' => (string)(($WinningAmount * 12.5) / 100)
                    );

                    $result[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );

                    $result[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );

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
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string)(($WinningAmount * 6) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "6-8",
                        'From' => '6',
                        'To' => '8',
                        'Percent' => '4',
                        'WinningAmount' => (string)(($WinningAmount * 4) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "9-11",
                        'From' => '9',
                        'To' => '11',
                        'Percent' => '3',
                        'WinningAmount' => (string)(($WinningAmount * 3) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "12-15",
                        'From' => '12',
                        'To' => '15',
                        'Percent' => '2',
                        'WinningAmount' => (string)(($WinningAmount * 2) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "16-25",
                        'From' => '16',
                        'To' => '25',
                        'Percent' => '1',
                        'WinningAmount' => (string)(($WinningAmount * 1) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 10;
                }

                if ($ContestSize == 15) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "4-6",
                        'From' => '4',
                        'To' => '6',
                        'Percent' => '7.5',
                        'WinningAmount' => (string)(($WinningAmount * 7.5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "7-10",
                        'From' => '7',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "11-15",
                        'From' => '11',
                        'To' => '15',
                        'Percent' => '2.5',
                        'WinningAmount' => (string)(($WinningAmount * 2.5) / 100)
                    );


                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 5;
                }

                if ($ContestSize == 10 && $size < 31) {

                    $result[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '20',
                        'WinningAmount' => (string)(($WinningAmount * 20) / 100)
                    );

                    $result[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );
                    $result[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result[] = array(
                        'Rank' => "5-10",
                        'From' => '5',
                        'To' => '10',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
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
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '8',
                        'WinningAmount' => (string)(($WinningAmount * 8) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string)(($WinningAmount * 6) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "6",
                        'From' => '6',
                        'To' => '6',
                        'Percent' => '4',
                        'WinningAmount' => (string)(($WinningAmount * 4) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "7",
                        'From' => '7',
                        'To' => '7',
                        'Percent' => '3.5',
                        'WinningAmount' => (string)(($WinningAmount * 3.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "8",
                        'From' => '8',
                        'To' => '8',
                        'Percent' => '3',
                        'WinningAmount' => (string)(($WinningAmount * 3) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "9",
                        'From' => '9',
                        'To' => '9',
                        'Percent' => '2.5',
                        'WinningAmount' => (string)(($WinningAmount * 2.5) / 100)
                    );

                    $result5[] = array(
                        'Rank' => "10",
                        'From' => '10',
                        'To' => '10',
                        'Percent' => '2',
                        'WinningAmount' => (string)(($WinningAmount * 2) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "11-25",
                        'From' => '11',
                        'To' => '25',
                        'Percent' => '1.5',
                        'WinningAmount' => (string)(($WinningAmount * 1.5) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "26-37",
                        'From' => '26',
                        'To' => '37',
                        'Percent' => '1',
                        'WinningAmount' => (string)(($WinningAmount * 1) / 100)
                    );
                    $result5[] = array(
                        'Rank' => "38-50",
                        'From' => '38',
                        'To' => '50',
                        'Percent' => '.5',
                        'WinningAmount' => (string)(($WinningAmount * .5) / 100)
                    );
                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result5);
                    $ContestSize = $ContestSize - 25;
                }

                if ($ContestSize == 25) {

                    $result4[] = array(
                        'Rank' => "1",
                        'From' => '1',
                        'To' => '1',
                        'Percent' => '25',
                        'WinningAmount' => (string)(($WinningAmount * 25) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "2",
                        'From' => '2',
                        'To' => '2',
                        'Percent' => '15',
                        'WinningAmount' => (string)(($WinningAmount * 15) / 100)
                    );

                    $result4[] = array(
                        'Rank' => "3",
                        'From' => '3',
                        'To' => '3',
                        'Percent' => '10',
                        'WinningAmount' => (string)(($WinningAmount * 10) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "4",
                        'From' => '4',
                        'To' => '4',
                        'Percent' => '6',
                        'WinningAmount' => (string)(($WinningAmount * 6) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "5",
                        'From' => '5',
                        'To' => '5',
                        'Percent' => '5',
                        'WinningAmount' => (string)(($WinningAmount * 5) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "6-8",
                        'From' => '6',
                        'To' => '8',
                        'Percent' => '4',
                        'WinningAmount' => (string)(($WinningAmount * 4) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "9-11",
                        'From' => '9',
                        'To' => '11',
                        'Percent' => '3',
                        'WinningAmount' => (string)(($WinningAmount * 3) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "12-15",
                        'From' => '12',
                        'To' => '15',
                        'Percent' => '2',
                        'WinningAmount' => (string)(($WinningAmount * 2) / 100)
                    );
                    $result4[] = array(
                        'Rank' => "16-25",
                        'From' => '16',
                        'To' => '25',
                        'Percent' => '1',
                        'WinningAmount' => (string)(($WinningAmount * 1) / 100)
                    );
                    $data[] = array('NoOfWinners' => $ContestSize - 0, 'Winners' => $result4);
                    $ContestSize = $ContestSize - 10;
                }
                $Return['Data'] = $data;
            }
        }
        return $Return;
    }

    /*
      Description: validate Advance or safe Play.
    */
    function validateAdvanceSafePlay($MatchID, $UserID, $UserTeamID)
    {
        $JoinedContest = $this->db->query('SELECT C.GameTimeLive,M.MatchStartDateTime FROM sports_contest C,sports_contest_join JC,sports_matches M WHERE C.ContestID = JC.ContestID AND JC.MatchID = M.MatchID AND JC.UserTeamID =' . $UserTeamID . ' AND JC.MatchID=' . $MatchID . ' AND JC.UserID=' . $UserID . ' AND C.GameType="Advance" LIMIT 1');
        if ($JoinedContest->num_rows() > 0 && $JoinedContest->row()->GameTimeLive > 0) {
            if ((strtotime($JoinedContest->row()->MatchStartDateTime) - ($JoinedContest->row()->GameTimeLive * 60)) < strtotime(date('Y-m-d H:i:s'))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /*
      Description: update virtual join contest status.
    */
    function updateVirtualJoinContest($ContestID)
    {

        /* Edit user team to user team table . */
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', array('IsVirtualUserJoined' => "Completed"));
        return true;
    }

    /*
      Description: get virtual team players (Match Wise).
    */
    function getVirtualTeamPlayerMatchWise($MatchID, $DummyUserPercentage)
    {
        $Users = array();
        $this->db->select("SUT.UserTeamID, SUT.UserID,(Select CONCAT('[',GROUP_CONCAT(distinct CONCAT('{\"PlayerID\":\"',PlayerID,'\",\"PlayerPosition\":\"',PlayerPosition,'\"}')),']') FROM sports_users_team_players UTP WHERE UTP.UserTeamID = SUT.UserTeamID) as Players");
        $this->db->from("sports_users_teams SUT");
        $this->db->where('SUT.MatchID', $MatchID);
        $this->db->where('EXISTS (select UserID from tbl_users U where U.UserID = SUT.UserID AND U.UserTypeID=3)');
        $this->db->limit($DummyUserPercentage);
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            $Users = $Query->result_array();
        }
        return $Users;
    }

    /*
      Description: contest update virtual team.
    */
    function contestUpdateVirtualTeam($ContestID, $IsDummyJoined)
    {
        /* Edit user team to user team table . */
        $this->db->where('ContestID', $ContestID);
        $this->db->limit(1);
        $this->db->update('sports_contest', array('IsDummyJoined' => $IsDummyJoined + 1));
        return true;
    }
}
