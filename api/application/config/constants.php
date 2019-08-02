<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



/*---------Site Settings--------*/
/*------------------------------*/	

/*Site Related Settings*/
define('SITE_NAME', 'Fantasy Master');
define('SITE_CONTACT_EMAIL', 'mwadmin@mailinator.com');
define('MULTISESSION', true);
define('PHONE_NO_VERIFICATION', true);
define('IS_VICECAPTAIN', true);
define('AUTO_WITHDRAWAL', false);
define('OTP_WITHDRAWAL', false);
define('IS_AUCTION', true);
define('DATE_FORMAT',"%Y-%m-%d %H:%i:%s"); /* dd-mm-yyyy hh:i:ss */

/*Pagination*/
define('PAGESIZE_DEFAULT', 15); /*Required*/
define('PAGESIZE_MAX', 151); /*Required*/

/*Image*/
define('THUMBNAIL_SIZE', 110); /*Required*/

define('SPORTS_FILE_PATH', FCPATH.'uploads/sports.txt');
define('CRICKET_SPORT_API_NAME', 'CRICKETAPI');
define('DEFAULT_SOURCE_ID', 1);
define('DEFAULT_DEVICE_TYPE_ID', 1);
define('DEFAULT_CURRENCY', 'Rs.');
define('REFERRAL_SIGNUP_BONUS', 50);
define('DEFAULT_PLAYER_CREDITS', 6.5);
define('DEFAULT_TIMEZONE', '+05:30');
define('ADMIN_ID', 125);
define('ADMIN_CONTEST_PERCENT', 10);
define('IS_USER_TEAMS_LIMIT', true);
define('IS_USER_TEAMS_ROLES', true);
define('USER_TEAMS_LIMIT', 60);
define('CONTEST_FILL_PERCENT_LIMIT', 80);

/*Social */
define('FACEBOOK_URL', 'https://www.facebook.com/FSLEleven/');
define('TWITTER_URL', 'https://twitter.com/FSL_Eleven');
define('LINKEDIN_URL', 'https://www.linkedin.com/company/fsl11');
define('INSTAGRAM_URL', 'https://www.instagram.com/FSL_Eleven/');

/* Entity Sports API Details */
define('SPORTS_API_URL_ENTITY', 'https://rest.entitysport.com');
define('SPORTS_API_ACCESS_KEY_ENTITY', '***');
define('SPORTS_API_SECRET_KEY_ENTITY', '***');

/* Cricket API Sports API Details */
define('SPORTS_API_URL_CRICKETAPI', 'https://rest.cricketapi.com');
define('SPORTS_API_ACCESS_KEY_CRICKETAPI', '2e1ea489eb700576032477ba907373f5');
define('SPORTS_API_SECRET_KEY_CRICKETAPI', 'f3c32e9d76cab75c10346b5598ca1426');
define('SPORTS_API_APP_ID_CRICKETAPI', 'MWB.SPORTS');
define('SPORTS_API_DEVICE_ID_CRICKETAPI', 'MWB');

/* PayUMoney Details */
define('PAYUMONEY_MERCHANT_KEY', '***');
define('PAYUMONEY_MERCHANT_ID', '6487911');
define('PAYUMONEY_SALT', 'qC5Ssp5Osb');

/* SMS API Details */
define('SMS_API_URL', 'https://login.bulksmsgateway.in/sendmessage.php');
define('SMS_API_USERNAME', '***');
define('SMS_API_PASSWORD', '***');

/* SENDINBLUE SMS API Details */
define('SENDINBLUE_SMS_API_URL', 'https://api.sendinblue.com/v3/transactionalSMS/sms');
define('SENDINBLUE_SMS_SENDER', 'EXACT11');
define('SENDINBLUE_SMS_API_KEY', 'xkeysib-******-72qcrDmbQ0HpGExS');

/* MSG91 SMS API Details */
define('MSG91_AUTH_KEY', '273511AObV1jwyuds43f33dds');
define('MSG91_SENDER_ID', 'DEMO');
define('MSG91_FROM_EMAIL', 'info@demo.com');

