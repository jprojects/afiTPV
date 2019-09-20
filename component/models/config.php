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

class config extends model
{
    function saveConfig()
    {            
        $app  	= factory::getApplication();
        $db   	= factory::getDatabase();
        $user 	= factory::getUser();
        $lang 	= factory::getLanguage();
        $config = factory::getConfig();
        
        $app->getToken($_POST['auth_token'], $config->token_time);
        $validate = false;
        
        $iva_productes = $_POST['iva_productes'];
        $iva_serveis   = $_POST['iva_serveis'];
        $empresa_nom   = $_POST['empresa_nom'];
        $empresa_cif   = $_POST['empresa_cif'];
        $empresa_adreca   = $_POST['empresa_adreca'];
        $empresa_poblacio   = $_POST['empresa_poblacio'];
        $empresa_telefon   = $_POST['empresa_telefon'];
        $empresa_cp   = $_POST['empresa_cp'];
        $empresa_logo   = $_POST['empresa_logo'];
        
        unset($_POST['iva_productes'],$_POST['iva_serveis'], $_POST['empresa_nom'],$_POST['empresa_cif'],$_POST['empresa_adreca'],$_POST['empresa_poblacio'],$_POST['empresa_telefon'],$_POST['empresa_cp'],$_POST['empresa_logo']);

        //validate old password...
        if($_POST['old_password'] != "") {
        	$db->query("SELECT password FROM #_users WHERE id = ".$user->id." AND block = 0");
			$dbpass = $db->loadResult();
			if($app->decryptPassword($_POST['old_password'], $dbpass)) {
                $validate = true;
            }
        }
        
        $obj = new stdClass();
        $obj->email     = $_POST['email'];
        if($validate && ($_POST['password'] == $_POST['password2'])) { 
            $obj->password  = $app->encryptPassword($_POST['password']); 
        }       
        $obj->language  = $_POST['language'];
        $obj->cf_salo   = $_POST['cf_salo'];
        
        $result = $db->updateRow("#_users", $obj, 'id', $user->id);
        
        $result2 = $db->query("UPDATE #_configuration SET iva_productes = ".$db->quote($iva_productes).", iva_serveis = ".$db->quote($iva_serveis).", empresa_nom = ".$db->quote($empresa_nom).", empresa_cif = ".$db->quote($empresa_cif).", empresa_adreca = ".$db->quote($empresa_adreca).", empresa_poblacio = ".$db->quote($empresa_poblacio).", empresa_telefon = ".$db->quote($empresa_telefon).", empresa_cp = ".$db->quote($empresa_cp));
        
        if($result && $result2) {
            $app->setMessage($lang->get('CW_SETTINGS_SAVE_SUCCESS'), 'success');
        } else {
            $app->setMessage($lang->get('CW_SETTINGS_SAVE_ERROR'), 'danger');  
        }
        $app->redirect($config->site.'/index.php?view=config');
    }
    
    /**
     * Method to upload user picture
    **/
    function upload()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'upload') {
            
            $app  = factory::getApplication();
            $db   = factory::getDatabase();
            $user = factory::getUser();
            $lang = factory::getLanguage();
    
            $path = 'assets/img/uploads/';
    
            if(strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
                $this->exit_status($lang->get('CW_SETTINGS_UPLOAD_INVALID_ERROR'));  
            }
            
            $data = $app->getVar('image-data', '');
            $logo = $app->getVar('logo', 0);
            
			if($data != "") {
			
				$data = str_replace('data:image/png;base64,', '', $data);
				$data = str_replace(' ', '+', $data);
				$data = base64_decode($data);

				//Set up the source and destination of the file
				$filename = uniqid() . '.png';
				$file = $path . $filename;
				$success = file_put_contents($file, $data);
				
				if($logo == 0) {
			
					$db->query('select image from #_users where id = '.$user->id);
					$old_image = $db->loadResult();
					
					//modify database...
					$db->updateField("#_users", "image", $filename, 'id', $user->id);
					
					//delete old image...
					if($old_image != '' && $old_image != 'nouser.png') {
						unlink($path.$old_image);
					}
					
				} else {
				
					$db->query('select empresa_logo from #_configuration');
					$old_image = $db->loadResult();
					
					//modify database...
					$db->query("UPDATE #_configuration SET empresa_logo = ".$db->quote($filename));
					
					//delete old image...
					if($old_image != '' && $old_image != 'nologo.png') {
						unlink($path.$old_image);
					}
				}
				
				$source_img = imagecreatefromstring($data);
				//$rotated_img = imagerotate($source_img, 90, 0); // rotate with angle 90 here
				//$imageSave = imagejpeg($source_img, $file, 10);
				imagedestroy($source_img);
				
            } else {
                $this->exit_status($lang->get('CW_SETTINGS_UPLOAD_ERROR_NOIMAGE'));
            }
            
            $app->redirect('index.php?view=config');
        }
    }
    
    function exit_status($str){
        echo json_encode(array('status'=>$str));
        exit(0);
    }
    
    function deleteAccount()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'deleteAccount') {
            
            $app  = factory::getApplication();
            $db   = factory::getDatabase();
            $user = factory::getUser();
            $lang = factory::getLanguage();
            $result = $db->deleteRow('#_users', 'id', $user->id);
            if($result) {
                $app->setMessage($lang->get('CW_SETTINGS_DELETE_SUCCESS'), 'success');
            } else {
                $app->setMessage($lang->get('CW_SETTINGS_DELETE_ERROR'), 'danger');
            }
            $app->redirect($config->site.'/index.php');
        }
    }
}
