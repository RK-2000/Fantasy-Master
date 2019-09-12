<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        mongoDBConnection();
    }

    /*
	Description: 	Use to get country list
	*/
    function getCountries($Field = '', $Where = array(), $multiRecords = false, $PageNo = 1, $PageSize = 15)
    {
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'CountryTeamName' => 'CountryTeamName',
                'iso3'            => 'iso3',
                'IsDefaultFavourite' => 'IsDefaultFavourite',
                'CountryFlag'     => 'CONCAT("' . BASE_URL . '","asset/countries/",CountryCode,".png") CountryFlag'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('CountryCode,CountryName,phonecode');
        if (!empty($Field))
            $this->db->select($Field, false);
        $this->db->from('set_location_country');
        if (!empty($Where['IsDefaultFavourite'])) {
            $this->db->like("IsDefaultFavourite", $Where['IsDefaultFavourite']);
        }
        if (!empty($Where['OrderBy']) && !empty($Where['Sequence'])) {
            $this->db->order_by($Where['OrderBy'], $Where['Sequence']);
        }
        $this->db->order_by('CountryName', 'ASC');

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

            /* Get User Details */
            if (in_array('IsUserFavourite', $Params)) {
                $UserData = $this->Users_model->getUsers('MyFavouriteTeams', array('UserID' => @$Where['SessionUserID']));
            }

            if ($multiRecords) {
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;

                    /* get user favourite teams */
                    if (in_array('IsUserFavourite', $Params)) {
                        $Records[$key]['IsUserFavourite'] = (in_array($Record['CountryTeamName'], $UserData['MyFavouriteTeams'])) ? 'Yes' : 'No';
                    }
                }
                $Return['Data']['Records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();

                /* get user favourite teams */
                if (in_array('IsUserFavourite', $Params)) {
                    $Record['IsUserFavourite'] = (in_array($Record['CountryTeamName'], $UserData['MyFavouriteTeams'])) ? 'Yes' : 'No';
                }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description: 	Use to get state list
     */

    function getStates($Where = array())
    {
        /* Define section  */
        $Return = array('Data' => array('Records' => array()));
        /* Define variables - ends */

        $this->db->select('StateName,CountryCode');
        $this->db->from('set_location_state');
        if (!empty($Where['CountryCode'])) {
            $this->db->where("CountryCode", $Where['CountryCode']);
        }
        $this->db->order_by("StateName", 'ASC');
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
      Description : To add banner
     */

    function addBanner($UserID, $Input = array(), $StatusID)
    {
        $this->db->trans_start();
        $EntityGUID = get_guid();
        /* Add to entity table and get ID. */
        $BannerID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 14, "UserID" => $UserID, "StatusID" => $StatusID));
        $this->db->trans_complete($this->SessionUserID, array_merge($this->Post), $this->StatusID);
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        $this->cache->memcached->delete('Banners');
        return array('BannerID' => $BannerID, 'BannerGUID' => $EntityGUID);
    }

    /*
      Description: 	Use to get banner list
     */
    function bannerList($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $MediaData = $this->cache->memcached->get('Banners');
        if (empty($MediaData)) {
            $MediaData = $this->Media_model->getMedia('MediaGUID, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,	M.MediaCaption', array("SectionID" => 'Banner'), TRUE);
            if ($MediaData) {
                $Return = ($MediaData ? $MediaData : new StdClass());
                $this->cache->memcached->save('Banners', $Return);
                return $Return;
            }
            return false;
        }
        return $MediaData;
    }

    /*
      Description: 	Use to add ReferralCode
     */
    function generateReferralCode($UserID = '')
    {
        $ReferralCode = random_string('alnum', 6);
        $this->db->insert('tbl_referral_codes', array_filter(array('UserID' => $UserID, 'ReferralCode' => $ReferralCode)));
        return $ReferralCode;
    }

    /*
      Description: Use to get site config.
     */
    function getConfigs($Where = array())
    {
        $this->db->select('ConfigTypeGUID,ConfigTypeDescprition,ConfigTypeValue, (CASE WHEN StatusID = 2 THEN "Active" WHEN StatusID = 6 THEN "Inactive" ELSE "Unknown" END) AS Status');
        $this->db->from('set_site_config');
        if (!empty($Where['ConfigTypeGUID'])) {
            $this->db->where("ConfigTypeGUID", $Where['ConfigTypeGUID']);
        }
        if (!empty($Where['StatusID'])) {
            $this->db->where("StatusID", $Where['StatusID']);
        }
        $this->db->order_by("Sort", 'ASC');
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
      Description: Use to update config.
     */
    function updateConfig($ConfigTypeGUID, $Input = array())
    {
        if (!empty($Input)) {
            /* Update Config */
            $this->db->where('ConfigTypeGUID', $ConfigTypeGUID);
            $this->db->limit(1);
            $this->db->update('set_site_config', array('ConfigTypeValue' => $Input['ConfigTypeValue'], 'StatusID' => $Input['StatusID']));

            /* Delete Caching */
            if(in_array($ConfigTypeGUID, array('AndridAppUrl','AndroidAppVersion','IsAndroidAppUpdateMandatory'))){
                $this->cache->memcached->delete('AndroidAppVersion');
            }
        }
    }

    /*
      Description: Use to send OTP on mobile
     */

    function sendMobileSMS($SMSArray)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/sendotp.php?authkey=" . MSG91_AUTH_KEY . "&sender=" . MSG91_SENDER_ID . "&mobile=" . $SMSArray['PhoneNumber'] . "&otp=" . $SMSArray['Text'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }

    /*
      Description: Use to send SMS on mobile
    */

    function sendSMS($SMSArray)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(

            CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=".MSG91_SENDER_ID."&mobiles=" . $SMSArray['PhoneNumber'] . "&authkey=" . MSG91_AUTH_KEY . "&message=" . $SMSArray['Text'] . "&country=91",

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
      Description: 	Use to get app version details
     */
    function getAppVersionDetails()
    {
        $VersionData = $this->cache->memcached->get('AndroidAppVersion');
        if(empty($VersionData)){
            $Query = $this->db->query("SELECT ConfigTypeGUID,ConfigTypeDescprition,ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID IN ('AndridAppUrl','AndroidAppVersion','IsAndroidAppUpdateMandatory')");
            if ($Query->num_rows() > 0) {
                $VersionData = array();
                foreach ($Query->result_array() as $Value) {
                    $VersionData[$Value['ConfigTypeGUID']] = $Value['ConfigTypeValue'];
                }
                $this->cache->memcached->save('AndroidAppVersion',$VersionData, 3600 * 24); // Expire in every 24 
                return $VersionData;
            }
        }
        return $VersionData;
    }

    /*
      Description: 	Use to get dummy user names
     */
    function getDummyNames($Limit = 10)
    {
        $Query = $this->db->query("SELECT names FROM dummy_names LIMIT $Limit");
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        }
        return FALSE;
    }

    /* -----Third Party Crons----- */
    /* ------------------------------ */

    /*
      Description: To Excecute curl request
     */
    function ExecuteCurl($Url, $Params = '')
    {
        $Curl = curl_init($Url);
        if (!empty($Params)) {
            curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);
        }
        curl_setopt($Curl, CURLOPT_HEADER, 0);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, TRUE);
        $Response = curl_exec($Curl);
        curl_close($Curl);
        $Result = json_decode($Response);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $Response;
        } else {
            return gzdecode($Response);
        }
    }

    /*
      Description: To get access token
     */

    function getAccessToken()
    {
        $this->load->helper('file');
        return trim(preg_replace("/\r|\n/", "", (file_exists(SPORTS_FILE_PATH)) ? read_file(SPORTS_FILE_PATH) : $this->generateAccessToken()));
    }

    /*
      Description: To generate access token
     */

    function generateAccessToken()
    {
        /* For Sports Entity Api */
        if (CRICKET_SPORT_API_NAME == 'ENTITY') {
            $Response = json_decode($this->ExecuteCurl(SPORTS_API_URL_ENTITY . '/v2/auth/', array('access_key' => SPORTS_API_ACCESS_KEY_ENTITY, 'secret_key' => SPORTS_API_SECRET_KEY_ENTITY, 'extend' => 1)), true);
            if ($Response['status'] == 'ok')
                $AccessToken = $Response['response']['token'];
        }

        /* For Sports Cricket Api */
        if (CRICKET_SPORT_API_NAME == 'CRICKETAPI') {
            $Response = json_decode($this->ExecuteCurl(SPORTS_API_URL_CRICKETAPI . '/rest/v2/auth/', array('access_key' => SPORTS_API_ACCESS_KEY_CRICKETAPI, 'secret_key' => SPORTS_API_SECRET_KEY_CRICKETAPI, 'app_id' => SPORTS_API_APP_ID_CRICKETAPI, 'device_id' => SPORTS_API_DEVICE_ID_CRICKETAPI)), true);
            if ($Response['status'])
                $AccessToken = $Response['auth']['access_token'];
        }

        /* Update Access Token */
        $this->load->helper('file');
        write_file(SPORTS_FILE_PATH, $AccessToken, 'w');
        return trim(preg_replace("/\r|\n/", "", $AccessToken));
    }

    /*
      Description: To fetch sports api data
     */

    function callSportsAPI($ApiUrl)
    {
        $Response = json_decode($this->ExecuteCurl($ApiUrl . $this->getAccessToken()), true);
        if (@$Response['status'] == 'unauthorized' || @$Response['status_code'] == 403) {
            if (@$Response['status_msg'] == 'RequestLimitExceeds') { // API Calling Limit Exceeds

                /* Request Limit Exceeds Respone */
                log_message('ERROR', "Request Limit Exceeds");
                return TRUE;
            } else {

                /* Re-generate token */
                $Response = json_decode($this->ExecuteCurl($ApiUrl . $this->generateAccessToken()), true);
            }
        }
        return $Response;
    }

    /* -----Cricket Sports Crons----- */
    /* ------------------------------ */

    /*
      Description: To set cricket series data (Entity API)
     */
    function getSeriesLive_Cricket_Entity($CronID)
    {
        /* Update Existing Series Status */
        $this->db->query('UPDATE sports_series AS S, tbl_entity AS E SET E.StatusID = 6 WHERE S.SportsType = "Cricket" AND E.EntityID = S.SeriesID AND E.StatusID != 6 AND SeriesEndDate < "' . date('Y-m-d') . '"');
        $this->db->query('UPDATE sports_series SET AuctionDraftStatusID = 2 WHERE S.SportsType = "Cricket" AND SeriesStartDate <= "' . date('Y-m-d') . '"');
        $this->db->query('UPDATE sports_series SET AuctionDraftStatusID = 5 WHERE S.SportsType = "Cricket" AND SeriesEndDate < "' . date('Y-m-d') . '"');
        $SeriesData = $this->Sports_model->getSeries('SeriesIDLive,SeriesID', array('StatusID' => 2,'SportsType' => 'Cricket'), true, 0);
        foreach ($SeriesData['Data']['Records'] as $SeriesValue) {
            $SeriesStartDate = $this->db->query('SELECT CAST(MatchStartDateTime as DATE) as MatchStartDateTime FROM sports_matches WHERE SeriesID = ' . $SeriesValue['SeriesID'] . ' ORDER BY MatchStartDateTime ASC LIMIT 1')->row()->MatchStartDateTime;
            $SeriesEndDate = $this->db->query('SELECT CAST(MatchStartDateTime as DATE) as MatchStartDateTime FROM sports_matches WHERE SeriesID = ' . $SeriesValue['SeriesID'] . ' ORDER BY MatchStartDateTime DESC LIMIT 1')->row()->MatchStartDateTime;
            $this->db->where('SeriesID', $SeriesValue['SeriesID']);
            $this->db->limit(1);
            $this->db->update('sports_series', array('SeriesStartDate' => $SeriesStartDate, 'SeriesEndDate' => $SeriesEndDate));
        }
        $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/seasons/?token=');
        if (empty($Response['response']['items'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        $SeriesData = array();
        foreach ($Response['response']['items'] as $Value) {
            if (!in_array($Value['sid'], array(date('Y'), date('Y') . date('y') + 1, date('Y') - 1 . date('y'))))
                continue;

            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/' . $Value['competitions_url'] . '?per_page=50&token=');
            /* To get All Series Data */
            $SeriesIdsData = $this->db->query('SELECT GROUP_CONCAT(SeriesIDLive) AS SeriesIDsLive FROM sports_series WHERE SportsType = "Cricket"')->row()->SeriesIDsLive;
            $SeriesIDsLive = array();
            if ($SeriesIdsData) {
                $SeriesIDsLive = explode(",", $SeriesIdsData);
            }
            foreach ($Response['response']['items'] as $Value) {
                if (in_array($Value['cid'], $SeriesIDsLive))
                    continue;

                /* Add series to entity table and get EntityID. */
                $SeriesGUID = get_guid();
                $SeriesData[] = array_filter(array(
                    'SeriesID' => $this->Entity_model->addEntity($SeriesGUID, array("EntityTypeID" => 7, "StatusID" => 2)),
                    'SeriesGUID' => $SeriesGUID,
                    'SeriesIDLive' => $Value['cid'],
                    'SeriesName' => $Value['title'],
                    'SeriesStartDate' => $Value['datestart'],
                    'SeriesEndDate' => $Value['dateend']
                ));
            }
        }
        if (!empty($SeriesData)) {
            $this->db->insert_batch('sports_series', $SeriesData);
        }
    }

    /*
      Description: To set cricket matches data (Entity API)
     */

    function getMatchesLive_Cricket_Entity($CronID)
    {
        /* Get series data */
        $SeriesData = $this->Sports_model->getSeries('SeriesIDLive,SeriesID', array('StatusID' => 2, 'SeriesEndDate' => date('Y-m-d'),'SportsType' => 'Cricket'), true, 0);
        if (!$SeriesData) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        /* To get All Match Types */
        $MatchTypesData = $this->cache->memcached->get('MatchTypes');
        if(empty($MatchTypesData)){
            $MatchTypesData = $this->Sports_model->getMatchTypes();
            $this->cache->memcached->save('MatchTypes', $MatchTypesData, 3600 * 24); // Expire in every 24 hours
        }
        $MatchTypeIdsData = array_column($MatchTypesData, 'MatchTypeID', 'MatchTypeName');

        /* Get Live Matches Data */
        foreach ($SeriesData['Data']['Records'] as $SeriesValue) {
            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/competitions/' . $SeriesValue['SeriesIDLive'] . '/matches/?per_page=150&token=');
            if (empty($Response['response']['items']))
                continue;
            foreach ($Response['response']['items'] as $key => $Value) {

                /* Managae Teams */
                $PreSquad = $Value['pre_squad'];
                $Verified = $Value['verified'];
                $LocalTeam = $Value['teama'];
                $VisitorTeam = $Value['teamb'];
                $LocalTeamData = $VisitorTeamData = array();
                if ($VisitorTeam['name'] == "TBA")
                    continue;

                /* To check if local team is already exist */
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = ' . $LocalTeam['team_id'] . ' LIMIT 1');
                $TeamIDLocal = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamIDLocal) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamIDLocal = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $LocalTeamData[] = array(
                        'TeamID' => $TeamIDLocal,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $LocalTeam['team_id'],
                        'TeamName' => $LocalTeam['name'],
                        'TeamNameShort' => (!empty($LocalTeam['short_name'])) ? $LocalTeam['short_name'] : null,
                        'TeamFlag' => (!empty($LocalTeam['logo_url'])) ? $LocalTeam['logo_url'] : null
                    );
                }

                /* To check if visitor team is already exist */
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = ' . $VisitorTeam['team_id'] . ' LIMIT 1');
                $TeamIDVisitor = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamIDVisitor) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamIDVisitor = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $VisitorTeamData[] = array(
                        'TeamID' => $TeamIDVisitor,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $VisitorTeam['team_id'],
                        'TeamName' => $VisitorTeam['name'],
                        'TeamNameShort' => (!empty($VisitorTeam['short_name'])) ? $VisitorTeam['short_name'] : null,
                        'TeamFlag' => (!empty($VisitorTeam['logo_url'])) ? $VisitorTeam['logo_url'] : null
                    );
                }
                $TeamsData = array_merge($VisitorTeamData, $LocalTeamData);
                if (!empty($TeamsData)) {
                    $this->db->insert_batch('sports_teams', $TeamsData);
                }

                /* To check if match is already exist */
                $Query = $this->db->query('SELECT M.MatchID,E.StatusID FROM sports_matches M,tbl_entity E WHERE M.MatchID = E.EntityID AND M.MatchIDLive = ' . $Value['match_id'] . ' LIMIT 1');
                $MatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                if (!$MatchID) {
                    if (strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i', strtotime($Value['date_start'])))) {
                        continue;
                    }

                    /* Add matches to entity table and get EntityID. */
                    $MatchGUID = get_guid();
                    $MatchesAPIData = array(
                        'MatchID' => $this->Entity_model->addEntity($MatchGUID, array("EntityTypeID" => 8, "StatusID" => 1)),
                        'MatchGUID' => $MatchGUID,
                        'MatchIDLive' => $Value['match_id'],
                        'SeriesID' => $SeriesValue['SeriesID'],
                        'MatchTypeID' => $MatchTypeIdsData[$Value['format_str']],
                        'MatchNo' => $Value['subtitle'],
                        'MatchLocation' => $Value['venue']['location'],
                        'TeamIDLocal' => $TeamIDLocal,
                        'TeamIDVisitor' => $TeamIDVisitor,
                        'MatchStartDateTime' => date('Y-m-d H:i', strtotime($Value['date_start']))
                    );
                    $this->db->insert('sports_matches', $MatchesAPIData);
                } else {

                    if ($Query->row()->StatusID != 1)
                        continue; // Pending Match

                    /* Update Match Data */
                    $MatchesAPIData = array(
                        'MatchNo' => $Value['subtitle'],
                        'MatchLocation' => $Value['venue']['location'],
                        'TeamIDLocal' => $TeamIDLocal,
                        'TeamIDVisitor' => $TeamIDVisitor,
                        'MatchStartDateTime' => date('Y-m-d H:i', strtotime($Value['date_start'])),
                        'LastUpdatedOn' => date('Y-m-d H:i:s')
                    );
                    $this->db->where('MatchID', $MatchID);
                    $this->db->limit(1);
                    $this->db->update('sports_matches', $MatchesAPIData);
                }
            }
        }
    }

    /*
      Description: To set cricket matches data (Cricket API)
     */

    function getMatchesLive_Cricket_CricketApi($CronID)
    {
        /* Get Live Matches Data */
        foreach (array(date('Y-m'), date('Y-m', strtotime('+1 month'))) as $DateValue) {
            $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/schedule/?date=' . $DateValue . '&access_token=');
            if (!$Response['status']) {
                $this->db->where('CronID', $CronID);
                $this->db->limit(1);
                $this->db->update('log_cron', array('CronStatus' => 'Exit'));
                exit;
            }
            $LiveMatchesData = @$Response['data']['months'][0]['days'];
            if (empty($LiveMatchesData))
                continue;

            /* To get All Series Data */
            $SeriesIdsData = array();
            $SeriesData = $this->Sports_model->getSeries('SeriesIDLive,SeriesID', array('SportsType' => 'Cricket'), true, 0);
            if ($SeriesData) {
                $SeriesIdsData = array_column($SeriesData['Data']['Records'], 'SeriesID', 'SeriesIDLive');
            }

            /* To get All Match Types */
            $MatchTypesData = $this->cache->memcached->get('MatchTypes');
            if(empty($MatchTypesData)){
                $MatchTypesData = $this->Sports_model->getMatchTypes();
                $this->cache->memcached->save('MatchTypes', $MatchTypesData, 3600 * 24); // Expire in every 24 hours
            }
            $MatchTypeIdsData = array_column($MatchTypesData, 'MatchTypeID', 'MatchTypeNameCricketAPI');
            foreach ($LiveMatchesData as $key => $Value) {
                if (empty($Value['matches']))
                    continue;

                foreach ($Value['matches'] as $MatchValue) {

                    /* To check past matches */
                    if (strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso'])))) {
                        continue;
                    }

                    $this->db->trans_start();

                    /* Manage Series Data */
                    if (!isset($SeriesIdsData[$MatchValue['season']['key']])) {

                        /* Add series to entity table and get EntityID. */
                        $SeriesGUID = get_guid();
                        $SeriesID = $this->Entity_model->addEntity($SeriesGUID, array("EntityTypeID" => 7, "StatusID" => 2));
                        $SeriesData = array_filter(array(
                            'SeriesID' => $SeriesID,
                            'SeriesGUID' => $SeriesGUID,
                            'SeriesIDLive' => $MatchValue['season']['key'],
                            'SeriesName' => $MatchValue['season']['name']
                        ));

                        $this->db->insert('sports_series', $SeriesData);
                        $SeriesIdsData[$MatchValue['season']['key']] = $SeriesID;
                    } else {
                        $SeriesID = $SeriesIdsData[$MatchValue['season']['key']];
                    }

                    /* Manage Teams */
                    $LocalTeam = $MatchValue['teams']['a'];
                    $VisitorTeam = $MatchValue['teams']['b'];
                    $LocalTeamData = $VisitorTeamData = $TeamLiveIds = array();
                    if ($LocalTeam['key'] == 'tbc' || $VisitorTeam['key'] == 'tbc')
                        continue;

                    /* To check if local team is already exist */
                    $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $LocalTeam['key'] . '" LIMIT 1');
                    $TeamIDLocal = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                    if (!$TeamIDLocal) {

                        /* Add team to entity table and get EntityID. */
                        $TeamGUID = get_guid();
                        $TeamIDLocal = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                        $LocalTeamData[] = array(
                            'TeamID' => $TeamIDLocal,
                            'TeamGUID' => $TeamGUID,
                            'TeamIDLive' => $LocalTeam['key'],
                            'TeamName' => $LocalTeam['name'],
                            'TeamNameShort' => strtoupper($LocalTeam['key'])
                        );
                    }

                    /* To check if visitor team is already exist */
                    $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $VisitorTeam['key'] . '" LIMIT 1');
                    $TeamIDVisitor = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                    if (!$TeamIDVisitor) {

                        /* Add team to entity table and get EntityID. */
                        $TeamGUID = get_guid();
                        $TeamIDVisitor = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                        $VisitorTeamData[] = array(
                            'TeamID' => $TeamIDVisitor,
                            'TeamGUID' => $TeamGUID,
                            'TeamIDLive' => $VisitorTeam['key'],
                            'TeamName' => $VisitorTeam['name'],
                            'TeamNameShort' => strtoupper($VisitorTeam['key'])
                        );
                    }
                    $TeamsData = array_merge($VisitorTeamData, $LocalTeamData);
                    if (!empty($TeamsData)) {
                        $this->db->insert_batch('sports_teams', $TeamsData);
                    }

                    /* To check if match is already exist */
                    $Query = $this->db->query('SELECT M.MatchID,E.StatusID FROM sports_matches M,tbl_entity E WHERE M.MatchID = E.EntityID AND M.MatchIDLive = "' . $MatchValue['key'] . '" LIMIT 1');
                    $MatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                    if (!$MatchID) {

                        /* Add matches to entity table and get EntityID. */
                        $MatchGUID = get_guid();
                        $MatchStatusArr = array('completed' => 5, 'notstarted' => 1, 'started' => 2);
                        $MatchesAPIData = array(
                            'MatchID' => $this->Entity_model->addEntity($MatchGUID, array("EntityTypeID" => 8, "StatusID" => $MatchStatusArr[$MatchValue['status']])),
                            'MatchGUID' => $MatchGUID,
                            'MatchIDLive' => $MatchValue['key'],
                            'SeriesID' => $SeriesID,
                            'MatchTypeID' => $MatchTypeIdsData[$MatchValue['format']],
                            'MatchNo' => $MatchValue['related_name'],
                            'MatchLocation' => $MatchValue['venue'],
                            'TeamIDLocal' => $TeamIDLocal,
                            'TeamIDVisitor' => $TeamIDVisitor,
                            'MatchStartDateTime' => date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso']))
                        );
                        $this->db->insert('sports_matches', $MatchesAPIData);
                    } else {

                        if ($Query->row()->StatusID != 1)
                            continue; // Pending Match

                        /* Update Match Data */
                        $MatchesAPIData = array(
                            'MatchNo' => $MatchValue['related_name'],
                            'MatchLocation' => $MatchValue['venue'],
                            'TeamIDLocal' => $TeamIDLocal,
                            'TeamIDVisitor' => $TeamIDVisitor,
                            'MatchStartDateTime' => date('Y-m-d H:i', strtotime($MatchValue['start_date']['iso'])),
                            'LastUpdatedOn' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('MatchID', $MatchID);
                        $this->db->limit(1);
                        $this->db->update('sports_matches', $MatchesAPIData);
                    }
                
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    }
                }  
            }
        }
    }

    /*
      Description: To set cricket players data (Entity API)
     */
    function getPlayersLive_Cricket_Entity($CronID, $MatchID = "")
    {
        /* Get series data */
        if (!empty($MatchID)) {
            $MatchData = $this->Sports_model->getMatches('MatchID,MatchIDLive,SeriesIDLive,SeriesID', array('StatusID' => array(1), "MatchID" => $MatchID, "SportsType" => "Cricket"), true, 0);
        } else {
            $MatchData = $this->Sports_model->getMatches('MatchStartDateTime,MatchIDLive,MatchID,MatchType,SeriesIDLive,SeriesID,TeamIDLiveLocal,TeamIDLiveVisitor,LastUpdateDiff', array('StatusID' => array(1), 'CronFilter' => 'OneDayDiff', "SportsType" => "Cricket"), true, 1, 10);
        }
        if(empty($MatchData['Data']['Records'])) { 
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        /* Player Roles */
        $PlayerRolesArr = array('bowl' => 'Bowler', 'bat' => 'Batsman', 'wkbat' => 'WicketKeeper', 'wk' => 'WicketKeeper', 'all' => 'AllRounder');
        foreach ($MatchData['Data']['Records'] as $Value) {

            $MatchID = $Value['MatchID'];
            $SeriesID = $Value['SeriesID'];
            $this->cache->memcached->delete('UserTeamPlayers_' . $MatchID);
            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/competitions/' . $Value['SeriesIDLive'] . '/squads/' . $Value['MatchIDLive'] . '?token=');
            if (empty($Response['response']['squads']))
                continue;

            /* To check if any team is created */
            $TotalJoinedTeams = $this->db->query("SELECT COUNT(UserTeamName) TotalJoinedTeams FROM `sports_users_teams` WHERE `MatchID` = " . $MatchID)->row()->TotalJoinedTeams;
            foreach ($Response['response']['squads'] as $SquadsValue) {
                $TeamID = $SquadsValue['team_id'];
                $Players = $SquadsValue['players'];
                $TeamPlayersData = array();
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $TeamID . '" LIMIT 1');
                $TeamID = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (empty($TeamID)) {
                    /* Add team to entity table and get EntityID. */
                    $TeamDetails = $SquadsValue['team'];
                    $TeamGUID = get_guid();
                    $TeamID = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $TeamData = array_filter(array(
                        'TeamID' => $TeamID,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $SquadsValue['team_id'],
                        'TeamName' => $TeamDetails['title'],
                        'TeamNameShort' => strtoupper($TeamDetails['abbr'])
                    ));
                    $this->db->insert('sports_teams', $TeamData);
                }
                $this->db->trans_start();
                if (!empty($Players)) {
                    foreach ($Players as $Player) {
                        if (isset($Player['pid']) && !empty($Player['pid'])) {
                            /* To check if player is already exist */
                            $Query = $this->db->query('SELECT PlayerID FROM sports_players WHERE PlayerIDLive = ' . $Player['pid'] . ' LIMIT 1');
                            $PlayerID = ($Query->num_rows() > 0) ? $Query->row()->PlayerID : false;
                            if (!$PlayerID) {
                                /* Add players to entity table and get EntityID. */
                                $PlayerGUID = get_guid();
                                $PlayerID = $this->Entity_model->addEntity($PlayerGUID, array("EntityTypeID" => 10, "StatusID" => 2));
                                $PlayersAPIData = array(
                                    'PlayerID' => $PlayerID,
                                    'PlayerGUID' => $PlayerGUID,
                                    'PlayerIDLive' => $Player['pid'],
                                    'PlayerName' => $Player['title'],
                                    'PlayerSalary' => $Player['fantasy_player_rating'],
                                    'PlayerCountry' => ($Player['country']) ? strtoupper($Player['country']) : null,
                                    'PlayerBattingStyle' => ($Player['batting_style']) ? $Player['batting_style'] : null,
                                    'PlayerBowlingStyle' => ($Player['bowling_style']) ? $Player['bowling_style'] : null
                                );
                                $this->db->insert('sports_players', $PlayersAPIData);
                            } else {
                                $PlayersAPIData = array(
                                    'PlayerName' => $Player['title'],
                                    'PlayerSalary' => $Player['fantasy_player_rating'],
                                    'PlayerCountry' => ($Player['country']) ? strtoupper($Player['country']) : null,
                                    'PlayerBattingStyle' => ($Player['batting_style']) ? $Player['batting_style'] : null,
                                    'PlayerBowlingStyle' => ($Player['bowling_style']) ? $Player['bowling_style'] : null
                                );
                                $this->db->where('PlayerID', $PlayerID);
                                $this->db->update('sports_players', $PlayersAPIData);
                            }
                            $Query = $this->db->query('SELECT MatchID FROM sports_team_players WHERE PlayerID = ' . $PlayerID . ' AND SeriesID = ' . $SeriesID . ' AND TeamID = ' . $TeamID . ' AND MatchID =' . $MatchID . ' LIMIT 1');
                            $IsMatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                            if (!$IsMatchID) {
                                if (!empty($PlayerRolesArr[strtolower($Player['playing_role'])])) {
                                    $TeamPlayersData[] = array(
                                        'SeriesID' => $SeriesID,
                                        'MatchID' => $MatchID,
                                        'TeamID' => $TeamID,
                                        'PlayerID' => $PlayerID,
                                        'PlayerSalary' => $Player['fantasy_player_rating'],
                                        'IsPlaying' => "No",
                                        'PlayerRole' => $PlayerRolesArr[strtolower($Player['playing_role'])]
                                    );
                                }
                            } else {
                                if ($TotalJoinedTeams > 0) {
                                    continue;
                                }
                                /* Update Fantasy Points */
                                $this->db->where(array('SeriesID' => $SeriesID,'MatchID' => $MatchID,'TeamID' => $TeamID,'PlayerID' => $PlayerID));
                                $this->db->limit(1);
                                $this->db->update('sports_team_players', array('PlayerSalary' => $Player['fantasy_player_rating'], 'PlayerRole' => $PlayerRolesArr[strtolower($Player['playing_role'])]));
                            }
                        }
                    }
                }

                if (!empty($TeamPlayersData)) {
                    $this->db->insert_batch('sports_team_players', $TeamPlayersData);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    return false;
                }
            }
        }
    }

    /*
      Description: To set cricket players data (Cricket API)
    */
    function getPlayersLive_Cricket_CricketApi($CronID)
    {
        /* Get matches data */
        $MatchesData = $this->Sports_model->getMatches('MatchStartDateTime,MatchIDLive,MatchID,MatchType,SeriesIDLive,SeriesID,TeamIDLiveLocal,TeamIDLiveVisitor,LastUpdateDiff', array('StatusID' => array(1, 2),'SportsType' => 'Cricket'), true, 1, 15);
        if (empty($MatchesData['Data']['Records'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }

        foreach ($MatchesData['Data']['Records'] as $Value) {

            /* Manage All Player Id's */
            $PlayersData = array();

            /* Get Both Teams */
            $TeamsArr = array($Value['TeamIDLiveLocal'] => $Value['SeriesIDLive'] . "_" . $Value['TeamIDLiveLocal'], $Value['TeamIDLiveVisitor'] => $Value['SeriesIDLive'] . "_" . $Value['TeamIDLiveVisitor']);
            foreach ($TeamsArr as $TeamKey => $TeamValue) {
                $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/season/' . $Value['SeriesIDLive'] . '/team/' . $TeamValue . '/?access_token=');

                /* Manage CRON API Response */
                if (empty($Response['data']['players_key']))
                    continue;

                $this->db->trans_start();

                /* To check if visitor team is already exist */
                $IsNewTeam = false;
                $Query = $this->db->query('SELECT TeamID FROM sports_teams WHERE TeamIDLive = "' . $TeamKey . '" LIMIT 1');
                $TeamID = ($Query->num_rows() > 0) ? $Query->row()->TeamID : false;
                if (!$TeamID) {

                    /* Add team to entity table and get EntityID. */
                    $TeamGUID = get_guid();
                    $TeamID = $this->Entity_model->addEntity($TeamGUID, array("EntityTypeID" => 9, "StatusID" => 2));
                    $TeamData = array_filter(array(
                        'TeamID' => $TeamID,
                        'TeamGUID' => $TeamGUID,
                        'TeamIDLive' => $TeamKey,
                        'TeamName' => $Response['data']['name'],
                        'TeamNameShort' => strtoupper($TeamKey)
                    ));
                    $IsNewTeam = true;
                    $this->db->insert('sports_teams', $TeamData);

                    /* Add Into MongoDB */
                    $TeamsDataMongo = array(
                        '_id'           => $TeamGUID,
                        'TeamID'        => (int) $TeamID,
                        'TeamName'      => $Response['data']['name'],
                        'TeamNameShort' => strtoupper($TeamKey),
                        'TeamFlag'      => 'team.png'
                    );
                    $this->fantasydb->sports_teams->insertOne($TeamsDataMongo);
                }
                if (!$IsNewTeam) {

                    /* To get all match ids */
                    $Query = $this->db->query('SELECT MatchID FROM `sports_matches` WHERE `SeriesID` = ' . $Value['SeriesID'] . ' AND (`TeamIDLocal` = ' . $TeamID . ' OR `TeamIDVisitor` = ' . $TeamID . ')');
                    $MatchIds = ($Query->num_rows() > 0) ? array_column($Query->result_array(), 'MatchID') : array();
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    return false;
                }

                /* Insert All Players */
                foreach ($Response['data']['players_key'] as $PlayerIDLive) {

                    $this->db->trans_start();

                    /* To check if player is already exist */
                    $Query = $this->db->query('SELECT PlayerID FROM sports_players WHERE PlayerIDLive = "' . $PlayerIDLive . '" LIMIT 1');
                    $PlayerID = ($Query->num_rows() > 0) ? $Query->row()->PlayerID : false;
                    if (!$PlayerID) {

                        /* Add players to entity table and get EntityID. */
                        $PlayerGUID = get_guid();
                        $PlayerID = $this->Entity_model->addEntity($PlayerGUID, array("EntityTypeID" => 10, "StatusID" => 2));
                        $PlayersAPIData = array(
                            'PlayerID' => $PlayerID,
                            'PlayerGUID' => $PlayerGUID,
                            'PlayerIDLive' => $PlayerIDLive,
                            'PlayerName' => $Response['data']['players'][$PlayerIDLive]['name'],
                            'PlayerBattingStyle' => @$Response['data']['players'][$PlayerIDLive]['batting_style'][0],
                            'PlayerBowlingStyle' => @$Response['data']['players'][$PlayerIDLive]['bowling_style'][0],
                        );
                        $this->db->insert('sports_players', $PlayersAPIData);

                        /* Add Into MongoDB */
                        $PlayersDataMongo = array(
                            '_id'         => $PlayerGUID,
                            'PlayerID'    => (int) $PlayerID,
                            'PlayerName'  => $Response['data']['players'][$PlayerIDLive]['name'],
                            'PlayerPic'   => 'player.png'
                        );
                        $this->fantasydb->sports_players->insertOne($PlayersDataMongo);
                    }
                    $PlayersData[$PlayerIDLive] = $PlayerID;

                    /* To check If match player is already exist */
                    if (!$IsNewTeam && !empty($MatchIds)) {
                        $TeamPlayersData = array();
                        foreach ($MatchIds as $MatchID) {
                            $this->cache->memcached->delete('UserTeamPlayers_' . $MatchID);
                            $Query = $this->db->query('SELECT MatchID FROM sports_team_players WHERE PlayerID = ' . $PlayerID . ' AND SeriesID = ' . $Value['SeriesID'] . ' AND TeamID = ' . $TeamID . ' AND MatchID =' . $MatchID . ' LIMIT 1');
                            $IsMatchID = ($Query->num_rows() > 0) ? $Query->row()->MatchID : false;
                            if (!$IsMatchID) {

                                /* Get Player Role */
                                $Keeper = $Response['data']['players'][$PlayerIDLive]['identified_roles']['keeper'];
                                $Batsman = $Response['data']['players'][$PlayerIDLive]['identified_roles']['batsman'];
                                $Bowler = $Response['data']['players'][$PlayerIDLive]['identified_roles']['bowler'];
                                $PlayerRole = ($Keeper == 1) ? 'WicketKeeper' : (($Batsman == 1 && $Bowler == 1) ? 'AllRounder' : ((empty($Batsman) && $Bowler == 1) ? 'Bowler' : ((empty($Bowler) && $Batsman == 1) ? 'Batsman' : '')));
                                $TeamPlayersData[] = array(
                                    'SeriesID' => $Value['SeriesID'],
                                    'MatchID' => $MatchID,
                                    'TeamID' => $TeamID,
                                    'PlayerID' => $PlayerID,
                                    'PlayerRole' => $PlayerRole
                                );
                            }
                        }
                        if (!empty($TeamPlayersData)) {
                            $this->db->insert_batch('sports_team_players', $TeamPlayersData);
                        }
                    }

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    }
                }
            }

           /* To check if any team is created */
           $TotalJoinedTeams = $this->db->query("SELECT COUNT(UserTeamType) TotalJoinedTeams FROM `sports_users_teams` WHERE `MatchID` = ".$Value['MatchID'])->row()->TotalJoinedTeams;
           if($TotalJoinedTeams == 0){

               /* Get Player Credit Points */
               $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v3/fantasy-match-credits/' . $Value['MatchIDLive'] . '/?access_token=');
               if (!empty($Response['data']['fantasy_points'])) {
                   foreach ($Response['data']['fantasy_points'] as $PlayerValue) {
                       $this->db->update('sports_team_players', array('PlayerSalary' => $PlayerValue['credit_value']), array('IsAdminUpdate' => 'No', 'MatchID' => $Value['MatchID'],'PlayerID' => $PlayersData[$PlayerValue['player']]));
                   }
               }
           }

            /* Update Last Updated Status */
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('LastUpdatedOn' => date('Y-m-d H:i:s')));
        }
    }

    /*
      Description: To set cricket player stats (Entity API)
     */

    function getPlayerStatsLive_Cricket_Entity($CronID)
    {
        /* To get All Player Stats Data */
        $MatchData = $this->Sports_model->getMatches('MatchID,MatchIDLive,SeriesIDLive,SeriesID', array('StatusID' => 5, 'PlayerStatsUpdate' => 'No', 'MatchCompleteDateTime' => date('Y-m-d H:i:s'),'SportsType' => 'Cricket'), true, 0);
        if (empty($MatchData['Data']['Records'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        foreach ($MatchData['Data']['Records'] as $Value) {
            $PlayerData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID', array('MatchID' => $Value['MatchID']), true, 0);
            if (empty($PlayerData))
                continue;

            foreach ($PlayerData['Data']['Records'] as $Player) {
                $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/players/' . $Player['PlayerIDLive'] . '/stats/?token=');
                $BattingStats = (!empty($Response['response']['batting'])) ? str_replace(array('odi', 't20', 't20i', 'lista', 'firstclass', 'test', 'others', 'womant20', 'womanodi', 'run4', 'run6', 'runs', 'balls', 'run50', 'notout', 'run100', 'strike', 'average', 'catches', 'highest', 'innings', 'matches', 'stumpings', 'match_id', 'inning_id'), array('ODI', 'T20', 'T20I', 'ListA', 'FirstClass', 'Test', 'Others', 'WomanT20', 'WomanODI', 'Fours', 'Sixes', 'Runs', 'Balls', 'Fifties', 'NotOut', 'Hundreds', 'StrikeRate', 'Average', 'Catches', 'HighestScore', 'Innings', 'Matches', 'Stumpings', 'MatchID', 'InningID'), json_encode($Response['response']['batting'])) : null;
                $BowlingStats = (!empty($Response['response']['bowling'])) ? str_replace(array('odi', 't20', 't20i', 'lista', 'firstclass', 'test', 'others', 'womant20', 'womanodi', 'match_id', 'inning_id', 'innings', 'matches', 'balls', 'overs', 'runs', 'wickets', 'bestinning', 'bestmatch', 'econ', 'average', 'strike', 'wicket4i', 'wicket5i', 'wicket10m'), array('ODI', 'T20', 'T20I', 'ListA', 'FirstClass', 'Test', 'Others', 'WomanT20', 'WomanODI', 'MatchID', 'InningID', 'Innings', 'Matches', 'Balls', 'Overs', 'Runs', 'Wickets', 'BestInning', 'BestMatch', 'Economy', 'Average', 'StrikeRate', 'FourPlusWicketsInSingleInning', 'FivePlusWicketsInSingleInning', 'TenPlusWicketsInSingleInning'), json_encode($Response['response']['bowling'])) : null;
                /* Update Player Stats */
                $PlayerStats = array(
                    'PlayerBattingStats' => $BattingStats,
                    'PlayerBowlingStats' => $BowlingStats,
                    'LastUpdatedOn' => date('Y-m-d H:i:s')
                );
                $this->db->where('PlayerID', $Player['PlayerID']);
                $this->db->limit(1);
                $this->db->update('sports_players', $PlayerStats);
            }

            $MatchUpdate = array(
                'PlayerStatsUpdate' => "Yes",
            );
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', $MatchUpdate);
        }
    }

    /*
      Description: To set cricket player stats (Cricket API)
     */

    function getPlayerStatsLive_Cricket_CricketApi($CronID)
    {
        /* To get All Player Stats Data */
        $MatchData = $this->Sports_model->getMatches('MatchID,MatchIDLive,SeriesIDLive,SeriesID,MatchStartDateTime', array('StatusID' => 5, 'PlayerStatsUpdate' => 'No', 'MatchCompleteDateTime' => date('Y-m-d H:i:s'),'SportsType' => 'Cricket'), true, 0);
        if (empty($MatchData['Data']['Records'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        foreach ($MatchData['Data']['Records'] as $Value) {
            $PlayerData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID', array('MatchID' => $Value['MatchID']), true, 0);
            if (empty($PlayerData))
                continue;

            foreach ($PlayerData['Data']['Records'] as $Value) {

                /* Call Player Stats API */
                $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/player/' . $Value['PlayerIDLive'] . '/league/icc/stats/?access_token=');

                /* Manage Batting Stats */
                $BattingStats = new stdClass();
                $BowlingStats = new stdClass();

                /* Test Batting Stats */
                $BattingStats->Test = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['test']['batting']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['test']['batting']['innings'],
                    'NotOut' => @$Response['data']['player']['stats']['test']['batting']['not_outs'],
                    'Runs' => @$Response['data']['player']['stats']['test']['batting']['runs'],
                    'Balls' => @$Response['data']['player']['stats']['test']['batting']['balls'],
                    'HighestScore' => @$Response['data']['player']['stats']['test']['batting']['high_score'],
                    'Hundreds' => @$Response['data']['player']['stats']['test']['batting']['hundreds'],
                    'Fifties' => @$Response['data']['player']['stats']['test']['batting']['fifties'],
                    'Fours' => @$Response['data']['player']['stats']['test']['batting']['fours'],
                    'Sixes' => @$Response['data']['player']['stats']['test']['batting']['sixes'],
                    'Average' => @$Response['data']['player']['stats']['test']['batting']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['test']['batting']['strike_rate'],
                    'Catches' => @$Response['data']['player']['stats']['test']['fielding']['catches'],
                    'Stumpings' => @$Response['data']['player']['stats']['test']['fielding']['stumpings']
                );
                /* ODI Batting Stats */
                $BattingStats->ODI = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['one-day']['batting']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['one-day']['batting']['innings'],
                    'NotOut' => @$Response['data']['player']['stats']['one-day']['batting']['not_outs'],
                    'Runs' => @$Response['data']['player']['stats']['one-day']['batting']['runs'],
                    'Balls' => @$Response['data']['player']['stats']['one-day']['batting']['balls'],
                    'HighestScore' => @$Response['data']['player']['stats']['one-day']['batting']['high_score'],
                    'Hundreds' => @$Response['data']['player']['stats']['one-day']['batting']['hundreds'],
                    'Fifties' => @$Response['data']['player']['stats']['one-day']['batting']['fifties'],
                    'Fours' => @$Response['data']['player']['stats']['one-day']['batting']['fours'],
                    'Sixes' => @$Response['data']['player']['stats']['one-day']['batting']['sixes'],
                    'Average' => @$Response['data']['player']['stats']['one-day']['batting']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['one-day']['batting']['strike_rate'],
                    'Catches' => @$Response['data']['player']['stats']['one-day']['fielding']['catches'],
                    'Stumpings' => @$Response['data']['player']['stats']['one-day']['fielding']['stumpings']
                );
                /* T20 Batting Stats */
                $BattingStats->T20 = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['t20']['batting']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['t20']['batting']['innings'],
                    'NotOut' => @$Response['data']['player']['stats']['t20']['batting']['not_outs'],
                    'Runs' => @$Response['data']['player']['stats']['t20']['batting']['runs'],
                    'Balls' => @$Response['data']['player']['stats']['t20']['batting']['balls'],
                    'HighestScore' => @$Response['data']['player']['stats']['t20']['batting']['high_score'],
                    'Hundreds' => @$Response['data']['player']['stats']['t20']['batting']['hundreds'],
                    'Fifties' => @$Response['data']['player']['stats']['t20']['batting']['fifties'],
                    'Fours' => @$Response['data']['player']['stats']['t20']['batting']['fours'],
                    'Sixes' => @$Response['data']['player']['stats']['t20']['batting']['sixes'],
                    'Average' => @$Response['data']['player']['stats']['t20']['batting']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['t20']['batting']['strike_rate'],
                    'Catches' => @$Response['data']['player']['stats']['t20']['fielding']['catches'],
                    'Stumpings' => @$Response['data']['player']['stats']['t20']['fielding']['stumpings']
                );

                /* Test Bowling Stats */
                $BowlingStats->Test = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['test']['bowling']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['test']['bowling']['innings'],
                    'Balls' => @$Response['data']['player']['stats']['test']['bowling']['balls'],
                    'Overs' => "",
                    'Runs' => @$Response['data']['player']['stats']['test']['bowling']['runs'],
                    'Wickets' => @$Response['data']['player']['stats']['test']['bowling']['wickets'],
                    'BestInning' => @$Response['data']['player']['stats']['test']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['test']['bowling']['best_innings']['runs'],
                    'BestMatch' => "",
                    'Economy' => @$Response['data']['player']['stats']['test']['bowling']['economy'],
                    'Average' => @$Response['data']['player']['stats']['test']['bowling']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['test']['bowling']['strike_rate'],
                    'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['four_wickets'],
                    'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['five_wickets'],
                    'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['test']['bowling']['ten_wickets']
                );

                /* ODI Bowling Stats */
                $BowlingStats->ODI = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['one-day']['bowling']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['one-day']['bowling']['innings'],
                    'Balls' => @$Response['data']['player']['stats']['one-day']['bowling']['balls'],
                    'Overs' => "",
                    'Runs' => @$Response['data']['player']['stats']['one-day']['bowling']['runs'],
                    'Wickets' => @$Response['data']['player']['stats']['one-day']['bowling']['wickets'],
                    'BestInning' => @$Response['data']['player']['stats']['one-day']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['one-day']['bowling']['best_innings']['runs'],
                    'BestMatch' => "",
                    'Economy' => @$Response['data']['player']['stats']['one-day']['bowling']['economy'],
                    'Average' => @$Response['data']['player']['stats']['one-day']['bowling']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['one-day']['bowling']['strike_rate'],
                    'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['four_wickets'],
                    'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['five_wickets'],
                    'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['one-day']['bowling']['ten_wickets']
                );

                /* T20 Bowling Stats */
                $BowlingStats->T20 = (object)array(
                    'MatchID' => 0,
                    'InningID' => 0,
                    'Matches' => @$Response['data']['player']['stats']['t20']['bowling']['matches'],
                    'Innings' => @$Response['data']['player']['stats']['t20']['bowling']['innings'],
                    'Balls' => @$Response['data']['player']['stats']['t20']['bowling']['balls'],
                    'Overs' => "",
                    'Runs' => @$Response['data']['player']['stats']['t20']['bowling']['runs'],
                    'Wickets' => @$Response['data']['player']['stats']['t20']['bowling']['wickets'],
                    'BestInning' => @$Response['data']['player']['stats']['t20']['bowling']['best_innings']['wickets'] . '/' . @$Response['data']['player']['stats']['t20']['bowling']['best_innings']['runs'],
                    'BestMatch' => "",
                    'Economy' => @$Response['data']['player']['stats']['t20']['bowling']['economy'],
                    'Average' => @$Response['data']['player']['stats']['t20']['bowling']['average'],
                    'StrikeRate' => @$Response['data']['player']['stats']['t20']['bowling']['strike_rate'],
                    'FourPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['four_wickets'],
                    'FivePlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['five_wickets'],
                    'TenPlusWicketsInSingleInning' => @$Response['data']['player']['stats']['t20']['bowling']['ten_wickets']
                );

                /* Update Player Stats */
                $PlayerStats = array(
                    'PlayerBattingStats' => json_encode($BattingStats),
                    'PlayerBowlingStats' => json_encode($BowlingStats),
                    'LastUpdatedOn' => date('Y-m-d H:i:s')
                );
                $this->db->where('PlayerID', $Value['PlayerID']);
                $this->db->limit(1);
                $this->db->update('sports_players', $PlayerStats);
            }

            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('PlayerStatsUpdate' => 'Yes'));
        }
    }

    /*
      Description: To get cricket match live score (Entity API)
     */

    function getMatchScoreLive_Cricket_Entity($CronID)
    {
        /* Get Live Matches Data */
        $LiveMatches = $this->Sports_model->getMatches('MatchIDLive,MatchID,StatusID', array('Filter' => 'Yesterday', 'StatusID' => array(1, 2, 10),'SportsType' => 'Cricket'), true, 1, 20);
        if (empty($LiveMatches['Data']['Records'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit'));
            exit;
        }
        $MatchStatus = array("live" => 2, "abandoned" => 8, "cancelled" => 3, "no result" => 9);
        $ContestStatus = array("live" => 2, "abandoned" => 5, "cancelled" => 3, "no result" => 5);
        $InningsStatus = array(1 => 'scheduled', 2 => 'completed', 3 => 'live', 4 => 'abandoned');

        foreach ($LiveMatches['Data']['Records'] as $Value) {
            $Response = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/matches/' . $Value['MatchIDLive'] . '/scorecard/?token=');
            if (!empty($Response)) {
                if ($Response['status'] == "ok" && !empty($Response['response'])) {
                    $MatchStatusLive = strtolower($Response['response']['status_str']);
                    $MatchStatusLiveCheck = $Response['response']['status'];
                    $GameState = $Response['response']['game_state'];
                    $Verified = $Response['response']['verified'];
                    $PreSquad = $Response['response']['pre_squad'];
                    $StatusNote = strtolower($Response['response']['status_note']);
                    if ($GameState != 7 || $GameState != 6) {
                        if ($MatchStatusLiveCheck == 2 || $MatchStatusLiveCheck == 3) {
                            /** set is playing player 22 * */
                            $ResponsePlayerSquad = $this->callSportsAPI(SPORTS_API_URL_ENTITY . '/v2/matches/' . $Value['MatchIDLive'] . '/squads/?token=');
                            if (!empty($ResponsePlayerSquad)) {
                                if ($ResponsePlayerSquad['status'] == 'ok') {
                                    $squadTeamA = $ResponsePlayerSquad['response']['teama']['squads'];
                                    $squadTeamB = $ResponsePlayerSquad['response']['teamb']['squads'];
                                    $PlayingPlayerIDs = array();
                                    foreach ($squadTeamA as $aRows) {
                                        if ($aRows['playing11'] == 'true') {
                                            $PlayingPlayerIDs[] = $aRows['player_id'];
                                        }
                                    }
                                    foreach ($squadTeamB as $bRows) {
                                        if ($bRows['playing11'] == 'true') {
                                            $PlayingPlayerIDs[] = $bRows['player_id'];
                                        }
                                    }
                                    if (count($PlayingPlayerIDs) > 20) {
                                        $PlayersIdsData = array();
                                        $PlayersData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID', array('MatchID' => $Value['MatchID']), true, 0);
                                        if ($PlayersData) {
                                            $PlayersIdsData = array_column($PlayersData['Data']['Records'], 'PlayerID', 'PlayerIDLive');
                                        }
                                        foreach ($PlayingPlayerIDs as $IsPlayer) {
                                            $this->db->where('MatchID', $Value['MatchID']);
                                            $this->db->where('PlayerID', $PlayersIdsData[$IsPlayer]);
                                            $this->db->limit(1);
                                            $this->db->update('sports_team_players', array('IsPlaying' => "Yes"));
                                        }
                                    }
                                }
                            }

                            if ($MatchStatusLive == 'scheduled' || $MatchStatusLive == 'rescheduled')
                                continue;

                            if (empty($Response['response']['players']))
                                continue;

                            $LivePlayersData = array_column($Response['response']['players'], 'title', 'pid');
                            $MatchScoreDetails = $InningsData = array();
                            $MatchScoreDetails['StatusLive'] = $Response['response']['status_str'];
                            $MatchScoreDetails['StatusNote'] = $Response['response']['status_note'];
                            $MatchScoreDetails['TeamScoreLocal'] = array('Name' => $Response['response']['teama']['name'], 'ShortName' => $Response['response']['teama']['short_name'], 'LogoURL' => $Response['response']['teama']['logo_url'], 'Scores' => @$Response['response']['teama']['scores'], 'Overs' => @$Response['response']['teama']['overs']);
                            $MatchScoreDetails['TeamScoreVisitor'] = array('Name' => $Response['response']['teamb']['name'], 'ShortName' => $Response['response']['teamb']['short_name'], 'LogoURL' => $Response['response']['teamb']['logo_url'], 'Scores' => @$Response['response']['teamb']['scores'], 'Overs' => @$Response['response']['teamb']['overs']);
                            $MatchScoreDetails['MatchVenue'] = @$Response['response']['venue']['name'] . ", " . $Response['response']['venue']['location'];
                            $MatchScoreDetails['Result'] = @$Response['response']['result'];
                            $MatchScoreDetails['Toss'] = @$Response['response']['toss']['text'];
                            $MatchScoreDetails['ManOfTheMatchPlayer'] = @$Response['response']['man_of_the_match']['name'];
                            foreach ($Response['response']['innings'] as $InningsValue) {
                                $BatsmanData = $BowlersData = $FielderData = $AllPlayingXI = array();

                                /* Manage Batsman Data */
                                foreach ($InningsValue['batsmen'] as $BatsmenValue) {
                                    $BatsmanData[] = array(
                                        'Name' => @$LivePlayersData[$BatsmenValue['batsman_id']],
                                        'PlayerIDLive' => $BatsmenValue['batsman_id'],
                                        'Role' => $BatsmenValue['role'],
                                        'Runs' => $BatsmenValue['runs'],
                                        'BallsFaced' => $BatsmenValue['balls_faced'],
                                        'Fours' => $BatsmenValue['fours'],
                                        'Sixes' => $BatsmenValue['sixes'],
                                        'HowOut' => $BatsmenValue['how_out'],
                                        'IsPlaying' => ($BatsmenValue['how_out'] == 'Not out') ? 'Yes' : 'No',
                                        'StrikeRate' => $BatsmenValue['strike_rate']
                                    );
                                    $AllPlayingXI[$BatsmenValue['batsman_id']]['batting'] = array(
                                        'Name' => @$LivePlayersData[$BatsmenValue['batsman_id']],
                                        'PlayerIDLive' => $BatsmenValue['batsman_id'],
                                        'Role' => $BatsmenValue['role'],
                                        'Runs' => $BatsmenValue['runs'],
                                        'BallsFaced' => $BatsmenValue['balls_faced'],
                                        'Fours' => $BatsmenValue['fours'],
                                        'Sixes' => $BatsmenValue['sixes'],
                                        'HowOut' => $BatsmenValue['how_out'],
                                        'IsPlaying' => ($BatsmenValue['how_out'] == 'Not out') ? 'Yes' : 'No',
                                        'StrikeRate' => $BatsmenValue['strike_rate']
                                    );
                                }

                                /* Manage Bowler Data */
                                foreach ($InningsValue['bowlers'] as $BowlersValue) {
                                    $BowlersData[] = array(
                                        'Name' => @$LivePlayersData[$BowlersValue['bowler_id']],
                                        'PlayerIDLive' => $BowlersValue['bowler_id'],
                                        'Overs' => $BowlersValue['overs'],
                                        'Maidens' => $BowlersValue['maidens'],
                                        'RunsConceded' => $BowlersValue['runs_conceded'],
                                        'Wickets' => $BowlersValue['wickets'],
                                        'NoBalls' => $BowlersValue['noballs'],
                                        'Wides' => $BowlersValue['wides'],
                                        'Economy' => $BowlersValue['econ']
                                    );
                                    $AllPlayingXI[$BowlersValue['bowler_id']]['bowling'] = array(
                                        'Name' => @$LivePlayersData[$BowlersValue['bowler_id']],
                                        'PlayerIDLive' => $BowlersValue['bowler_id'],
                                        'Overs' => $BowlersValue['overs'],
                                        'Maidens' => $BowlersValue['maidens'],
                                        'RunsConceded' => $BowlersValue['runs_conceded'],
                                        'Wickets' => $BowlersValue['wickets'],
                                        'NoBalls' => $BowlersValue['noballs'],
                                        'Wides' => $BowlersValue['wides'],
                                        'Economy' => $BowlersValue['econ']
                                    );
                                }

                                /* Manage Fielder Data */
                                foreach ($InningsValue['fielder'] as $FielderValue) {
                                    $FielderData[] = array(
                                        'Name' => $FielderValue['fielder_name'],
                                        'PlayerIDLive' => $FielderValue['fielder_id'],
                                        'Catches' => $FielderValue['catches'],
                                        'RunOutThrower' => $FielderValue['runout_thrower'],
                                        'RunOutCatcher' => $FielderValue['runout_catcher'],
                                        'RunOutDirectHit' => $FielderValue['runout_direct_hit'],
                                        'Stumping' => $FielderValue['stumping']
                                    );
                                    $AllPlayingXI[$FielderValue['fielder_id']]['fielding'] = array(
                                        'Name' => $FielderValue['fielder_name'],
                                        'PlayerIDLive' => $FielderValue['fielder_id'],
                                        'Catches' => $FielderValue['catches'],
                                        'RunOutThrower' => $FielderValue['runout_thrower'],
                                        'RunOutCatcher' => $FielderValue['runout_catcher'],
                                        'RunOutDirectHit' => $FielderValue['runout_direct_hit'],
                                        'Stumping' => $FielderValue['stumping']
                                    );
                                }

                                $InningsData[] = array(
                                    'Name' => $InningsValue['name'],
                                    'ShortName' => $InningsValue['short_name'],
                                    'Scores' => $InningsValue['scores'],
                                    'Status' => $InningsStatus[$InningsValue['status']],
                                    'ScoresFull' => $InningsValue['scores_full'],
                                    'BatsmanData' => $BatsmanData,
                                    'BowlersData' => $BowlersData,
                                    'FielderData' => $FielderData,
                                    'AllPlayingData' => $AllPlayingXI,
                                    'ExtraRuns' => array('Byes' => $InningsValue['extra_runs']['byes'], 'LegByes' => $InningsValue['extra_runs']['legbyes'], 'Wides' => $InningsValue['extra_runs']['wides'], 'NoBalls' => $InningsValue['extra_runs']['noballs'])
                                );
                            }
                            $MatchScoreDetails['Innings'] = $InningsData;

                            $MatchCompleteDateTime = date('Y-m-d H:i:s', strtotime("+2 hours"));
                            /* Update Match Data */
                            $this->db->trans_start();

                            $this->db->where('MatchID', $Value['MatchID']);
                            $this->db->limit(1);
                            $this->db->update('sports_matches', array('MatchScoreDetails' => json_encode($MatchScoreDetails), 'MatchCompleteDateTime' => $MatchCompleteDateTime));

                            $this->Sports_model->getPlayerPointsCricket(0, $Value['MatchID']);

                            if ($Value['StatusID'] != 2) {
                                /* Update Contest Status */
                                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 2 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . '  AND E.StatusID != 3');

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 2));
                            }
                            if (strtolower($MatchStatusLive) == "completed" && $StatusNote != "abandoned") {

                                $this->Sports_model->getPlayerPointsCricket(0, $Value['MatchID']);

                                $this->Sports_model->getJoinedContestPlayerPointsCricket($CronID, array(2), $Value['MatchID']);

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 10));
                                if ($Verified == "true") {
                                    /* Update Contest Status */
                                    $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 5 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . ' AND E.StatusID != 3');

                                    /* Update Match Status */
                                    $this->db->where('EntityID', $Value['MatchID']);
                                    $this->db->limit(1);
                                    $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 5));
                                }
                            }

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                                return false;
                            }
                        } else {
                            if ($StatusNote == 'no result') {
                                $MatchStatusLive = 'no result';
                            }
                            if ($MatchStatusLiveCheck == 4) {
                                $MatchStatusLive = 'abandoned';
                            }
                            if (strpos($StatusNote, 'abandoned') !== false) {
                                $MatchStatusLive = 'abandoned';
                            }
                            if (strpos($StatusNote, 'scheduled') !== false) {
                                $MatchStatusLive = 'scheduled';
                            }
                            $this->db->trans_start();
                            if ($MatchStatusLiveCheck == 4) {
                                /* Update Contest Status */
                                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 3 WHERE C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID'] . ' AND E.StatusID != 3');

                                /* Update Match Status */
                                $this->db->where('EntityID', $Value['MatchID']);
                                $this->db->limit(1);
                                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 8));
                            }
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                                return false;
                            }
                        }
                    }
                }
            }
        }
    }

    /*
      Description: To get cricket match live score (Cricket API)
     */

    function getMatchScoreLive_Cricket_CricketApi($CronID)
    {
        /* Get Live Matches Data */
        $LiveMatches = $this->Sports_model->getMatches('MatchIDLive,MatchID,MatchStartDateTime,Status', array('Filter'=> 'Yesterday','StatusID' => array(1,2,10),'SportsType' => 'Cricket'), true, 1, 20);
        if (empty($LiveMatches['Data']['Records'])) {
            $this->db->where('CronID', $CronID);
            $this->db->limit(1);
            $this->db->update('log_cron', array('CronStatus' => 'Exit', 'CronResponse' => $this->db->last_query()));
            exit;
        }

        $MatchStatus   = array('completed' => 5, "started" => 2, "notstarted" => 9);
        $ContestStatus = array('completed' => 5, "started" => 2, "notstarted" => 9, "Abandoned" => 5, "Cancelled" => 3, "No Result" => 5);
        $InningsStatus = array(1 => 'Scheduled', 2 => 'Completed', 3 => 'Live', 4 => 'Abandoned');
        foreach ($LiveMatches['Data']['Records'] as $Value) {

            if ($Value['Status'] == 'Pending' && (strtotime(date('Y-m-d H:i:s')) + 19800 >= strtotime($Value['MatchStartDateTime']))) { // +05:30

                /* Update Match Status */
                $this->db->where('EntityID', $Value['MatchID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 2));

                /* Update Contest Status */
                $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 2 WHERE E.StatusID = 1 AND C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
            }

            $Response = $this->callSportsAPI(SPORTS_API_URL_CRICKETAPI . '/rest/v2/match/' . $Value['MatchIDLive'] . '/?access_token=');

            $MatchStatusLive     = @$Response['data']['card']['status'];
            $MatchStatusOverView = @$Response['data']['card']['status_overview'];

            /* Get Match Review Check Point */
            $MatchReviewCheckPoint = @$Response['data']['card']['data_review_checkpoint'];
           
            if ($Value['Status'] == 'Running' && $MatchStatusLive != 'notstarted') {

                /* Update Match Status */
                $this->db->where('EntityID', $Value['MatchID']);
                $this->db->limit(1);
                $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => ($MatchStatusLive != 'completed') ? $MatchStatus[$MatchStatusLive] : (($MatchReviewCheckPoint == 'post-match-validated') ? 5 : 10)));

                /* Update Contest Status */
                if($MatchStatusLive != 'completed'){
                    $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = ' . $ContestStatus[$MatchStatusLive] . ' WHERE  E.StatusID = 2 AND C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
                }

            }
            
            /* Get Match Players Live */
            if (empty($Response['data']['card']['players']))
                continue;
            foreach ($Response['data']['card']['players'] as $PlayerIdLive => $Player) {
                $LivePlayersData[$PlayerIdLive] = $Player['name'];
            }

            /* Get Playing XI */
            $PlayingXIArr = array_merge(empty($Response['data']['card']['teams']['a']['match']['playing_xi']) ? array() : $Response['data']['card']['teams']['a']['match']['playing_xi'], empty($Response['data']['card']['teams']['b']['match']['playing_xi']) ? array() : $Response['data']['card']['teams']['b']['match']['playing_xi']);

            /* Get Match Players */
            $IsPlayingPlayers = 0;
            $PlayersData = $this->Sports_model->getPlayers('PlayerIDLive,PlayerID,MatchID,IsPlaying', array('MatchID' => $Value['MatchID']), true, 0);
            if ($PlayersData) {
                $IsPlayingData = array_count_values(array_values(array_column($PlayersData['Data']['Records'], 'IsPlaying', 'PlayerID')));
                $IsPlayingPlayers = (isset($IsPlayingData['Yes'])) ? (int) $IsPlayingData['Yes'] : 0;
            }
            if (!empty($PlayingXIArr) && $IsPlayingPlayers < 22) {

                /* Get Match Players */
                $PlayersIdsData = array();
                if ($PlayersData) {
                    $PlayersIdsData = array_column($PlayersData['Data']['Records'], 'PlayerID', 'PlayerIDLive');
                }
                foreach ($PlayingXIArr as $PlayerIdLiveNew => $PlayerValue) {

                    /* Update Playing XI Status */
                    $this->db->where(array('MatchID'=> $Value['MatchID'], 'PlayerID'=> $PlayersIdsData[$PlayerValue]));
                    $this->db->limit(1);
                    $this->db->update('sports_team_players', array('IsPlaying' => "Yes"));
                }
            }

            if(!in_array($MatchStatusLive,array('started','completed'))){
                continue;
            }
            
            $MatchScoreDetails = $InningsData = array();
            $MatchScoreDetails['StatusLive'] = ($MatchStatusLive == 'started') ? 'Live' : (($MatchStatusLive == 'notstarted') ? 'Not Started' : 'Completed');
            $MatchScoreDetails['StatusNote'] = (!empty($Response['data']['card']['msgs']['result'])) ? $Response['data']['card']['msgs']['result'] : '';
            $MatchScoreDetails['TeamScoreLocal'] = array('Name' => $Response['data']['card']['teams']['a']['name'], 'ShortName' => $Response['data']['card']['teams']['a']['short_name'], 'LogoURL' => '');
            $MatchScoreDetails['TeamScoreVisitor'] = array('Name' => $Response['data']['card']['teams']['b']['name'], 'ShortName' => $Response['data']['card']['teams']['b']['short_name'], 'LogoURL' => '');
            $MatchScoreDetails['MatchVenue'] = @$Response['data']['card']['venue'];
            $MatchScoreDetails['Result'] = (!empty($Response['data']['cards']['msgs']['result'])) ? $Response['data']['cards']['msgs']['result'] : '';
            $MatchScoreDetails['Toss'] = @$Response['data']['card']['toss']['str'];
            
            foreach ($Response['data']['card']['batting_order'] as $Key => $BattingValue) {
                $AllPlayingXI = array();
                if($BattingValue[0] == 'a'){
                    $MatchScoreDetails['TeamScoreLocal']['Scores'][] = array('Scores' =>  @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['runs'].'/'.@$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['wickets'],'Overs' =>  @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['overs']);
                    if(count($Response['data']['card']['batting_order']) == 1){
                        $MatchScoreDetails['TeamScoreVisitor']['Scores'][] = array('Scores' => '','Overs' => '');
                    }
                }
                if($BattingValue[0] == 'b'){
                    $MatchScoreDetails['TeamScoreVisitor']['Scores'][] = array('Scores' =>  @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['runs'].'/'.@$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['wickets'],'Overs' =>  @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['overs']);
                    if(count($Response['data']['card']['batting_order']) == 1){
                        $MatchScoreDetails['TeamScoreLocal']['Scores'][] = array('Scores' => '','Overs' => '');
                    }
                }

                /* Manage Team Players Details */
                foreach ($Response['data']['card']['teams'][$BattingValue[0]]['match']['playing_xi'] as $InningPlayer) {

                    /* Get Player Details */
                    $PlayerDetails = @$Response['data']['card']['players'][$InningPlayer];

                    /* Get Player Role */
                    $Keeper = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['keeper'];
                    $Batsman = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['batsman'];
                    $Bowler = $Response['data']['card']['players'][$InningPlayer]['identified_roles']['bowler'];
                    $PlayerRole = ($Keeper == 1) ? 'WicketKeeper' : (($Batsman == 1 && $Bowler == 1) ? 'AllRounder' : ((empty($Batsman) && $Bowler == 1) ? 'Bowler' : ((empty($Bowler) && $Batsman == 1) ? 'Batsman' : '')));

                    /* Batting */
                    if (isset($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['balls'])) {

                        $AllPlayingXI[$InningPlayer]['batting'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => @$InningPlayer,
                            'Role' => @$PlayerRole,
                            'Runs' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['runs'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['runs'] : "",
                            'BallsFaced' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['balls'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['balls'] : "",
                            'Fours' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['fours'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['fours'] : "",
                            'Sixes' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['sixes'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['sixes'] : "",
                            'HowOut' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['out_str'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['out_str'] : "",
                            'IsPlaying' => (@$PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['dismissed'] == 1) ? 'No' : ((isset($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['balls'])) ? 'Yes' : ''),
                            'StrikeRate' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['strike_rate'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['batting']['strike_rate'] : ""
                        );
                    }

                    /* Bowling */
                    if (!empty(@$PlayerDetails['match']['innings'][$BattingValue[1]]['bowling'])) {

                        $AllPlayingXI[$InningPlayer]['bowling'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => $InningPlayer,
                            'Overs' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['overs'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['overs'] : '',
                            'Maidens' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['maiden_overs'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['maiden_overs'] : '',
                            'RunsConceded' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['runs'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['runs'] : '',
                            'Wickets' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['wickets'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['wickets'] : '',
                            'Economy' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['economy'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['bowling']['economy'] : ''
                        );
                    }

                    /* Fielding */
                    if (!empty(@$PlayerDetails['match']['innings'][$BattingValue[1]]['fielding'])) {

                        $AllPlayingXI[$InningPlayer]['fielding'] = array(
                            'Name' => @$PlayerDetails['name'],
                            'PlayerIDLive' => $InningPlayer,
                            'Catches' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['catches'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['catches'] : '',
                            'RunOutThrower' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'] : '',
                            'RunOutCatcher' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'] : '',
                            'RunOutDirectHit' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['runouts'] : '',
                            'Stumping' => (!empty($PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['stumbeds'])) ? $PlayerDetails['match']['innings'][$BattingValue[1]]['fielding']['stumbeds'] : ''
                        );
                    }
                }

                /* Get Team Details */
                $InningsData[] = array(
                    'Name' => $Response['data']['card']['teams'][$BattingValue[0]]['name'] . ' inning',
                    'ShortName' => $Response['data']['card']['teams'][$BattingValue[0]]['short_name'] . ' inn.',
                    'Scores' => $Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['runs'] . "/" . $Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['wickets'],
                    'ScoresFull' => $Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['runs'] . "/" . $Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['wickets'] . " (" . $Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['overs'] . " ov)",
                    'AllPlayingData' => $AllPlayingXI,
                    'ExtraRuns' => array('Byes' => @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['bye'], 'LegByes' => @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['legbye'], 'Wides' => @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['wide'], 'NoBalls' => @$Response['data']['card']['innings'][$BattingValue[0].'_'.$BattingValue[1]]['noball'])
                );

            }
            $MatchScoreDetails['Innings'] = $InningsData;

            /* Update Match Data */
            $this->db->where('MatchID', $Value['MatchID']);
            $this->db->limit(1);
            $this->db->update('sports_matches', array('MatchScoreDetails' => json_encode($MatchScoreDetails)));

            if ($MatchStatusLive == 'completed') {

                if (strtolower($MatchStatusOverView) == "abandoned" || strtolower($MatchStatusOverView) == "canceled" || strtolower($MatchStatusOverView) == "play_suspended_unknown"){

                    /* Cancel Contest */
                    $CronID = $this->Common_model->insertCronLogs('autoCancelContest');
                    $this->Sports_model->autoCancelContest($CronID,'Abonded',$Value['MatchID']);
                    $this->Common_model->updateCronLogs($CronID);
                    
                    /* Update Match Status */
                    $this->db->where('EntityID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => 8));

                }else{

                    /* Update Final points before complete match */
                    $CronID = $this->Common_model->insertCronLogs('getPlayerPoints');
                    $this->Sports_model->getPlayerPointsCricket($CronID);
                    $this->Common_model->updateCronLogs($CronID);

                    /* Update Final player points before complete match */
                    $CronID = $this->Common_model->insertCronLogs('getJoinedContestPlayerPoints');
                    $this->Sports_model->getJoinedContestPlayerPointsCricket($CronID);
                    $this->Common_model->updateCronLogs($CronID);

                    /* Update Match Status */
                    $this->db->where('EntityID', $Value['MatchID']);
                    $this->db->limit(1);
                    $this->db->update('tbl_entity', array('ModifiedDate' => date('Y-m-d H:i:s'), 'StatusID' => ($MatchReviewCheckPoint == 'post-match-validated') ? 5 : 10));

                    /* Update Contest Status */
                    if($MatchReviewCheckPoint == 'post-match-validated'){
                        $this->db->query('UPDATE sports_contest AS C, tbl_entity AS E SET E.StatusID = 5 WHERE  E.StatusID = 2 AND  C.ContestID = E.EntityID AND C.MatchID = ' . $Value['MatchID']);
                    }
                }
            }
        }
    }
}
