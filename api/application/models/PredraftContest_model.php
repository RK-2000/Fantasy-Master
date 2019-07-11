<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PredraftContest_model extends CI_Model {

    public function __construct() { 
        parent::__construct();
    }

    /*
      Description: ADD pre draft to system.
    */
    function addDraft($Input = array(), $SessionUserID, $StatusID = 1) {
        
        /* Add pre draft contest to pre draft contest table . */
        $InsertData = array_filter(array(
            "UserID" => $SessionUserID,
            "DraftName" => @$Input['DraftName'],
            "DraftFormat" => @$Input['DraftFormat'],
            "DraftType" => (@$Input['DraftFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['DraftType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => (@$Input['Privacy'] == 'Yes') ? 'No' : @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "UnfilledWinningPercent" => @$Input['UnfilledWinningPercent'],
            "ShowJoinedDraft" => @$Input['ShowJoinedDraft'],
            "WinningAmount" => @$Input['WinningAmount'],
            "DraftSize" => (@$Input['DraftSize'] == 'Head to Head') ? 2 : @$Input['DraftSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => (@$Input['IsPaid'] == 'Yes') ? @$Input['NoOfWinners'] : 1,
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            'EntryDate' => date('Y-m-d H:i:s')
        ));
        $InsertData['CustomizeWinning'] = ($InsertData['IsPaid'] == 'Yes') ? (($InsertData['DraftSize'] == 2) ? json_encode(array(array('From' => 1,'To' => 1,'Percent' => 100,'WinningAmount' => $InsertData['WinningAmount']))) : json_encode(@$Input['CustomizeWinning'])) : NULL;
        $this->db->insert('sports_predraft_contest', $InsertData);
        $PredraftContestID = $this->db->insert_id();
        if (!$PredraftContestID) {
            return FALSE;
        }
        return $PredraftContestID;
    }

    /*
      Description: Update pre draft contest to system.
    */
    function updateDraft($Input = array(), $SessionUserID, $PredraftContestID, $StatusID = 1) {

        /* Updated contest to contest table . */ 
        $UpdateData = array_filter(array(
            "UserID" => $SessionUserID,
            "DraftName" => @$Input['DraftName'],
            "DraftFormat" => @$Input['DraftFormat'],
            "DraftType" => (@$Input['DraftFormat'] == 'Head to Head') ? 'Head to Head' : @$Input['DraftType'],
            "Privacy" => @$Input['Privacy'],
            "IsPaid" => @$Input['IsPaid'],
            "IsConfirm" => (@$Input['Privacy'] == 'Yes') ? 'No' : @$Input['IsConfirm'],
            "IsAutoCreate" => @$Input['IsAutoCreate'],
            "UnfilledWinningPercent" => @$Input['UnfilledWinningPercent'],
            "ShowJoinedDraft" => @$Input['ShowJoinedDraft'],
            "WinningAmount" => @$Input['WinningAmount'],
            "DraftSize" => (@$Input['DraftSize'] == 'Head to Head') ? 2 : @$Input['DraftSize'],
            "EntryFee" => (@$Input['IsPaid'] == 'Yes') ? @$Input['EntryFee'] : 0,
            "NoOfWinners" => (@$Input['IsPaid'] == 'Yes') ? @$Input['NoOfWinners'] : 1,
            "EntryType" => @$Input['EntryType'],
            "UserJoinLimit" => (@$Input['EntryType'] == 'Multiple') ? @$Input['UserJoinLimit'] : 1,
            "CashBonusContribution" => @$Input['CashBonusContribution'],
            "AdminPercent" => @$Input['AdminPercent'],
            'ModifiedDate' => date('Y-m-d H:i:s')
        ));
        $UpdateData['CustomizeWinning'] = ($UpdateData['IsPaid'] == 'Yes') ? (($UpdateData['DraftSize'] == 2) ? json_encode(array(array('From' => 1,'To' => 1,'Percent' => 100,'WinningAmount' => $UpdateData['WinningAmount']))) : json_encode(@$Input['CustomizeWinning'])) : NULL;
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
      Description: To get pre draft contests
     */
    function getPredraftContest($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15) {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'Privacy' => 'C.Privacy',
                'IsPaid' => 'C.IsPaid',
                'IsConfirm' => 'C.IsConfirm',
                'IsAutoCreate' => 'C.IsAutoCreate',
                'UnfilledWinningPercent' => 'C.UnfilledWinningPercent',
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
                'AdminPercent' => 'C.AdminPercent'
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
        if (!empty($Where['Privacy']) && $Where['Privacy'] != 'All') {
            $this->db->where("C.Privacy", $Where['Privacy']);
        }
        if (!empty($Where['DraftType'])) {
            $this->db->where("C.DraftType", $Where['DraftType']);
        }
        if (!empty($Where['DraftFormat'])) {
            $this->db->where("C.DraftFormat", $Where['DraftFormat']);
        }
        if (!empty($Where['IsPaid'])) {
            $this->db->where("C.IsPaid", $Where['IsPaid']);
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
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }else{
            $this->db->order_by('C.PredraftContestID', 'DESC');
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
                    $Records[$key]['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                }
                $Return['Data']['Records'] = $Records;
            } else {
                $Record = $Query->row_array();
                $Record['CustomizeWinning'] = (!empty($Record['CustomizeWinning'])) ? json_decode($Record['CustomizeWinning'], true) : array();
                return $Record;
            }
        }
        $Return['Data']['Records'] = empty($Records) ? array() : $Records;
        return $Return;
    }

    /*
      Description: To auto create pre draft contests (Using Cron)
     */
    function createPreDraftContest($PredraftContestID = NULL)
    {
        /* Get Pre Draft Contest Data */
        $DraftData = ((!empty($PredraftContestID))) ? $this->db->query('SELECT * FROM sports_predraft_contest WHERE PredraftContestID = '.$PredraftContestID) : $this->db->query('SELECT * FROM sports_predraft_contest');
        if ($DraftData->num_rows() > 0) {

            /* Get next 10 matches */ 
            $MatchesData = $this->db->query('SELECT M.SeriesID,M.MatchID FROM tbl_entity E, sports_matches M WHERE E.EntityID = M.MatchID AND E.StatusID = 1 ORDER BY M.MatchStartDateTime DESC LIMIT 10');
            if($MatchesData->num_rows() == 0){
                return FALSE;
            }

            /* Create Pre Draft Contests */
            foreach($DraftData->result_array() as $Value)
            {
                $FieldArray = array(
                    'PredraftContestID'      => $Value['PredraftContestID'],
                    'ContestFormat'          => $Value['DraftFormat'],
                    'ContestType'            => $Value['DraftType'],
                    'ContestName'            => $Value['DraftName'],
                    'Privacy'                => $Value['Privacy'],
                    'IsPaid'                 => $Value['IsPaid'],
                    'IsConfirm'              => $Value['IsConfirm'],
                    'IsAutoCreate'           => $Value['IsAutoCreate'],
                    'ShowJoinedContest'      => $Value['ShowJoinedDraft'],
                    'WinningAmount'          => $Value['WinningAmount'],
                    'ContestSize'            => $Value['DraftSize'],
                    'UnfilledWinningPercent' => $Value['UnfilledWinningPercent'],
                    'CashBonusContribution'  => $Value['CashBonusContribution'],
                    'UserJoinLimit'          => $Value['UserJoinLimit'],
                    'EntryType'              => $Value['EntryType'],
                    'EntryFee'               => $Value['EntryFee'],
                    'NoOfWinners'            => $Value['NoOfWinners'],
                    'CustomizeWinning'       => (!empty($Value['CustomizeWinning'])) ? json_decode($Value['CustomizeWinning'], true) : NULL
                );
                foreach($MatchesData->result_array() as $Record)
                {
                    if ($this->db->query('SELECT Privacy FROM sports_contest WHERE PredraftContestID = '.$Value['PredraftContestID'].' AND MatchID = '.$Record['MatchID'].' LIMIT 1')->num_rows() == 0) {
                        $this->Contest_model->addContest($FieldArray, ADMIN_ID, array($Record['MatchID']), $Record['SeriesID']);
                    }
                }
            }
        }
    }
}
