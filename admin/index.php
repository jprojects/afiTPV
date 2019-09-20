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

session_start();

define('_Afi', 1);
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
date_default_timezone_set('Europe/Berlin');
define('CWPATH_BASE', str_replace('/admin/index.php', '', $_SERVER['SCRIPT_FILENAME']) );
define('DS', DIRECTORY_SEPARATOR );
require_once(CWPATH_BASE.DS.'includes/defines.php');
require_once(CWPATH_CLASSES.DS.'factory.php');

$config  = factory::getConfig();
$app     = factory::getApplication();
$db      = factory::getDatabase();
$admin   = factory::getAdmin();
$lang    = factory::getLanguage();
$html    = factory::getHtml();
$url     = factory::getUrl();
$session = factory::getSession();
 
if(isset($_SESSION['timeout']) ) {
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $config->inactive) { 
		$session->destroySession(); 
		header("Location: index.php?view=cpanel"); 
	}
}
$_SESSION['timeout'] = time();

//set error level
ini_set('display_errors', $config->debug);

//render application    
include($app->getTemplate(true)); 

?>

