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

class Admin 
{
    public $id              = 0;
    public $username        = "";
    public $password        = "";
    public $registerDate    = "";
    public $email           = "0000-00-00 00:00:00";
    public $lastvisitDate   = "0000-00-00 00:00:00";
    public $level           = 2; //registered by default
    public $language        = "";
    public $token           = "";
    public $block           = 0;
    public $image           = 'nouser.png';
    
    /**
     * Constructor
    */
    function __construct() {
        if( isset($_SESSION['cw_admin']) ) {
                
            if(isset($_SESSION['cw_admin'])) { $this->id = $_SESSION['cw_admin']; }
            $this->setAuth($this->id);
        }
    }
    
    /**
     * Method to know if user exist
     * @param int $id
     * @return boolean true if owner false if not
    */
    function isUser($id)
    {
        $db  = factory::getDatabase();
        $db->query('SELECT * FROM #_users WHERE id = '.(int)$id);  
        if($db->num_rows() > 0) {
            return true;
        }
        return false;
    }
    
    /**
     * Method to get the user authentication
     * @return boolean true if authenticate false if not
    */
    function getAuth()
    {
        if( !isset($_SESSION['cw_admin']) ) {
            return false;
        }
        return true;
    }
    
    /**
     * Method to set authentication values
     * @param id int the user id
     * @return void
    */
    function setAuth($id)
    { 
        $_SESSION['cw_admin'] = $id;
        
        $db  = factory::getDatabase();
        $sql = "SELECT u.* FROM #_users AS u WHERE u.id = ".$id." AND u.level = 1";
        $db->query($sql);
        $row = $db->fetchArray();
           
        foreach($row as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * Method to get the user object
     * @param id int the user id
     * @return object
    */
    function getUserObject($id)
    { 
       
        $db  = factory::getDatabase();
        $db->query('SELECT * FROM #_users WHERE id = '.$id);
        $row = $db->fetchObject();
           
        return $row;
    }
}
?>
