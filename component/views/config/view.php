<?php

/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@aficat.com
 * @website	    http://www.aficat.com
 *
*/

defined('_Afi') or die ('restricted access');

$app->addStylesheet($config->site.'/assets/css/bootstrap-colorpicker.min.css');
$app->addStylesheet($config->site.'/assets/css/bootstrap-datetimepicker.min.css');
//if(file_exists('users/'.$user->username.'/style.css')) {
	//$app->addStylesheet($config->site.'/users/'.$user->username.'/style.css');
//}
//$app->addScript($config->site.'/assets/js/colorpicker-color.js');
$app->addScript($config->site.'/assets/js/moment.js');
$app->addScript($config->site.'/assets/js/bootstrap-datetimepicker.min.js');
$app->addScript($config->site.'/assets/js/jquery.cropit.js');
