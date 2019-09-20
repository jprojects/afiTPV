<?php

/**
 * @version     1.0.0 Afi framework $
 * @package     Afi framework
 * @copyright   Copyright © 2016 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');

class Application 
{
    /**
     * Array of scripts placed in the header
     *
     * @var  array 
     * @access   private
    */
    var $scripts = array();
    
    /**
     * Array of scripts placed in the header
     *
     * @var  array 
     * @access   private
    */
    var $scriptCode = array();
    
    /**
     * Array of stylesheets placed in the header
     *
     * @var  array 
     * @access   private
    */
    var $stylesheets = array();
    
    /**
     * View
     *
     * @var     string
     * @access  private
    */
    var $view = '';
    
    /**
     * Task
     *
     * @var     string
     * @access  private
    */
    var $task = '';
    
    /**
     * Var
     *
     * @var     mixed
     * @access  private
    */
    var $var = "";
    
    /**
     * Adds a linked script to the page
     *
     * @param    string  $url        URL to the linked script
     * @param    string  $type        Type of script. Defaults to 'text/javascript'
     * @access   public
     */
    function addScript($url) {
        $this->scripts[] = $url;
    }
    
    /**
     * Adds a javascript code to the page
     *
     * @param    string  $code        code string
     * @param    string  $type        Type of script. Defaults to 'text/javascript'
     * @access   public
     */
    function addScriptCode($code) {
        $this->scriptCode[] = $code;
    }
    
    /**
     * Adds a linked stylesheet to the page
     *
     * @param    string  $url        URL to the linked stylesheet
     * @access   public
     */
    function addStylesheet($url) {
        $this->stylesheets[] = $url;
    }
    
    /**
     * Adds a linked stylesheet to the page
     *
     * @param    string  $url    URL to the linked style sheet
     * @param    string  $type   Mime encoding type
     * @param    string  $media  Media type that this stylesheet applies to
     * @access   public
     */
    function setMessage($msg, $type, $admin = false)
    {
    	$admin == true ? $prefix = 'admin_' : $prefix = '';
        $_SESSION[$prefix.'message'] = $msg;
        $_SESSION[$prefix.'messageType'] = $type;
    }
    
    /**
     * Method to get application version 
    */
    function getVersion()
    {
        $local  = json_decode(file_get_contents(CWPATH_BASE.DS.'afi.json'), true);

    	return $local['version'];
    }
    
    /**
     * Method to encrypt passwords 
    */
    function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);   
    }
    
    /**
     * Method to decrypt passwords 
    */
    function decryptPassword($password, $hash)
    {  
        if (password_verify($password, $hash)) {
			return true;
		} else {
			return false;
		} 
    }
    
    function trigger($type, $args=array())
    {
    	$path = 'plugins/'.$type.'/'.$type.'.php';

		if (file_exists($path))
		{
			include_once $path;
			$type::execute($args);
		}
    }
    
    /**
     * Fetches and returns a given variable.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * an entry from $_GET, POST and PUT will result in returning an
     * entry from $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     * post    $_POST
     * get     $_GET
     * files   $_FILES
     * cookie  $_COOKIE
     * env     $_ENV
     * server  $_SERVER
     * method  via current $_SERVER['REQUEST_METHOD']
     * default $_REQUEST
     * 
     *  You can force the type of variable
     *  
     * (int), (integer) - forzado a integer
     * (bool), (boolean) - forzado a boolean
     * (float), (double), (real) - forzado a float
     * (string) - forzado a string
     * (array) - forzado a array
     * (object) - forzado a object
     * (unset) - forzado a NULL (PHP 5)
     *
     * @param   string   $name     Variable name.
     * @param   string   $default  Default value if the variable does not exist.
     * @param   string   $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @param   string   $type     Return type for the variable (int,string).
     *
     * @return  mixed  Requested variable.
     */
    function getVar($name, $default = null, $hash = 'REQUEST', $type = 'none')
    {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
 
        // Get the input hash
        switch ($hash)
        {
            case 'GET':
                $input = &$_GET;
                break;
            case 'POST':
                $input = &$_POST;
                break;
            case 'FILES':
                $input = &$_FILES;
                break;
            case 'COOKIE':
                $input = &$_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                break;
        }
        
        if (array_key_exists($name, $input)) {
			$var = $input[$name];
		}
        
        //set default value
        if(empty($var)) {
            $var = $default;
        }
        
        //force type
        switch ($type)
        {
            case 'int':
                $var = (int)$var;
                break;
            case 'bool':
                $var = (bool)$var;
                break;
            case 'float':
                $var = (float)$var;
                break;
            case 'string':
                $var = (string)$var;
                break;
            default:
                $var = $var;
                break;
        }
        return $var;
    }
    
    /**
     * Method to load a layout
    */
    function getLayout($admin=false)
    {   	    	
		$admin == false ? $default = 'home' : $default = 'cpanel';
        $this->task     = $this->getVar('task', null, 'get', 'string');
        $this->view     = $this->getVar('view', $default, 'get', 'string');
        $this->layout   = $this->getVar('layout', null, 'get', 'string');
        
        $user = factory::getUser();
        //allowed views without redirect
        $admin == true ? $views = array('cpanel') : $views = array('home', 'register');
    	if(!$user->getAuth() && in_array($views, $this->view)) {
    		$this->redirect('index.php?view=home');
    	}

        $path = 'component/views/'.$this->view.'/tmpl/'.$this->view.'.php';
        
        if($this->layout != null) {
            $path = 'component/views/'.$this->view.'/tmpl/'.$this->layout.'.php';
        }

        if($this->task != null) {
            $pos = strpos($this->task, '.');
            if($pos == true) { 
                $parts = explode('.', $this->task);
                $model = $this->getModel($parts[0]);
                $task  = $parts[1];
                $model->$task();
            } else {
                $model = $this->getModel();
                $task  = $this->task;
                $model->$task();
            }
        } else {
            if (is_file($path)) {  
                return $path;
            }  else {
                return 'error.php';
            } 
        }
    }
    
    /**
     * Method to load the view model
     * @param $model string call to specific model
     * @return object
    */
    function getModel($model = null)
    {
        $model == null ? $path  = 'component/models/'.$this->view.'.php' : $path = 'component/models/'.$model.'.php';
        $model == null ? $class = $this->view : $class = $model;
        $instance = "";
        
		if (file_exists($path))
		{
			include_once $path;
			if (class_exists($class)) {
			    $instance = new $class;
			}
		}  
        return $instance;
    }
    
    /**
     * Method to load a module
     * @access public
     * @return boolean, return module output
    */
    public function getModule($name)
    {
        $html = "";
        $path = 'modules/'.$name.'/default.php';
        if (is_file($path)) {  
            ob_start();
			include_once $path;
			$html = ob_get_clean();		
       	} 
        return $html;
    }
    
    /**
     * Method to load a view
     * @access public
     * @return boolean, return view output
    */
    public function getView($admin=false)
    {    	
        $admin == false ? $default = 'home' : $default = 'cpanel';
        $this->view     = $this->getVar('view', $default, 'get', 'string');

        $path = 'component/views/'.$this->view.'/view.php';

        if (is_file($path)) {  
        	return $path;
        }
    }
    
    /**
     * Method to load the template
     * @param $tmpl string call to specific template
     * @return string
    */
    function getTemplate($admin=false)
    {
        $config = factory::getConfig();
        $admin == true ? $tmpl = $config->admin_tmpl : $tmpl = $config->template;
        $mode = $this->getVar('mode', '');
        if($mode == 'raw') {
            $path = 'template/'.$tmpl.'/index2.php';
        } else {
            $path = 'template/'.$tmpl.'/index.php';
        }
        if (is_file($path)) {  
            return $path;
        }  else {
            return 'error.php';
        }
    }
    
    /**
     * Method to redirect to other url
     * @param $url string 
    */
    function redirect( $url )
    {
        /*
         * If the headers have been sent, then we cannot send an additional location header
         * so we will output a javascript redirect statement.
         */
        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>\n";
        } else {
            //@ob_end_clean(); // clear output buffer
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( 'Location: ' . $url );
        }
    }
    
    /**
     * Method to set a form security token
     * @return string
    */
    function setToken() 
    {
       $token = md5(uniqid(microtime(), true));
     
       $token_time = time();
     
       $_SESSION['csrf']['token'] = array('token'=>$token, 'time'=>$token_time);
     
       return $token;
    }
    
    /**
     * Method to check a form security token
     * @return boolean
    */
    function getToken($token, $delta_time=0) 
    {
        $config = factory::getConfig();
        $lang = factory::getLanguage();
        if(!isset($_SESSION['csrf']['token'])) {
            $this->setMessage($lang->get('CW_FRAUD_ATTEMPT'), 'warning');
            $this->redirect($config->site);
        }
     
        if ($_SESSION['csrf']['token']['token'] !== $token) {
            $this->setMessage($lang->get('CW_FRAUD_ATTEMPT'), 'warning');
            $this->redirect($config->site);
        }

        if($delta_time > 0){
           $token_age = time() - $_SESSION['csrf']['token']['time'];
           if($token_age >= $delta_time){
                $this->setMessage($lang->get('CW_FRAUD_ATTEMPT'), 'warning');
                $this->redirect($config->site);
           }
        }
     
        return true;
    }
}
?>
