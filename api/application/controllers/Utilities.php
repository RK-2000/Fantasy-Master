<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Utilities extends API_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Utility_model');
        $this->load->model('Sports_model');
        $this->load->model('Contest_model');
        mongoDBConnection();
    }

    /*
      Description: 	Use to send email to webadmin.
      URL: 			/api/utilities/contact/
     */
    public function contact_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('Name', 'Name', 'trim');
        $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim');
        $this->form_validation->set_rules('Title', 'Title', 'trim');
        $this->form_validation->set_rules('Message', 'Message', 'trim|required');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        /* Send Mail to admin */
        send_mail(array(
            'emailTo' => SITE_CONTACT_EMAIL,
            'template_id' => 'd-30d87722d7fa42f8b0b671f6482a83f9',
            'Subject' => $this->Post['Name'] . ' filled out the contact form on ' . SITE_NAME,
            "Name" => $this->Post['Name'],
            'Email' => $this->Post['Email'],
            'PhoneNumber' => $this->Post['PhoneNumber'],
            'Title' => $this->Post['Title'],
            'Message' => $this->Post['Message']
        ));
    }

    /*
      Description:  Use to get countries.
      URL:      /api/utilities/getCountries
     */

    public function getCountries_post()
    {
        $CountryData = $this->Utility_model->getCountries(@$this->Post['Params'],$this->Post, TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']); 
       
        if (!empty($CountryData)) {
            $this->Return['Data'] = $CountryData['Data'];
        }
    }

    /*
      Description:  Use to get country states.
      URL:      /api/utilities/getStates
     */
    public function getStates_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('CountryCode', 'Country Code', 'trim|required');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        $StateData = $this->Utility_model->getStates(array('CountryCode' => $this->Post['CountryCode']));
        if (!empty($StateData)) {
            $this->Return['Data'] = $StateData['Data'];
        }
    }

    /*
      Description:    Use to get list of random posts.
      URL:            /api/utilities/getPosts
     */
    public function getPosts_post()
    {
        /* Validation section */
        $this->form_validation->set_rules('PageNo', 'PageNo', 'trim|integer');
        $this->form_validation->set_rules('PageSize', 'PageSize', 'trim|integer');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->load->model('Post_model');
        $Posts = $this->Post_model->getPosts('
            P.PostGUID,
            E.EntryDate,
            P.PostContent,
            P.PostCaption,
            ', array(), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($Posts) {
            $this->Return['Data'] = $Posts['Data'];
        }
    }

    /*
      Description:  Use to get referel amount details.
      URL:      /api/utilities/getReferralDetails
     */

    public function getReferralDetails_post()
    {
        $ReferByQuery = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "ReferByDepositBonus" AND StatusID = 2 LIMIT 1');
        $ReferToQuery = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "ReferToDepositBonus" AND StatusID = 2 LIMIT 1');
        $this->Return['Data']['ReferByBonus'] = ($ReferByQuery->num_rows() > 0) ? $ReferByQuery->row()->ConfigTypeValue : 0;
        $this->Return['Data']['ReferToBonus'] = ($ReferToQuery->num_rows() > 0) ? $ReferToQuery->row()->ConfigTypeValue : 0;
    }

    /*
      Description:    Use to send new app link
      URL:            /api/utilities/sendAppLink
     */
    public function sendAppLink_post()
    {
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $this->Utility_model->sendSMS(array(
            'PhoneNumber' => $this->Post['PhoneNumber'],
            'Text' => "Here is the new " . SITE_NAME . " Android Application! Click on the link to download the App and Start Winning. " . $this->db->query("SELECT ConfigTypeValue FROM `set_site_config` WHERE `ConfigTypeGUID` = 'AndridAppUrl' LIMIT 1")->row()->ConfigTypeValue
        ));
        $this->Return['Message'] = "Link Sent successfully.";
    }

    /*
      Description:  Use to get app version details
      URL:      /api/utilities/getAppVersionDetails
     */
    public function getAppVersionDetails_post()
    {
        $this->form_validation->set_rules('SessionKey', 'SessionKey', 'trim|required|callback_validateSession');
        $this->form_validation->set_rules('UserAppVersion', 'UserAppVersion', 'trim|required');
        $this->form_validation->set_rules('DeviceType', 'Device type', 'trim|required|callback_validateDeviceType');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */

        $VersionData = $this->Utility_model->getAppVersionDetails();
        if (!empty($VersionData)) {
            $this->Return['Data'] = $VersionData;
        }
    }

    /*
      Description: 	Cron jobs to get cricket series data.
      URL: 			/api/utilities/getSeriesLiveCricket
     */
    public function getSeriesLiveCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getSeriesLiveCricket');
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $SeriesData = $this->Utility_model->getSeriesLive_Cricket_Entity($CronID);
        }
        if (!empty($SeriesData)) {
            $this->Return['Data'] = $SeriesData;
        }
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get cricket matches data.
      URL: 			/api/utilities/getMatchesLiveCricket
     */
    public function getMatchesLiveCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getMatchesLiveCricket');
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $MatchesData = $this->Utility_model->getMatchesLive_Cricket_Entity($CronID);
        }
        if (CRICKET_SPORT_API_NAME == 'CRICKETAPI') {
            $MatchesData = $this->Utility_model->getMatchesLive_Cricket_CricketApi($CronID);
        }
        if (!empty($MatchesData)) {
            $this->Return['Data'] = $MatchesData;
        }
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get cricket players data.
      URL: 			/api/utilities/getPlayersLiveCricket
     */
    public function getPlayersLiveCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getPlayersLiveCricket');
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $PlayersData = $this->Utility_model->getPlayersLive_Cricket_Entity($CronID);
        }
        if (CRICKET_SPORT_API_NAME == 'CRICKETAPI') {
            $PlayersData = $this->Utility_model->getPlayersLive_Cricket_CricketApi($CronID);
        }
        if (!empty($PlayersData)) {
            $this->Return['Data'] = $PlayersData;
        }
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get cricket player stats data.
      URL: 			/api/utilities/getPlayerStatsLiveCricket
     */
    public function getPlayerStatsLiveCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getPlayerStatsLiveCricket');
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $PlayersStatsData = $this->Utility_model->getPlayerStatsLive_Cricket_Entity($CronID);
        }
        if (CRICKET_SPORT_API_NAME == 'CRICKETAPI') {
            $PlayersStatsData = $this->Utility_model->getPlayerStatsLive_Cricket_CricketApi($CronID);
        }
        if (!empty($PlayersStatsData)) {
            $this->Return['Data'] = $PlayersStatsData;
        }
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get cricket match live score
      URL: 			/api/utilities/getMatchScoreLiveCricket
     */
    public function getMatchScoreLiveCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getMatchScoreLiveCricket');
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $MatchScoreLiveData = $this->Utility_model->getMatchScoreLive_Cricket_Entity($CronID);
        }
        if (CRICKET_SPORT_API_NAME == 'CRICKETAPI') {
            $MatchScoreLiveData = $this->Utility_model->getMatchScoreLive_Cricket_CricketApi($CronID);
        }
        if (!empty($MatchScoreLiveData)) {
            $this->Return['Data'] = $MatchScoreLiveData;
        }
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get cricket player points.
      URL: 			/api/utilities/getPlayerPointsCricket
     */
    public function getPlayerPointsCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getPlayerPointsCricket');
        $this->Sports_model->getPlayerPointsCricket($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get joined cricket player points.
      URL: 			/api/utilities/getJoinedContestPlayerPointsCricket
     */
    public function getJoinedContestPlayerPointsCricket_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getJoinedContestPlayerPointsCricket');
        $this->Sports_model->getJoinedContestPlayerPointsCricket($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto set winner.
      URL: 			/api/utilities/setContestWinners
     */
    public function setContestWinners_get()
    {
        $CronID = $this->Common_model->insertCronLogs('setContestWinners');
        $this->Sports_model->setContestWinners($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to transfer joined contest data (MongoDB To MySQL).
      URL:          /api/utilities/tranferJoinedContestData
     */
    public function tranferJoinedContestData_get()
    {
        $CronID = $this->Common_model->insertCronLogs('tranferJoinedContestData');
        $this->Sports_model->tranferJoinedContestData($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to amount distribuit contest amount.
      URL:          /api/utilities/amountDistributeContestWinner
     */
    public function amountDistributeContestWinner_get()
    {
        $CronID = $this->Common_model->insertCronLogs('amountDistributeContestWinner');
        $this->Sports_model->amountDistributeContestWinner($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto cancel contest.
      URL: 			/api/utilities/autoCancelContest
     */
    public function autoCancelContest_get()
    {
        $CronID = $this->Common_model->insertCronLogs('autoCancelContest');
        $this->Sports_model->autoCancelContest($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to auto cancel contest refund amount.
      URL:          /api/utilities/refundAmountCancelContest
     */
    public function refundAmountCancelContest_get()
    {
        $CronID = $this->Common_model->insertCronLogs('refundAmountCancelContest');
        $this->Sports_model->refundAmountCancelContest($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get auction joined player points.
      URL: 			/api/utilities/getAuctionJoinedUserTeamsPlayerPoints
     */
    public function getAuctionJoinedUserTeamsPlayerPoints_get()
    {
        $CronID = $this->Common_model->insertCronLogs('getAuctionJoinedUserTeamsPlayerPoints');
        $this->Sports_model->getAuctionJoinedUserTeamsPlayerPoints($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto add minute in every hours.
      URL: 			/api/utilities/liveAuctionAddMinuteInEveryHours
     */
    public function auctionLiveAddMinuteInEveryHours_get()
    {
        $CronID = $this->Common_model->insertCronLogs('liveAuctionAddMinuteInEveryHours');
        $this->load->model('AuctionDrafts_model');
        $this->AuctionDrafts_model->auctionLiveAddMinuteInEveryHours($CronID);
        $this->Common_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to create pre draft contest
      URL:      /api/utilities/createPreDraftContest
    */
    public function createPreDraftContest_get()
    {
        $this->load->model('PredraftContest_model');
        $this->PredraftContest_model->createPreDraftContest();
    }

    /*
        Description: Use to manage cashfree webhook response
        URL: /api/utilities/cashFreeWebHookResponse
    */
    public function cashFreeWebHookResponse_post()
    {
        $this->Users_model->cashFreeWebHookResponse($this->input->post());
    }

    /*
      Description: To get statics
     */
    public function dashboardStatics_post()
    {
        $SiteStatics = new stdClass();
        $SiteStatics = $this->db->query(
            'SELECT
                                            TotalUsers,
                                            TodayContests,
                                            TotalDeposits,
                                            TotalWithdraw,
                                            TodayDeposit,
                                            NewUsers,
                                            PendingWithdraw
                                        FROM
                                            (SELECT
                                                (
                                                    SELECT
                                                        COUNT(UserID)
                                                    FROM
                                                        `tbl_users`
                                                    WHERE
                                                        `UserTypeID` = 2
                                                ) AS TotalUsers,
                                                (
                                                    SELECT
                                                        COUNT(UserID)
                                                    FROM
                                                        `tbl_users` U, `tbl_entity` E
                                                    WHERE
                                                        U.`UserTypeID` = 2 AND U.UserID = E.EntityID AND DATE(E.EntryDate) = "' . date('Y-m-d') . '"
                                                ) AS NewUsers,
                                                (
                                                    SELECT
                                                        COUNT(ContestID)
                                                    FROM
                                                        `sports_contest` C, `tbl_entity` E
                                                    WHERE
                                                        C.ContestID = E.EntityID AND DATE(E.EntryDate) = "' . date('Y-m-d') . '"
                                                ) AS TodayContests,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`WalletAmount`),0)
                                                    FROM
                                                        `tbl_users_wallet`
                                                    WHERE
                                                        `Narration`= "Deposit Money" AND
                                                        `StatusID` = 5
                                                ) AS TotalDeposits,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`WalletAmount`),0)
                                                    FROM
                                                        `tbl_users_wallet`
                                                    WHERE
                                                        `Narration`= "Deposit Money" AND
                                                        `StatusID` = 5 AND DATE(EntryDate) = "' . date('Y-m-d') . '"
                                                ) AS TodayDeposit,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`Amount`),0)
                                                    FROM
                                                        `tbl_users_withdrawal`
                                                    WHERE
                                                        `StatusID` = 2
                                                ) AS TotalWithdraw,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`Amount`),0)
                                                    FROM
                                                        `tbl_users_withdrawal`
                                                    WHERE
                                                        `StatusID` = 1
                                                ) AS PendingWithdraw
                                            ) Total'
        )->row();
        $this->Return['Data'] = $SiteStatics;
    }

    /*
      Name: 		createVirtualUsers
      Description: 	create virtual user users
      URL: 			/utilities/createVirtualUsers/
     */

    public function createVirtualUsers_get()
    {

        $tlds = array("com");
        $char = "0123456789abcdefghijklmnopqrstuvwxyz";
        $Limit = 23000;
        $Names = $this->Utility_model->getDummyNames($Limit);
        for ($j = 0; $j < $Limit; $j++) {
            $UserName = $Names[$j]['names'];
            $UserUnique = str_replace(" ", "", $UserName);
            $ulen = mt_rand(5, 10);
            $dlen = mt_rand(7, 17);
            $email = "";
            for ($i = 1; $i <= $ulen; $i++) {
                $email .= substr($char, mt_rand(0, strlen($char)), 1);
            }
            $email .= "@";
            $email .= "gmail";
            $email .= ".";
            $email .= $tlds[mt_rand(0, (sizeof($tlds) - 1))];
            $username = strtolower($UserUnique) . substr(md5(microtime()), rand(0, 26), 4);
            $Input = array();
            $Input['Email'] = $username . "@gmail.com";
            $Input['Username'] = $username;
            $Input['FirstName'] = $UserName;
            $Input['Password'] = 'A123456';
            $Input['Source'] = "Direct";
            $Input['PanStatus'] = 2;
            $Input['BankStatus'] = 2;
            $Input['DocumentStatus'] = 2;
            $UserID = $this->Users_model->addUser($Input, 3, 1, 2);
            if ($UserID) {
                $this->Utility_model->generateReferralCode($UserID);
                $WalletData = array(
                    "Amount" => 10000,
                    "CashBonus" => 5000,
                    "TransactionType" => 'Cr',
                    "Narration" => 'Deposit Money',
                    "EntryDate" => date("Y-m-d H:i:s")
                );
                $this->Users_model->addToWallet($WalletData, $UserID, 5);
            }
        }
    }

    /*
      Name: 		createVirtualUserTeams
      Description: 	create virtual user team
      URL: 			/utilities/createVirtualUserTeams/
     */

    public function createVirtualUserTeams_get()
    {

        $AllUsers = $this->Users_model->getUsers('UserID', array('UserTypeID' => 3), true, 1, 6000);
        if (!empty($AllUsers)) {
            $MatchContest = $this->Contest_model->getContests('MatchID', array('StatusID' => array(1), 'IsVirtualUserJoined' => "Yes"), TRUE);
            if (!empty($MatchContest['Data']['Records'])) {
                $Matches = array_unique(array_column($MatchContest['Data']['Records'], "MatchID"));
                foreach ($Matches as $Rows) {
                    $Match = $this->Sports_model->getMatches('SeriesID,MatchID,TeamIDLocal,TeamIDVisitor', array('StatusID' => array(1), 'MatchID' => $Rows), False, 1, 1);
                    if (!empty($Match)) {
                        $MatchID = $Match['MatchID'];
                        $SeriesID = $Match['SeriesID'];
                        $playersData = $this->Sports_model->getPlayers('PlayerID,TeamID,PlayerRole,PlayerSalary', array('MatchID' => $MatchID, 'RandData' => "rand()"), TRUE, 1, 50);
                        if (!empty($playersData)) {
                            $unique = 0;
                            foreach ($AllUsers['Data']['Records'] as $users) {
                                if ($unique % 2 == 0) {
                                    $localteamIDS = $Match['TeamIDLocal'];
                                    $visitorteamIDS = $Match['TeamIDVisitor'];
                                } else {
                                    $visitorteamIDS = $Match['TeamIDLocal'];
                                    $localteamIDS = $Match['TeamIDVisitor'];
                                }
                                $this->createTeamProcessByMatch($playersData, $localteamIDS, $visitorteamIDS, $SeriesID, $users['UserID'], $MatchID);
                                $unique++;
                            }
                        }
                    }
                }
            }
        }
    }

    /*
      Name: 		createTeamProcessByMatch
      Description: 	virtual usercommon create team
      URL: 			/testApp/createTeamProcessByMatch/
     */

    public function createTeamProcessByMatch($matchPlayer, $localteam_id, $visitorteam_id, $series_id, $user_id, $match_id)
    {
        $returnArray = array();
        $playerCount = 1;
        $secondPlayerCount = 1;
        $batsman = 0;
        $wicketkeeper = 0;
        $bowler = 0;
        $allrounder = 0;
        $teamCount = 0;
        $teamB = array();
        $Arr1 = array();
        $Arr2 = array();
        $Arr3 = array();
        $Arr4 = array();
        $Arr5 = array();
        $Arr6 = array();
        $Arr7 = array();
        $Arr8 = array();
        $creditPoints = 0;
        $points = 0;
        $selectedViceCaptainPlayer = [];
        $selectedCaptainPlayer = [];

        foreach ($matchPlayer['Data']['Records'] as $player) {
            if (count($playerCount) <= 7) {
                $playerId = $player['PlayerID'];
                $teamId = $player['TeamID'];
                $playerRole = strtoupper($player['PlayerRole']);
                $creditPoints += 9;
                if ($teamId == $localteam_id) {
                    if ($wicketkeeper < 1) {
                        if ($playerRole == 'WICKETKEEPER') {
                            $temp['play_role'] = strtoupper($player['PlayerRole']);
                            $temp['play_id'] = $player['PlayerID'];
                            $temp['team_id'] = $teamId;
                            $temp['PlayerPosition'] = 'ViceCaptain';
                            $temp['PlayerGUID'] = $player['PlayerGUID'];
                            $temp['creditPoints'] = $player['PointCredits'];
                            $Arr1[] = $temp;
                            $wicketkeeper++;
                        }
                    }
                    if ($batsman < 3) {
                        if ($playerRole == 'BATSMAN') {
                            $temp['play_role'] = strtoupper($player['PlayerRole']);
                            $temp['play_id'] = $player['PlayerID'];
                            $temp['team_id'] = $teamId;
                            $temp['PlayerPosition'] = 'Player';
                            $temp['PlayerGUID'] = $player['PlayerGUID'];
                            $temp['creditPoints'] = $player['PointCredits'];
                            $Arr2[] = $temp;
                            $batsman++;
                        }
                    }
                    if ($bowler < 2) {
                        if ($playerRole == 'BOWLER') {
                            $temp['play_role'] = strtoupper($player['PlayerRole']);
                            $temp['play_id'] = $player['PlayerID'];
                            $temp['team_id'] = $teamId;
                            $temp['PlayerPosition'] = 'Player';
                            $temp['PlayerGUID'] = $player['PlayerGUID'];
                            $temp['creditPoints'] = $player['PointCredits'];
                            $Arr3[] = $temp;
                            $bowler++;
                        }
                    }
                    if ($allrounder < 1) {
                        if ($playerRole == 'ALLROUNDER') {
                            $temp['play_role'] = strtoupper($player['PlayerRole']);
                            $temp['play_id'] = $player['PlayerID'];
                            $temp['team_id'] = $teamId;
                            $temp['PlayerPosition'] = 'Captain';
                            $temp['PlayerGUID'] = $player['PlayerGUID'];
                            $temp['creditPoints'] = $player['PointCredits'];
                            $Arr4[] = $temp;
                            $allrounder++;
                        }
                    }
                }
            }
            $playerCount++;
            $res1 = array_merge($Arr1, $Arr2, $Arr3, $Arr4);
        }
        foreach ($matchPlayer['Data']['Records'] as $player) {
            if (count($secondPlayerCount) <= 4) {
                $playerId = $player['PlayerID'];
                $teamId = $player['TeamID'];
                $playerRole = strtoupper($player['PlayerRole']);
                if ($teamId == $visitorteam_id) {
                    if ($wicketkeeper < 1) {
                        if ($playerRole == 'WICKETKEEPER') {
                            $temp1['play_role'] = strtoupper($player['PlayerRole']);
                            $temp1['play_id'] = $player['PlayerID'];
                            $temp1['team_id'] = $teamId;
                            $temp1['PlayerPosition'] = 'ViceCaptain';
                            $temp1['PlayerGUID'] = $player['PlayerGUID'];
                            $temp1['creditPoints'] = $player['PointCredits'];
                            $Arr5[] = $temp1;
                            $wicketkeeper++;
                        }
                    }
                    if ($batsman < 4) {
                        if ($playerRole == 'BATSMAN') {
                            $temp1['play_role'] = strtoupper($player['PlayerRole']);
                            $temp1['play_id'] = $player['PlayerID'];
                            $temp1['team_id'] = $teamId;
                            $temp1['PlayerPosition'] = 'Player';
                            $temp1['PlayerGUID'] = $player['PlayerGUID'];
                            $temp1['creditPoints'] = $player['PointCredits'];
                            $Arr6[] = $temp1;
                            $batsman++;
                        }
                    }
                    if ($bowler < 4) {
                        if ($playerRole == 'BOWLER') {
                            $temp1['play_role'] = strtoupper($player['PlayerRole']);
                            $temp1['play_id'] = $player['PlayerID'];
                            $temp1['team_id'] = $teamId;
                            $temp1['PlayerPosition'] = 'Player';
                            $temp1['PlayerGUID'] = $player['PlayerGUID'];
                            $temp1['creditPoints'] = $player['PointCredits'];
                            $Arr7[] = $temp1;
                            $bowler++;
                        }
                    }
                    if ($allrounder < 2) {
                        if ($playerRole == 'ALLROUNDER') {
                            $temp1['play_role'] = strtoupper($player['PlayerRole']);
                            $temp1['play_id'] = $player['PlayerID'];
                            $temp1['team_id'] = $teamId;
                            $temp1['PlayerPosition'] = 'Player';
                            $temp1['PlayerGUID'] = $player['PlayerGUID'];
                            $temp1['creditPoints'] = $player['PointCredits'];
                            $Arr8[] = $temp1;
                            $allrounder++;
                        }
                    }
                }
            }
            $secondPlayerCount++;
            $res2 = array_merge($Arr5, $Arr6, $Arr7, $Arr8);
        }
        $playing11 = array_merge($res2, $res1);
        if (count($playing11) == 11) {
            $this->Contest_model->addUserTeam(array('UserTeamPlayers' => json_encode($playing11), 'UserTeamType' => 'Normal'), $user_id, $match_id);
        }
        return true;
    }

    /*
      Name: 		autoJoinContestVirtualUser
      Description: 	join virtual user contest
      URL: 			/testApp/autoJoinContestVirtualUser/
     */

    public function autoJoinContestVirtualUser_get()
    {

        $UtcDateTime = date('Y-m-d H:i');
        $UtcDateTime = date('Y-m-d H:i', strtotime($UtcDateTime));
        $NextDateTime = strtotime($UtcDateTime) + 3600 * 20;
        $MatchDateTime = date('Y-m-d H:i', $NextDateTime);
        $Contests = $this->Contest_model->getContests('IsPaid,EntryFee,CashBonusContribution,WinningAmount,MatchID,IsDummyJoined,ContestID,ContestSize,TotalJoined,MatchStartDateTimeUTC,VirtualUserJoinedPercentage', array('ContestID' => 96164), TRUE);
        if (!empty($Contests['Data']['Records'])) {
            foreach ($Contests['Data']['Records'] as $Rows) {
                $Seconds = strtotime($Rows['MatchStartDateTimeUTC']) - strtotime($UtcDateTime);
                $hours = $Seconds / 60 / 60;

                $dummyJoinedContest = 0;
                $dummyJoinedContests = $this->db->query("SELECT count(JC.ContestID) as DummyJoinedContest FROM sports_contest_join as JC JOIN tbl_users ON tbl_users.UserID = JC.UserID WHERE JC.ContestID = ".$Rows['ContestID']." AND tbl_users.UserTypeID = 3")->row()->DummyJoinedContest;

                if ($dummyJoinedContests) {
                    $dummyJoinedContest = $dummyJoinedContests;
                }

                $totalJoined = $Rows['TotalJoined'];
                $contestSize = $Rows['ContestSize'];
                $joinDummyUser = $Rows['VirtualUserJoinedPercentage'];
                $dummyUserPercentage = round(($contestSize * $joinDummyUser) / 100);

                if ($dummyJoinedContest >= $dummyUserPercentage) {
                    $this->Contest_model->updateVirtualJoinContest($Rows['ContestID']);
                    // continue;
                }

                if ($hours > 7 || $Rows['IsDummyJoined'] == 0) {
                    if ($Rows['IsDummyJoined'] == 0) {
                        $dummyUserPercentage = round(($dummyUserPercentage * 40 / 100));
                    } else {
                        // continue;
                    }
                } else if ($hours > 4 || ($Rows['IsDummyJoined'] == 1 && $hours < 4)) {
                    if ($Rows['IsDummyJoined'] == 1) {
                        $dummyUserPercentage = round(($dummyUserPercentage * 40 / 100));
                    } else {
                        // continue;
                    }
                } else {
                    if ($Rows['IsDummyJoined'] >= 2 && $hours < 3) {
                        $dummyUserPercentage = round(($dummyUserPercentage * 100 / 100)) - $dummyJoinedContest;
                    } else {
                        // continue;
                    }
                }


                $isEliglibleJoin = $totalJoined + $dummyUserPercentage;
                if (!($isEliglibleJoin <= $contestSize)) {
                    $dummyUserPercentage = $contestSize - $totalJoined - 5;
                }

                $VitruelTeamPlayer = $this->Contest_model->getVirtualTeamPlayerMatchWise($Rows['MatchID'], 21000);
                if (!empty($VitruelTeamPlayer)) {
                    foreach ($VitruelTeamPlayer as $usersTeam) {
                        $userTeamPlayers = json_decode($usersTeam['Players']);
                        $myPlayers = '';
                        $c = 0;
                        $vc = 0;
                        foreach ($userTeamPlayers as $player) {
                            $myPlayers .= $player->PlayerID . ",";
                            if ($player->PlayerPosition == "Captain") {
                                $captain_player = $player->PlayerID;
                                $c++;
                            }
                            if ($player->PlayerPosition == "ViceCaptain") {
                                $vice_captain_player = $player->PlayerID;
                                $vc++;
                            }
                        }
                        if (isset($myPlayers) && isset($captain_player) && isset($vice_captain_player)) {
                            $myPlayers = rtrim($myPlayers, ",");
                            if (!empty($usersTeam['UserTeamID'])) {
                                if ($c > 1 || $vc > 1) {
                                    continue;
                                }
                                $PostInput = array();
                                $PostInput['IsPaid'] = $this->Contest_model->joinContest($Rows, $usersTeam['UserID'], $Rows['ContestID'], $Rows['MatchID'], $usersTeam['UserTeamID']);
                            }
                        }
                    }
                    $this->Contest_model->contestUpdateVirtualTeam($Rows['ContestID'], $Rows['IsDummyJoined']);
                }
            }
        }
    }
}
