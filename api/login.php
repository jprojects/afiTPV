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

$result = array();
            
$email    = $app->getVar('email', '', 'post');
$password = $app->getVar('password', '', 'post');
$format   = $app->getVar('format', 'json', 'post');
        
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

$db->query("SELECT password FROM #_users WHERE username = ".$db->quote($email)." AND block = 0");
$dbpass = $db->loadResult();

//if password match
if($app->decryptPassword($password, $dbpass)) {
	$db->query("SELECT id FROM #_users WHERE username = ".$db->quote($email)." AND block = 0");
	if($id = $db->loadResult()) {
		//update last visit date
		$db->updateField('#_users', 'lastvisitDate',  $app->getVar('lastvisitDate'), 'id', $id);
		//get data from user
		$db->query("SELECT * FROM #_users WHERE id = ".$id);
		$row = $db->fetchObject();
		
		if($format == 'json') {
		
			header('Content-Type: application/json');
			
			$result['data']['type'] = 'login';  
			$result['data']['user']['id'] = $row->id;
			$result['data']['user']['auth_token'] = $row->token;
			$result['data']['user']['username'] = $row->username;
			$result['data']['user']['email'] = $row->email;
			$result['data']['user']['level'] = 2;
			$result['data']['user']['lastvisitDate'] = $row->lastvisitDate;
			$result['data']['user']['language'] = $row->language;
			$result['data']['user']['registerDate'] = $row->registerDate;
			echo json_encode($result);
		}
		if($format == 'xml') {
		
			header('Content-type: text/xml');
			
			$output = "<data><type>login</type><user><id>".$row->id."</id><auth_token>".$row->token."</auth_token><username>".$row->username."</username><email>".$row->email."</email><level>2</level><lastvisitDate>".$row->lastvisitDate."</lastvisitDate><language>".$row->language."</language><registerDate>".$row->registerDate."</registerDate></user></data>";
			echo $output;
		}
		
	} else {
		if($format == 'json') {
			$result['error']['message'] = 'Wrong email or password try again';
			$result['error']['error_code'] = 120;		
			echo json_encode($result);
		}
		if($format == 'xml') {
			$output = "<error><error_code>120</error_code><error_message>Wrong email or password try again</error_message></error>";
			echo $output;
		}
	}
} else {
	if($format == 'json') {
    	$result['error']['message'] = 'Wrong email or password try again';
    	$result['error']['error_code'] = 120;
    	echo json_encode($result);
    }
    if($format == 'xml') {
    	$output = "<error><error_code>120</error_code><error_message>Wrong email or password try again</error_message></error>";
    	echo $output;
    }
}
            


?>
