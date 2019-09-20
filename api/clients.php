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
define('CWPATH_BASE', '/var/www/vhosts/aficat.com/projectes/afiTPV' );
define('DS', DIRECTORY_SEPARATOR );

require_once(CWPATH_BASE.DS.'includes/defines.php');
require_once(CWPATH_CLASSES.DS.'factory.php');

$config = factory::getConfig();
$app    = factory::getApplication();
$db     = factory::getDatabase();

$search = $app->getVar('query', '', 'get');

$result = array();

$db->query("select id, nom from #_clients where nom LIKE '%".$search."%'"); 
$rows = $db->fetchObjectList();         
		
header('Content-Type: application/json');

foreach($rows as $row) {
	$result[] = array('id' => $row->id, 'nom' => $row->nom);
}

echo json_encode($result);
		
            


?>
