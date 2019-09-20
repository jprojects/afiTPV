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

class topmenuHelper
{

	public static function getItems() 
	{
		$db   = factory::getDatabase();
		$user = factory::getUser();
		
		if($user->level == 2) {
			$sql = 'SELECT * FROM #_menu WHERE level = 0 ORDER BY id';
		} else {
			$sql = 'SELECT * FROM #_menu ORDER BY id';
		}

	    $db->query($sql);

		return $db->fetchObjectList();
	}

}
