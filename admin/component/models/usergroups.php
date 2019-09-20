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

include('../includes/model.php');

class usergroups extends model
{
	private $table = '#_usergroups';
	private $view  = 'usergroups';

	function getUsergroups() 
	{
		$db   = factory::getDatabase();

	    $db->query('SELECT * FROM #_usergroups ORDER BY id');

		return $db->fetchObjectList();
	}

	function getUsergroupData()
	{
		$db   = factory::getDatabase();
		$app  = factory::getApplication();
		
		$id   = $app->getVar('id', '', 'get');
		
		$sql = 'SELECT * FROM #_usergroups';
		if($id != '') {
			$sql .= ' WHERE id = '.$id;
		}

	    $db->query($sql);

		return $db->fetchObject();
	}

	function saveUsergroup()
	{
		$app    = factory::getApplication();            
		$db     = factory::getDatabase();
		$config = factory::getConfig();
		
		$id   	= $app->getVar('id', '', 'get');

		if($id == 0) {
        	$result = $db->insertRow($this->table, $_POST);
        } else {
        	$result = $db->updateRow($this->table, $_POST, 'id', $id);
        }

		if($result) {
			$link = $config->site.'/admin/index.php?view='.$view;
			$type = 'success';
			$msg  = 'El grup ha estat guardat amb exit.';
		} else {
			$link = $config->site.'/admin/index.php?view='.$view.'&layout=edit';
			$type = 'danger';
			$msg  = 'Hi ha hagut un error al intentar guardar aquest grup.';
		}

		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

}