define('POST_PICTURE_URL', BASE_URL . 'uploads/Post/');
switch (ENVIRONMENT)
{
	case 'local':
	/*Paths*/
	define('SITE_HOST', 'http://localhost/');
	define('ROOT_FOLDER', 'fantasy-master/');

	/*SMTP Settings*/
	define('SMTP_PROTOCOL', 'smtp');
	define('SMTP_HOST', 'smtp.gmail.com');
	define('SMTP_PORT', '587');
	define('SMTP_USER', '');
	define('SMTP_PASS', '');
	define('SMTP_CRYPTO', 'tls'); /*ssl*/

	/*From Email Settings*/
	define('FROM_EMAIL', 'info@expertteam.in');
	define('FROM_EMAIL_NAME', SITE_NAME);

	/*No-Reply Email Settings*/
	define('NOREPLY_EMAIL', SITE_NAME);
	define('NOREPLY_NAME', "info@expertteam.in");

	/*Logs Settings*/
	define('API_SAVE_LOG', true);
	define('CRON_SAVE_LOG', true);
	
	/* PayUmoney Details */
	define('PAYUMONEY_ACTION_KEY','https://test.payu.in/_payment');

	/* Paytm Details */
	define('PAYTM_MERCHANT_ID', 'Pfytge92537984428170');
	define('PAYTM_MERCHANT_KEY', 'PvlUslpF7655u%eV');
	define('PAYTM_DOMAIN', 'securegw-stage.paytm.in');
	define('PAYTM_INDUSTRY_TYPE_ID', 'Retail');
	define('PAYTM_WEBSITE_WEB', 'WEBSTAGING');
	define('PAYTM_WEBSITE_APP', 'APPSTAGING');
	define('PAYTM_TXN_URL','https://' . PAYTM_DOMAIN . '/theia/processTransaction');
	define('PAYTM_MERCHANT_GRATIFICATION_ID', 'SDsAag68559014846478');
	define('PAYTM_MERCHANT_GUID', '24a48865-182f-4793-a5ab-6de76213b8c6');
	define('PAYTM_SALES_WALLET_GUID', '42582edf-74b5-4ea1-b546-61b46d2b2de0');
	define('PAYTM_MERCHANT_GRATIFICATION_KEY', 'FHHXkny2&FMBo&Av');
	define('PAYTM_GRATIFICATION_URL', 'https://trust.paytm.in/wallet-web/salesToUserCredit');
	
	/* Razorpay Details */
	define('RAZORPAY_KEY_ID', 'rzp_test_vlY7NbvbHCzHy3');
	define('RAZORPAY_KEY_SECRET', '6p0CmdhTRsSljuthIAmmZBFC');

	/* CashFree Details */
	define('CASHFREE_URL', 'https://test.cashfree.com/');
	define('CASHFREE_APP_ID', '4202cce7364682b1e84ec8682024');
	define('CASHFREE_SECRET_KEY', '643b7c8db0c91cabae8210eb8c36c137ef07dd3c');
	
	break;
	case 'testing':
	
	/*Paths*/
	define('SITE_HOST', 'http://dev.fantasy96.com/');
	define('ROOT_FOLDER', '');

	/*SMTP Settings*/
	define('SMTP_PROTOCOL', 'smtp');
	define('SMTP_HOST', 'smtp.gmail.com');
	define('SMTP_PORT', '587');
	define('SMTP_USER', '');
	define('SMTP_PASS', '');
	define('SMTP_CRYPTO', 'tls'); /*ssl*/

	/*From Email Settings*/
	define('FROM_EMAIL', 'info@expertteam.in');
	define('FROM_EMAIL_NAME', SITE_NAME);

	/*No-Reply Email Settings*/
	define('NOREPLY_EMAIL', SITE_NAME);
	define('NOREPLY_NAME', "info@expertteam.in");

	/*Logs Settings*/
	define('API_SAVE_LOG', false);
	define('CRON_SAVE_LOG', true);
	
	/* PayUmoney Details */
	define('PAYUMONEY_ACTION_KEY','https://test.payu.in/_payment');

	/* Paytm Details */
	define('PAYTM_MERCHANT_ID', 'Pfytge92537984428170');
	define('PAYTM_MERCHANT_KEY', 'PvlUslpF7655u%eV');
	define('PAYTM_DOMAIN', 'securegw-stage.paytm.in/');
	define('PAYTM_INDUSTRY_TYPE_ID', 'Retail');
	define('PAYTM_WEBSITE_WEB', 'WEBSTAGING');
	define('PAYTM_WEBSITE_APP', 'APPSTAGING');
	define('PAYTM_TXN_URL','https://' . PAYTM_DOMAIN . '/theia/processTransaction');
	define('PAYTM_MERCHANT_GRATIFICATION_ID', 'SDsAag68559014846478');
	define('PAYTM_MERCHANT_GUID', '24a48865-182f-4793-a5ab-6de76213b8c6');
	define('PAYTM_SALES_WALLET_GUID', '42582edf-74b5-4ea1-b546-61b46d2b2de0');
	define('PAYTM_MERCHANT_GRATIFICATION_KEY', 'FHHXkny2&FMBo&Av');
	define('PAYTM_GRATIFICATION_URL', 'https://trust.paytm.in/wallet-web/salesToUserCredit');

	/* Razorpay Details */
	define('RAZORPAY_KEY_ID', 'rzp_test_vlY7NbvbHCzHy3');
	define('RAZORPAY_KEY_SECRET', '6p0CmdhTRsSljuthIAmmZBFC');

	/* CashFree Details */
	define('CASHFREE_URL', 'https://test.cashfree.com/');
	define('CASHFREE_APP_ID', '4202cce7364682b1e84ec8682024');
	define('CASHFREE_SECRET_KEY', '643b7c8db0c91cabae8210eb8c36c137ef07dd3c');
	break;
	case 'demo':
	/*Paths*/
	define('SITE_HOST', 'http://mwdemoserver.com/');
	define('ROOT_FOLDER', '527-fsl11/');

	/*SMTP Settings*/
	define('SMTP_PROTOCOL', 'smtp');
	define('SMTP_HOST', 'smtp.gmail.com');
	define('SMTP_PORT', '587');
	define('SMTP_USER', '');
	define('SMTP_PASS', '');
	define('SMTP_CRYPTO', 'tls'); /*ssl*/

	/*From Email Settings*/
	define('FROM_EMAIL', 'info@expertteam.in');
	define('FROM_EMAIL_NAME', SITE_NAME);

	/*No-Reply Email Settings*/
	define('NOREPLY_EMAIL', SITE_NAME);
	define('NOREPLY_NAME', "info@expertteam.in");

	/*Logs Settings*/
	define('API_SAVE_LOG', false);
	define('CRON_SAVE_LOG', true);
	
	/* PayUmoney Details */
	define('PAYUMONEY_ACTION_KEY','https://test.payu.in/_payment');

	/* Paytm Details */
	define('PAYTM_MERCHANT_ID', '****');
	define('PAYTM_MERCHANT_KEY', '***');
	define('PAYTM_DOMAIN', 'securegw-stage.paytm.in');
	define('PAYTM_INDUSTRY_TYPE_ID', '****');
	define('PAYTM_WEBSITE_WEB', 'WEBSTAGING');
	define('PAYTM_WEBSITE_APP', 'APPSTAGING');
	define('PAYTM_TXN_URL','https://' . PAYTM_DOMAIN . '/theia/processTransaction');
	define('PAYTM_MERCHANT_GRATIFICATION_ID', 'SDsAag68559014846478');
	define('PAYTM_MERCHANT_GUID', '24a48865-182f-4793-a5ab-6de76213b8c6');
	define('PAYTM_SALES_WALLET_GUID', '42582edf-74b5-4ea1-b546-61b46d2b2de0');
	define('PAYTM_MERCHANT_GRATIFICATION_KEY', 'FHHXkny2&FMBo&Av');
	define('PAYTM_GRATIFICATION_URL', 'https://trust.paytm.in/wallet-web/salesToUserCredit');

	/* Razorpay Details */
	define('RAZORPAY_KEY_ID', 'rzp_test_vlY7NbvbHCzHy3');
	define('RAZORPAY_KEY_SECRET', '6p0CmdhTRsSljuthIAmmZBFC');

	/* CashFree Details */
	define('CASHFREE_URL', 'https://test.cashfree.com/');
	define('CASHFREE_APP_ID', '4202cce7364682b1e84ec8682024');
	define('CASHFREE_SECRET_KEY', '643b7c8db0c91cabae8210eb8c36c137ef07dd3c');
	break;
case 'production':
	/*Paths*/
	define('SITE_HOST', 'https://fsl11.com/');
	define('ROOT_FOLDER', '');

	/*SMTP Settings*/
	define('SMTP_PROTOCOL', 'smtp');
	define('SMTP_HOST', 'smtp.gmail.com');
	define('SMTP_PORT', '587');
	define('SMTP_USER', '');
	define('SMTP_PASS', '');
	define('SMTP_CRYPTO', 'tls'); /*ssl*/

	/*From Email Settings*/
	define('FROM_EMAIL', 'info@expertteam.in');
	define('FROM_EMAIL_NAME', SITE_NAME);

	/*No-Reply Email Settings*/
	define('NOREPLY_EMAIL', SITE_NAME);
	define('NOREPLY_NAME', "info@expertteam.in");

	/*Logs Settings*/
	define('API_SAVE_LOG', false);
	define('CRON_SAVE_LOG', true);
	
	/* PayUmoney Details */
	define('PAYUMONEY_ACTION_KEY','https://secure.payu.in/_payment');

	/* Paytm Details */
	define('PAYTM_MERCHANT_ID', 'SDsAag68559014846478');
	define('PAYTM_MERCHANT_KEY', 'qSAmx7TS#MbPmHiA');
	define('PAYTM_DOMAIN', 'securegw.paytm.in');
	define('PAYTM_INDUSTRY_TYPE_ID', 'Retail');
	define('PAYTM_WEBSITE_WEB', 'DEFAULT');
	define('PAYTM_WEBSITE_APP', 'DEFAULT');
	define('PAYTM_TXN_URL','https://' . PAYTM_DOMAIN . '/theia/processTransaction');
	define('PAYTM_MERCHANT_GRATIFICATION_ID', 'SDsAag68559014846478');
	define('PAYTM_MERCHANT_GUID', '24a48865-182f-4793-a5ab-6de76213b8c6');
	define('PAYTM_SALES_WALLET_GUID', '42582edf-74b5-4ea1-b546-61b46d2b2de0');
	define('PAYTM_MERCHANT_GRATIFICATION_KEY', 'FHHXkny2&FMBo&Av');
	define('PAYTM_GRATIFICATION_URL', 'https://trust.paytm.in/wallet-web/salesToUserCredit');

	/* Razorpay Details */
	define('RAZORPAY_KEY_ID', 'rzp_live_lZu5DvgspmKvxH');
	define('RAZORPAY_KEY_SECRET', '6avy2fkR79Z4JgexXkdicl18');

	/* CashFree Details */
	define('CASHFREE_URL', 'https://api.cashfree.com/');
	define('CASHFREE_APP_ID', '11973e706836675cdfa9b1f7337911');
	define('CASHFREE_SECRET_KEY', '430f20c80393273159143189c5b86324dd09f818');
	break;
}

define('BASE_URL', SITE_HOST . ROOT_FOLDER .'api/');
define('ASSET_BASE_URL', BASE_URL . 'asset/');
define('PROFILE_PICTURE_URL', BASE_URL . 'uploads/profile/picture');

/* S3 Bucket Settings */
define('BUCKET', 'BucketName');
define('AWS_ACCESS_KEY', 'AKIAJZZB67JRZRFEVSMQ');
define('AWS_SECRET_KEY', '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');
define('IMAGE_SERVER', '');
define('IMAGE_SERVER_PATH', (IMAGE_SERVER == 'remote' ? "https://".BUCKET.'.s3.amazonaws.com/':BASE_URL));

