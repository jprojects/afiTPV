<?php
/**
 * @version     1.0.0 Afi framework $
 * @package     Afi framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.aficat.com
 *
*/

session_start();
define('_Afi', 1);
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Europe/Berlin');
define('CWPATH_BASE', dirname(__FILE__) );
define('DS', DIRECTORY_SEPARATOR );

require_once(CWPATH_BASE.DS.'includes/defines.php');
require_once(CWPATH_CLASSES.DS.'factory.php');

$config = factory::getConfig();
$app    = factory::getApplication();
$db     = factory::getDatabase();
$user   = factory::getUser();
$lang   = factory::getLanguage();
$html   = factory::getHtml();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= $config->sitename; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="template/clean/css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Asap' rel='stylesheet' type='text/css'>
    
    <link href="template/clean/css/homepage.css" rel="stylesheet">
		
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://www.wishedly.com/template/clean/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" href="http://www.wishedly.com/assets/img/icons/icon64.jpg">
    <link rel="shortcut icon" href="assets/img/icons/icon16.jpg">
    
    <style>
    .header_error { color: #fff; }
    .a {
	  padding: 10px 35px;
	  border-radius: 20px;
	  border: 0;
	  outline: none;
	  font-style: italic;
	  font-weight: 700;
	  font-size: 14px;
	  color: #ffffff;
	  background-color: #DD4869;
	  transition-duration: 0.4s;
	}

	a:hover,
	a:focus,
	a:active:focus {
	  outline: none;
	  color: #ffffff;
	  background-color: #FF7E00;
	}
    </style>

</head>

<body>
<div class="wrap" style="background-image: url('assets/img/error.jpg')">
    
    <div class="main">

    	<div class="cover black" data-color="black"></div>

		<div class="container">
		    <h1 class="logo cursive">
		        <?= $config->sitename; ?> <sup><small>Beta</small></sup>
		    </h1>

		    <div class="content text-center center">
		       <h1 class="header_error"><?= $lang->get('CW_ERROR_TEXT'); ?></h1>
               <a class="btn btn-default" href="<?= $config->site; ?>/index.php?view=home"><?= $lang->replace('CW_ERROR_RETURN_TO_HOME'); ?></a>
		    </div>		    		

		</div>
	 </div>
    
</div> <!-- /wrap -->

</body>
</html>
