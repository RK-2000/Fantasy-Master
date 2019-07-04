<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PredraftContest_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sports_model');
        $this->load->model('Contest_model');
    }

    /*
      Description:    ADD contest to system.
     */

    function addDraft($Input = array(), $SessionUserID, $StatusID = 1) {
        
        $this->db->trans_start();
        $EntityGUID = get_guid();

        /* Add contest to entity table and get EntityID. */
        $EntityID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 11, "UserID" => $SessionUserID, "StatusID" => $StatusID));
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
            "DraftName" => @$Input['DraftName'],
            "DraftFormat" => @$Input['DraftFormat'],
            "DraftType" => (@$Input['DraftFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['DraftType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => @$Input['IsConfirm'], 
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "unfilledWinningPercent" => @$Input['unfilledWinningPercent'],
            "ShowJoinedDraft" => @$Input['ShowJoinedDraft'],
            "WinningAmount" => @$Input['WinningAmount'],
            "DraftSize" => (@$Input['DraftSize'] == 'Head to Head') ? 2 : @$Input['DraftSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => @$Input['NoOfWinners'],
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            //"CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((@$Input['DraftFormat'] == 'Head to Head') ? json_encode(array(array('From' => 1,'To' => 1,'Percent' => 100,'WinningAmount' => @$Input['WinningAmount']))) : ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'],JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning'])) : json_encode($CustomizeWinningCustom),
             "CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'],JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning']) : json_encode($CustomizeWinningCustom),
           
            "IsWinnerSocialFeed" => @$Input['IsWinnerSocialFeed'],
         
        ));

        $this->db->insert('sports_predraft_contest', $InsertData);
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

    function updateDraft($Input = array(), $SessionUserID, $PredraftContestID, $StatusID = 1) {

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
            "DraftName" => @$Input['DraftName'],
            "DraftFormat" => @$Input['DraftFormat'],
            "DraftType" => (@$Input['DraftFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['DraftType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "unfilledWinningPercent" => @$Input['unfilledWinningPercent'],
            "ShowJoinedDraft" => @$Input['ShowJoinedDraft'],
            "WinningAmount" => @$Input['WinningAmount'],
            "DraftSize" => (@$Input['DraftFormat'] == 'Head to Head') ? 2 : @$Input['DraftSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => @$Input['NoOfWinners'],
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            "IsWinnerSocialFeed" => @$Input['IsWinnerSocialFeed'],
             // "CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((@$Input['DraftFormat'] == 'Head to Head') ? json_encode(array(array('From' => 1,'To' => 1,'Percent' => 100,'WinningAmount' => @$Input['WinningAmount']))) : ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'],JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning'])) : json_encode($CustomizeWinningCustom)
             
              "CustomizeWinning" => (!empty(@$Input['CustomizeWinning'])) ? ((is_array(@$Input['CustomizeWinning'])) ? json_encode(@$Input['CustomizeWinning'],JSON_NUMERIC_CHECK) : @$Input['CustomizeWinning']) : json_encode($CustomizeWinningCustom),
        ));

        $this->db->where('PredraftContestID', $Input['PredraftContestID']);
        $this->db->limit(1);
        $this->db->update('sports_predraft_contest', $UpdateData);
    }

    /*
      Description: Delete contest to system.
     */

    function deleteDraft($SessionUserID, $PredraftContestID) {

        $this->db->trans_start();

        /* Get All Upcoming Contest (Non Joined) */
        $ContestsData = $this->Contest_model->getContests('TotalJoined',array('PredraftContestID' => $PredraftContestID,'StatusID' => 1,'Filter' => 'NonJoined'),TRUE,1,100);
        if($ContestsData['Data']['TotalRecords'] > 0){

            /* Delete Contests */
            $this->db->where_in('EntityGUID', array_column($ContestsData['Data']['Records'],'ContestGUID'));
            $this->db->limit($ContestsData['Data']['TotalRecords']);
            $this->db->delete('tbl_entity');
        }

        /* Delete Predraft */
        $this->db->where('PredraftContestID', $PredraftContestID);
        $this->db->limit(1);
        $this->db->delete('sports_predraft_contest');

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return true;
    }

    /*
      Description: To get contest
     */

    function getPredraftContest($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'PredraftContestID' => 'C.PredraftContestID',
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                'IsAutoCreate' => 'C.IsAutoCreate',
                'unfilledWinningPercent' => 'C.unfilledWinningPercent',
                'ShowJoinedDraft' => 'C.ShowJoinedDraft',
                'WinningAmount' => 'C.WinningAmount',
                'DraftSize' => 'C.DraftSize',
                'DraftName' => 'C.DraftName',
                'DraftFormat' => 'C.DraftFormat',
                'DraftType' => 'C.DraftType',
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
                'CurrentDateTime'       =>  'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","'.DEFAULT_TIMEZONE.'"), "'.DATE_FORMAT.' ") CurrentDateTime'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }

        $this->db->select('C.DraftName,C.PredraftContestID');
        if (in_array('IsJoined', $Params)) {
            $this->db->select('C.PredraftContestID');
        }
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_predraft_contest C');
      
        if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = $Where['Keyword'];
            $this->db->group_start();
            $this->db->like("C.DraftName", $Where['Keyword']);
           
            $this->db->group_end();
        }
        if (!empty($Where['PredraftContestID'])) {
            $this->db->where("C.PredraftContestID", $Where['PredraftContestID']);
        }
        if (!empty($Where['UserID'])) {
            $this->db->where("C.UserID", $Where['UserID']);
        }
        // if (!empty($Where['Filter']) && $Where['Filter'] == 'Today') {
        //     $this->db->where("DATE(M.MatchStartDateTime)", date('Y-m-d'));
        // }
        // if (!empty($Where['Filter']) && $Where['Filter'] == 'YesterdayToday') {
        //     $this->db->where_in("DATE(M.MatchStartDateTime)", array(date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))), date('Y-m-d')));
        // }
        // if (!empty($Where['Filter']) && $Where['Filter'] == 'NonCanceled') {
        //     $this->db->where_in("E.StatusID", array(1,2,5));
        // }
        // if (!empty($Where['Filter']) && $Where['Filter'] == 'LiveMatch') {
        //     $this->db->where("M.MatchStartDateTime <=", date('Y-m-d H:i:s'));
        // }
        if (!empty($Where['Privacy']) && $Where['Privacy'] != 'All') {
            $this->db->where("C.Privacy", $Where['Privacy']);
        }
        if (!empty($Where['DraftType'])) {
            $this->db->where("C.DraftType", $Where['DraftType']);
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
        if (!empty($Where['DraftSizeStartFrom'])) {
            $this->db->where("C.DraftSize >=", $Where['DraftSizeStartFrom']);
        }
        if (!empty($Where['DraftSizeEndTo'])) {
            $this->db->where("C.DraftSize <=", $Where['DraftSizeEndTo']);
        }
        if (!empty($Where['DraftFormat'])) {
            $this->db->where("C.DraftFormat", $Where['DraftFormat']);
        }
        if (!empty($Where['IsPaid'])) {
            $this->db->where("C.IsPaid", $Where['IsPaid']);
        }
        if (!empty($Where['WinningAmount'])) {
            $this->db->where("C.WinningAmount >=", $Where['WinningAmount']);
        }
        if (!empty($Where['DraftSize'])) {
            $this->db->where("C.DraftSize", $Where['DraftSize']);
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
        // if (!empty($Where['MatchID'])) {
        //     $this->db->where("C.MatchID", $Where['MatchID']);
        // }
        // if (!empty($Where['SeriesID'])) {
        //     $this->db->where("C.SeriesID", $Where['SeriesID']);
        // }
        // if (!empty($Where['StatusID'])) {
        //     $this->db->where_in("E.StatusID", $Where['StatusID']);
        // }
        // if (!empty($Where['MyJoinedContest']) && $Where['MyJoinedContest'] == "Yes") {
        //     $this->db->where('EXISTS (select ContestID from sports_contest_join JE where JE.ContestID = C.PredraftContestID AND JE.UserID=' . @$Where['SessionUserID'] . ')');
        // }
        // if (!empty($Where['UserInvitationCode'])) {
        //     $this->db->where("C.UserInvitationCode", $Where['UserInvitationCode']);
        // }
        if (!empty($Where['IsWinningDistributed'])) {
            $this->db->where("C.IsWinningDistributed", $Where['IsWinningDistributed']);
        }
        if (!empty($Where['ContestFull']) && $Where['ContestFull'] == 'No') {
            $this->db->having("TotalJoined !=", 'C.DraftSize', FALSE);
        }
        // if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
        //     $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        // }
        //$this->db->order_by('M.MatchStartDateTime', 'ASC');

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
                    $Records[$key]['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                    $TotalAmountReceived = $this->getTotalContestCollections($Record['ContestIDAsUse']);
                    $Records[$key]['TotalAmountReceived'] = ($TotalAmountReceived) ? $TotalAmountReceived : 0;
                    $TotalWinningAmount = $this->getTotalWinningAmount($Record['ContestIDAsUse']);
                    $Records[$key]['TotalWinningAmount'] = ($TotalWinningAmount) ? $TotalWinningAmount : 0;
                    $Records[$key]['NoOfWinners'] = ($Record['NoOfWinners'] == 0 ) ? 1 : $Record['NoOfWinners'];
                    if (in_array('IsJoined', $Params)) {
                        if($Record['IsJoined'] == 'No'){
                            $Records[$key]['UserTeamDetails'] = array();
                        }else{
                            $UserTeamDetails = $this->getUserTeams('TotalPoints', array('ContestID' => $Record['ContestID']), true, 0);
                            $Records[$key]['UserTeamDetails'] = $UserTeamDetails['Data']['Records'];
                        }
                        unset($Records[$key]['ContestID']);
                    }
                    unset($Records[$key]['ContestIDAsUse']);
                }
                $Return['Data']['Records'] = $Records;
            } else {
               
                $Record = $Query->row_array();
                $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                $Record['MatchScoreDetails'] = (!empty($Record['MatchScoreDetails'])) ? json_decode($Record['MatchScoreDetails'], TRUE) : new stdClass();
                $TotalAmountReceived = $this->getTotalContestCollections($Record['ContestIDAsUse']);
                $Record['TotalAmountReceived'] = ($TotalAmountReceived) ? $TotalAmountReceived : 0;
                $TotalWinningAmount = $this->getTotalWinningAmount($Record['ContestIDAsUse']);
                $Record['TotalWinningAmount'] = ($TotalWinningAmount) ? $TotalWinningAmount : 0;
                if (in_array('IsJoined', $Params)) {
                    if ($Record['IsJoined'] == 'No') {
                        $Record['UserTeamDetails'] = array();
                    } else {
                        $UserTeamDetails = $this->getUserTeams('TotalPoints', array('ContestID' => $Record['ContestID']), true, 0);
                        $Record['UserTeamDetails'] = $UserTeamDetails['Data']['Records'];
                    }
                    unset($Record['ContestID']);
                }
                unset($Record['ContestIDAsUse']);
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

    function getTotalContestCollections($ContestID) {
        return $this->db->query('SELECT SUM(C.EntryFee) as TotalAmountReceived FROM sports_contest C join sports_contest_join J on C.ContestID = J.ContestID WHERE C.ContestID = "' . $ContestID . '"')->row()->TotalAmountReceived;
    }

    function getTotalWinningAmount($ContestID) {
        return $this->db->query('SELECT SUM(J.UserWinningAmount) as TotalWinningAmount FROM sports_contest C join sports_contest_join J on C.ContestID = J.ContestID WHERE C.ContestID = "' . $ContestID . '"')->row()->TotalWinningAmount;
    }

    function getJoinedContestsUsersUnfild($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {

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
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            if (!empty(@$Where['SessionUserID'])) {
                $this->db->order_by('JC.UserID=' . @$Where['SessionUserID'] . ' DESC', null, FALSE);
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
                return $Return;
            } else {
                $result = $Query->row_array();
                return $Return;
            }
        }
        return FALSE;
    }

    /*
      Description: Join contest
     */

    function joinContest($Input = array(), $SessionUserID, $ContestID,$MatchID,$UserTeamID) {

        $this->db->trans_start();

        /* Add entry to join contest table . */
        $InsertData = array(
            "UserID" => $SessionUserID,
            "ContestID" => $ContestID,
            "MatchID"   => $MatchID,
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
            if ($ContestEntryRemainingFees > 0 && @$Input['WinningAmount'] > 0) {
                if (@$Input['WinningAmount'] >= $ContestEntryRemainingFees) {
                    $WinningAmountDeduction = $ContestEntryRemainingFees;
                } else {
                    $WinningAmountDeduction = @$Input['WinningAmount'];
                }
                $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WinningAmountDeduction;
            }
            if ($ContestEntryRemainingFees > 0 && @$Input['WalletAmount'] > 0) {
                if (@$Input['WalletAmount'] >= $ContestEntryRemainingFees) {
                    $WalletAmountDeduction = $ContestEntryRemainingFees;
                } else {
                    $WalletAmountDeduction = @$Input['WalletAmount'];
                }
                $ContestEntryRemainingFees = $ContestEntryRemainingFees - $WalletAmountDeduction;
            }
            $InsertData = array(
                "Amount" => @$Input['EntryFee'],
                "WalletAmount" => $WalletAmountDeduction,
                "WinningAmount" => $WinningAmountDeduction,
                "CashBonus" => $CashBonusDeduction,
                "TransactionType" => 'Dr',
                "EntityID" => $ContestID,
                "UserTeamID" => $UserTeamID,
                "Narration" => 'Join Contest',
                "EntryDate" => date("Y-m-d H:i:s")
            );
            $WalletID = $this->Users_model->addToWallet($InsertData, $SessionUserID, 5);
            if (!$WalletID)
                return FALSE;
        }

        /* To Check If Contest Is Auto Create (Yes) */
        if(@$Input['IsAutoCreate'] == 'Yes' && ($Input['ContestSize'] - $Input['TotalJoined']) <= 1){
            
            /* Get Contests Details */
            $ContestData = $this->db->query('SELECT * FROM sports_contest WHERE ContestID = '.$ContestID.' LIMIT 1')->result_array()[0];

            /* Create Contest */
            $Contest = array();
            $Contest['ContestName']           = $ContestData['ContestName'];
            $Contest['ContestFormat']         = $ContestData['ContestFormat'];
            $Contest['ContestType']           = $ContestData['ContestType'];
            $Contest['Privacy']               = $ContestData['Privacy'];
            $Contest['IsPaid']                = $ContestData['IsPaid'];
            $Contest['IsConfirm']             = $ContestData['IsConfirm'];
            $Contest['IsAutoCreate']          = $ContestData['IsAutoCreate'];
            $Contest['ShowJoinedContest']     = $ContestData['ShowJoinedContest'];
            $Contest['WinningAmount']         = $ContestData['WinningAmount'];
            $Contest['ContestSize']           = $ContestData['ContestSize'];
            $Contest['EntryFee']              = $ContestData['EntryFee'];
            $Contest['NoOfWinners']           = $ContestData['NoOfWinners'];
            $Contest['EntryType']             = $ContestData['EntryType'];
            $Contest['UserJoinLimit']         = $ContestData['UserJoinLimit'];
            $Contest['CashBonusContribution'] = $ContestData['CashBonusContribution'];
            $Contest['CustomizeWinning']      = $ContestData['CustomizeWinning'];
            $Contest['IsWinnerSocialFeed']    = $ContestData['IsWinnerSocialFeed'];
            $this->addContest($Contest, $ContestData['UserID'], $ContestData['MatchID'], $ContestData['SeriesID']);
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
                'ContestID' => 'C.ContestID',
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                'ShowJoinedContest' => 'C.ShowJoinedContest',
                'CashBonusContribution' => 'C.CashBonusContribution',
                'UserInvitationCode' => 'C.UserInvitationCode',
                'WinningAmount' => 'C.WinningAmount',
                'ContestSize' => 'C.ContestSize',
                'ContestFormat' => 'C.ContestFormat',
                'ContestType' => 'C.ContestType',
                'EntryFee' => 'C.EntryFee',
                'NoOfWinners' => 'C.NoOfWinners',
                'EntryType' => 'C.EntryType',
                'CustomizeWinning' => 'C.CustomizeWinning',
                'UserID' => 'JC.UserID',
                'UserTeamID' => 'JC.UserTeamID',
                'JoinInning' => 'JC.JoinInning',
                'EntryDate' => 'JC.EntryDate',
                'TotalPoints' => 'JC.TotalPoints',
                'UserTeamName' => 'UT.UserTeamName',
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
                                                WHERE sports_contest_join.MatchID =  M.MatchID AND UserID= '.$Where['SessionUserID'].') AS UserTotalJoinedInMatch',
                'UserRank' => 'JC.UserRank',
                'StatusID' => 'E.StatusID',
                'Status' => 'CASE E.StatusID
                when "1" then "Pending"
                when "2" then "Running"
                when "3" then "Cancelled"
                when "5" then "Completed"
                END as Status',
                'MatchStartDateTime' => 'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","' . DEFAULT_TIMEZONE . '"), "' . DATE_FORMAT . ' %h:%i %p") MatchStartDateTime',
                'CurrentDateTime'       =>  'DATE_FORMAT(CONVERT_TZ(Now(),"+00:00","'.DEFAULT_TIMEZONE.'"), "'.DATE_FORMAT.' ") CurrentDateTime',
                'MatchDate'             =>  'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","'.DEFAULT_TIMEZONE.'"), "%Y-%m-%d") MatchDate',
                'MatchTime'             =>  'DATE_FORMAT(CONVERT_TZ(M.MatchStartDateTime,"+00:00","'.DEFAULT_TIMEZONE.'"), "%H:%i:%s") MatchTime',
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
        if(in_array('UserTeamName', $Params)){
            $this->db->from('sports_users_teams UT');
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", false);
        }
        $this->db->where("C.ContestID", "E.EntityID", FALSE);
        $this->db->where("M.MatchID", "C.MatchID", FALSE);
        $this->db->where("S.SeriesID", "C.SeriesID", FALSE);
        $this->db->where("M.TeamIDLocal", "TL.TeamID", FALSE);
        $this->db->where("M.TeamIDVisitor", "TV.TeamID", FALSE);
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
        // print_r($Where['MatchID']);die;
        if (!empty($Where['MatchID'])) {
            $this->db->where("C.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['StatusID']) && !is_array($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }
        if (!empty($Where['StatusID']) && is_array($Where['StatusID'])) {
            $this->db->where_in("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('M.MatchStartDateTime', 'ASC');
        //$this->db->group_by('C.ContestGUID');

        if (!empty($Where['getJoinedMatches']) && $Where['getJoinedMatches'] == 'Yes') {
            $this->db->group_by('C.MatchID');
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
        //echo $this->db->laset_query();exit;
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
            
            
        }else{
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
      Description: Switch user team
     */

    function switchUserTeam($UserID,$ContestID, $UserTeamID, $OldUserTeamGUID)
    {
        /* Update Joined Contest Team Status */
        $this->db->where('UserID', $UserID);
        $this->db->where('ContestID', $ContestID);
        $this->db->where('UserTeamID', $OldUserTeamGUID);
        $this->db->limit(1);
        $this->db->update('sports_contest_join', array('UserTeamID' => $UserTeamID));
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
        $this->db->select('UT.UserTeamGUID,UT.UserTeamName,UT.UserTeamType,UT.UserTeamID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        if(in_array('TotalPoints',$Params)){
            $this->db->from('tbl_entity E, sports_users_teams UT,sports_contest_join JC');
            $this->db->where("UT.UserTeamID", "E.EntityID", false);
            $this->db->where("JC.UserTeamID", "UT.UserTeamID", false);
        }else{
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
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['UserID']) && empty($Where['UserTeamID'])) { // UserTeamID used to manage other user team details (On live score page)
            $this->db->where("UT.UserID", $Where['UserID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("E.StatusID", $Where['StatusID']);
        }

        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            $this->db->order_by('UT.UserTeamID', 'DESC');
        }
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
                        $Return['Data']['Records'][$key]['UserTeamPlayers'] = $this->getUserTeamPlayers('PlayerPosition,PlayerName,PlayerPic,PlayerCountry,PlayerRole,Points,TeamGUID,TotalPoints,PlayerBattingStyle,PlayerBowlingStyle,PlayerSalary,TotalPointCredits', array('UserTeamID' => $value['UserTeamID']));
                    }
                }
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('UserTeamPlayers', $Params)) {
                    $UserTeamPlayers = $this->getUserTeamPlayers('PlayerPosition,PlayerName,PlayerPic,PlayerCountry,PlayerRole,Points,TotalPoints,PlayerBattingStyle,PlayerBowlingStyle,PlayerSalary,TotalPointCredits', array('UserTeamID' => $Record['UserTeamID']));
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
                'PlayerPosition' => 'UTP.PlayerPosition',
                'Points' => 'UTP.Points',
                'PlayerID' => 'UTP.PlayerID',
                'PlayerName' => 'P.PlayerName',
                'PlayerBattingStyle' => 'P.PlayerBattingStyle',
                'PlayerBowlingStyle' => 'P.PlayerBowlingStyle',
                'PlayerPic' => 'IF(P.PlayerPic IS NULL,CONCAT("' . BASE_URL . '","uploads/PlayerPic/","player.png"),CONCAT("' . BASE_URL . '","uploads/PlayerPic/",P.PlayerPic)) AS PlayerPic',
                'PlayerCountry' => 'P.PlayerCountry',
                'PlayerSalary' => 'TP.PlayerSalary',
                'IsPlaying' => 'TP.IsPlaying',
                'PointsData' => 'TP.PointsData',
                'PlayerRole' => 'TP.PlayerRole',
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
        $this->db->select('P.PlayerGUID,M.MatchGUID,UTP.Points');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_users_team_players UTP, sports_players P, sports_team_players TP,sports_teams T,sports_matches M,sports_set_match_types SM');
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
                if($key == 0){

                    /* Get Match Status */
                    $Query = $this->db->query('SELECT E.StatusID FROM `sports_matches` `M`,`tbl_entity` `E` WHERE M.`MatchGUID` = "'.$Record['MatchGUID'].'" AND M.MatchID = E.EntityID LIMIT 1');
                    $MatchStatus = ($Query->num_rows() > 0) ? $Query->row()->StatusID : 0;
                }
                $Records[] = $Record;
                $Records[$key]['PointCredits'] = (in_array($MatchStatus,array(2,5,10)))  ? $Record['Points'] : $Record['TotalPointCredits'];
                $Records[$key]['PointsData'] = (!empty($Record['PointsData'])) ? json_decode($Record['PointsData'], TRUE) : array();
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
        $this->db->select('U.UserGUID,UT.UserTeamGUID,UT.UserTeamID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_contest_join JC, tbl_users U, sports_users_teams UT');
        $this->db->where("JC.UserTeamID", "UT.UserTeamID", FALSE);
        $this->db->where("JC.UserID", "U.UserID", FALSE);
        if (!empty($Where['ContestID'])) {
            $this->db->where("JC.ContestID", $Where['ContestID']);
        }
        if (!empty($Where['MatchID'])) {
            $this->db->where("JC.MatchID", $Where['MatchID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            if(!empty($Where['SessionUserID'])){
                $this->db->order_by('JC.UserID='.$Where['SessionUserID'].' DESC',null,FALSE);
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
                    $UserTeamPlayers = $this->getUserTeamPlayers('PlayerPosition,PlayerName,PlayerRole,PlayerPic,TeamGUID,PlayerSalary,MatchType,PointCredits,PlayerBattingStyle,PlayerBowlingStyle,IsPlaying,Points,PointsData', array('UserTeamID' => $record['UserTeamID']));
                    $Return['Data']['Records'][$key]['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                }
                return $Return;
            } else {
                $result = $Query->row_array();

                foreach ($result as $key => $record) {
                    $UserTeamPlayers = $this->getUserTeamPlayers('PlayerPosition,PlayerName,PlayerRole,PlayerPic,TeamGUID,PlayerSalary,MatchType,PointCredits,PlayerBattingStyle,PlayerBowlingStyle,IsPlaying,Points,PointsData', array('UserTeamID' => $record['UserTeamID']));
                    $Return['Data']['Records'][$key]['UserTeamPlayers'] = ($UserTeamPlayers) ? $UserTeamPlayers : array();
                }

                return $Return;
            }
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

        /* Get Joined Contest */
        $JoinedContestsUsers = $this->getJoinedContestsUsers('UserID,FirstName,Email,UserTeamID', array('ContestID' => $ContestID), TRUE, 0);
        if (!$JoinedContestsUsers)
            exit;

        foreach ($JoinedContestsUsers['Data']['Records'] as $Value) {

            /* Refund Wallet Money */
            if (!empty($Input['EntryFee'])) {

                /* Get Wallet Details */
                $WalletDetails = $this->Users_model->getWallet('WalletAmount,WinningAmount,CashBonus', array(
                    'UserID' => $Value['UserID'],
                    'EntityID' => $ContestID,
                    'UserTeamID' => $Value['UserTeamID'],
                    'Narration' => 'Join Contest'
                ));

                $InsertData = array(
                    "Amount" => $WalletDetails['WalletAmount'] + $WalletDetails['WinningAmount'] + $WalletDetails['WinningAmount'],
                    "WalletAmount" => $WalletDetails['WalletAmount'],
                    "WinningAmount" => $WalletDetails['WinningAmount'],
                    "CashBonus" => $WalletDetails['CashBonus'],
                    "TransactionType" => 'Cr',
                    "EntityID" => $ContestID,
                    "UserTeamID" => $Value['UserTeamID'],
                    "Narration" => 'Cancel Contest',
                    "EntryDate" => date("Y-m-d H:i:s")
                );
                $this->Users_model->addToWallet($InsertData, $Value['UserID'], 5);
            }

            /* Send Mail To Users */
            $EmailArr = array(
                "Name" => $Value['FirstName'],
                "SeriesName" => @$Input['SeriesName'],
                "ContestName" => @$Input['ContestName'],
                "MatchNo" => @$Input['MatchNo'],
                "TeamNameLocal" => @$Input['TeamNameLocal'],
                "TeamNameVisitor" => @$Input['TeamNameVisitor']
            );
            sendMail(array(
                'emailTo' => $Value['Email'],
                'emailSubject' => "Cancel Contest- " . SITE_NAME,
                'emailMessage' => emailTemplate($this->load->view('emailer/cancel_contest', $EmailArr, TRUE))
            ));
        }
    }

    /*
      Description: To Download Contest Teams
     */

    function downloadTeams($Input = array()) {

        /* Teams File Name */
        $FileName = 'contest-teams-'.$Input['ContestGUID'].'.pdf';
        if(file_exists(getcwd().'/uploads/Contests/'.$FileName)){
            return array('TeamsPdfFileURL' => BASE_URL.'uploads/Contests/'.$FileName);
        }else{

            /* Manage Directory */
            if(!file_exists(getcwd().'/uploads/Contests/')){
                mkdir(getcwd().'/uploads/' . 'Contests', 0777);
            }
            
            /* Create PDF file using MPDF Library */
            ob_start();
            ini_set('max_execution_time', 300);
            require_once  getcwd().'/vendor/autoload.php';

            /* Get Matches Details */
            $ContestsData = $this->getContests('TeamNameLocal,TeamNameVisitor,EntryFee,ContestSize,UserInvitationCode',array('ContestID' => $Input['ContestID']));

            /* Get Contest User Teams */
            $UserTeams = $this->getUserTeams('TotalPoints,UserTeamPlayers',array('ContestID' => $Input['ContestID']),TRUE,0);

            /* Player Positions */
            $PlayerPositions = array('Captain' => '(C)','ViceCaptain' => '(VC)','Player' => '');

            /* Create PDF HTML */
            $PDFHtml  = '<html lang="en" data-ng-app="fxi"><body style ="font-family: Montserrat, sans-serif;">';
            $PDFHtml .= '<div style="width:100%; max-width:1500px;">';
            $PDFHtml .= '<table style="background:#ffa100; width:100%;" width="100%" cellpadding="0"  cellspacing="0">';
            $PDFHtml .= '<tr>';
            $PDFHtml .= '<td style="padding:10px 0;">';
            $PDFHtml .= '<span>'.SITE_NAME.'</span>';
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0;font-size:15px; color:#fff;">';
            $PDFHtml .= $ContestsData['TeamNameLocal'] . ' V/S ' . $ContestsData['TeamNameVisitor'];
            $PDFHtml .= '</td>';
            $PDFHtml .= '<td style="padding:10px 0; font-size:15px; color:#fff;">';
            $PDFHtml .= 'Entry Fee: '.DEFAULT_CURRENCY . $ContestsData['EntryFee'];
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
            foreach($UserTeams['Data']['Records'] as $TeamValue){
                $PDFHtml .= '<tr>';
                $PDFHtml .= '<td style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">' . $TeamValue['UserTeamName'] . '</td>';
                foreach($TeamValue['UserTeamPlayers'] as $PlayerValue){
                    $PDFHtml .= '<td style="font-size:13px; font-weight:600;border:1px solid #000; text-align:center;">' . $PlayerValue['PlayerName']. ' '.$PlayerPositions[$PlayerValue['PlayerPosition']]. '</td>';
                }
                $PDFHtml .= '</tr>';
            }
            $PDFHtml .= '</tbody>';
            $PDFHtml .= '</table>';
            $PDFHtml .= '</div></body></html>';

            /* MPDF Object */
            $MPDF = (phpversion() >= 7.0) ? new \Mpdf\Mpdf() : new mPDF(); // (PHP - 5.5, mPDF - 5.7.1)
            ini_set("pcre.backtrack_limit", "500000000");
            $MPDF->WriteHTML($PDFHtml);
            $MPDF->Output(getcwd() . '/uploads/Contests/'.$FileName,(phpversion() >= 7.0) ? \Mpdf\Output\Destination::FILE : 'F');
            return array('TeamsPdfFileURL' => BASE_URL.'uploads/Contests/'.$FileName);
        }
    }

    public function getWinningBreakup($Field='',$Input=array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15){
        $dataArr = array();
        $EntryFee       = $Input['EntryFee'];
        $WinningAmount  = $Input['WinningAmount'];
        $MatchID        = $Input['MatchID'];
        $UserID         = $Input['UserID'];
        $ContestSize    = $Input['ContestSize'];

        $IsMultiEntry   = $Input['EntryType'];

        $TotalFee       = (abs($WinningAmount) * 20) / 100;

        if($Input['IsPaid']=='Yes'){
            $MatchID = $Input['MatchID'];
            $UserID = $Input['UserID'];
            $WinningAmount  = $Input['WinningAmount'];

            if($ContestSize > 0 && $ContestSize < 11){
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

                if ($ContestSize > 49 && $ContestSize < 101) {
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
      Description: To get rankings 
     */

    function getRankings($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'TotalPoints' => 'SUM(JC.TotalPoints) AS TotalPoints',
                'Username' => 'U.Username',
                'ProfilePic' => 'IF(U.ProfilePic IS NULL,CONCAT("' . BASE_URL . '","uploads/profile/picture/","default.jpg"),CONCAT("' . BASE_URL . '","uploads/profile/picture/",U.ProfilePic)) AS ProfilePic'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('U.UserGUID');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('sports_contest_join JC, tbl_users U,sports_matches M,sports_series S');
        $this->db->where("JC.MatchID", "M.MatchID", FALSE);
        $this->db->where("S.SeriesID", "M.SeriesID", FALSE);
        $this->db->where("JC.UserID", "U.UserID", FALSE);
        if (!empty($Where['SeriesID'])) {
            $this->db->where("S.SeriesID", $Where['SeriesID']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        } else {
            $this->db->order_by('TotalPoints', 'DESC');
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

    function createPreDraftContest($InsertID='')
    {

          if(!empty($InsertID))
          {
             $DraftData = $this->db->query('SELECT * FROM sports_predraft_contest WHERE PredraftContestID = '.$InsertID);
          }else{
             $DraftData = $this->db->query('SELECT * FROM sports_predraft_contest');
          }
       
          if ($DraftData->num_rows() > 0) {

                /* Get matches of next 5 days */ 
                $MatchesData = $this->Sports_model->getMatches('SeriesID,MatchID', array('OrderBy' => 'MatchStartDateTime','Sequence' => 'ASC','StatusID' => 1), TRUE,1,10);
                
                if($MatchesData['Data']['TotalRecords'] == 0){
                    return FALSE;
                }

                foreach($DraftData->result_array() as $Res)
                {
                   
                    $FieldArray = array(
                        'PredraftContestID' => $Res['PredraftContestID'],
                        'ContestFormat' => $Res['DraftFormat'],
                        'ContestType' => $Res['DraftType'],
                        'ContestName' => $Res['DraftName'],
                        'Privacy' => $Res['Privacy'],
                        'IsPaid' => $Res['IsPaid'],
                        'IsConfirm' => $Res['IsConfirm'],
                        'IsAutoCreate' => $Res['IsAutoCreate'],
                        'ShowJoinedContest' => $Res['ShowJoinedDraft'],
                        'WinningAmount' => $Res['WinningAmount'],
                        'ContestSize' => $Res['DraftSize'],
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
                    foreach($MatchesData['Data']['Records'] as $Record)
                    {
                        $GetContest = $this->db->query('SELECT * FROM sports_contest WHERE PredraftContestID = '.$Res['PredraftContestID'].' AND MatchID = '.$Record['MatchID'].'');

                       
                       if ($GetContest->num_rows() == 0) {
                        
                         $this->Contest_model->addContest($FieldArray, ADMIN_ID, $Record['MatchID'], $Record['SeriesID']);
                       }
                    }
                }
            }
    }
}

?>