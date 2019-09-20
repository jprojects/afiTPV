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

class cpanel extends model
{

	/**
     * Method to login into the application
    */
    public static function login()
    { 

        if(isset($_GET['task']) && $_GET['task'] == 'login') {

            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $admin  = factory::getAdmin();
            $lang   = factory::getLanguage();
            
            $email    = $app->getVar('email', '', 'post');
            $password = $app->getVar('password', '', 'post');
                    
            //si un campo esta vacio abortamos...
            if($email == "" || $password == "") {
                $app->setMessage($lang->get('CW_CPANEL_LOGIN_MANDATORY_FIELDS_MSG'), 'danger', true);
                $app->redirect($config->site.'/admin/index.php?view=cpanel');
                return false;
            }   
            
            //check token
            $app->getToken($_POST['auth_token'], $config->token_time);

            $db->query("SELECT password FROM #_users WHERE email = ".$db->quote($email)." AND block = 0");
			$dbpass = $db->loadResult();
            if($app->decryptPassword($password, $dbpass)) {
            
		        $db->query("SELECT u.id FROM #_users AS u WHERE u.email = ".$db->quote($email)." AND u.block = 0 AND u.level = 1");
		        if($id = $db->loadResult()) {
		            $admin->setAuth($id);

		            $app->setMessage($lang->replace('CW_CPANEL_LOGIN_SUCCESS_MSG',  $email), 'success', true);
		            $link = $config->site.'/admin/index.php?view=cpanel';
		        } else {
		            $app->setMessage($lang->get('CW_CPANEL_LOGIN_ERROR_MSG'), 'danger', true); 
		            $link = $config->site.'/admin/index.php?view=cpanel';
		        }
		        
		        $app->redirect($link);
            
            }
        }
        
    }
    
    function isUpdate()
    {
    	$local  = json_decode(file_get_contents(CWPATH_BASE.DS.'afi.json'), true);
    	$remote = json_decode(file_get_contents('http://projectes.aficat.com/afi-framework/afi.json'), true);

    	//-1 is updated, 0 equal, greater than 0 is out to date
    	if(version_compare($remote['version'],  $local['version']) > 0) {
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * Method to logout the application
    */
    function logout()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'logout') {
            
            $config = factory::getConfig();
            $app    = factory::getApplication();
            unset($_SESSION['cw_admin']);
            $app->redirect($config->site.'/admin');
        }
    }
    
    /**
     * Method to destroy session messages
    */
    public function unsetSession() 
    {
    	$_SESSION['admin_message'] = ''; 
		$_SESSION['admin_messageType'] = '';
    }
}
