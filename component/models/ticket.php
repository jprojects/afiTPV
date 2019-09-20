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

class ticket extends model
{

	private $view  = 'ticket';

    /**
     * Metode per agafar families
    */
    function getFamiliesBySalo()
    {
        $db  = factory::getDatabase();
        $user = factory::getUser();

		$db->query('SELECT * FROM #_families WHERE idSalo = '.$user->cf_salo);

		return $db->fetchObjectList();
    }

    /**
     * Metode per agafar articles per families
    */
    function getArticlesByFamilia()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();
        $user = factory::getUser();

        $familia_id = $app->getVar('id', 0, 'get');

		$db->query('SELECT * FROM #_articles WHERE idSalo = '.$user->cf_salo.' AND idFamilia = '.$familia_id);

		echo json_encode($db->fetchObjectList());
    }

    /**
     * Metode per agafar articles per families
    */
    function getArticleById()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();

        $article_id = $app->getVar('id', 0, 'get');

		$db->query('SELECT * FROM #_articles WHERE id = '.$article_id);

		echo json_encode($db->fetchObject());
    }

    /**
     * Metode per agafar tot el detall d'un tocador obert
    */
    function getDetallTocador()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();

        $article = $app->getVar('article', 0, 'get');
        $tocador = $app->getVar('tocador', 0, 'get');
        $id      = $app->getVar('id', 0, 'get');

		//si passem la id nomes volem un detall concret
		if($id == 0) {
			$db->query('SELECT td.*, t.import AS suma, a.descripcio FROM #_tocadors_oberts_detall AS td INNER JOIN #_tocadors_oberts AS t ON t.id = td.idTocador INNER JOIN #_articles AS a ON a.id = td.idArticle WHERE td.idTocador = '.$tocador);

			echo json_encode($db->fetchObjectList());

		} else {
			$db->query('SELECT td.*, t.import AS suma, a.descripcio FROM #_tocadors_oberts_detall AS td INNER JOIN #_tocadors_oberts AS t ON t.id = td.idTocador INNER JOIN #_articles AS a ON a.id = td.idArticle WHERE td.id = '.$id);

			echo json_encode($db->fetchObject());
		}
    }

    /**
     * Metode per agafar articles per families
    */
    function getTocadorsOberts()
    {
        $db  = factory::getDatabase();

		$db->query('SELECT t.*, c.nom FROM #_tocadors_oberts t INNER JOIN #_clients c ON t.idClient = c.id');

		return $db->fetchObjectList();
    }

    /**
     * Metode per agafar articles per families
    */
    function deleteArticle()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();

        $id  = $app->getVar('id', 0, 'get');

        $db->query('SELECT idTocador FROM #_tocadors_oberts_detall WHERE id = '.$id);
		$tocador = $db->loadResult();

		$db->query('DELETE FROM #_tocadors_oberts_detall WHERE id = '.$id);

		//recalculem els detalls del tocador i guardem l'import
		$db->query('SELECT SUM(importNet) FROM #_tocadors_oberts_detall WHERE idTocador = '.$tocador);
		$suma = $db->loadResult();

		$db->updateField('#_tocadors_oberts', 'import', $suma, 'id', $tocador);

		echo json_encode(array('suma' => $suma));
    }

    /**
     * Metode per guardar un tocador quan es selecciona desde la vista ticket
    */
    function saveTocador()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();
        $user = factory::getUser();

        $client = $app->getVar('client', 0, 'get');
        $total  = $app->getVar('total', 0, 'get');

        $db->query('SELECT id FROM #_tocadors_oberts WHERE idClient = '.$client);
        if($id = $db->loadResult()) {
        	echo json_encode(array('idTocador' => $id));
        	return false;
        }

        $toc = new stdClass();
        $toc->idSalo   		= $user->cf_salo;
        $toc->idTerminal    = $user->cf_terminal;
        $toc->idClient 		= $client;
        $toc->import 		= $total;

		$db->insertRow('#_tocadors_oberts', $toc);
    }

    /**
     * Metode per guardar un detall del ticket
    */
    function saveDetall()
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();

        $article  = $app->getVar('article', 0, 'get');
        $tocador  = $app->getVar('tocador', 0, 'get');

        $db->query('SELECT * FROM #_articles WHERE id = '.$article);
        $row = $db->fetchObject();

        $det = new stdClass();
        $det->idTocador   	= $tocador;
        $det->idArticle     = $article;
        $det->quantitat 	= 1;
        $det->preu 			= $row->preuBase;
        $det->importBrut 	= $row->preuBase;
        $det->idTipusIVA 	= $row->idTipusIVA;
        $det->percIVA 		= $this->getPercIVA($row->idTipusIVA, $row->servei);
        $det->percDte 		= '';
        $det->importDte 	= '';
        $det->importBase 	= $row->preuBase;
        $det->importIVA 	= $row->importIVA;
        $det->importNet 	= $row->preuBase + $row->importIVA;
        $det->especificat 	= '';
        $det->especificacio = '';

		$db->insertRow('#_tocadors_oberts_detall', $det);

		$idDetall = $db->lastId();

		//recalculem els detalls del tocador i guardem l'import
		$db->query('SELECT SUM(importNET) FROM #_tocadors_oberts_detall WHERE idTocador = '.$tocador);
		$import = $db->loadResult();

		$db->updateField('#_tocadors_oberts', 'import', $import, 'id', $tocador);

		echo json_encode(array('idDetall' => $idDetall));
    }

    function getPercIVA($idTipusIVA, $servei)
    {
    	$db  = factory::getDatabase();

    	 $db->query('SELECT percIVA FROM #_tipus_iva WHERE id = '.$idTipusIVA);

        return $db->loadResult();
    }

		function fwrite_stream($fp, $string) {
			for ($written = 0; $written < strlen($string); $written += $fwrite) {
					$fwrite = fwrite($fp, substr($string, $written));
					if ($fwrite === false) {
							return $written;
					}
			}
			return $written;
	}

		public function printer() {

			$fp = fsockopen("192.168.1.63", 610);
			if (!$fp){
			    die("Cannot open sock");
			}
			$mytext="Hello this is a test print 13 ";
			$string="\x1B@".$mytext."\x1Bd\x07\x1Bi";
			$bytes=fwrite_stream($fp, $string);
			fclose($fp);
			printf('wrote %d bytes',$bytes);
		}
}
