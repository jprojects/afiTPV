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

include('includes/model.php');

class report extends model
{	

	public static function getIssuesByYear(){
		$db = factory::getDatabase();

		$date = date('m');

		$db->query("SELECT CONCAT('*', YEAR(data_incidencia), '*') AS exercici, COUNT(incidencia_id) AS numero FROM #_incidencies WHERE year(data_incidencia) > 2015 and month(data_incidencia) <= $date  GROUP BY YEAR(data_incidencia)");
		$rows = $db->fetchObjectList();
		
		$str = "['Any', 'Incidències']";
		foreach($rows as $row) {
			$str .= ',[' . $db->quote($row->exercici) . ',' . $db->quote($row->numero) . ']';
		}
	
		return $str;
	}

	public static function getIssuesTimeByProject(){
		$db = factory::getDatabase();

		$db->query("SELECT afi_projectes.nom AS projecte, SUM(temps_previst) AS numero FROM #_incidencies, #_projectes WHERE projecteId = projecte_id GROUP BY projecteId");
		$rows = $db->fetchObjectList();
		
		$str = "['Projecte', 'Hores']";
		foreach($rows as $row) {
			$str .= ',[' . $db->quote($row->projecte) . ',' . $db->quote($row->numero) . ']';
		}
	
		return $str;
	}

	public static function getIssuesByProject(){
		$db = factory::getDatabase();

		$db->query("SELECT afi_projectes.nom AS projecte, COUNT(*) AS numero FROM #_incidencies, #_projectes WHERE projecteId = projecte_id GROUP BY projecteId");
		$rows = $db->fetchObjectList();
		
		$str = "['Projecte', 'Hores']";
		foreach($rows as $row) {
			$str .= ',[' . $db->quote($row->projecte) . ',' . $db->quote($row->numero) . ']';
		}
	
		return $str;
	}
}
