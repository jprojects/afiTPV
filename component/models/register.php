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

class register extends model
{
    
    /**
     * Method to check if email exists
    */
    function checkEmail() 
    {
        if(isset($_GET['task']) && $_GET['task'] == 'checkEmail') {
            $db         = factory::getDatabase();
            $email      = $_GET['email'];

            $db->query('select id from #_users where email = '.$db->quote($email));
            if($id = $db->loadResult()) {
                echo false;
            } else {
                echo true;
            }
        }
    }
    
    /**
     * Method to register a new user
    */
    function register()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'register') {

            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();
            
            //si un campo esta vacio abortamos...
            if($_POST['email'] == "" || $_POST['password'] == "" || $_POST['password2'] == "") {
                $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
                $app->redirect($config->site.'/index.php?view=register');
                return false;
            }
            
            //check if email exists...
            $db->query('select id from #_users where email = '.$db->quote($_POST['email']));
            if($id = $db->loadResult()) {
                $app->setMessage($lang->get('El email ya existe, por favor elige otro'), 'danger');
                $app->redirect($config->site.'/index.php?view=register');
                return false;
            }
        
            $app->getToken($_POST['auth_token'], $config->token_time);
            
            if($_POST['password'] === $_POST['password2']) {
            
                unset($_POST['password2']);
                unset($_POST['auth_token']);
                
                //create user
                $_POST['username']      = $_POST['email'];
                $_POST['email']         = $_POST['email'];
                $_POST['password']      = $app->encryptPassword($_POST['password']);
                $_POST['registerDate']  = date('Y-m-d H:i:s');
                $token                  = $user->genToken($_POST['email']);
                $_POST['token']         = $token;
                $_POST['level']         = 2;
                $_POST['language']      = 'en-gb';
                $result = $db->insertRow('#_users', $_POST);
                
                $lastid = $db->lastId();
                
                if($result && $result2) {
                    //send a confirmation to the user...
                    $subject    = $lang->replace('CW_REGISTER_WELCOME_SUBJECT', $config->sitename);
                    $link       = $config->site.'/index.php?view=register&task=validate&token='.$token;
                    $body       = $lang->replace('CW_REGISTER_WELCOME_BODY', $_POST['username'],  $config->sitename, $link);
                    $send       = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);
        
                    if($send) {
                        $app->setMessage($lang->replace('CW_REGISTER_SUCCESS_MSG', $config->sitename), 'success');
                        $app->redirect($config->site.'/index.php?view=home');
                        exit(0);
                    } else {
                        //mostrar el link de activacion en el mensaje ya que fallo el email...
                        $app->setMessage($lang->replace('CW_REGISTER_EMAIL_ERROR_MSG', $link), 'danger');
                        $app->redirect($config->site.'/index.php?view=register');
                        return true;
                    }
                } else {
                    $app->setMessage($lang->get('CW_REGISTER_ERROR_MSG'), 'danger');
                    $app->redirect($config->site.'/index.php?view=register');
                    return false;
                }
            } else {
                $app->setMessage($lang->get('CW_REGISTER_PASSWORDS_NOT_MATCH_MSG'), 'danger');
                $app->redirect($config->site.'/index.php?view=register');
                return false;
            }
        }
    }
    
    /**
     * Method to reset the user password
    */
    function resendActivation()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'resendActivation') {
        
            $config = factory::getConfig();
            $app    = factory::getApplication();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();

            //send a confirmation to the user...
            $subject    = $lang->replace('CW_REGISTER_WELCOME_SUBJECT', $config->sitename);
            $link       = $config->site.'/index.php?view=register&task=validate&token='.$user->token;
            $body       = $lang->replace('CW_REGISTER_WELCOME_BODY', $user->username,  $config->sitename, $link);
            $send       = $this->sendMail($user->email, $user->email, $subject, $body);
    
            if($send) {
                $app->setMessage($lang->get('CW_REGISTER_RESET_SUCCESS_MSG'), 'success');
                $app->redirect($config->site.'/index.php?view=home');
            } else {
                $app->setMessage($lang->get('CW_REGISTER_RESET_ERROR_MSG'), 'danger');
            }
        }
    }
    
    /**
     * Method to reset the user password
    */
    function reset()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'reset') {
        
            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();
            
            //si un campo esta vacio abortamos...
            if($_POST['email'] == "") {
                $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
                $app->redirect($config->site.'/index.php?view=register&layout=reset');
                return false;
            }
            
            $email  = $db->quote($_POST['email']);

            $db->query("SELECT id FROM #_users WHERE email = $email AND block = 0");
            $id = $db->loadResult();
            $newpassword = uniqid();
            $password = $app->encryptPassword($newpassword);
            $result = $db->updateField('#_users', 'password', $password, 'id', $id);
            //send email to user...
            if($result) {
                //send a confirmation to the user...
                $subject  = $lang->replace('CW_REGISTER_RESET_SUBJECT', $config->sitename);
                $body     = $lang->replace('CW_REGISTER_RESET_BODY', $_POST['email'], $config->sitename, $newpassword);
                $send     = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);
    
                if($send) {
                    $app->setMessage($lang->get('CW_REGISTER_RESET_SUCCESS_MSG'), 'success');
                    $app->redirect($config->site.'/index.php?view=home');
                } else {
                    $app->setMessage($lang->get('CW_REGISTER_RESET_ERROR_MSG'), 'danger');
                }
            } else {
                $app->setMessage($lang->get('CW_REGISTER_RESET_ERROR_MSG'), 'danger');
            }
        }
    }
    
    /**
     * Method to login into the application with Google API
    */
    public static function glogin()
    { 

        if(isset($_GET['task']) && $_GET['task'] == 'glogin') {

            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();
            $session   = factory::getSession();
            
            include_once CWPATH_LIBRARIES.DS.'google'.DS.'gpConfig.php';
                     
            if(isset($_GET['code'])){
    			$gClient->authenticate($_GET['code']);
    			$_SESSION['token'] = $gClient->getAccessToken();
    			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
			} 
			
			if (isset($_SESSION['token'])) {
    			$gClient->setAccessToken($_SESSION['token']);
			}  

			if ($gClient->getAccessToken()) {
				//Get user profile data from google
    			$gpUserProfile = $google_oauthV2->userinfo->get();
    			
    			//Insert or update user data to the database
				$gpUserData = new stdClass();
				$gpUserData->oauth_provider = 'google';
				$gpUserData->oauth_uid      = $gpUserProfile['id'];
				$gpUserData->username       = $gpUserProfile['given_name'];
				$gpUserData->email          = $gpUserProfile['email'];
				$gpUserData->language       = 'es-es';
				$gpUserData->level          = 2;
				$gpUserData->image          = $gpUserProfile['picture'];
				$gpUserData->token          = $user->genToken($gpUserProfile['email']);				
				$gpUserData->lastvisitDate  = date('Y-m-d H:i:s');
				
				$db->query("SELECT id FROM #_users WHERE email = ".$db->quote($gpUserProfile['email'])." AND block = 0");
            	if($id = $db->loadResult()) {
            		$userData = $db->updateRow('#_users', $gpUserData, 'id', $id);
            	} else {
            		$gpUserData->registerDate   = date('Y-m-d H:i:s');				
					$userData = $db->insertRow('#_users', $gpUserData);				
					$id = $db->lastId();
				}
				
				//auth into application
				$user->setAuth($id);
				
				//register session
				$session->createSession();
    			
    			$app->getVar('return', '', 'post') == '' ? $authUrl = $config->site.$config->login_redirect : $authUrl = base64_decode($app->getVar('return', '', 'post'));
    			
			} else {
			
    			$authUrl = $gClient->createAuthUrl();
			}
            
            $app->redirect($authUrl);
        }
        
    }
       
    /**
     * Method to login into the application
    */
    public static function login()
    { 

        if(isset($_GET['task']) && $_GET['task'] == 'login') {

            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();
            $session= factory::getSession();
            
            $email    = $app->getVar('email', '', 'post');
            $password = $app->getVar('password', '', 'post');
            $redirect = $app->getVar('return', '', 'post');
                    
            //si un campo esta vacio abortamos...
            if($email == "" || $password == "") {
                $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
                $app->redirect($config->site.'/index.php?view=home');
                return false;
            }   
            
            //check token
            $app->getToken($_POST['auth_token'], $config->token_time);
            
            //check if online , allow only admins...
            if($config->offline == 1) {
            	$db->query("SELECT password FROM #_users WHERE email = ".$db->quote($email)." AND block = 0 AND level = 1");
				$dbpass = $db->loadResult();
            } else {
				$db->query("SELECT password FROM #_users WHERE email = ".$db->quote($email)." AND block = 0");
				$dbpass = $db->loadResult();
			}
			
            if($app->decryptPassword($password, $dbpass)) {
		        $remember = "";
		        
		        $db->query("SELECT id FROM #_users WHERE email = ".$db->quote($email)." AND block = 0");
		        if($id = $db->loadResult()) {
		            $user->setAuth($id);
		            
		            //register session
					$session->createSession();

		            $db->updateField('#_users', 'lastvisitDate',  $app->getVar('lastvisitDate'), 'id', $id);
		            $app->setMessage($lang->replace('CW_LOGIN_SUCCESS_MSG',  $username), 'success');
		            $redirect == '' ? $authUrl = $config->site.$config->login_redirect : $authUrl = base64_decode($redirect);
		        } else {
		            $app->setMessage($lang->get('CW_LOGIN_ERROR_MSG'), 'danger'); 
		            $authUrl = $config->site.'/index.php?view=home';
		        }
            } else {
            	$app->setMessage($lang->get('Password not match'), 'danger'); 
		        $authUrl = $config->site.'/index.php?view=home';
            }
            
            $app->redirect($authUrl);
        }
        
    }
    
    /**
     * Method to logout the application
    */
    function logout()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'logout') {
            
            $config = factory::getConfig();
            $app    = factory::getApplication(); 
            $session   = factory::getSession(); 
            
            //register session
			$session->destroySession();          
            
			include_once CWPATH_LIBRARIES.DS.'google'.DS.'gpConfig.php';

			//Unset token and user data from session
			unset($_SESSION['afiToken']);
			unset($_SESSION['token']);
			unset($_SESSION['userData']);

			//Reset OAuth access token
			$gClient->revokeToken();

            $app->redirect($config->site);
        }
    }
    
    /**
     * Method to validate user for the first time into the application after a successful registration
    */
    function validate()
    {
        if(isset($_GET['task']) && $_GET['task'] == 'validate') {
            
            $config = factory::getConfig();
            $app    = factory::getApplication();
            $db     = factory::getDatabase();
            $user   = factory::getUser();
            $lang   = factory::getLanguage();
            
            $sitename = $config->sitename;
            
            //if token...
            if(isset($_GET['token'])) {
                $result = $db->updateField('#_users', 'block', 0, 'token', $_GET['token']);
                if($result) {
                    if($config->admin_mails == 1) {
                        $this->sendAdminMail('Nuevo registro en '.$sitename, "Un nuevo usuario se ha registrado en ".$sitename.".");
                    }
                    $app->setMessage($lang->replace('CW_REGISTER_WELCOME_MSG_SUCCESS',  $sitename), 'success');
                } else {
                    $app->setMessage($lang->get('CW_REGISTER_WELCOME_MSG_ERROR'), 'danger');
                }
                $app->redirect($config->site.'/index.php');
            }
        }
    }
}
