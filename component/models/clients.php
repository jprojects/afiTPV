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

class clients extends model
{

	private $table = '#_clients';
    private $view  = 'clients';
    private $key    = 'id';
	private $order  = 'ordering';
	private $dir  = 'ASC';
	private $sql    = 'SELECT i.* FROM `#_clients` AS i';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_clients` AS i';
    
    /**
     * Metode per agafar families
    */
    public function getList()
	{
		$db  	= factory::getDatabase();
		$user 	= factory::getUser();
		$app    = factory::getApplication();
		$config = factory::getConfig();
		$session = factory::getSession();

		$page  = $app->getVar('page', 1, 'get');
		$order = $app->getVar('list_column', $this->order, 'get');
		$dir   = $app->getVar('list_dir', $this->dir, 'get');

		$session->setVar('list_dir', $dir);

		$no_of_records_per_page = $app->getVar('list', $config->pagination, 'get');
		if($no_of_records_per_page == '*') $no_of_records_per_page = 100000;

		unset($_GET['page'], $_GET['view'], $_GET['list'], $_GET['list_column'], $_GET['list_dir']);

        $offset = ($page-1) * $no_of_records_per_page;


		//get all url vars from filters
		$i = 0;
		$filters = '';
		foreach($_GET as $k => $v) {
			$k = explode('_', $k, 3);
			if($v != '') {

				if (strpos($this->sql, 'WHERE') !== false) $filters.= ' AND ';
				else $filters .= $i == 0  ? ' WHERE ' : ' AND ';

				if(strtolower($k[1]) == 'like') {
					$filters .= 'i.'.$k[2].' LIKE "%'.$v.'%"';
				}
				if(strtolower($k[1]) == 'equal') {
					$options = explode(':', $v);
					$j = 0;
					$filters .= '(';
					foreach($options as $option) {
						if($j != 0) $filters .= ' OR ';
						$filters .= 'i.'.$k[2].' = '.$options[$j];
						$j++;
					}
					$filters .= ')';
				}
				$i++;
			}
		}
		$db->query($this->rows.$filters);
		$count_rows = $db->loadResult();

        if($count_rows > 0) {

			$this->sql .= $filters;
		    $this->sql .= ' ORDER BY i.'.$order.' '.$dir.' LIMIT '.$offset.', '.$no_of_records_per_page;
			if($config->debug == 1) { echo 'getList: '.$this->sql.'\n'; }
		    $db->query($this->sql);
		}
		    $_SESSION['total_pages'] = ceil($count_rows / $no_of_records_per_page);

		return $db->fetchObjectList();
	}  

    	/**
     * Method to reorder item into database
    */
    function reorderItems()
    {
        $db    = factory::getDatabase();
        $items  = json_decode($_POST['items'], true);

        $i = 1;
  		foreach($items as $item) {
        	$db->query('UPDATE '.$this->table.' SET ordering = '.$i.' WHERE '.$this->key.' = '.$item);
        	$i++;
        }
    }

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
        	$msg  = "El client ha estat guardat";
        	$type = 'success';
        } else {
        	$msg  = "El client no s'ha pogut guardar";
        	$type = 'danger';
        }
        
        $app->redirect('index.php?view='.$this->view, $msg, $type);
    }
    
    /**
     * Method to delete an item
    */
	function deleteItem(){
		$db  = factory::getDatabase();
		$app = factory::getApplication();

		$result = $db->deleteRow($this->table, $this->key, $_GET['id']);

		if($result) {
        	$msg  = "La familia ha estat esborrada";
        	$type = 'success';
        } else {
        	$msg  = "La familia no s'ha pogut esborrar";
        	$type = 'danger';
        }

        $app->redirect('index.php?view='.$this->view, $msg, $type);
	}

    
    function deleteItems()
    {
        $db  = factory::getDatabase();

        $items  = json_decode($_POST['items'], true);

        foreach($items as $item) {
        	$db->deleteRow($this->table, $this->key, $item);
        }

	}
}
