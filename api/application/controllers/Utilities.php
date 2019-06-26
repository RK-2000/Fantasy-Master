<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utilities extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Utility_model');
        $this->load->model('Sports_model');
        $this->load->model('Contest_model');
        
        /* Require MongoDB Library & Connection */
        require_once getcwd() . '/vendor/autoload.php';
        $this->ClientObj = new MongoDB\Client("mongodb://fantasyadmin:fantasymw123@localhost:48017");
       $this->fantasydb = $this->ClientObj->fantasy;
    }

    /*
      Description: 	Use to send email to webadmin.
      URL: 			/api/utilities/contact/
     */

    public function contact_post() {
        /* Validation section */
        $this->form_validation->set_rules('Name', 'Name', 'trim');
        $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim');
        $this->form_validation->set_rules('Title', 'Title', 'trim');
        $this->form_validation->set_rules('Message', 'Message', 'trim|required');
        $this->form_validation->validation($this); /* Run validation */
        /* Validation - ends */


        // sendMail(array(
        //     'emailTo' => SITE_CONTACT_EMAIL,
        //     'emailSubject' => $this->Post['Name'] . " filled out the contact form on the " . SITE_NAME,
        //     'emailMessage' => emailTemplate($this->load->view('emailer/contact', array(
        //                 "Name" => $this->Post['Name'],
        //                 'Email' => $this->Post['Email'],
        //                 'PhoneNumber' => $this->Post['PhoneNumber'],
        //                 'Title' => $this->Post['Title'],
        //                 'Message' => $this->Post['Message'],
        //                     ), TRUE))
        // ));
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
      Description:  Use execute cron jobs.
      URL:      /api/utilities/getCountries
     */

    public function getCountries_post() {
        $CountryData = $this->Utility_model->getCountries();
        if (!empty($CountryData)) {
            $this->Return['Data'] = $CountryData['Data'];
        }
    }

    public function getStates_post() {
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

    public function getPosts_post() {
        /* Validation section */
        $this->form_validation->set_rules('PageNo', 'PageNo', 'trim|integer');
        $this->form_validation->set_rules('PageSize', 'PageSize', 'trim|integer');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        $this->load->model('Post_model');
        $Posts = $this->Post_model->getPosts('
            P.PostGUID,
            P.PostContent,
            P.PostCaption,
            ', array(), TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if ($Posts) {
            $this->Return['Data'] = $Posts['Data'];
        }
    }

    public function sendAppLink_post() {
        $this->form_validation->set_rules('PhoneNumber', 'PhoneNumber', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */

        $this->Utility_model->sendSMS(array(
            'PhoneNumber' => $this->Post['PhoneNumber'],
            'Text' => "Here is the new " . SITE_NAME . " Android Application! Click on the link to download the App and Start Winning. https://fsl11.com/android/FSL11.apk"
        ));
        $this->Return['Message'] = "Link Sent successfully.";
    }

    /*
      Description:  Use to create pre draft contest
      URL:      /api/utilities/createPreContest
     */

    public function createPreContest_get() {
        $this->load->model('PreContest_model');
        $this->PreContest_model->createPreContest();
    }

    /*
      Description: 	Cron jobs to get series data.
      URL: 			/api/utilities/getSeriesLive
     */

    public function getSeriesLive_get() {
        $CronID = $this->Utility_model->insertCronLogs('getSeriesLive');
        if (SPORTS_API_NAME == 'ENTITY') {
            $SeriesData = $this->Sports_model->getSeriesLiveEntity($CronID);
        } else {
            $SeriesData = $this->Sports_model->getSeriesLiveEntity($CronID);
        }
        if (!empty($SeriesData)) {
            $this->Return['Data'] = $SeriesData;
        }
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get matches data.
      URL: 			/api/utilities/getMatchesLive
     */

    public function getMatchesLive_get() {
        $CronID = $this->Utility_model->insertCronLogs('getMatchesLive');
        if (SPORTS_API_NAME == 'ENTITY') {
            $MatchesData = $this->Sports_model->getMatchesLiveEntity($CronID);
        }
        if (SPORTS_API_NAME == 'CRICKETAPI') {
            $MatchesData = $this->Sports_model->getMatchesLiveCricketApi($CronID);
        }
        if (!empty($MatchesData)) {
            $this->Return['Data'] = $MatchesData;
        }
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get players data.
      URL: 			/api/utilities/getPlayersLive
     */

    public function getPlayersLive_get() {
        $CronID = $this->Utility_model->insertCronLogs('getPlayersLive');
        if (SPORTS_API_NAME == 'ENTITY') {
            //$PlayersData = $this->Sports_model->getPlayersLiveEntity($CronID);
            $PlayersData = $this->Sports_model->getMatchWisePlayersLiveEntity($CronID);
        }
        if (SPORTS_API_NAME == 'CRICKETAPI') {
            $PlayersData = $this->Sports_model->getPlayersLiveCricketApi($CronID);
        }
        if (!empty($PlayersData)) {
            $this->Return['Data'] = $PlayersData;
        }
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get player stats data.
      URL: 			/api/utilities/getPlayerStatsLive
     */

    public function getPlayerStatsLive_get() {
        $CronID = $this->Utility_model->insertCronLogs('getPlayerStatsLive');
        if (SPORTS_API_NAME == 'ENTITY') {
            $PlayersStatsData = $this->Sports_model->getPlayerStatsLiveEntity($CronID);
        }
        if (SPORTS_API_NAME == 'CRICKETAPI') {
            $PlayersStatsData = $this->Sports_model->getPlayerStatsLiveCricketApi($CronID);
        }
        if (!empty($PlayersStatsData)) {
            $this->Return['Data'] = $PlayersStatsData;
        }
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get match live score
      URL: 			/api/utilities/getMatchScoreLive
     */

    public function getMatchScoreLive_get() {

        $CronID = $this->Utility_model->insertCronLogs('getMatchScoreLive');
        if (SPORTS_API_NAME == 'ENTITY') {
            $MatchScoreLiveData = $this->Sports_model->getMatchScoreLiveEntity($CronID);
        }
        if (SPORTS_API_NAME == 'CRICKETAPI') {
            $MatchScoreLiveData = $this->Sports_model->getMatchScoreLiveCricketApi($CronID);
        }
        if (!empty($MatchScoreLiveData)) {
            $this->Return['Data'] = $MatchScoreLiveData;
        }
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto cancel contest.
      URL: 			/api/utilities/autoCancelContest
     */

    public function autoCancelContest_get() {
        $CronID = $this->Utility_model->insertCronLogs('autoCancelContest');
        $this->Sports_model->autoCancelContest($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to amount distribuit contest amount.
      URL:          /api/utilities/amountDistributeContestWinner
     */
    public function amountDistributeContestWinner_get() {
        $CronID = $this->Utility_model->insertCronLogs('amountDistributeContestWinner');
        $this->Sports_model->amountDistributeContestWinner($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to auto cancel contest refund amount.
      URL:          /api/utilities/refundAmountCancelContest
     */

    public function refundAmountCancelContest_get() {
        $CronID = $this->Utility_model->insertCronLogs('refundAmountCancelContest');
        $this->Sports_model->refundAmountCancelContest($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get player points.
      URL: 			/api/utilities/getPlayerPoints
     */

    public function getPlayerPoints_get() {
        $CronID = $this->Utility_model->insertCronLogs('getPlayerPoints');
        $this->Sports_model->getPlayerPoints($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get joined player points.
      URL: 			/api/utilities/getJoinedContestPlayerPoints
     */

    public function getJoinedContestPlayerPoints_get() {
        $CronID = $this->Utility_model->insertCronLogs('getJoinedContestPlayerPoints');
        $this->Sports_model->getJoinedContestTeamPoints($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to get auction joined player points.
      URL: 			/api/utilities/getAuctionJoinedUserTeamsPlayerPoints
     */

    public function getAuctionJoinedUserTeamsPlayerPoints_get() {
        $CronID = $this->Utility_model->insertCronLogs('getAuctionJoinedUserTeamsPlayerPoints');
        $this->Sports_model->getAuctionJoinedUserTeamsPlayerPoints($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto set winner.
      URL: 			/api/utilities/setContestWinners
     */

    public function setContestWinners_get() {
        $CronID = $this->Utility_model->insertCronLogs('setContestWinners');
        $this->Sports_model->setContestWinners($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description:  Cron jobs to transfer joined contest data (MongoDB To MySQL).
      URL:          /api/utilities/tranferJoinedContestData
     */

    public function tranferJoinedContestData_get() {
        $CronID = $this->Utility_model->insertCronLogs('tranferJoinedContestData');
        $this->Sports_model->tranferJoinedContestData($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: 	Cron jobs to auto add minute in every hours.
      URL: 			/api/utilities/liveAuctionAddMinuteInEveryHours
     */

    public function auctionLiveAddMinuteInEveryHours_get() {
        $CronID = $this->Utility_model->insertCronLogs('liveAuctionAddMinuteInEveryHours');
        $this->load->model('AuctionDrafts_model');
        $this->AuctionDrafts_model->auctionLiveAddMinuteInEveryHours($CronID);
        $this->Utility_model->updateCronLogs($CronID);
    }

    /*
      Description: To get statics
     */

    public function dashboardStatics_post() {
        $SiteStatics = new stdClass();
        $SiteStatics = $this->db->query('SELECT
                                            TotalUsers,
                                            TotalContest,
                                            TodayContests,
                                            TotalDeposits,
                                            TotalWithdraw,
                                            TodayDeposit,
                                            NewUsers,
                                            TotalDeposits - TotalWithdraw AS TotalEarning,
                                            PendingWithdraw
                                        FROM
                                            (SELECT
                                                (
                                                    SELECT
                                                        COUNT(UserID) AS `TotalUsers`
                                                    FROM
                                                        `tbl_users`
                                                    WHERE
                                                        `UserTypeID` = 2
                                                ) AS TotalUsers,
                                                (
                                                    SELECT
                                                        COUNT(UserID) AS `NewUsers`
                                                    FROM
                                                        `tbl_users` U, `tbl_entity` E
                                                    WHERE
                                                        U.`UserTypeID` = 2 AND U.UserID = E.EntityID AND DATE(E.EntryDate) = "' . date('Y-m-d') . '"
                                                ) AS NewUsers,
                                                (
                                                    SELECT
                                                        COUNT(ContestID) AS `TotalContest`
                                                    FROM
                                                        `sports_contest`
                                                ) AS TotalContest,
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
                                                        IFNULL(SUM(`WalletAmount`),0) AS TotalDeposits
                                                    FROM
                                                        `tbl_users_wallet`
                                                    WHERE
                                                        `Narration`= "Deposit Money" AND
                                                        `StatusID` = 5
                                                ) AS TotalDeposits,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`WalletAmount`),0) AS TodayDeposit
                                                    FROM
                                                        `tbl_users_wallet`
                                                    WHERE
                                                        `Narration`= "Deposit Money" AND
                                                        `StatusID` = 5 AND DATE(EntryDate) = "' . date('Y-m-d') . '"
                                                ) AS TodayDeposit,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`Amount`),0) AS TotalWithdraw
                                                    FROM
                                                        `tbl_users_withdrawal`
                                                    WHERE
                                                        `StatusID` = 2
                                                ) AS TotalWithdraw,
                                                (
                                                    SELECT
                                                        IFNULL(SUM(`Amount`),0) AS TotalWithdraw
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
      Name:           getTotalDeposits
      Description:    To get Total Deposits data
      URL:            /Utilites/getTotalDeposits/
     */

    public function getTotalDeposits_post() {
        /* Get Total Deposit Data */
        $WalletDetails = $this->Utility_model->getTotalDeposit(@$this->Post['Params'], $this->Post, TRUE, @$this->Post['PageNo'], @$this->Post['PageSize']);
        if (!empty($WalletDetails)) {
            $this->Return['Data'] = $WalletDetails['Data'];
        }
    }

    /*
      Description:  Use to get app version details
      URL:      /api/utilities/getAppVersionDetails
     */

    public function getAppVersionDetails_post() {
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
      Name: 			createVirtualUsers
      Description: 	create virtual user users
      URL: 			/utilities/createVirtualUsers/
     */

    public function createVirtualUsers_get() {

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
      Name: 			createVirtualUserTeams
      Description: 	create virtual user team
      URL: 			/utilities/createVirtualUserTeams/
     */

    public function createVirtualUserTeams_get() {

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
      Name: 			createTeamProcessByMatch
      Description: 	virtual usercommon create team
      URL: 			/testApp/createTeamProcessByMatch/
     */

    public function createTeamProcessByMatch($matchPlayer, $localteam_id, $visitorteam_id, $series_id, $user_id, $match_id) {
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
                    } if ($batsman < 3) {
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
                    }if ($bowler < 2) {
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
                    }if ($allrounder < 1) {
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
                    } if ($batsman < 4) {
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
                    }if ($bowler < 4) {
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
                    }if ($allrounder < 2) {
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
      Name: 			autoJoinContestVirtualUser
      Description: 	join virtual user contest
      URL: 			/testApp/autoJoinContestVirtualUser/
     */

    public function autoJoinContestVirtualUser_get() {

        $UtcDateTime = date('Y-m-d H:i');
        $UtcDateTime = date('Y-m-d H:i', strtotime($UtcDateTime));
        $NextDateTime = strtotime($UtcDateTime) + 3600 * 20;
        $MatchDateTime = date('Y-m-d H:i', $NextDateTime);

        // $Contests = $this->Contest_model->getContests('IsPaid,EntryFee,CashBonusContribution,WinningAmount,MatchID,IsDummyJoined,ContestID,ContestSize,TotalJoined,MatchStartDateTimeUTC,VirtualUserJoinedPercentage', array('StatusID' => array(1), 'IsVirtualUserJoined' => "Yes", "ContestFull" => "No"), TRUE);
        $Contests = $this->Contest_model->getContests('IsPaid,EntryFee,CashBonusContribution,WinningAmount,MatchID,IsDummyJoined,ContestID,ContestSize,TotalJoined,MatchStartDateTimeUTC,VirtualUserJoinedPercentage', array('ContestID' => 75159), TRUE);
        // echo "<pre>";
        // print_r($Contests);die;
        if (!empty($Contests['Data']['Records'])) {
            foreach ($Contests['Data']['Records'] as $Rows) {
                $Seconds = strtotime($Rows['MatchStartDateTimeUTC']) - strtotime($UtcDateTime);
                $hours = $Seconds / 60 / 60;

                $dummyJoinedContest = 0;
                $dummyJoinedContests = $this->Contest_model->getTotalDummyJoinedContest($Rows['ContestID']);

                if ($dummyJoinedContests) {
                    $dummyJoinedContest = $dummyJoinedContests;
                }

                $totalJoined = $Rows['TotalJoined'];
                $contestSize = $Rows['ContestSize'];
                $joinDummyUser = $Rows['VirtualUserJoinedPercentage'];
                $dummyUserPercentage = round(($contestSize * $joinDummyUser) / 100);

                if ($dummyJoinedContest >= $dummyUserPercentage) {
                    $this->Contest_model->UpdateVirtualJoinContest($Rows['ContestID']);
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

                $VitruelTeamPlayer = $this->Contest_model->GetVirtualTeamPlayerMatchWise($Rows['MatchID'], 10000);
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
                    $this->Contest_model->ContestUpdateVirtualTeam($Rows['ContestID'], $Rows['IsDummyJoined']);
                }
            }
        }
    }

    /*
      Description:  Use to get referel amount details.
      URL:      /api/utilities/getReferralDetails
     */

    public function getReferralDetails_post() {
        $ReferByQuery = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "ReferByDepositBonus" AND StatusID = 2 LIMIT 1');
        $ReferToQuery = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "ReferToDepositBonus" AND StatusID = 2 LIMIT 1');
        $this->Return['Data']['ReferByBonus'] = ($ReferByQuery->num_rows() > 0) ? $ReferByQuery->row()->ConfigTypeValue : 0;
        $this->Return['Data']['ReferToBonus'] = ($ReferToQuery->num_rows() > 0) ? $ReferToQuery->row()->ConfigTypeValue : 0;
    }

    /*
      Description:  Use to get referel amount details.
      URL:      /api/utilities/getReferralDetails
     */

    public function razorpayWebResponse_post() {

        $Input = file_get_contents("php://input");
        $PayResponse = json_decode($Input, 1);


        $InsertData = array_filter(array(
            "PageGUID" => "RazorPay",
            "Title" => "Test",
            "Content" => json_encode($Input)
        ));
        $this->db->insert('set_pages', $InsertData);



        $payResponse = $PayResponse['payload']['payment']['entity'];
        if ($payResponse['status'] === "authorized") {

            $this->db->trans_start();

            $payment_id = $payResponse['id'];
            /* update profile table */
            $UpdataData = array_filter(
                    array(
                        'PaymentGatewayResponse' => @$Input,
                        'ModifiedDate' => date("Y-m-d H:i:s"),
                        'StatusID' => 5
            ));
            $this->db->where('WalletID', $payResponse['notes']['OrderID']);
            $this->db->where('UserID', $payResponse['notes']['UserID']);
            $this->db->where('StatusID', 1);
            $this->db->limit(1);
            $this->db->update('tbl_users_wallet', $UpdataData);
            if ($this->db->affected_rows() <= 0)
                return FALSE;

            $Amount = $payResponse['amount'] / 100;
            $this->db->set('WalletAmount', 'WalletAmount+' . $Amount, FALSE);
            $this->db->where('UserID', $payResponse['notes']['UserID']);
            $this->db->limit(1);
            $this->db->update('tbl_users');

            $UserID = $payResponse['notes']['UserID'];
            $this->Notification_model->addNotification('AddCash', 'Cash Added', $UserID, $UserID, '', 'Deposit of ' . DEFAULT_CURRENCY . @$Amount . ' is Successful.');

            $TotalDeposits = $this->db->query('SELECT COUNT(*) TotalDeposits FROM `tbl_users_wallet` WHERE `UserID` = ' . $UserID . ' AND Narration = "Deposit Money" AND StatusID = 5')->row()->TotalDeposits;

            if ($TotalDeposits == 1) { // On First Successful Transaction

                /* Get Deposit Bonus Data */
                $DepositBonusData = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "FirstDepositBonus" LIMIT 1');
                if ($DepositBonusData->row()->StatusID == 2) {

                    $MinimumFirstTimeDepositLimit = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "MinimumFirstTimeDepositLimit" LIMIT 1');
                    $MaximumFirstTimeDepositLimit = $this->db->query('SELECT ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID = "MaximumFirstTimeDepositLimit" LIMIT 1');

                    if ($MinimumFirstTimeDepositLimit->row()->ConfigTypeValue <= @$Amount && $MaximumFirstTimeDepositLimit->row()->ConfigTypeValue >= @$Amount) {
                        /* Update Wallet */
                        $FirstTimeAmount = (@$Amount * $DepositBonusData->row()->ConfigTypeValue) / 100;
                        $WalletData = array(
                            "Amount" => $FirstTimeAmount,
                            "CashBonus" => $FirstTimeAmount,
                            "TransactionType" => 'Cr',
                            "Narration" => 'First Deposit Bonus',
                            "EntryDate" => date("Y-m-d H:i:s")
                        );
                        $this->addToWallet($WalletData, $UserID, 5);
                    }
                }

                /* Get User Data */
                $UserData = $this->Users_model->getUsers('ReferredByUserID', array("UserID" => $UserID));
                if (!empty($UserData['ReferredByUserID'])) {

                    /* Get Referral To Bonus Data */
                    $ReferralToBonus = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "ReferToDepositBonus" LIMIT 1');
                    if ($ReferralToBonus->row()->StatusID == 2) {

                        /* Update Wallet */
                        $WalletData = array(
                            "Amount" => $ReferralToBonus->row()->ConfigTypeValue,
                            "CashBonus" => $ReferralToBonus->row()->ConfigTypeValue,
                            "TransactionType" => 'Cr',
                            "Narration" => 'Referral Bonus',
                            "EntryDate" => date("Y-m-d H:i:s")
                        );
                        $this->addToWallet($WalletData, $UserID, 5);
                        $this->Notification_model->addNotification('ReferralBonus', 'Referred Bonus Added', $UserID, $UserID, '', 'You have received ' . DEFAULT_CURRENCY . @$ReferralToBonus->row()->ConfigTypeValue . ' Cash Bonus for Referred.');
                    }

                    /* Get Referral By Bonus Data */
                    $ReferralByBonus = $this->db->query('SELECT ConfigTypeValue,StatusID FROM set_site_config WHERE ConfigTypeGUID = "ReferByDepositBonus" LIMIT 1');
                    if ($ReferralByBonus->row()->StatusID == 2) {

                        /* Update Wallet */
                        $WalletData = array(
                            "Amount" => $ReferralByBonus->row()->ConfigTypeValue,
                            "CashBonus" => $ReferralByBonus->row()->ConfigTypeValue,
                            "TransactionType" => 'Cr',
                            "Narration" => 'Referral Bonus',
                            "EntryDate" => date("Y-m-d H:i:s")
                        );
                        $this->addToWallet($WalletData, $UserData['ReferredByUserID'], 5);
                        $this->Notification_model->addNotification('ReferralBonus', 'Referral Bonus Added', $UserData['ReferredByUserID'], $UserData['ReferredByUserID'], '', 'You have received ' . DEFAULT_CURRENCY . @$ReferralByBonus->row()->ConfigTypeValue . ' Cash Bonus for Successful Referral.');
                    }
                }
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            }
        } else {
            /* if ($payResponse['status'] === "failed") {
              $UpdataData = array_filter(
              array(
              'PaymentGatewayResponse' => @$Input,
              'ModifiedDate' => date("Y-m-d H:i:s"),
              'StatusID' => 3
              ));
              $this->db->where('WalletID', $payResponse['notes']['OrderID']);
              $this->db->where('UserID', $payResponse['notes']['UserID']);
              $this->db->where('StatusID', 1);
              $this->db->limit(1);
              $this->db->update('tbl_users_wallet', $UpdataData);
              if ($this->db->affected_rows() <= 0)
              return FALSE;
              } */
        }
    }

    /*
      Description:  Use to update wallet opening balance
      URL:      /api/utilities/updateOpeningBalance
     */

    public function updateOpeningBalance_get() {

        /* Reset Entries */
        $this->db->query('UPDATE `tbl_users_wallet` SET `OpeningWalletAmount` = 0,`OpeningWinningAmount`=0,`OpeningCashBonus`=0,`ClosingWalletAmount`=0,`ClosingWinningAmount`=0,`ClosingCashBonus` =0');
        $Query = $this->db->query('SELECT `UserID` FROM `tbl_users_wallet` GROUP BY UserID');
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $key => $Record) {
                $Query1 = $this->db->query('SELECT * FROM `tbl_users_wallet` WHERE `UserID` = ' . $Record['UserID'] . ' ORDER BY `WalletID` ASC');
                foreach ($Query1->result_array() as $key1 => $Record1) {
                    $Query2 = $this->db->query('SELECT * FROM `tbl_users_wallet` WHERE `UserID` = ' . $Record['UserID'] . ' AND WalletID < ' . $Record1['WalletID'] . ' ORDER BY `WalletID` DESC LIMIT 1');
                    if ($Query2->num_rows() > 0) {
                        $OpeningWalletAmount = $Query2->row()->ClosingWalletAmount;
                        $OpeningWinningAmount = $Query2->row()->ClosingWinningAmount;
                        $OpeningCashBonus = $Query2->row()->ClosingCashBonus;
                        $ClosingWalletAmount = ($Record1['StatusID'] == 5) ? (($OpeningWalletAmount != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningWalletAmount + $Record1['WalletAmount'] : $OpeningWalletAmount - $Record1['WalletAmount'] ) : $Record1['WalletAmount']) : $OpeningWalletAmount;
                        $ClosingWinningAmount = ($Record1['StatusID'] == 5) ? (($OpeningWinningAmount != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningWinningAmount + $Record1['WinningAmount'] : $OpeningWinningAmount - $Record1['WinningAmount'] ) : $Record1['WinningAmount']) : $OpeningWinningAmount;
                        $ClosingCashBonus = ($Record1['StatusID'] == 5) ? (($OpeningCashBonus != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningCashBonus + $Record1['CashBonus'] : $OpeningCashBonus - $Record1['CashBonus'] ) : $Record1['CashBonus']) : $OpeningCashBonus;
                    } else {
                        $OpeningWalletAmount = $OpeningWinningAmount = $OpeningCashBonus = 0;
                        $ClosingWalletAmount = ($Record1['StatusID'] == 5) ? (($OpeningWalletAmount != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningWalletAmount + $Record1['WalletAmount'] : $OpeningWalletAmount - $Record1['WalletAmount'] ) : $Record1['WalletAmount']) : 0;
                        $ClosingWinningAmount = ($Record1['StatusID'] == 5) ? (($OpeningWinningAmount != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningWinningAmount + $Record1['WinningAmount'] : $OpeningWinningAmount - $Record1['WinningAmount'] ) : $Record1['WinningAmount']) : 0;
                        $ClosingCashBonus = ($Record1['StatusID'] == 5) ? (($OpeningCashBonus != 0) ? (($Record1['TransactionType'] == 'Cr') ? $OpeningCashBonus + $Record1['CashBonus'] : $OpeningCashBonus - $Record1['CashBonus'] ) : $Record1['CashBonus']) : 0;
                    }
                    $UpdateArr = array(
                        'OpeningWalletAmount' => $OpeningWalletAmount,
                        'OpeningWinningAmount' => $OpeningWinningAmount,
                        'OpeningCashBonus' => $OpeningCashBonus,
                        'ClosingWalletAmount' => $ClosingWalletAmount,
                        'ClosingWinningAmount' => $ClosingWinningAmount,
                        'ClosingCashBonus' => $ClosingCashBonus
                    );
                    $this->db->where('WalletID', $Record1['WalletID']);
                    $this->db->limit(1);
                    $this->db->update('tbl_users_wallet', $UpdateArr);
                }
            }
        }
    }

    public function wrongWinningDistribution_get() {
        exit;
        /* Reset Entries */
        $Query = $this->db->query("SELECT * FROM `tbl_users_wallet` WHERE `Narration` = 'Join Contest Winning' AND `EntryDate` LIKE '%2019-06-10%' ORDER BY `WalletID` DESC");
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $key => $Record) {
                $Query1 = $this->db->query('SELECT WinningAmount,UserID FROM `tbl_users` WHERE `UserID` = ' . $Record['UserID'] . '');
                $UserWallet = $Query1->row_array();
                if (!empty($UserWallet)) {
                    $ContestWinningAmount = $Record['WinningAmount'];
                    $UserWinningAmount = $UserWallet['WinningAmount'];
                    if ($UserWinningAmount >= $ContestWinningAmount) {
                        $WalletData = array(
                            "Amount" => $ContestWinningAmount,
                            "WinningAmount" => $ContestWinningAmount,
                            "TransactionType" => 'Dr',
                            "Narration" => 'Wrong Winning Distribution',
                            "EntityID" => $Record['EntityID'],
                            "UserTeamID" => $Record['UserTeamID'],
                            "EntryDate" => date("Y-m-d H:i:s")
                        );
                        $this->Users_model->addToWallet($WalletData, $Record['UserID'], 5);
                    }
                }
            }
        }
    }

}
