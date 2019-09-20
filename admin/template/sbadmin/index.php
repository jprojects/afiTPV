<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $config->description; ?>">
    <meta name="author" content="<?= $config->sitename; ?>">

    <title><?= $config->sitename; ?> Admin</title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=  $config->site; ?>/admin/template/<?=  $config->admin_tmpl; ?>/css/sb-admin-2.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css' rel='stylesheet' type='text/css'>

    <!-- jQuery -->
    <script
  	src="http://code.jquery.com/jquery-1.12.4.min.js"
  	integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  	crossorigin="anonymous"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=  $config->site; ?>/admin/template/<?=  $config->admin_tmpl; ?>/js/sb-admin-2.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link rel="apple-touch-icon-precomposed" href="<?= $config->site; ?>/assets/img/icons/icon64.jpg">
    <link rel="shortcut icon" href="<?= $config->site; ?>/assets/img/icons/icon16.jpg">

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" target="_blank" href="index.php?view=cpanel"><?=  $config->sitename; ?> Administració</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="<?= $config->site; ?>" target="_blank">
                        <i class="fa fa-eye fa-fw"></i>
                    </a>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <?php if($admin->getAuth()) : ?>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=  $config->site; ?>/admin/index.php?view=cpanel&task=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <?php endif; ?>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <?php if($admin->getAuth()) { echo $app->getModule('sidebar'); } ?>
        </nav>
        
	 	<?php include('template/'.$config->admin_tmpl.'/message.php'); ?>
		<?php include($app->getLayout(true)); ?>

    </div>
    <!-- /#wrapper -->

</body>

</html>
