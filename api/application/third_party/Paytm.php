<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Paytm Class
 * Help to manage all internal Paytm functions
 */
class Paytm
{

    /*
      Description: To Get Paytm Transaction Details
    */
    function getPaytmTxnDetails($OrderID)
    {
        $PaytmParams["MID"] = PAYTM_MERCHANT_ID;
        $PaytmParams["ORDERID"] = $OrderID;
        $PaytmParams['CHECKSUMHASH'] = urlencode($this->generatePaytmCheckSum($PaytmParams, PAYTM_MERCHANT_KEY));
        $Connection = curl_init();
        curl_setopt($Connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($Connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($Connection, CURLOPT_URL, "https://" . PAYTM_DOMAIN . "/merchant-status/getTxnStatus");
        curl_setopt($Connection, CURLOPT_POST, true);
        curl_setopt($Connection, CURLOPT_POSTFIELDS, "JsonData=" . json_encode($PaytmParams, JSON_UNESCAPED_SLASHES));
        curl_setopt($Connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        return json_decode(curl_exec($Connection), true);
    }

    function generatePaytmCheckSum($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getArray2Str($arrayList)
    {
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function checkString_e($value)
    {
        if ($value == 'null')
            $value = '';
        return $value;
    }

    function generateSalt_e($length)
    {
        $random = "";
        srand((double)microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    function getChecksumFromString($str, $key)
    {

        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function encrypt_e($input, $ky)
    {
        $ky   = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_encrypt($input, "AES-128-CBC", $ky, 0, $iv);
        return $data;
    }

    function decrypt_e($crypt, $ky)
    {
        $ky   = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_decrypt($crypt, "AES-128-CBC", $ky, 0, $iv);
        return $data;
    }

    function pkcs5_pad_e($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad_e($text)
    {
        $pad = ord($text{
            strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        return substr($text, 0, -1 * $pad);
    }

    function verifychecksum_e($arrayList, $key, $checksumvalue)
    {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2Str($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;
        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function removeCheckSumParam($arrayList)
    {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }
}
