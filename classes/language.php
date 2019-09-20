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

class Language 
{
    public $code = 'en-gb';
    
    /**
     * Constructor
    */
    function __construct() {
        require_once(dirname(__FILE__).'/factory.php');
        $user = factory::getUser();
        if($user->getAuth() && !isset($_GET['lang'])) {            
            $this->code = $user->language;
        }
        if(isset($_GET['lang'])){
            switch($_GET['lang']) {
                case 'ca':
                    $this->code = 'ca-es'; 
                    break;
                case 'es':
                    $this->code = 'es-es';
                    break;
                case 'en':
                    $this->code = 'en-gb';
                    break;
                default:
                    $this->code = 'en-gb';
                    break;
            }
        }
    }
    
    /**
     * Method to get a translatable string from the language file
     * @param string $text
     * @return string if success false if not
    */
    function get($text)
    {
        //ToDo:: agafar idioma anglès si falla l'idioma seleccionat
        if($this->code == "") { $this->code = 'en-gb'; }

        $file = 'languages/'.$this->code.'.afiframework.ini';
        
        if (file_exists($file) && is_readable($file))
        {
            $strings = parse_ini_file($file);
            $translation = @$strings[strtoupper($text)];
            if($translation != "") {
                return nl2br($translation);
            } else { 
                $file = 'languages/en-gb.afiframework.ini'; 
                $strings = parse_ini_file($file);
                $translation = @$strings[strtoupper($text)];
                if($translation != "") { 
                    return nl2br($translation);
                } else {            
                    return $text;
                }
            }
        } else {
            return false;
        }
    }
    
    /**
     * Method to get a translatable string from the language file and sprintf arguments
     * @param string $text
     * @param mixed args you can pass unlimited number of arguments to complete the string
     * @see http://www.php.net/manual/en/function.sprintf.php for especifications
     * @return string if success false if not
    */
    function replace($text)
    {        
        $args = @func_get_args();
        $count = count($args);
        if ($count > 0)
        {        
            $args[0] = $this->get($text);
            return call_user_func_array('sprintf', $args);                
        }
    }
}

?>
