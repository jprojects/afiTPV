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

class custom extends model
{
	function saveStep()
    {
    	$config = factory::getConfig();
        $app    = factory::getApplication();
        $db     = factory::getDatabase();
        $user   = factory::getUser();

        $db->updateRow('#_users', $_POST, 'id', $user->id);
        
        $app->redirect($config->site.'/index.php?view=families');
    }
}
