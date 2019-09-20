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

class iva extends model
{

	private $table = '#_tipus_iva';
	private $view  = 'iva';
    
    /**
     * Metode per agafar families
    */
    function getList() 
    {
        $db  = factory::getDatabase();
        $user = factory::getUser();
        $config = factory::getConfig();
        
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        
        $no_of_records_per_page = $config->pagination;
        
        $offset = ($page-1) * $no_of_records_per_page;
        
        $db->query('SELECT COUNT(id) FROM '.$this->table);
        $count_rows = $db->loadResult();
        
        if($count_rows != 0) {
		    $sql  = 'SELECT * FROM '.$this->table;
		    $sql .= ' ORDER BY id ASC LIMIT '.$offset.', '.$no_of_records_per_page;

		    $db->query($sql);
		    
		    $_SESSION['total_pages'] = ceil($count_rows / $no_of_records_per_page);	
		    
		    return $db->fetchObjectList();
        }
    }
    
    /**
     * Metode per guardar families
    */
    function getItemById() 
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();
        
        $id    = $app->getVar('id', 0, 'get');
        
        $db->query('SELECT * FROM '.$this->table.' WHERE id = '.$id);
        $row = $db->fetchObject();
        
        echo json_encode($row);
    }
    
    /**
     * Metode per guardar families
    */
    function saveItem() 
    {
        $db  = factory::getDatabase();
        $app = factory::getApplication();
        
        $id    = $app->getVar('id', 0, 'post');
        
        if($id == 0) {
        	$result = $db->insertRow($this->table, $_POST);
        } else {
        	$result = $db->updateRow($this->table, $_POST, 'id', $id);
        }
        
        if($result) {
        	$msg  = "El impost ha estat guardada";
        	$type = 'success';
        } else {
        	$msg  = "El impost no s'ha pogut guardar";
        	$type = 'danger';
        }
        
        $app->redirect('index.php?view='.$this->view, $msg, $type);
    }
    
}
