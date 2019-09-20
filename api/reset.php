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
$lang   = factory::getlanguage();

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

	$db->query("SELECT id FROM #_users WHERE email = $email AND block = 0");
	$id = $db->loadResult();
	$newpassword = uniqid();
	$password = $app->encryptPassword($newpassword);
	$result = $db->updateField('#_users', 'password', $password, 'id', $id);

	//send a confirmation to the user...
	$subject  = $lang->replace('CW_REGISTER_RESET_SUBJECT', $config->sitename);
    $body     = $lang->replace('CW_REGISTER_RESET_BODY', $_POST['email'], $config->sitename, $newpassword);  

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
			$result['data']['message']  = 'We just send an email to set a new password, check your inbox';
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<data><type>reset</type><message>We just send an email to set a new password, check your inbox</message></data>";
			echo $output;
		}
	} else {
		if($format == 'json') {
			$result['error']['error_message'] = 'Reset password email not sent';
			$result['error']['error_code'] = 140;
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<error><error_code>140</error_code><error_message>Reset password email could not be sent</error_message></error>";
			echo $output;
		}
		return false;
	}

}
           
?>
