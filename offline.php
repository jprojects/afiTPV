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
    <title>Wishedly</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://www.wishedly.com/template/clean/css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Asap' rel='stylesheet' type='text/css'>
    
    <link href="http://www.wishedly.com/template/clean/css/homepage.css" rel="stylesheet">
		
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://www.wishedly.com/template/clean/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" href="http://www.wishedly.com/assets/img/icons/icon64.jpg">
    <link rel="shortcut icon" href="http://www.wishedly.com/assets/img/icons/icon16.jpg">
    
    <style>
    .subscribe-form-wrapper button {
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

.subscribe-form-wrapper button:hover,
.subscribe-form-wrapper button:focus,
.subscribe-form-wrapper button:active:focus {
  outline: none;
  color: #ffffff;
  background-color: #FF7E00;
}

.subscribe-form-wrapper input {
  border: 0;
  border-radius: 20px;
}

.subscribe-form-wrapper input,
.subscribe-form-wrapper button {
  -webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.3);
  -moz-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.3);
  box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.3);
}

/*--- FORMS ---*/
/*
This is the bit you need.
*/
.subscribe-form-wrapper label{
  position:absolute;
  left:-9999px;
}
.subscribe-form-wrapper input,
.subscribe-form-wrapper textarea{
  font:inherit;
  resize:none;
height: 38px;
width: 234px !important;
}

/*--- THE MAGIC ---*/
/*
This is the best bit!
*/
[placeholder]{
  cursor:pointer;
}
[placeholder]:active,
[placeholder]:focus{
  cursor:text;
}
    </style>

</head>

<body>
<div class="wrap">
    
    <div class="main" style="background-image: url('assets/img/cats.jpg')">

    	<div class="cover black" data-color="black"></div>

		<div class="container">
		    <h1 class="logo cursive">
		        <?= $config->sitename; ?> <sup><small>Beta</small></sup>
		    </h1>

		    <div class="content">
		        <h4 class="motto"><?= $lang->get('CW_BRAND_OFFLINE_TEXT'); ?></h4>
		        <div class="text-center subscribe-form-wrapper">
				<form action="index.php?view=register&task=loginOffline" method="post" class="form-inline">
				  <div class="form-group">
				   <input type="text" name="username" class="center-block form-control" placeholder="Username" />
				  </div>

				  <div class="form-group">
					<input type="password" name="password" class="center-block form-control" placeholder="Password" />
				  </div>

				  	<button type="submit" class="btn btn-default">Enter</button>
				</form>
			</div>
		    </div>		    		

		</div>
	 </div>
    
</div> <!-- /wrap -->

</body>
</html>
