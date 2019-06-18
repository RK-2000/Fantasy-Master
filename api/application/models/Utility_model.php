<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
      Description: 	Use to get country list
     */

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
      Description: Use to manage cron api logs
     */

    function insertCronAPILogs($CronID, $Response)
    {
        if (!CRON_SAVE_LOG) {
            return true;
        }
        $this->db->insert('log_cron_api', array('CronID' => $CronID, 'Response' => @json_encode($Response, JSON_UNESCAPED_UNICODE)));
    }

    /*
      Description: 	Use to get banner list
     */

    function bannerList($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15)
    {
        $MediaData = $this->Media_model->getMedia('E.EntityGUID MediaGUID, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaThumbURL, CONCAT("' . BASE_URL . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,	M.MediaCaption', array("SectionID" => 'Banner'), TRUE);
        if ($MediaData) {
            $Return = ($MediaData ? $MediaData : new StdClass());
            return $Return;
        }
        return false;
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
      Description: Use to manage cron logs
     */

    function insertCronLogs($CronType)
    {
        if (!CRON_SAVE_LOG) {
            return true;
        }
        $this->db->insert('log_cron', array('CronType' => $CronType, 'EntryDate' => date('Y-m-d H:i:s')));
        return $this->db->insert_id();
    }

    /*
      Description: Use to manage cron logs
     */

    function updateCronLogs($CronID, $CronStatus = 'Completed')
    {
        if (!CRON_SAVE_LOG) {
            return true;
        }
        $this->db->where('CronID', $CronID);
        $this->db->limit(1);
        $this->db->update('log_cron', array('CompletionDate' => date('Y-m-d H:i:s'), 'CronStatus' => $CronStatus));
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
        }
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
        return array('BannerID' => $BannerID, 'BannerGUID' => $EntityGUID);
    }

    /*
      Description: Use to send OTP on mobile
     */

    function sendMobileSMS($SMSArray)
    {
        if (ENVIRONMENT == 'testing') {
            return TRUE;
        }
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

            CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=FSLELE&mobiles=" . $SMSArray['PhoneNumber'] . "&authkey=" . MSG91_AUTH_KEY . "&message=" . $SMSArray['Text'] . "&country=91",

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
        // print_r($response);exit();

        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    /*
      Description: Use to send emails
     */

    function sendMails($MailArray)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/sendmail.php?body=" . $MailArray['emailMessage'] . "&subject=" . $MailArray['emailSubject'] . "&to=" . $MailArray['emailTo'] . "&from=" . MSG91_FROM_EMAIL . "&authkey=" . MSG91_AUTH_KEY,
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
            return FALSE;
        } else {
            return TRUE;
        }
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
      Description: 	Use to get app version details
     */

    function getAppVersionDetails()
    {
        $Query = $this->db->query("SELECT ConfigTypeGUID,ConfigTypeDescprition,ConfigTypeValue FROM set_site_config WHERE ConfigTypeGUID IN ('AndridAppUrl','AndroidAppVersion','IsAndroidAppUpdateMandatory')");
        if ($Query->num_rows() > 0) {
            $VersionData = array();
            foreach ($Query->result_array() as $Value) {
                $VersionData[$Value['ConfigTypeGUID']] = $Value['ConfigTypeValue'];
            }
            return $VersionData;
        }
        return FALSE;
    }

    function getDummyNames($Limit = 10)
    {
        $Query = $this->db->query("SELECT names FROM dummy_names LIMIT $Limit");
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        }
        return FALSE;
    }
}
