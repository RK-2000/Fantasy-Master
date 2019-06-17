<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PreContest_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sports_model');
        $this->load->model('Contest_model');
    }

    /*
      Description:    ADD contest to system.
     */

    function addContest($Input = array(), $SessionUserID, $StatusID = 1) {

        $this->db->trans_start();
        $EntityGUID = get_guid();

        /* Add contest to entity table and get EntityID. */
        /* $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 11, "UserID" => $SessionUserID, "StatusID" => $StatusID)); */
        $CustomizeWinningCustom = null;
        if (empty($Input['CustomizeWinning'])) {
            $CustomizeWinningCustom = array(array(
                    'From' => 1,
                    'To' => (int) @$Input['NoOfWinners'],
                    'Percent' => 100,
                    'WinningAmount' => @$Input['WinningAmount']
            ));
        }

        /* Add contest to contest table . */
        $InsertData = array_filter(array(
            "UserID" => $SessionUserID,
            "ContestName" => @$Input['ContestName'],
            "ContestFormat" => @$Input['ContestFormat'],
            "ContestType" => (@$Input['ContestFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['ContestType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "unfilledWinningPercent" => @$Input['unfilledWinningPercent'],
            "ShowJoinedContest" => @$Input['ShowJoinedContest'],
            "WinningAmount" => @$Input['WinningAmount'],
            "ContestSize" => (@$Input['ContestSize'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => @$Input['NoOfWinners'],
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            "CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((@$Input['ContestFormat'] == 'Head to Head') ? json_encode(array(array('From' => 1, 'To' => 1, 'Percent' => 100, 'WinningAmount' => @$Input['WinningAmount']))) : ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'], JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning'])) : json_encode($CustomizeWinningCustom),
            "IsWinnerSocialFeed" => @$Input['IsWinnerSocialFeed'],
        ));

        $this->db->insert('sports_pre_contest', $InsertData);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return $insert_id;
    }

    /*
      Description: Update contest to system.
     */

    function updateContest($Input = array(), $SessionUserID, $PreContestID, $StatusID = 1) {

        $CustomizeWinningCustom = NULL;
        if (empty($Input['CustomizeWinning'])) {
            $CustomizeWinningCustom = array(array(
                    'From' => 1,
                    'To' => (int) @$Input['NoOfWinners'],
                    'Percent' => 100,
                    'WinningAmount' => @$Input['WinningAmount']
            ));
        }

        /* Updated contest to contest table . */
        $UpdateData = array_filter(array(
            "ContestName" => @$Input['ContestName'],
            "ContestFormat" => @$Input['ContestFormat'],
            "ContestType" => (@$Input['ContestFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['ContestType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "unfilledWinningPercent" => @$Input['unfilledWinningPercent'],
            "ShowJoinedContest" => @$Input['ShowJoinedContest'],
            "WinningAmount" => @$Input['WinningAmount'],
            "ContestSize" => (@$Input['ContestFormat'] == 'Head to Head') ? 2 : @$Input['ContestSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => @$Input['NoOfWinners'],
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            "IsWinnerSocialFeed" => @$Input['IsWinnerSocialFeed'],
            "CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((@$Input['ContestFormat'] == 'Head to Head') ? json_encode(array(array('From' => 1, 'To' => 1, 'Percent' => 100, 'WinningAmount' => @$Input['WinningAmount']))) : ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'], JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning'])) : json_encode($CustomizeWinningCustom)
        ));

        $this->db->where('PreContestID', $Input['PreContestID']);
        $this->db->limit(1);
        $this->db->update('sports_pre_contest', $UpdateData);
    }

    /*
      Description: Delete contest to system.
     */

    function deleteContest($SessionUserID, $PreContestID) {
        $this->db->where('PreContestID', $PreContestID);
        $this->db->limit(1);
        $this->db->delete('sports_pre_contest');
    }

    /*
      Description: To get contest
     */

    function getPreContest($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'PreContestID' => 'C.PreContestID',
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                'IsAutoCreate' => 'C.IsAutoCreate',
                'unfilledWinningPercent' => 'C.unfilledWinningPercent',
                'ShowJoinedContest' => 'C.ShowJoinedContest',
                'WinningAmount' => 'C.WinningAmount',
                'ContestSize' => 'C.ContestSize',
                'ContestName' => 'C.ContestName',
                'ContestFormat' => 'C.ContestFormat',
                'ContestType' => 'C.ContestType',
                'CustomizeWinning' => 'C.CustomizeWinning',
                'EntryFee' => 'C.EntryFee',
                'NoOfWinners' => 'C.NoOfWinners',
                'EntryType' => 'C.EntryType',
                'UserJoinLimit' => 'C.UserJoinLimit',
                'CashBonusContribution' => 'C.CashBonusContribution',
                'EntryType' => 'C.EntryType',
                'IsWinnerSocialFeed' => 'C.IsWinnerSocialFeed',
                'IsWinningDistributed' => 'C.IsWinningDistributed',
                'IsWinningDistributed' => 'C.IsWinningDistributed',
                'AdminPercent' => 'C.AdminPercent',
                'CurrentDateTime' => 'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' ") CurrentDateTime',
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }

        $this->db->select('C.ContestName,C.PreContestID');
        if (in_array('IsJoined', $Params)) {
            $this->db->select('C.PreContestID');
        }
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_pre_contest C');

        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = $Where['Keyword'];
            $this->db->group_start();
            $this->db->like("C.ContestName", $Where['Keyword']);

            $this->db->group_end();
        }
        if (!empty($Where['PreContestID'])) {
            $this->db->where("C.PreContestID", $Where['PreContestID']);
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
        if (!empty($Where['ContestFormat'])) {
            $this->db->where("C.ContestFormat", $Where['ContestFormat']);
        }
        if (!empty($Where['IsPaid'])) {
            $this->db->where("C.IsPaid", $Where['IsPaid']);
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
        // $this->db->group_by('C.ContestID'); // Will manage later
        if (!empty($Where['getJoinedMatches']) && $Where['getJoinedMatches'] == 'Yes') {
            $this->db->group_by('C.MatchID');
        }
        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                    $Records[$key]['NoOfWinners'] = ($Record['NoOfWinners'] == 0 ) ? 1 : $Record['NoOfWinners'];
                }
                $Return['Data']['Records'] = $Records;
            } else {

                $Record = $Query->row_array();
                $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                if (!empty($Where['MatchID'])) {
                    $Record['Statics'] = $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                            )as NormalContest,
                            ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                            )as ReverseContest,(
                            SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C, `tbl_entity` E WHERE C.ContestID = J.ContestID AND C.ContestID = E.EntityID AND E.StatusID != 3 AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '" 
                            )as JoinedContest,( 
                            SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '"
                        ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
                }
                return $Record;
            }
        }
        if (!empty($Where['MatchID'])) {
            $Return['Data']['Statics'] = $this->db->query('SELECT (SELECT COUNT(*) AS `NormalContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Normal" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                                    )as NormalContest,
                    ( SELECT COUNT(*) AS `ReverseContest` FROM `sports_contest` C, `tbl_entity` E WHERE C.ContestID = E.EntityID AND E.StatusID IN(1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestType="Reverse" AND C.ContestFormat="League" AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID)
                    )as ReverseContest,(
                    SELECT COUNT(*) AS `JoinedContest` FROM `sports_contest_join` J, `sports_contest` C, `tbl_entity` E WHERE C.ContestID = J.ContestID AND C.ContestID = E.EntityID AND E.StatusID != 3 AND J.UserID = "' . @$Where['SessionUserID'] . '" AND C.MatchID = "' . $Where['MatchID'] . '" 
                    )as JoinedContest,( 
                    SELECT COUNT(*) AS `TotalTeams` FROM `sports_users_teams`WHERE UserID = "' . @$Where['SessionUserID'] . '" AND MatchID = "' . $Where['MatchID'] . '"
                ) as TotalTeams,(SELECT COUNT(*) AS `H2HContest` FROM `sports_contest` C, `tbl_entity` E, `sports_contest_join` CJ WHERE C.ContestID = E.EntityID AND E.StatusID IN (1,2,5) AND C.MatchID = "' . $Where['MatchID'] . '" AND C.ContestFormat="Head to Head" AND E.StatusID = 1 AND C.ContestID = CJ.ContestID AND C.ContestSize != (SELECT COUNT(*) from sports_contest_join where sports_contest_join.ContestID = C.ContestID )) as H2HContests')->row();
        }
        $Return['Data']['Records'] = empty($Records) ? array() : $Records;
        return $Return;
    }

    /*
      Description: Create Pre contest
     */

    function createPreContest($InsertID = '') {

        if (!empty($InsertID)) {
            $ContestData = $this->db->query('SELECT * FROM sports_pre_contest WHERE PreContestID = ' . $InsertID);
        } else {
            $ContestData = $this->db->query('SELECT * FROM sports_pre_contest');
        }

        if ($ContestData->num_rows() > 0) {

            /* Get matches of next 5 days */
            $MatchesData = $this->Sports_model->getMatches('SeriesID,MatchID', array('OrderBy' => 'MatchStartDateTime', 'Sequence' => 'ASC', 'StatusID' => 1), TRUE, 1, 10);

            if ($MatchesData['Data']['TotalRecords'] == 0) {
                return FALSE;
            }

            foreach ($ContestData->result_array() as $Res) {

                $FieldArray = array(
                    'ContestFormat' => $Res['ContestFormat'],
                    'ContestType' => $Res['ContestType'],
                    'ContestName' => $Res['ContestName'],
                    'Privacy' => $Res['Privacy'],
                    'IsPaid' => $Res['IsPaid'],
                    'IsConfirm' => $Res['IsConfirm'],
                    'IsAutoCreate' => $Res['IsAutoCreate'],
                    'PreContestID' => $Res['PreContestID'],
                    'ShowJoinedContest' => $Res['ShowJoinedContest'],
                    'WinningAmount' => $Res['WinningAmount'],
                    'ContestSize' => $Res['ContestSize'],
                    'unfilledWinningPercent' => $Res['unfilledWinningPercent'],
                    'CashBonusContribution' => $Res['CashBonusContribution'],
                    'UserJoinLimit' => $Res['UserJoinLimit'],
                    'EntryType' => $Res['EntryType'],
                    'EntryFee' => $Res['EntryFee'],
                    'NoOfWinners' => $Res['NoOfWinners'],
                    'CustomizeWinning' => $Res['CustomizeWinning'],
                    'IsWinnerSocialFeed' => $Res['IsWinnerSocialFeed'],
                    'IsWinningDistributed' => $Res['IsWinningDistributed']
                );
                foreach ($MatchesData['Data']['Records'] as $Record) {
                    $GetContest = $this->db->query('SELECT * FROM sports_contest WHERE PreContestID = ' . $Res['PreContestID'] . ' AND MatchID = ' . $Record['MatchID'] . '');
                    if ($GetContest->num_rows() == 0) {
                        $this->Contest_model->addContest($FieldArray, '125', $Record['MatchID'], $Record['SeriesID']);
                    }
                }
            }
        }
    }

}

?>