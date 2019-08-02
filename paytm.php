<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included

require_once("encdec_paytm.php");

$checkSum = "";
$paramList = array();




$paramList = array();
$paramList['request'] = array( 'requestType' =>'VERIFY',
        'merchantGuid' => '5317f555-41b6-4538-85ce-b6bd0ac57c2d', //f1eee465-d172-4c18-a49c-76334791a1a7
       	'merchantOrderId' => 'ORDSjjhj0994',     
        'salesWalletGuid'=>'1256320b-e7d0-11e8-8399-fa163e429e83', //10f05aea-cdb3-11e7-a631-52540059b2ee   38191d5e-1318-11e8-b3d4-52540059b2ee
        'payeeEmailId'=>'',       
		'payeePhoneNumber'=>'7777777777',
        'payeeSsoId'=>'',	
        'appliedToNewUsers'=>'N',
        'amount'=>'1',
        'currencyCode'=>'INR');
		
		


$paramList['metadata'] = 'Testing Data';
$paramList['ipAddress'] = '127.0.01';
$paramList['operationType'] = 'SALES_TO_USER_CREDIT';
$paramList['platformName'] = 'PayTM';



$data_string = json_encode($paramList); 

echo $data_string;

$checkSum = getChecksumFromString($data_string,'uSvO431sCgX&@v%B');


$ch = curl_init();                    // initiate curl
$url = "https://trust-uat.paytm.in/wallet-web/salesToUserCredit"; // where you want to post data

//$url = "https://trust.paytm.in/wallet-web/salesToUserCredit" ;   // Live server URL 


$headers = array('Content-Type:application/json','mid:5317f555-41b6-4538-85ce-b6bd0ac57c2d','checksumhash:'.$checkSum);


$ch = curl_init();  // initiate curl
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);  // tell curl you want to post something
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string); // define what you want to post
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$output = curl_exec ($ch); // execute
$info = curl_getinfo($ch);
print_r($info)."<br />";






echo $output;
?>