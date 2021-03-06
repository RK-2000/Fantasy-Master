<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sports extends API_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Sports_model');
    }

    /*
      Description: To get series data
    */
    public function getSeries_post()
    {
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Series Data */
        $SeriesData = $this->Sports_model->getSeries(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => @$this->SeriesID)),(!empty($this->SeriesID)) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($SeriesData)) {
            $this->Return['Data'] = (!empty($this->SeriesID)) ? $SeriesData :  $SeriesData['Data'];
        }
    }

    /*
      Description: To get teams
     */

    public function getTeams_post()
    {
        $this->form_validation->set_rules('TeamGUID', 'TeamGUID', 'trim|callback_validateEntityGUID[Teams,TeamID]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        $TeamsData = $this->Sports_model->getTeams(@$this->Post['Params'], array_merge($this->Post, array('TeamID' => @$this->TeamID, 'SeriesID' => @$this->SeriesID)), (!empty($this->TeamID)) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($TeamsData)) {
            $this->Return['Data'] = (!empty($this->TeamID)) ? $TeamsData : $TeamsData['Data'];
        }
    }

    /*
      Description: To get matches data
     */
    public function getMatches_post() 
    {
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|callback_validateSession');
        $this->form_validation->set_rules('Filter', 'Filter', 'trim|in_list[Today,Series,MyJoinedMatch]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('Status', 'Status', 'trim|callback_validateStatus');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Matches Data */
        $MatchesData = $this->Sports_model->getMatches(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => @$this->SeriesID, 'MatchID' => @$this->MatchID, 'StatusID' => @$this->StatusID, 'SessionUserID' => @$this->SessionUserID)), (!empty($this->MatchID)) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($MatchesData)) {
            $this->Return['Data'] = (!empty($this->MatchID)) ? $MatchesData : $MatchesData['Data'];
        }
    }

    /*
      Description: To get players data
     */

    public function getPlayers_post()
    {
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('TeamGUID', 'TeamGUID', 'trim|callback_validateEntityGUID[Teams,TeamID]');
        $this->form_validation->set_rules('PlayerGUID', 'PlayerGUID', 'trim|callback_validateEntityGUID[Players,PlayerID]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->validation($this);  /* Run validation */

        /* Get Players Data */
        $PlayersData = $this->Sports_model->getPlayers(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => @$this->SeriesID, 'TeamID' => @$this->TeamID, 'MatchID' => @$this->MatchID,'PlayerID' => @$this->PlayerID, 'SessionUserID' => @$this->SessionUserID)), (!empty($this->PlayerID)) ? FALSE : TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($PlayersData)) {
            $this->Return['Data'] = (!empty($this->PlayerID)) ? $PlayersData : $PlayersData['Data'];
        }
    }

    /*
      Description: To get sports points
     */
    public function getPoints_post()
    {
        $this->form_validation->set_rules('PointsCategory', 'PointsCategory', 'trim|in_list[Normal,InPlay,Reverse]');
        $this->form_validation->set_rules('Sequence', 'Sequence', 'trim|in_list[ASC,DESC]');
        $this->form_validation->validation($this);  /* Run validation */

        $PointsData = $this->Sports_model->getPoints($this->Post);
        if (!empty($PointsData)) {
            $this->Return['Data'] = $PointsData['Data'];
        }
    }

    /*
      Description: To get sports best players of the match
    */
    public function getMatchBestPlayers_post()
    {
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->validation($this);  /* Run validation */

        $BestPlayersData = $this->Sports_model->getMatchBestPlayers(array('MatchID' => $this->MatchID, 'SessionUserID' => $this->SessionUserID));
        if (!empty($BestPlayersData)) {
            $this->Return['Data'] = $BestPlayersData['Data'];
        }
    }

    /*
      Description: To get sports player fantasy stats series wise
     */
    public function getPlayerFantasyStats_post()
    {
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->set_rules('SeriesGUID', 'SeriesGUID', 'trim|required|callback_validateEntityGUID[Series,SeriesID]');
        $this->form_validation->set_rules('PlayerGUID', 'PlayerGUID', 'trim|required|callback_validateEntityGUID[Players,PlayerID]');
        $this->form_validation->set_rules('MatchGUID', 'MatchGUID', 'trim|required|callback_validateEntityGUID[Matches,MatchID]');
        $this->form_validation->validation($this);  /* Run validation */

        $PendingMatchStatsArr = $CompletedMatchesStatsArr = array();
        $TotalRecords = 0;

        /* Get Pending Match Stats */
        $PendingMatchStats = $this->Sports_model->getPlayerFantasyStats(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => $this->SeriesID, 'PlayerID' => $this->PlayerID, 'StatusID' => 1, 'OrderBy' => 'MatchStartDateTime', 'Sequence' => 'ASC')), TRUE, 1, 1);
        if (!empty($PendingMatchStats)) {
            $TotalRecords = $PendingMatchStats['Data']['TotalRecords'];
            $PendingMatchStatsArr = $PendingMatchStats['Data']['Records'];
        }

        /* Get Completed Matches Stats */
        $CompletedMatchesStats = $this->Sports_model->getPlayerFantasyStats(@$this->Post['Params'], array_merge($this->Post, array('SeriesID' => $this->SeriesID, 'PlayerID' => $this->PlayerID, 'StatusID' => 5)), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($CompletedMatchesStats)) {
            $TotalRecords += $CompletedMatchesStats['Data']['TotalRecords'];
            $CompletedMatchesStatsArr = $CompletedMatchesStats['Data']['Records'];
        }
        $this->Return['Data']['TotalRecords'] = $TotalRecords;
        $this->Return['Data']['Records']      = array_merge_recursive($PendingMatchStatsArr, $CompletedMatchesStatsArr);
        $this->Return['Data']['PlayerDetails'] = $this->Sports_model->getPlayers('PlayerPic,PlayerCountry,PlayerBattingStyle,PlayerBowlingStyle,PlayerSalary,PointsData', array('PlayerID' => $this->PlayerID, 'MatchID' => $this->MatchID));
        $this->Return['Data']['PlayerDetails']['TotalPoints'] = ($TotalRecords > 0) ? (string) array_sum(array_column($this->Return['Data']['Records'], 'TotalPoints')) : '0';
    }
}
