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

abstract class factory 
{
    /**
     * Method to get a instance of the application class
     * @return object
    */
    public static function getApplication()
    {
		$path = dirname(__FILE__).DS.'application.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new Application;
		}
        return $instance;
    }
 	
    /**
     * Method to get a instance of the config class
     * @return object
    */
    public static function getConfig()
    {
    	$path = dirname(__FILE__).DS.'config.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new Configuration;
		}
        return $instance;
    }
    
    /**
     * Method to get a instance of the Logging class
     * @return object
    */
    public static function getLog()
    {
    	$path = dirname(__FILE__).DS.'log.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new Logging;
		}
        return $instance;
    }
    
    /**
     * Method to get a instance of the database class
     * @return object
    */
    public static function getDatabase()
    {
		$config = factory::getConfig();
		$driver = $config->driver;
		$path = dirname(__FILE__).DS."database".DS.$driver.".php";
		if (file_exists($path))
		{
			include_once $path;
			$driver   = 'Afi'.$driver;
			$instance = new $driver;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the user class
     * @return object
    */
    public static function getUser()
    {
    	$path = dirname(__FILE__).DS.'user.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new User;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the user admin
     * @return object
    */
    public static function getAdmin()
    {
    	$path = dirname(__FILE__).DS.'admin.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new Admin;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the language class
     * @return object
    */
    public static function getLanguage()
    {
        $path = dirname(__FILE__).DS.'language.php';
		if (file_exists($path))
		{
			include_once $path;
			$instance = new Language;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the mailer class
     * @return object
    */
    public static function getMailer()
    {
        $path = dirname(__FILE__).'/mailer.php';
    	if (file_exists($path))
		{
			include_once $path;
			$instance = new Mailer;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the html class
     * @return object
    */
   public static function getHtml()
    {
        $path = dirname(__FILE__).'/html.php';
    	if (file_exists($path))
		{
			include_once $path;
			$instance = new Html;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the sef class
     * @return object
    */
    public static function getUrl()
    {
        $path = dirname(__FILE__).'/url.php';
    	if (file_exists($path))
		{
			include_once $path;
			$instance = new Url;
		}  
        return $instance;
    }

     /**
     * Method to get a instance of the sef class
     * @return object
    */
    public static function getSession()
    {
        $path = dirname(__FILE__).'/session.php';
    	if (file_exists($path))
		{
			include_once $path;
			$instance = new Session;
		}  
        return $instance;
    }
    
    /**
     * Method to get a instance of the custom class
     * @return object
    */
    public static function getCustom()
    {
        $path = dirname(__FILE__).'/custom.php';
    	if (file_exists($path))
		{
			include_once $path;
			$instance = new Custom;
		}  
        return $instance;
    }
}

?>
