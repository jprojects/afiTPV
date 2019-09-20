<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= $config->sitename; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="<?= $config->description; ?>">
    <meta name="author" content="<?= $config->sitename; ?>">
    <link rel="canonical" href="<?= $url->selfUrl(); ?>">

    <!-- styles -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $config->site; ?>/assets/css/app.css" rel="stylesheet">
    <link href="<?= $config->site; ?>/template/<?= $config->template; ?>/css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Asap' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css' rel='stylesheet' type='text/css'>
    
    <?php 
	if(count($app->stylesheets) > 0) : 
		foreach($app->stylesheets as $stylesheet) : ?>
		    <link href="<?= $stylesheet; ?>" rel="stylesheet">
		<?php endforeach;
	endif; 
	?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo $config->site; ?>/template/<?php echo $config->template; ?>/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" href="<?= $config->site; ?>/assets/img/icons/icon64.jpg">
    <link rel="shortcut icon" href="<?= $config->site; ?>/assets/img/icons/icon16.jpg">
    
    <!-- Scripts -->
		<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
	  <?php 
	  if(count($app->scriptCode) > 0) : ?>
	  <script>
	  <?php foreach($app->scriptCode as $script) : ?>
	  <?= $script; ?>
	  <?php endforeach; ?>
	  </script>
	  <?php endif; ?>
	  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <script src="<?= $config->site; ?>/assets/js/eModal.min.js"></script>
	  <script src="<?= $config->site; ?>/assets/js/herbyCookie.js"></script>
	  <script src="<?= $config->site; ?>/assets/js/confirm-bootstrap.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	  <?php 
	  if(count($app->scripts) > 0) : 
	  foreach($app->scripts as $script) : ?>
	  <script src='<?= $script; ?>'></script>
	  <?php endforeach;
	  endif; ?>
	  <script src="<?= $config->site; ?>/assets/js/app.js"></script>

</head>
