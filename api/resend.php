<?php
/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

define('_Afi', 1);
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Europe/Berlin');
define('CWPATH_BASE', '/var/www/vhosts/easyticketapp.com/httpdocs/dev/' );
define('DS', DIRECTORY_SEPARATOR );

require_once(CWPATH_BASE.DS.'includes/defines.php');
require_once(CWPATH_CLASSES.DS.'factory.php');

$config = factory::getConfig();
$app    = factory::getApplication();
$db     = factory::getDatabase();
$user   = factory::getUser();
$mail   = factory::getMailer();

$result = array();
            
$email    = $app->getVar('email', '', 'post');
$format   = $app->getVar('format', 'json', 'post');
        
//si un campo esta vacio abortamos...
if($email == "") {
	if($format == 'json') {    	
    	$result['error']['message'] = 'Email empty';
    	$result['error']['error_code'] = 110;
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>110</error_code><error_message>Email empty</error_message></error>";
    	echo $output;
    }
    return false;
} else {  

	$db->query("SELECT token FROM #_users WHERE email = ".$db->quote($email)." AND block = 0");
	$token = $db->loadResult();

	//send a confirmation to the user...
	$token      = $user->genToken($email);
	$subject    = $lang->replace('CW_REGISTER_WELCOME_SUBJECT', $config->sitename);
	$link       = $config->site.'/index.php?view=register&task=validate&token='.$token;
	$body       = $lang->replace('CW_REGISTER_WELCOME_BODY', $email,  $config->sitename, $link);   

	@ob_start();
	include CWPATH_BASE.DS.'/assets/mail/mail.html';
	$html = @ob_get_clean();
	$htmlbody = str_replace('{{LOGO}}', $config->site.'/assets/img/mail_logo.png', $html);
	$htmlbody = str_replace('{{BODY}}', $body, $htmlbody);

	$mail->setFrom($config->email, $config->sitename);
	$mail->addRecipient($email, $email);
	$mail->setReplyTo($config->email);
	$mail->Subject($subject);
	$mail->Body($htmlbody);
	
	if($mail->send()) {
		if($format == 'json') {
			$result['data']['type'] = 'reset';
			$result['data']['message']  = 'We just send an email, check your inbox';
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<data><type>resend</type><message>We just send an email, check your inbox</message></data>";
			echo $output;
		}
	} else {
		if($format == 'json') {
			$result['error']['error_message'] = 'Activation mail could not be sent';
			$result['error']['error_code'] = 145;
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<error><error_code>145</error_code><error_message>Activation mail could not be sent</error_message></error>";
			echo $output;
		}
		return false;
	}

}
           
?>
