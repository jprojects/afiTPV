<?php
session_start();

//Include Google client library 
include_once 'Google_Client.php';
include_once 'contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '954649540464-l69r33364h9sbtauqche2vnpm9h8qdrh.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'rfQallx0mRkaF4AMgW2mw4b2'; //Google client secret
$redirectURL = 'http://easyticketapp.com/dev/index.php?view=register&task=glogin'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('EasyTicket');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
