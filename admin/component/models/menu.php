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

class menu extends model
{

	public static function getMenuItems() 
	{
		$db   = factory::getDatabase();

	    $db->query('SELECT * FROM #_menu ORDER BY id');

		return $db->fetchObjectList();
	}
	
	public static function getMenuData() 
	{
		$db   = factory::getDatabase();
		$app  = factory::getApplication();
		
		$id     = $app->getVar('id', 0, 'get', 'int');

	    $db->query('SELECT * FROM #_menu WHERE id = '.$id.' ORDER BY id');

		return $db->fetchObject();
	}

	function saveMenuItem()
	{
		$config = factory::getConfig();
    	$app    = factory::getApplication();
    	$db     = factory::getDatabase();
    	$user   = factory::getUser();
    	$lang   = factory::getLanguage();
		$id     = $app->getVar('id', 0, 'post', 'int');
		
		$create = $_POST['create'];
		unset($_POST['create']);
		
		if($id != 0) {
			$result = $db->updateRow('#_menu', $_POST, 'id', $id);
		} else {
			$result = $db->insertRow('#_menu', $_POST);
		}
		
		//si s'escull la creació automaticament afegim els directoris i fitxers al component
		if($create != 0) {
			mkdir(CWPATH_BASE.DS.'component'.DS.'views'.DS.$_POST['slug']);
			mkdir(CWPATH_BASE.DS.'component'.DS.'views'.DS.$_POST['slug'].DS.'tmpl');			
			$fp = fopen(CWPATH_BASE.DS.'component'.DS.'views'.DS.$_POST['slug'].DS.'view.php', 'wb');
			fclose($fp);
			$fp = fopen(CWPATH_BASE.DS.'component'.DS.'views'.DS.$_POST['slug'].DS.'tmpl'.DS.$_POST['slug'].'.php', 'wb');
			fclose($fp);
			$fp = fopen(CWPATH_BASE.DS.'component'.DS.'models'.DS.$_POST['slug'].'.php', 'wb');
			fclose($fp);
		}
		
		//save item
		if($result) {
			$link = 'index.php?view=menu';
			$type = 'success';
			$msg  = 'El menú ha estat guardat amb exit.';
		} else {
			$link = 'index.php?view=menu&layout=edit';
			if($id != 0) { $link .= '&id='.$id; }
			$type = 'danger';
			$msg  = 'Hi ha hagut un error al intentar guardar aquest item.';
		}


		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

}
