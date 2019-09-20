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

class Custom
{
    public function saveStep(){

        $db = factory::getDatabase();
        $user = factory::getUser();
        $app = factory::getApplication();

        $data = new stdClass();
        $data->cf_salo = $app->getVar('cf_salo');
        $data->cf_terminal = $app->getVar('cf_terminal');

        $result = $db->updateRow('#_users', $data, 'id', $user->id);

        if($result) {
            $return  = "index.php?view=ticket";
        } else {
        	$return  = "index.php?view=register&layout=step";
        }
        
        $app->redirect($return);

    }
}
