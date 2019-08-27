<?php 
    const VERSION = 4.3; 
    const SITE_NAME = 'Fantasy Master';
    const DOMAIN_NAME = 'example.com';
    const FACEBOOK_URL  = 'https://www.facebook.com/';
    const TWITTER_URL   = 'https://www.twitter.com/';
    const LINKDIN_URL   = 'https://www.linkedin.com/';
    const INSTAGRAM_URL = 'https://www.instagram.com/';
    switch ($_SERVER['SERVER_NAME']) {
    case 'localhost':
        $base_url = 'http://localhost/fantasy-master/';
        $api_url = 'http://localhost/fantasy-master/api/';
        break;
    case '178.128.60.157':
    case 'dev.fantasy96.com':
        $base_url = 'http://dev.fantasy96.com/';
        $api_url = 'http://dev.fantasy96.com/api/';
        break;
    case 'example.com':
        $base_url = 'https://example.com/';
        $api_url = 'https://example.com/api/';
        break;  
    default :
        $_SERVER['CI_ENV'] = 'production';
        $base_url = 'https://www.example.com/';
        $api_url = 'https://www.example.com/api/';
        break;
    }
?>