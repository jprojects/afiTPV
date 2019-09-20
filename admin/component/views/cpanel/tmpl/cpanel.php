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
$model = $app->getModel();
?>

<div <?php if($admin->getAuth()) : ?>id="page-wrapper"<?php endif; ?>>
        
    <div class="row">
    
    	<?php if(!$admin->getAuth()) : ?>
    	<div class="col-xs-12 col-md-4 col-md-offset-4" style="position:relative;z-index:3;text-align:center;">
			<img class="logo" src="<?= $config->site; ?>/assets/img/logo.png" alt="<?= $config->sitename; ?>" />
			<form class="form-signin" name="login-form" id="login-form" action="<?= $config->site; ?>/admin/index.php?view=cpanel&task=login" method="post">       		
				<!-- Username-->
		        <?= $html->getEmailField('login', 'email'); ?>
		        <!-- Password -->
		        <?= $html->getPasswordField('login', 'password'); ?>             
				<!-- Token -->
			    <?= $html->getTextField('login', 'auth_token', $app->setToken()); ?>
			    <!-- Submit -->
			    <?= $html->getButton('login', 'submit'); ?> 
			</form>   
        </div>
	    <?php else : ?>	
	    <div class="row">
	    	<?php if($model->isUpdate()) : ?>
	    	<div class="col-md-12" style="margin-top:20px;">
	    		<div class="alert alert-warning" role="alert">Hay una nueva versión de Afi Framework disponible, <a href="http://projectes.aficat.com/afi-framework/afi-framework.zip">haz click para descargar.</a></div>
	    	</div>
	    	<?php endif; ?>
		    <div class="col-lg-12">
		        <h1 class="page-header">Dashboard</h1>
		    </div>
		    <!-- /.col-lg-12 -->
   		</div>        
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Stats

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div id="morris-area-chart"></div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Online users
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <?php echo $app->getModule('online'); ?>
                    
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <!-- /.col-lg-4 -->
        <?php endif; ?>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
