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

class Session {

	public function setVar($name, $value) {

		$_SESSION[$name] = $value;

	}

	public function getVar($name, $default='') {
		if(!isset($_SESSION[$name]) && $default != '') { $this->setVar($name, $default); }
		if (isset ( $_SESSION[$name] ) && !empty(  $_SESSION[$name]  )) {
			return $_SESSION[$name];
		} else {
			return false;
		}
	}

	public function deleteVar($name) {

		unset ( $_SESSION[$name] );
	}

	public function createSession() {

		$db = factory::getDatabase();
		$user = factory::getUser();

		$s = new stdClass();
		$s->userid = $user->id;
		$s->ssid = session_id();
		$s->lastvisitDate = date('Y-m-d H:i:s');
		$db->insertRow('#_sessions', $s);
	}

	public function destroySession() {

		$db = factory::getDatabase();
		$user = factory::getUser();

		$db->query('DELETE FROM #_sessions WHERE userid = '.$user->id);
		$_SESSION = array();
		session_destroy ();

	}

}
