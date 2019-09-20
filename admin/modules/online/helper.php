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

defined('_Afi') or die ('restricted access');

class onlineHelper {
	
	function getUsersOnline() {
		
		$db = factory::getDatabase();
		
		$db->query('select s.*, u.username from #_sessions s inner join #_users u on u.id = s.userid');
		
		return $db->fetchObjectList();
	}
}

?>
