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

class users extends model
{

	public static function getUsers() 
	{
		$db   = factory::getDatabase();

	    $db->query('SELECT u.*, g.usergroup FROM #_users as u INNER JOIN #_usergroups as g ON g.id = u.level ORDER BY u.id');

		return $db->fetchObjectList();
	}

	function getUserData()
	{
		$db   = factory::getDatabase();
		$app  = factory::getApplication();
		
		$id   = $app->getVar('id', '', 'get');
		
		$sql = 'SELECT * FROM #_users';
		if($id != '') {
			$sql .= ' WHERE id = '.$id;
		}

	    $db->query($sql);

		return $db->fetchObject();
	}
	
	function saveField()
	{
    	$app    = factory::getApplication();
    	$db     = factory::getDatabase();
    	$lang   = factory::getLanguage();
    	
    	//si un campo esta vacio abortamos...
    	if($_POST['nom'] == "" || $_POST['tipus'] == "" || $_POST['longitud'] == "") {
        	$app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
        	$app->redirect('index.php?view=users');
        	return false;
    	}
    	
    	$result = $db->query('ALTER TABLE #_users ADD cf_'.$_POST['nom'].' '.$_POST['tipus'].'('.$_POST['longitud'].');');
    	
    	if($result) {
			$link = 'index.php?view=users';
			$type = 'success';
			$msg  = 'El custom field ha estat guardat amb exit.';
		} else {
			$link = 'index.php?view=users';
			$type = 'danger';
			$msg  = 'Hi ha hagut un error al intentar guardar aquest custom field.';
		}


		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

	function saveUser()
	{
		$config = factory::getConfig();
    	$app    = factory::getApplication();
    	$db     = factory::getDatabase();
    	$user   = factory::getUser();
    	$lang   = factory::getLanguage();
		$id     = $app->getVar('id', 0, 'get', 'int');

		if($id == 0) {
			//si un campo esta vacio abortamos...
	    	if($_POST['email'] == "" || $_POST['password'] == "" || $_POST['password2'] == "") {
	        	$app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
	        	$app->redirect('index.php?view=users&layout=edit');
	        	return false;
	    	}
		    
	    	//check if email exists...
	    	$db->query('select id from #_users where email = '.$db->quote($_POST['email']));
	    	if($id = $db->loadResult()) {
	        	$app->setMessage($lang->get('El email ya existe, por favor elige otro'), 'danger');
	        	$app->redirect($config->site.'/index.php?view=users&layout=edit');
	        	return false;
	    	}

			if($_POST['password'] === $_POST['password2']) {
				unset($_POST['password2']);
                $_POST['password']      = $app->encryptPassword($_POST['password']);
			}

        	$_POST['registerDate']   = date('Y-m-d H:i:s');
        	$token                   = uniqid();
        	$_POST['token']          = $token;
        	$_POST['language']       = 'en-gb';
			$_POST['lastvisitDate']  = date('Y-m-d H:i:s');

			$result = $db->insertRow('#_users', $_POST);

		} else {
	    
	    	//check if email exists...
	    	$db->query('select id from #_users where email = '.$db->quote($_POST['email']));
	    	if($db->loadResult() != $id) {
	        	$app->setMessage($lang->get('El email ya existe, por favor elige otro'), 'danger');
	        	$app->redirect($config->site.'/index.php?view=users&layout=edit&id='.$id);
	        	return false;
	    	}

			if(($_POST['password'] != '' && $_POST['password2'] != '') && ($_POST['password'] === $_POST['password2'])) {
				unset($_POST['password2']);
                $_POST['password'] = $app->encryptPassword($_POST['password']);
			}
        	
        	$result = $db->updateRow('#_users', $_POST, 'id', $id);
		}

		if($result) {
			$link = 'index.php?view=users';
			$type = 'success';
			$msg  = 'El usuari ha estat guardat amb exit.';
		} else {
			$link = 'index.php?view=users&layout=edit&id='.$id;
			$type = 'danger';
			$msg  = 'Hi ha hagut un error al intentar guardar aquest usuari.';
		}


		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

	function getGroups()
	{
		$db = factory::getDatabase();
		$db->query('select * from #_usergroups order by id');
		return $db->fetchObjectList();
	}

}
