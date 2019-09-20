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
$lang   = factory::getLanguage();
$mail   = factory::getMailer();

$result = array();
            
$email     = $app->getVar('email', '', 'post');
$password  = $app->getVar('password', '', 'post');
$password2 = $app->getVar('password2', '', 'post');
$format    = $app->getVar('format', 'json', 'post');

unset($_POST['password']);
unset($_POST['password2']);
unset($_POST['format']);
        
//si un campo esta vacio abortamos...
if($email == "" || $password == "") {
	if($format == 'json') {
    	$result['error']['message'] = 'Email or password empty';
    	$result['error']['error_code'] = 110;
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>110</error_code><error_message>Email or password empty</error_message></error>";
    	echo $output;
    }
    return false;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      if($format == 'json') {
    	$result['error']['error_message'] = 'Invalid email format';
    	$result['error']['error_code'] = 123;    
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>123</error_code><error_message>Invalid email format</error_message></error>";
    	echo $output;
    }
    return false;
}

//check if email exists...
$db->query('select id from #_users where email = '.$db->quote($email));
if($id = $db->loadResult()) {
    if($format == 'json') {
    	$result['error']['error_message'] = 'Email exists in database';
    	$result['error']['error_code'] = 124;    	
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>124</error_code><error_message>Email exist in database</error_message></error>";
    	echo $output;
    }
    return false;
} 

//if password match
if($password === $password2) {	
        
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
    
    	//create user
    	$_POST['username']      = $email;
    	$_POST['email']         = $email;
		$_POST['password']      = $app->encryptPassword($password);
		$registerDate           = date('Y-m-d H:i:s');
		$_POST['registerDate']  = $registerDate;   
		$_POST['token']         = $token;
		$_POST['language']      = 'es-es';
		$db->insertRow('#_users', $_POST);
	
		$lastid = $db->lastId();
	
		//create usergroup
		$group = new stdClass();
		$group->usergroup = 'registered';
		$group->userid    = $lastid;
		$db->insertRow('#_usergroups', $group);

	    if($format == 'json') {
			$result['data']['type'] = 'register';
			$result['data']['user']['id']  = $lastid;
			$result['data']['user']['auth_token']  = $token;
			$result['data']['user']['username'] = $email;
			$result['data']['user']['email'] = $email;
			$result['data']['user']['level'] = 2;
			$result['data']['user']['language'] = 'es-es';
			$result['data']['user']['registerDate'] = $registerDate;
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<data><type>register</type><user><id>".$lastid."</id><auth_token>".$token."</auth_token><username>".$email."</username><email>".$email."</email><level>2</level><registerDate>".$registerDate."</registerDate><language>es-es</language></user></data>";
			echo $output;
		}

    } else {
        if($format == 'json') {
        	$result['error']['error_message'] = 'Confirmation mail not sent';
			$result['error']['error_code'] = 125;			
			$result['error']['user']['auth_token'] = $token;
			$result['error']['user']['registerDate'] = $registerDate;
			$result['error']['user']['language'] = 'es-es';
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<error><error_code>125</error_code><error_message>Confirmation mail not sent</error_message><user><auth_token>".$token."</auth_token><registerDate>".$registerDate."</registerDate><language>es-es</language></user></error>";
			echo $output;
		}
		return false;
    }
                
} else {
	if($format == 'json') {
		$result['error']['error_message'] = 'Passwords not match';
    	$result['error']['error_code'] = 130;
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>130</error_code><error_message>Passwords not match</error_message></error>";
    	echo $output;
    }
    return false;
} 

?>
